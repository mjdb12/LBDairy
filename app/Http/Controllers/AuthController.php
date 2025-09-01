<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:farmer,admin',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
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
            'name' => $request->name,
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
            $userData['admin_code'] = $request->admin_code;
            $userData['position'] = $request->position;
        } elseif ($request->role === 'farmer') {
            $userData['farmer_code'] = 'F' . strtoupper(substr(md5(uniqid()), 0, 3));
            $userData['farm_name'] = $request->farm_name;
            $userData['farm_address'] = $request->farm_address;
        }

        $user = User::create($userData);

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
