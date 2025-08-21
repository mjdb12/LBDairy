<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
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

        // Check if user exists and is approved
        if (!$user) {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        // Check if user is approved
        if ($user->status !== 'approved') {
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

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'username' => 'Your account has been deactivated. Please contact support.',
            ])->withInput();
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            
            // Log the login action
            $this->logAuditAction('login', 'users', $user->id);
            
            return redirect()->intended($this->getDashboardRoute($user->role));
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $this->logAuditAction('logout', 'users', Auth::id());
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:farmer,admin',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'terms_accepted' => 'required|accepted',
        ]);

        // Add role-specific validation
        if ($request->role === 'admin') {
            $validator->addRules([
                'admin_code' => 'required|string|max:255|unique:users',
                'position' => 'required|string|max:255',
            ]);
        } elseif ($request->role === 'farmer') {
            $validator->addRules([
                'farmer_code' => 'required|string|max:255|unique:users',
                'farm_name' => 'required|string|max:255',
                'farm_address' => 'required|string|max:500',
            ]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Prepare user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'barangay' => $request->barangay,
            'terms_accepted' => $request->terms_accepted,
            'status' => 'pending', // Default status for new registrations
        ];

        // Add role-specific fields
        if ($request->role === 'admin') {
            $userData['admin_code'] = $request->admin_code;
            $userData['position'] = $request->position;
        } elseif ($request->role === 'farmer') {
            $userData['farmer_code'] = $request->farmer_code;
            $userData['farm_name'] = $request->farm_name;
            $userData['farm_address'] = $request->farm_address;
        }

        $user = User::create($userData);

        // Log the registration action
        $this->logAuditAction('create', 'users', $user->id);

        // Don't auto-login for new registrations, require approval
        return redirect()->route('login')->with('success', 'Registration successful! Your account is pending approval. You will be notified via email once approved.');
    }

    /**
     * Get the dashboard route based on user role.
     */
    private function getDashboardRoute($role)
    {
        switch ($role) {
            case 'farmer':
                return route('farmer.dashboard');
            case 'admin':
                return route('admin.dashboard');
            case 'superadmin':
                return route('superadmin.dashboard');
            default:
                return route('dashboard');
        }
    }

    /**
     * Log audit action.
     */
    private function logAuditAction($action, $tableName, $recordId)
    {
        if (Auth::check()) {
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'table_name' => $tableName,
                'record_id' => $recordId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
