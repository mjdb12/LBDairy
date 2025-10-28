<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

        // Prevent caching of the login page itself
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:farmer,admin,superadmin',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('username', 'password');
        $user = User::where('username', $credentials['username'])
                   ->where('role', $request->role)
                   ->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        // Superadmin and admin users should not need approval
        if ($user->status !== 'approved' && !in_array($user->role, ['superadmin', 'admin'])) {
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

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Update last login time
            $user->update(['last_login_at' => now()]);
            
            Auth::login($user);
            return redirect()->intended($this->getDashboardRoute($user->role));
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput();
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
            'admin_code' => 'nullable|string|max:255|unique:users',
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

        // Send notification to super admins if someone registers as admin
        if ($request->role === 'admin') {
            notifySuperAdmins(
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

        return redirect('/login')->with('success', 'Registration successful! Please wait for admin approval.');
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
