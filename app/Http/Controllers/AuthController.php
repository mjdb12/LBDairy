<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Farm;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        // If a logged-in user reaches the login page (e.g., via browser Back),
        // immediately log them out and invalidate the session so Forward cannot
        // navigate back into protected pages.
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Determine whether to require CAPTCHA for this session
        $requireCaptcha = $request->session()->get('require_login_captcha', false);
        $captchaQuestion = null;

        if ($requireCaptcha) {
            // Generate a simple math CAPTCHA (e.g., 3 + 5)
            $a = random_int(1, 9);
            $b = random_int(1, 9);
            $captchaQuestion = $a . ' + ' . $b;
            $request->session()->put('login_captcha_answer', $a + $b);
        } else {
            $request->session()->forget('login_captcha_answer');
        }

        // Prevent caching of the login page itself
        return response()
            ->view('auth.login', compact('requireCaptcha', 'captchaQuestion'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        // Single, centralized login: only username + password are required.
        $rules = [
            'username' => 'required|string',
            'password' => 'required|string',
        ];

        // When a CAPTCHA is active for this session, require an answer
        if ($request->session()->has('login_captcha_answer')) {
            $rules['captcha'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, [
            'captcha.required' => 'Please answer the security question.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Validate CAPTCHA answer if present before hitting authentication logic
        if ($request->session()->has('login_captcha_answer')) {
            $expected = (int) $request->session()->get('login_captcha_answer');
            $provided = (int) $request->input('captcha');
            if ($provided !== $expected) {
                return back()->withErrors([
                    'captcha' => 'Incorrect security answer. Please try again.',
                ])->withInput();
            }
        }

        $credentials = $request->only('username', 'password');
        // Detect the user's role automatically based on their account record.
        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        // Simple per-user brute-force protection
        // If the account is currently locked, block login attempts until cooldown expires.
        if ($user->locked_until && now()->lessThan($user->locked_until)) {
            $remainingSeconds = now()->diffInSeconds($user->locked_until, false);
            $remainingMinutes = $remainingSeconds > 0 ? ceil($remainingSeconds / 60) : 1;

            return back()->withErrors([
                'username' => 'Too many failed login attempts. Please try again in ' . $remainingMinutes . ' minute(s).',
            ])->withInput();
        }

        // Validate password and track failures
        if (!Hash::check($credentials['password'], $user->password)) {
            $maxAttempts = 5;

            // Increase lock duration to 60 minutes after multiple lockouts
            $currentLockouts = (int) ($user->lockout_count ?? 0);
            $nextLockoutNumber = $currentLockouts + 1;
            $baseLockMinutes = 15;
            $escalatedLockMinutes = 60;
            $lockMinutes = $nextLockoutNumber >= 3 ? $escalatedLockMinutes : $baseLockMinutes;

            $user->failed_login_attempts = (int) ($user->failed_login_attempts ?? 0) + 1;
            $user->last_failed_login_at = now();

            // After a few failures, require CAPTCHA on subsequent attempts for this session
            if ($user->failed_login_attempts >= 3 || $currentLockouts > 0) {
                $request->session()->put('require_login_captcha', true);
            }

            if ($user->failed_login_attempts >= $maxAttempts) {
                // Lock the account for a cooldown period
                $user->locked_until = now()->addMinutes($lockMinutes);
                $user->lockout_count = $nextLockoutNumber;
                // Reset attempt counter after lock so the user gets a fresh window afterwards
                $user->failed_login_attempts = 0;
                $user->save();

                return back()->withErrors([
                    'username' => 'Too many failed login attempts. Please try again in ' . $lockMinutes . ' minute(s).',
                ])->withInput();
            }

            $user->save();

            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        if ($user->role !== 'superadmin' && is_null($user->email_verified_at)) {
            return back()->withErrors([
                'username' => 'Please verify your email address before signing in. Check your inbox for the verification link.',
            ])->withInput();
        }

        if ($user->status !== 'approved' && $user->role !== 'superadmin') {
            if ($user->status === 'pending') {
                return back()->withErrors([
                    'username' => 'Your account is pending approval. Please wait for admin approval.',
                ])->withInput();
            } elseif ($user->status === 'rejected') {
                return back()->withErrors([
                    'username' => 'Your account registration was rejected. Please contact support.',
                ])->withInput();
            }
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'username' => 'Your account has been deactivated. Please contact support.',
            ])->withInput();
        }

        // Successful login: reset security counters and track login metadata
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);

        // Clear any CAPTCHA flags on successful login
        $request->session()->forget(['require_login_captcha', 'login_captcha_answer']);
        Auth::login($user);
        return redirect()->intended($this->getDashboardRoute($user->role));
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Enforce automatic sequence codes; ignore any user-supplied values
        $request->request->remove('admin_code');
        $request->request->remove('farmer_code');

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:farmer,admin',
            'phone' => ['required', 'regex:/^\d{11}$/'],
            'address' => 'required|string|max:500',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'farm_name' => 'nullable|string|max:255',
            'farm_address' => 'nullable|string|max:500',
            'terms_accepted' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userData = [
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'barangay' => $request->barangay,
            'status' => 'pending',
            'is_active' => true,
            'terms_accepted' => $request->terms_accepted ? 'accepted' : null,
        ];

        // Add role-specific fields
        if ($request->role === 'admin') {
            $userData['admin_code'] = \App\Models\User::generateNextCode('admin');
            $userData['position'] = $request->position;
        } elseif ($request->role === 'farmer') {
            $userData['farmer_code'] = \App\Models\User::generateNextCode('farmer');
            $userData['farm_name'] = $request->farm_name;
            $userData['farm_address'] = $request->farm_address;
        }

        $user = User::create($userData);

        // Send email verification if applicable
        event(new Registered($user));

        // Auto-create a default Farm for farmer accounts so farmer views have farms
        if ($user->role === 'farmer') {
            try {
                // Avoid duplicate farm creation if somehow already exists
                if ($user->farms()->count() === 0) {
                    $farmName = $request->farm_name ?: ($user->farm_name ?: 'My Farm');
                    $farmAddress = $request->farm_address ?: ($user->address ?: 'N/A');
                    Farm::create([
                        'name' => $farmName,
                        'description' => null,
                        'location' => $farmAddress,
                        'size' => null,
                        'owner_id' => $user->id,
                        'status' => 'active',
                    ]);
                }
            } catch (\Exception $e) {
                // Registration should not fail solely due to farm creation
            }
        }

        // Send notification to super admins if someone registers as admin
        if ($request->role === 'admin') {
            \notifySuperAdmins(
                'admin_registration',
                'New Admin Registration',
                "A new admin '{$user->name}' has registered and is pending approval.",
                'fas fa-user-plus',
                '/superadmin/admins',
                'info',
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'registration_date' => $user->created_at->toISOString()
                ]
            );
        }

        return redirect('/login')->with('success', 'Registration successful! Please check your email to verify your address. Your account may require admin approval before you can sign in.');
    }

    /**
     * Verify email via signed link without requiring the user to be authenticated.
     * Keeps the flow: register -> verify -> approval -> login.
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Validate hash matches the user's email (same as default Laravel behavior)
        $expected = sha1((string) $user->email);
        if (! hash_equals((string) $hash, $expected)) {
            abort(403, 'Invalid verification link.');
        }

        // If already verified, just redirect to login
        if (!is_null($user->email_verified_at)) {
            return redirect('/login')->with('success', 'Your email is already verified. You can sign in once approved.');
        }

        // Mark as verified and dispatch event
        $user->forceFill(['email_verified_at' => now()])->save();
        event(new Verified($user));

        return redirect('/login')->with('success', 'Email verified successfully. You can sign in after your account is approved.');
    }

    private function getDashboardRoute($role)
    {
        switch ($role) {
            case 'superadmin':
                return '/superadmin/dashboard';
            case 'admin':
                return '/admin/dashboard';
            case 'farmer':
                return '/farmer/dashboard';
            default:
                return '/dashboard';
        }
    }
}
