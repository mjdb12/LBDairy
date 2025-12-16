<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\ProductionRecord;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    /**
     * Update the super admin's profile information.
     */
    public function updateProfile(Request $request)
    {
        // Log that we reached the controller
        \Log::info('=== PROFILE UPDATE CONTROLLER REACHED ===');
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Debug: Log the incoming request
        \Log::info('Profile update request received', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
            'current_name' => $user->name,
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson()
        ]);
        
        try {
            $request->validate([
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'phone' => ['nullable', 'regex:/^\d{11}$/'],
                'barangay' => 'required|string|max:255',
                'position' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
            ]);

            $updateData = [
                'email' => trim($request->email),
                'phone' => $request->phone ? trim($request->phone) : null,
                'barangay' => trim($request->barangay),
                'position' => $request->position ? trim($request->position) : null,
                'address' => $request->address ? trim($request->address) : null,
            ];
            
            \Log::info('About to update user with data', $updateData);
            
            // Log before update
            \Log::info('Updating user with data:', [
                'user_id' => $user->id,
                'update_data' => $updateData,
                'current_name' => $user->name
            ]);
            
            // Update the user using direct DB query to bypass any model events
            $updated = \DB::table('users')
                ->where('id', $user->id)
                ->update($updateData);
                
            // Get fresh data directly from database
            $updatedUser = \App\Models\User::find($user->id);
            
            \Log::info('User update result:', [
                'rows_affected' => $updated,
                'new_name' => $updatedUser->name,
                'direct_db_check' => \DB::table('users')->where('id', $user->id)->value('name')
            ]);
            
            // Update the user model with fresh data
            $user = $updatedUser;

            // Log the profile update
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'profile_updated',
                'description' => 'Super admin profile updated - Name: ' . $user->name,
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                $response = [
                    'success' => true,
                    'message' => 'Profile updated successfully!',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name ?? 'Not provided',
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'barangay' => $user->barangay,
                        'position' => $user->position
                    ]
                ];
                
                \Log::info('Sending JSON response:', $response);
                return response()->json($response);
            }
            return redirect()->back()->with('success', 'Profile updated successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Profile update validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Profile update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Change the super admin's password.
     */
    public function changePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        \Log::info('Password change request received', [
            'user_id' => $user->id,
            'has_current_password' => !empty($request->current_password),
            'has_new_password' => !empty($request->password),
            'has_password_confirmation' => !empty($request->password_confirmation)
        ]);
        
        try {
            $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            \Log::info('Password changed successfully', ['user_id' => $user->id]);

            // Log the password change
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'password_changed',
                'description' => 'Super admin password changed',
                'severity' => 'warning',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Password changed successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Password change validation failed', [
                'errors' => $e->errors(),
                'user_id' => $user->id
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Password change failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            return redirect()->back()->with('error', 'Failed to change password: ' . $e->getMessage());
        }
    }

    /**
     * Get current profile picture for super admin.
     */
    public function getCurrentProfilePicture()
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'profile_image' => $user->profile_image ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get profile picture'
            ], 500);
        }
    }

    /**
     * Upload profile picture for super admin.
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store the file in the web server document root img directory
                $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? public_path(), '/\\');
                $targetDir = $docRoot . DIRECTORY_SEPARATOR . 'img';
                $file->move($targetDir, $filename);
                
                // Update user's profile_image field
                $user->update(['profile_image' => $filename]);
                
                // Log the profile picture upload
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'profile_picture_uploaded',
                    'description' => 'Super admin profile picture uploaded',
                    'severity' => 'info',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Profile picture uploaded successfully!',
                    'filename' => $filename
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload profile picture: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display audit logs dashboard for super admin (can see all logs)
     */
    public function auditLogs()
    {
        // Get audit log statistics
        $stats = $this->getAuditLogStats();
        
        // Get latest audit logs with user information and enrich fields expected by the view
        $rawLogs = AuditLog::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        $auditLogs = $rawLogs->map(function($log) {
            // Derive module from action or fallback
            $action = $log->action ?? '';
            $module = 'system';
            if (str_contains($action, 'user')) { $module = 'users'; }
            elseif (str_contains($action, 'admin')) { $module = 'admins'; }
            elseif (str_contains($action, 'farm')) { $module = 'farms'; }
            elseif (str_contains($action, 'livestock')) { $module = 'livestock'; }
            elseif (str_contains($action, 'profile')) { $module = 'profile'; }

            $log->timestamp = optional($log->created_at)->format('M d, Y H:i:s');
            $log->module = $log->module ?? $module;
            $log->details = $log->details ?? ($log->description ?? null);
            return $log;
        });

        // Get security alerts (warning and high-severity events from last 7 days)
        $securityAlerts = AuditLog::with(['user'])
            ->whereIn('severity', ['warning', 'danger', 'critical', 'error'])
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($log) {
                // Normalize into the same 3-level severity categories used by the Events by Severity chart
                $raw = strtolower($log->severity ?? 'info');
                $category = 'Low';
                if (in_array($raw, ['critical', 'error', 'danger'], true)) {
                    $category = 'High';
                } elseif ($raw === 'warning') {
                    $category = 'Medium';
                }

                // Map category to Bootstrap class and shared hex colors
                switch ($category) {
                    case 'High':
                        $severityClass = 'danger';
                        $severityColor = '#c82333';
                        $textColor = 'white';
                        break;
                    case 'Medium':
                        $severityClass = 'warning';
                        $severityColor = '#ffc107';
                        $textColor = '#212529';
                        break;
                    default: // Low
                        $severityClass = 'info';
                        $severityColor = '#387057';
                        $textColor = 'white';
                        break;
                }

                return (object) [
                    'id' => $log->id,
                    'timestamp' => $log->created_at->format('M d, Y H:i:s'),
                    'user_name' => $log->user ? $log->user->name : 'System',
                    'event' => ucfirst(str_replace('_', ' ', $log->action)),
                    'details' => $log->description ?: 'No additional details',
                    'severity' => $raw,
                    'severity_class' => $severityClass,
                    'severity_label' => $category,
                    'severity_color' => $severityColor,
                    'severity_text_color' => $textColor,
                ];
            });

        // Get user activity summary (last 30 days)
        $userActivity = User::withCount([
            'auditLogs as total_actions' => function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            },
            'auditLogs as critical_events' => function($query) {
                $query->where('created_at', '>=', now()->subDays(30))
                      ->whereIn('severity', ['warning', 'danger', 'critical', 'error']);
            }
        ])
        ->where('is_active', true)
        ->get()
        ->map(function($user) {
            $lastActivity = AuditLog::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'last_activity' => $lastActivity ? $lastActivity->created_at->format('M d, Y H:i:s') : 'Never',
                'total_actions' => $user->total_actions ?? 0,
                'critical_events' => $user->critical_events ?? 0,
                'status' => $user->is_active ? 'Active' : 'Inactive'
            ];
        })
        ->sortByDesc('total_actions')
        ->take(20);

        return view('superadmin.audit-logs', [
            'totalLogs' => $stats['total_logs'],
            'criticalEvents' => $stats['critical_logs'],
            'todayLogs' => $stats['today_logs'],
            'systemHealth' => $stats['system_health'],
            'activeUsers' => User::where('is_active', true)->count(),
            'auditLogs' => $auditLogs,
            'securityAlerts' => $securityAlerts,
            'userActivity' => $userActivity
        ]);
    }

    /**
     * Get audit log statistics
     */
    private function getAuditLogStats()
    {
        $totalLogs = AuditLog::count();
        $criticalLogs = AuditLog::where('severity', 'critical')->count();
        $todayLogs = AuditLog::whereDate('created_at', today())->count();
        
        // Calculate system health based on critical errors
        $systemHealth = $criticalLogs > 0 ? 'warning' : 'healthy';
        if ($criticalLogs > 5) {
            $systemHealth = 'critical';
        }

        return [
            'total_logs' => $totalLogs,
            'critical_logs' => $criticalLogs,
            'today_logs' => $todayLogs,
            'system_health' => $systemHealth
        ];
    }

    /**
     * Get audit log details
     */
    public function getAuditLogDetails($id)
    {
        try {
            $auditLog = AuditLog::with(['user'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'auditLog' => $auditLog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load audit log details'
            ], 500);
        }
    }

    /**
     * Export audit logs
     */
    public function exportAuditLogs(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $severity = $request->get('severity');
            
            $query = AuditLog::with(['user']);
            
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            
            if ($severity) {
                $query->where('severity', $severity);
            }
            
            $auditLogs = $query->get();
            
            if ($format === 'csv') {
                return $this->exportToCSV($auditLogs);
            } elseif ($format === 'pdf') {
                return $this->exportToPDF($auditLogs);
            } else {
                return response()->json(['success' => false, 'message' => 'Unsupported format'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Export failed'], 500);
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCSV($auditLogs)
    {
        $filename = 'superadmin_audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'User', 'Role', 'Action', 'Severity', 'Description', 'IP Address', 'Timestamp']);
            
            // CSV data
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'System',
                    $log->user->role ?? 'System',
                    $log->action,
                    $log->severity,
                    $log->description,
                    $log->ip_address,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF (placeholder)
     */
    private function exportToPDF($auditLogs)
    {
        // This would require a PDF library like DomPDF or similar
        // For now, return a placeholder response
        return response()->json(['success' => false, 'message' => 'PDF export not implemented yet'], 501);
    }

    /**
     * Clear old audit logs
     */
    public function clearOldLogs(Request $request)
    {
        try {
            $days = (int) $request->get('days', 90);

            if ($days === 0) {
                // Clear all logs
                $deleted = AuditLog::query()->delete();
                $message = "Cleared all audit logs";
            } else {
                // Clear logs older than N days
                $deleted = AuditLog::where('created_at', '<', now()->subDays($days))->delete();
                $message = "Cleared {$deleted} audit logs older than {$days} days";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'deleted_count' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to clear old logs'], 500);
        }
    }

    /**
     * Get system overview statistics
     */
    public function getSystemOverview()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'total_farms' => Farm::count(),
                'active_users' => User::where('is_active', true)->count(),
                'system_uptime' => $this->getSystemUptime(),
                'recent_activity' => AuditLog::with(['user'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get()
            ];
            
            return response()->json(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get system overview'], 500);
        }
    }

    /**
     * Get system uptime (placeholder)
     */
    private function getSystemUptime()
    {
        // This would require system-level access
        // For now, return a placeholder
        return '99.9%';
    }

    // clearOldLogs method removed - audit logs now exclusive to admin and farmer only

    // User Management Methods
    public function getUsersList()
    {
        try {
            $users = User::select([
                'id',
                'name',
                'first_name',
                'last_name',
                'email',
                'username',
                'role',
                'status',
                'is_active',
                'phone',
                'barangay',
                'last_login_at',
                'created_at',
                'updated_at'
            ])->get();

            // Check which users are currently online (have active sessions)
            try {
                $onlineUserIds = DB::table('sessions')
                    ->where('user_id', '!=', null)
                    ->where('last_activity', '>', now()->subMinutes(5)) // Consider users online if active in last 5 minutes
                    ->pluck('user_id')
                    ->toArray();

                // Add online status to each user
                $users->each(function ($user) use ($onlineUserIds) {
                    $user->is_online = in_array($user->id, $onlineUserIds);
                });
            } catch (\Exception $e) {
                // If sessions table doesn't exist or there's an error, mark all users as offline
                $users->each(function ($user) {
                    $user->is_online = false;
                });
            }

            return response()->json(['success' => true, 'data' => $users]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get users list'], 500);
        }
    }

    public function getUserStats()
    {
        try {
            $stats = [
                'total' => User::count(),
                'approved' => User::where('status', 'approved')->count(),
                'pending' => User::where('status', 'pending')->count(),
                'rejected' => User::where('status', 'rejected')->count(),
            ];
            
            // Try to get online and recently active stats
            try {
                $stats['online'] = DB::table('sessions')
                    ->where('user_id', '!=', null)
                    ->where('last_activity', '>', now()->subMinutes(5))
                    ->count();
                $stats['recently_active'] = User::where('last_login_at', '>', now()->subHours(24))->count();
            } catch (\Exception $e) {
                $stats['online'] = 0;
                $stats['recently_active'] = 0;
            }
            
            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get user stats'], 500);
        }
    }

    public function showUser($id)
    {
        try {
            $user = User::select([
                'id',
                'name',
                'first_name',
                'last_name',
                'email',
                'username',
                'role',
                'status',
                'is_active',
                'phone',
                'barangay',
                'address',
                'last_login_at',
                'created_at',
                'updated_at'
            ])->findOrFail($id);
            
            return response()->json(['success' => true, 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }

    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'role' => 'required|in:farmer,admin,superadmin',
                'phone' => ['nullable', 'regex:/^\d{11}$/'],
                'barangay' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
                'status' => 'required|in:pending,approved,rejected',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'role' => $request->role,
                'phone' => $request->phone,
                'barangay' => $request->barangay,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'is_active' => $request->status === 'approved',
                'status' => $request->status,
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'user_created',
                'description' => "Created user: {$user->name} ({$user->email})",
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json(['success' => true, 'data' => $user, 'message' => 'User created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create user'], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Debug: Log the incoming request data
            Log::info('Update user request data:', $request->all());
            Log::info('Current user status:', ['id' => $id, 'current_status' => $user->status]);
            
            // Check if current user status is valid
            $validStatuses = ['pending', 'approved', 'rejected'];
            if (!in_array($user->status, $validStatuses)) {
                Log::warning('User has invalid status:', ['id' => $id, 'invalid_status' => $user->status]);
                // Fix the invalid status
                $user->update(['status' => 'pending']);
            }
            
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
                'role' => 'required|in:farmer,admin,superadmin',
                'phone' => ['nullable', 'regex:/^\d{11}$/'],
                'barangay' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
                // Accept UI variants and normalize below
                'status' => 'required|in:pending,approved,rejected,active,inactive,suspended',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            // Normalize status to system domain values
            $incomingStatus = $request->status;
            $normalizedStatus = in_array($incomingStatus, ['active', 'inactive'])
                ? ($incomingStatus === 'active' ? 'approved' : 'rejected')
                : $incomingStatus; // pending/approved/rejected/suspended
            $isActive = ($normalizedStatus === 'approved');

            $updateData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'role' => $request->role,
                'phone' => $request->phone,
                'barangay' => $request->barangay,
                'status' => $normalizedStatus,
                'is_active' => $isActive,
                'address' => $request->address,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'user_updated',
                'description' => "Updated user: {$user->name} ({$user->email})",
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json(['success' => true, 'message' => 'User updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update user: ' . $e->getMessage()], 500);
        }
    }

    public function toggleUserStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $newStatus = $user->status === 'approved' ? 'suspended' : 'approved';
            $newActiveStatus = $newStatus === 'approved';
            
            $user->update([
                'status' => $newStatus,
                'is_active' => $newActiveStatus
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'user_status_changed',
                'description' => "Changed status of user {$user->name} ({$user->email}) to {$newStatus}",
                'severity' => 'warning',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'User status updated successfully',
                'status' => $newStatus,
                'is_active' => $newActiveStatus
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update user status'], 500);
        }
    }

    public function destroyUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Protect superadmin accounts from deletion at the backend level
            if ($user->role === 'superadmin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Superadmin accounts cannot be deleted.'
                ], 403);
            }
            $userName = $user->name;
            $userEmail = $user->email;
            $user->delete();

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'user_deleted',
                'description' => "Deleted user: {$userName} ({$userEmail})",
                'severity' => 'danger',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete user'], 500);
        }
    }

    // Admin Management Methods
    public function getPendingAdmins()
    {
        try {
            $pendingAdmins = User::where('role', 'admin')
                ->where('status', 'pending')
                ->get();
            
            return response()->json(['success' => true, 'data' => $pendingAdmins]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get pending admins'], 500);
        }
    }

    public function getActiveAdmins()
    {
        try {
            $activeAdmins = User::where('role', 'admin')
                ->where('status', 'approved')
                ->get();
            
            return response()->json(['success' => true, 'data' => $activeAdmins]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get active admins'], 500);
        }
    }

    public function getAdminStats()
    {
        try {
            $stats = [
                'total' => User::where('role', 'admin')->count(),
                'active' => User::where('role', 'admin')->where('status', 'approved')->count(),
                'pending' => User::where('role', 'admin')->where('status', 'pending')->count(),
                'rejected' => User::where('role', 'admin')->where('status', 'rejected')->count(),
            ];
            
            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get admin stats'], 500);
        }
    }

    public function approveAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $admin->update([
                'status' => 'approved',
                'is_active' => true
            ]);
            
            return response()->json(['success' => true, 'message' => 'Admin approved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to approve admin'], 500);
        }
    }

    public function rejectAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $admin->update([
                'status' => 'rejected',
                'is_active' => false
            ]);
            
            return response()->json(['success' => true, 'message' => 'Admin rejected successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to reject admin'], 500);
        }
    }

    public function deactivateAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $admin->update([
                'status' => 'rejected',
                'is_active' => false
            ]);
            
            return response()->json(['success' => true, 'message' => 'Admin deactivated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to deactivate admin'], 500);
        }
    }

    public function contactAdmin(Request $request)
    {
        try {
            $request->validate([
                'admin_id' => 'required|exists:users,id',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
            ]);

            // Get the admin being contacted
            $admin = User::findOrFail($request->admin_id);
            
            // Log the contact attempt
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'admin_contacted',
                'description' => "SuperAdmin contacted admin: {$admin->name} - Subject: {$request->subject}",
                'severity' => 'info',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Create a notification for the admin
            \App\Models\Notification::create([
                'type' => 'message',
                'title' => 'Message from SuperAdmin',
                'message' => "{$request->subject} - {$request->message}",
                'icon' => 'fas fa-envelope',
                'action_url' => '#', // Will open modal instead of navigation
                'severity' => 'info',
                'is_read' => false,
                'recipient_id' => $request->admin_id,
                'metadata' => [
                    'sender_id' => Auth::id(),
                    'sender_name' => Auth::user()->name,
                    'subject' => $request->subject,
                    'message_type' => 'contact_admin'
                ]
            ]);

            return response()->json(['success' => true, 'message' => 'Message sent to admin successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Contact admin failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send message'], 500);
        }
    }

    public function storeAdmin(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|string|max:255|unique:users,username',
                'phone' => ['nullable', 'regex:/^\d{11}$/'],
                'barangay' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|in:approved,pending',
                'role' => 'required|in:admin',
            ]);

            // Create the admin user
            $admin = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'barangay' => $request->barangay,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
                'is_active' => $request->status === 'approved',
            ]);

            // Log the admin creation
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'admin_created',
                'description' => "New admin '{$admin->name}' created by super admin",
                'severity' => 'low'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin created successfully',
                'admin' => $admin
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating admin: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create admin. Please try again.'
            ], 500);
        }
    }

    // Farm Management Methods
    public function getFarmStats()
    {
        try {
            return response()->json([
                'success' => true,
                'total' => Farm::count(),
                'active' => Farm::where('status', 'active')->count(),
                'inactive' => Farm::where('status', 'inactive')->count(),
                'barangays' => Farm::query()->distinct('location')->count('location'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get farm stats'], 500);
        }
    }

    /**
     * List farms with owner data for superadmin UI
     */
    public function getFarmsList()
    {
        try {
            $farms = Farm::with('owner')->get()->map(function ($farm) {
                return [
                    'id' => $farm->id,
                    'farm_id' => $farm->id,
                    'name' => $farm->name,
                    'barangay' => $farm->location,
                    'status' => $farm->status,
                    'created_at' => $farm->created_at,
                    'owner' => [
                        'name' => $farm->owner->name ?? trim(($farm->owner->first_name ?? '') . ' ' . ($farm->owner->last_name ?? '')),
                        'email' => $farm->owner->email ?? null,
                        'phone' => $farm->owner->phone ?? null,
                    ],
                ];
            });

            return response()->json(['success' => true, 'data' => $farms]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load farms'], 500);
        }
    }

    public function showFarm($id)
    {
        try {
            // Load farm with owner and livestock for accurate, real-time metrics
            $farm = Farm::with(['owner', 'livestock'])->findOrFail($id);

            // Real-time livestock count
            $livestockCount = $farm->livestock ? $farm->livestock->count() : 0;

            // Real-time monthly production for this farm (current month)
            $monthStart = now()->startOfMonth();
            $monthlyProduction = (float) ProductionRecord::where('farm_id', $farm->id)
                ->where(function ($q) use ($monthStart) {
                    $q->whereDate('production_date', '>=', $monthStart)
                      ->orWhereDate('created_at', '>=', $monthStart);
                })
                ->sum('milk_quantity');

            // Payload consumed by the farm details modal
            $payload = [
                'id' => $farm->id,
                'farm_id' => $farm->id,
                'name' => $farm->name,
                'location' => $farm->location,
                'barangay' => $farm->location,
                'status' => $farm->status,
                'description' => $farm->description,
                'created_at' => $farm->created_at,
                'updated_at' => $farm->updated_at,
                'owner' => [
                    'name' => $farm->owner->name ?? trim(($farm->owner->first_name ?? '') . ' ' . ($farm->owner->last_name ?? '')),
                    'email' => $farm->owner->email ?? null,
                    'phone' => $farm->owner->phone ?? null,
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $payload,
                'livestock_count' => $livestockCount,
                'monthly_production' => round($monthlyProduction, 1),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Farm not found'], 404);
        }
    }

    public function updateFarmStatus($id)
    {
        try {
            $farm = Farm::findOrFail($id);
            $newStatus = request('status');
            if (!in_array($newStatus, ['active', 'inactive'])) {
                return response()->json(['success' => false, 'message' => 'Invalid status'], 422);
            }
            $farm->update(['status' => $newStatus]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Farm status updated successfully',
                'status' => $farm->status
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update farm status'], 500);
        }
    }

    public function destroyFarm($id)
    {
        try {
            $farm = Farm::findOrFail($id);
            $farm->delete();
            
            return response()->json(['success' => true, 'message' => 'Farm deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete farm'], 500);
        }
    }

    public function importFarms(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt',
            ]);

            // Placeholder import logic
            $imported = 0;
            return response()->json(['success' => true, 'imported' => $imported]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to import farms'], 500);
        }
    }

    /**
     * Update an existing farm from SuperAdmin UI
     */
    public function updateFarm($id, Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                // Required to satisfy non-nullable `location` column
                'barangay' => 'required|string|max:255',
                'status' => 'required|in:active,inactive',
                'owner_name' => 'nullable|string|max:255',
                'owner_email' => 'nullable|email',
                'owner_phone' => ['nullable', 'regex:/^\d{11}$/'],
            ]);

            $farm = \App\Models\Farm::findOrFail($id);
            $farm->name = $validated['name'];
            $farm->location = $validated['barangay'];
            $farm->status = $validated['status'];
            $farm->description = $request->input('description');
            $farm->save();

            return response()->json([
                'success' => true,
                'message' => 'Farm updated successfully',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update farm'
            ], 500);
        }
    }

    /**
     * Store a newly created farm from SuperAdmin UI
     */
    public function storeFarm(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                // Required to satisfy non-nullable `location` column
                'barangay' => 'required|string|max:255',
                'status' => 'required|in:active,inactive',
                'owner_name' => 'nullable|string|max:255',
                'owner_email' => 'nullable|email',
                'owner_phone' => ['nullable', 'regex:/^\d{11}$/'],
            ]);

            // Determine owner_id to satisfy non-nullable foreign key
            $ownerId = null;
            if ($request->filled('owner_email')) {
                $existing = User::where('email', $request->input('owner_email'))->first();
                if ($existing) {
                    $ownerId = $existing->id;
                }
            }
            // Fallback to current authenticated user if no owner provided/found
            if (!$ownerId) {
                $ownerId = Auth::id();
            }

            $farm = new \App\Models\Farm();
            $farm->name = $validated['name'];
            $farm->location = $validated['barangay'];
            $farm->status = $validated['status'];
            $farm->description = $request->input('description');
            $farm->owner_id = $ownerId;
            $farm->save();

            // Optionally handle owner info if model supports relations
            // Skipping owner creation/linking due to unspecified schema

            return response()->json([
                'success' => true,
                'message' => 'Farm created successfully',
                'data' => [
                    'id' => $farm->id,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create farm'
            ], 500);
        }
    }

    // Settings Methods
    public function getSettings()
    {
        try {
            $settings = [
                'general' => [
                    'site_name' => 'LBDAIRY',
                    'site_description' => 'Modern Dairy Management System',
                ],
                'security' => [
                    'password_min_length' => 8,
                    'session_timeout' => 120,
                ],
                'notifications' => [
                    'email_notifications' => true,
                    'sms_notifications' => false,
                ],
            ];
            
            return response()->json(['success' => true, 'settings' => $settings]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get settings'], 500);
        }
    }

    public function updateGeneralSettings(Request $request)
    {
        try {
            // This would typically update database settings
            return response()->json(['success' => true, 'message' => 'General settings updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update general settings'], 500);
        }
    }

    public function updateSecuritySettings(Request $request)
    {
        try {
            // This would typically update security settings
            return response()->json(['success' => true, 'message' => 'Security settings updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update security settings'], 500);
        }
    }

    public function updateNotificationSettings(Request $request)
    {
        try {
            // This would typically update notification settings
            return response()->json(['success' => true, 'message' => 'Notification settings updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update notification settings'], 500);
        }
    }

    public function testEmail(Request $request)
    {
        try {
            // This would typically send a test email
            return response()->json(['success' => true, 'message' => 'Test email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send test email'], 500);
        }
    }

    public function createBackup(Request $request)
    {
        try {
            // This would typically create a system backup
            return response()->json(['success' => true, 'message' => 'Backup created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create backup'], 500);
        }
    }

    public function clearCache(Request $request)
    {
        try {
            // This would typically clear system cache
            return response()->json(['success' => true, 'message' => 'Cache cleared successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to clear cache'], 500);
        }
    }

    public function optimizeDatabase(Request $request)
    {
        try {
            // This would typically optimize the database
            return response()->json(['success' => true, 'message' => 'Database optimized successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to optimize database'], 500);
        }
    }

    public function getSettingsLogs(Request $request)
    {
        try {
            // This would typically return settings change logs
            return response()->json(['success' => true, 'logs' => []]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get settings logs'], 500);
        }
    }

    public function clearSettingsLogs(Request $request)
    {
        try {
            // This would typically clear settings logs
            return response()->json(['success' => true, 'message' => 'Settings logs cleared successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to clear settings logs'], 500);
        }
    }

    public function exportSettingsLogs(Request $request)
    {
        try {
            // This would typically export settings logs
            return response()->json(['success' => true, 'message' => 'Settings logs exported successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to export settings logs'], 500);
        }
    }

    

    

    

    

    

    /**
     * Create a farmer (Super Admin scope)
     */
    public function storeFarmer(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'phone' => ['nullable', 'regex:/^\d{11}$/'],
                'address' => 'nullable|string|max:500',
                'barangay' => 'nullable|string|max:255',
                'password' => 'required|string|min:8|confirmed',
                // Farm fields
                'farm_name' => 'required|string|max:255',
                'farm_location' => 'nullable|string|max:500',
                'farm_size' => 'nullable|numeric|min:0',
                'farm_description' => 'nullable|string|max:1000',
            ]);

            // Split full name into first and last names for proper accessor compatibility
            $fullName = trim((string) $request->name);
            $firstName = $fullName;
            $lastName = '';
            if (strpos($fullName, ' ') !== false) {
                $parts = preg_split('/\s+/', $fullName);
                $firstName = array_shift($parts);
                $lastName = trim(implode(' ', $parts));
            }

            $farmer = User::create([
                'name' => $fullName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'barangay' => $request->barangay,
                'password' => Hash::make($request->password),
                'role' => 'farmer',
                'is_active' => true,
                'status' => 'approved',
            ]);

            // Create a default farm for the new farmer
            $farm = Farm::create([
                'name' => $request->farm_name,
                'description' => $request->farm_description,
                'location' => $request->farm_location,
                'size' => $request->farm_size,
                'owner_id' => $farmer->id,
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Farmer created successfully!',
                'farmer' => $farmer,
                'farm' => $farm,
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create farmer',
            ], 500);
        }
    }

    /**
     * Get livestock population trends (last N months) for super admin dashboard.
     */
    public function getLivestockTrends(Request $request)
    {
        try {
            $months = (int) $request->get('months', 6);
            if ($months < 1 || $months > 24) {
                $months = 6;
            }

            $startDate = now()->startOfMonth()->subMonths($months - 1);

            $aggregates = Livestock::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, type, COUNT(*) as cnt")
                ->where('created_at', '>=', $startDate)
                ->groupBy('ym', 'type')
                ->orderBy('ym')
                ->get();

            // Build month keys and labels
            $labels = [];
            $ymKeys = [];
            $cursor = $startDate->copy();
            for ($i = 0; $i < $months; $i++) {
                $labels[] = $cursor->format('M');
                $ymKeys[] = $cursor->format('Y-m');
                $cursor->addMonth();
            }

            $seriesCow = array_fill(0, $months, 0);
            $seriesGoat = array_fill(0, $months, 0);

            foreach ($aggregates as $row) {
                $index = array_search($row->ym, $ymKeys, true);
                if ($index === false) {
                    continue;
                }
                if ($row->type === 'cow') {
                    $seriesCow[$index] = (int) $row->cnt;
                } elseif ($row->type === 'goat') {
                    $seriesGoat[$index] = (int) $row->cnt;
                }
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Cattle',
                        'data' => $seriesCow,
                        'borderColor' => '#fca700',
                        'backgroundColor' => 'rgba(252, 167, 0, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ],
                    [
                        'label' => 'Goats',
                        'data' => $seriesGoat,
                        'borderColor' => '#28a745',
                        'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load livestock trends'], 500);
        }
    }

    /**
     * Show admin details.
     */
    public function showAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            
            // Check if user is online
            $admin->is_online = DB::table('sessions')
                ->where('user_id', $admin->id)
                ->where('last_activity', '>=', now()->subMinutes(5))
                ->exists();

            return response()->json([
                'success' => true,
                'data' => $admin
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found'
            ], 404);
        }
    }

    /**
     * Update admin.
     */
    public function updateAdmin(Request $request, $id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($admin->id)],
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($admin->id)],
                'phone' => ['nullable', 'regex:/^\d{11}$/'],
                'barangay' => 'required|string|max:255',
                'role' => 'required|string|in:admin',
                'password' => 'nullable|string|min:8',
            ]);

            $updateData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'barangay' => $request->barangay,
                'role' => $request->role,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $admin->update($updateData);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'admin_updated',
                'description' => "Admin '{$admin->first_name} {$admin->last_name}' updated by super admin",
                'severity' => 'info'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update admin'
            ], 500);
        }
    }

    /**
     * Delete admin.
     */
    public function destroyAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $adminName = $admin->first_name . ' ' . $admin->last_name;
            
            $admin->delete();

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'admin_deleted',
                'description' => "Admin '{$adminName}' deleted by super admin",
                'severity' => 'warning'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete admin'
            ], 500);
        }
    }

    /**
     * Show manage farmers page.
     */
    public function manageFarmers()
    {
        try {
            // Get all farmers (users with role 'farmer')
            $farmers = User::where('role', 'farmer')
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate statistics
            $totalFarmers = User::where('role', 'farmer')->count();
            // Align with system-wide statuses (approved/pending/rejected)
            $activeFarmers = User::where('role', 'farmer')->where('status', 'approved')->count();
            $pendingFarmers = User::where('role', 'farmer')->where('status', 'pending')->count();
            $totalFarms = Farm::count();

            return view('superadmin.manage-farmers', compact(
                'farmers',
                'totalFarmers',
                'activeFarmers',
                'pendingFarmers',
                'totalFarms'
            ));

        } catch (\Exception $e) {
            Log::error('Error in manageFarmers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load farmers data. Please try again.');
        }
    }

    /**
     * Get farmer details for modal display.
     */
    public function getFarmerDetails($id)
    {
        try {
            $farmer = User::where('role', 'farmer')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $farmer->id,
                    'name' => $farmer->first_name . ' ' . $farmer->last_name,
                    'email' => $farmer->email,
                    'phone' => $farmer->phone,
                    'username' => $farmer->username,
                    'barangay' => $farmer->barangay,
                    'status' => $farmer->status,
                    'created_at' => $farmer->created_at,
                    'updated_at' => $farmer->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getFarmerDetails: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Farmer not found'
            ], 404);
        }
    }

    /**
     * Update farmer information.
     */
    public function updateFarmer(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
                'phone' => ['nullable', 'regex:/^\d{11}$/'],
                'barangay' => 'required|string|max:255',
                'status' => 'required|in:pending,approved,rejected',
                'password' => 'nullable|string|min:8',
            ]);

            $farmer = User::where('role', 'farmer')->findOrFail($id);
            
            $updateData = [
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'barangay' => $request->barangay,
                'status' => $request->status,
            ];

            // Handle name update (will automatically split into first_name and last_name)
            if ($request->filled('name')) {
                $farmer->name = $request->name;
            }

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $farmer->update($updateData);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'farmer_updated',
                'description' => "Farmer '{$farmer->name}' updated by super admin",
                'severity' => 'info'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Farmer updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating farmer: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update farmer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete farmer.
     */
    public function deleteFarmer($id)
    {
        try {
            $farmer = User::where('role', 'farmer')->findOrFail($id);
            $farmerName = $farmer->name;
            
            $farmer->delete();

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'farmer_deleted',
                'description' => "Farmer '{$farmerName}' deleted by super admin",
                'severity' => 'warning'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Farmer deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting farmer: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete farmer'
            ], 500);
        }
    }

    // Analysis API: Summary KPIs for manage-analysis page
    public function getAnalysisSummary()
    {
        try {
            $totalFarms = \App\Models\Farm::count();
            $totalLivestock = \App\Models\Livestock::count();
            $monthStart = now()->startOfMonth();
            $monthlyProduction = \App\Models\ProductionRecord::where(function($q) use ($monthStart) {
                $q->whereDate('production_date', '>=', $monthStart)
                  ->orWhereDate('created_at', '>=', $monthStart);
            })->sum('milk_quantity') ?? 0;

            $expected = max(1, $totalLivestock) * 15; // 15L per livestock baseline
            $efficiency = $expected > 0 ? round(min(100, ($monthlyProduction / $expected) * 100)) : 0;

            return response()->json([
                'success' => true,
                'totals' => [
                    'farms' => $totalFarms,
                    'livestock' => $totalLivestock,
                    'monthly_production_liters' => round($monthlyProduction, 1),
                    'efficiency_percent' => $efficiency,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load summary'], 500);
        }
    }

    // Analysis API: Farm Performance Overview (by last N days)
    public function getFarmPerformanceData(\Illuminate\Http\Request $request)
    {
        try {
            $days = (int) $request->get('days', 30);
            if ($days < 7) $days = 7;
            if ($days > 365) $days = 365;

            $start = now()->startOfDay()->subDays($days - 1);

            $rows = \App\Models\ProductionRecord::selectRaw('DATE(COALESCE(production_date, created_at)) as d, SUM(milk_quantity) as total')
                ->where(function($q) use ($start) {
                    $q->whereDate('production_date', '>=', $start)
                      ->orWhereDate('created_at', '>=', $start);
                })
                ->groupBy('d')
                ->orderBy('d')
                ->get();

            $labels = [];
            $data = [];
            $cursor = $start->copy();
            $map = $rows->keyBy('d');
            for ($i = 0; $i < $days; $i++) {
                $key = $cursor->format('Y-m-d');
                $labels[] = $cursor->format('M d');
                $data[] = (float) ($map[$key]->total ?? 0);
                $cursor->addDay();
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'dataset' => [
                    'label' => 'Production (L)',
                    'data' => $data,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load farm performance'], 500);
        }
    }

    // Analysis API: Livestock distribution by type
    public function getLivestockDistributionData()
    {
        try {
            $rows = \App\Models\Livestock::selectRaw('COALESCE(type, "Unknown") as type, COUNT(*) as cnt')
                ->groupBy('type')
                ->orderByDesc('cnt')
                ->get();

            return response()->json([
                'success' => true,
                'labels' => $rows->pluck('type'),
                'data' => $rows->pluck('cnt')->map(fn($v) => (int) $v),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load livestock distribution'], 500);
        }
    }

    // Analysis API: Production trends (monthly totals and related series)
    public function getProductionTrendsData()
    {
        try {
            $monthsBack = 12;
            $start = now()->startOfMonth()->subMonths($monthsBack - 1);

            $rows = \App\Models\ProductionRecord::selectRaw("DATE_FORMAT(COALESCE(production_date, created_at), '%Y-%m') as ym, SUM(milk_quantity) as total")
                ->where(function($q) use ($start) {
                    $q->whereDate('production_date', '>=', $start)
                      ->orWhereDate('created_at', '>=', $start);
                })
                ->groupBy('ym')
                ->orderBy('ym')
                ->get();

            $labels = [];
            $data = [];
            $avgProduction = [];
            $activeFarms = [];
            $activeLivestock = [];

            $map = $rows->keyBy('ym');
            $cursor = $start->copy();
            for ($i = 0; $i < $monthsBack; $i++) {
                $ym = $cursor->format('Y-m');
                $labels[] = $cursor->format('M');
                $total = (float) ($map[$ym]->total ?? 0);
                $data[] = round($total, 1);
                $daysInMonth = (int) $cursor->daysInMonth;
                $avgProduction[] = $daysInMonth > 0 ? round($total / $daysInMonth, 1) : 0;

                // Active farms and livestock as of the end of this month
                $monthEnd = $cursor->copy()->endOfMonth();
                $activeFarms[] = \App\Models\Farm::where('status', 'active')
                    ->where('created_at', '<=', $monthEnd)
                    ->count();
                $activeLivestock[] = \App\Models\Livestock::where('status', 'active')
                    ->where('created_at', '<=', $monthEnd)
                    ->count();
                $cursor->addMonth();
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'data' => $data,
                'avgProduction' => $avgProduction,
                'activeFarms' => $activeFarms,
                'activeLivestock' => $activeLivestock,
                'summary' => [ 'total' => array_sum($data) ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load production trends'], 500);
        }
    }

    // Analysis API: Top performing farms (current month)
    public function getTopPerformingFarms()
    {
        try {
            $monthStart = now()->startOfMonth();
            $farms = \App\Models\Farm::with(['livestock' => function($q) { $q->select('id','farm_id'); }])
                ->select('id','name','location','status','owner_id')
                ->get()
                ->map(function($farm) use ($monthStart) {
                    $livestockCount = (int) ($farm->livestock ? $farm->livestock->count() : 0);
                    $monthlyProduction = (float) \App\Models\ProductionRecord::where('farm_id', $farm->id)
                        ->where(function($q) use ($monthStart) {
                            $q->whereDate('production_date', '>=', $monthStart)
                              ->orWhereDate('created_at', '>=', $monthStart);
                        })
                        ->sum('milk_quantity');
                    $expected = max(1, $livestockCount) * 15;
                    $performance = $expected > 0 ? min(100, ($monthlyProduction / $expected) * 100) : 0;
                    return [
                        'name' => $farm->name ?? 'Unnamed Farm',
                        'location' => $farm->location ?? 'N/A',
                        'livestock_count' => $livestockCount,
                        'monthly_production' => round($monthlyProduction, 1),
                        'performance_score' => (int) round($performance),
                    ];
                })
                ->sortByDesc('performance_score')
                ->take(5)
                ->values()
                ->all();

            return response()->json(['success' => true, 'farms' => $farms]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load top farms'], 500);
        }
    }

    /**
     * Get audit log chart data for dashboard charts
     */
    public function getAuditLogChartData()
    {
        try {
            // Get severity distribution data
            $severityData = AuditLog::select('severity', DB::raw('count(*) as count'))
                ->whereNotNull('severity')
                ->groupBy('severity')
                ->get()
                ->pluck('count', 'severity')
                ->toArray();

            // Prepare data for Chart.js
            $severityLabels = [];
            $severityCounts = [];
            $severityColors = [];

            // Define simplified 3-category severity levels and their colors
            $severityLevels = [
                'low' => ['label' => 'Low', 'color' => '#387057'],
                'medium' => ['label' => 'Medium', 'color' => '#ffc107'],
                'high' => ['label' => 'High', 'color' => '#c82333']
            ];

            // Map existing severity values to new 3-category system
            $severityMapping = [
                'debug' => 'low',
                'info' => 'low',
                'warning' => 'medium',
                'error' => 'high',
                'critical' => 'high',
                'danger' => 'high',
            ];

            // Count events by new categories
            $categoryCounts = ['low' => 0, 'medium' => 0, 'high' => 0];
            
            foreach ($severityData as $severity => $count) {
                $category = $severityMapping[$severity] ?? 'low';
                $categoryCounts[$category] += $count;
            }

            foreach ($severityLevels as $level => $config) {
                $severityLabels[] = $config['label'];
                $severityCounts[] = $categoryCounts[$level];
                $severityColors[] = $config['color'];
            }

            // Get timeline data for the last 24 hours (hourly breakdown)
            $timelineData = AuditLog::select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('count(*) as count')
                )
                ->where('created_at', '>=', now()->subDay())
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            // Create array for all 24 hours (0-23) with default count of 0
            $hourlyData = [];
            for ($i = 0; $i < 24; $i++) {
                $hourlyData[$i] = 0;
            }

            // Fill in actual data
            foreach ($timelineData as $data) {
                $hourlyData[$data->hour] = $data->count;
            }

            $timelineLabels = [];
            $timelineCounts = [];
            
            // Generate labels and data arrays
            for ($i = 0; $i < 24; $i++) {
                $timelineLabels[] = sprintf('%02d:00', $i);
                $timelineCounts[] = $hourlyData[$i];
            }

            // Calculate additional statistics
            $totalEvents = array_sum($severityCounts);
            $totalEvents24h = array_sum($timelineCounts);
            $avgPerHour = $totalEvents24h > 0 ? round($totalEvents24h / 24, 1) : 0;
            
            // Find peak hour
            $maxHourIndex = array_search(max($timelineCounts), $timelineCounts);
            $peakHour = $maxHourIndex !== false ? $timelineLabels[$maxHourIndex] : '00:00';
            
            // Calculate system health metrics
            $highSeverityCount = $severityCounts[2] ?? 0; // High severity events
            $mediumSeverityCount = $severityCounts[1] ?? 0; // Medium severity events
            $lowSeverityCount = $severityCounts[0] ?? 0; // Low severity events
            
            // Get recent critical events (last 24 hours)
            $recentCriticalEvents = AuditLog::where('created_at', '>=', now()->subDay())
                ->whereIn('severity', ['error', 'critical'])
                ->count();

            return response()->json([
                'success' => true,
                'severity' => [
                    'labels' => $severityLabels,
                    'data' => $severityCounts,
                    'colors' => $severityColors
                ],
                'timeline' => [
                    'labels' => $timelineLabels,
                    'data' => $timelineCounts
                ],
                'statistics' => [
                    'total_events' => $totalEvents,
                    'total_events_24h' => $totalEvents24h,
                    'avg_per_hour' => $avgPerHour,
                    'peak_hour' => $peakHour,
                    'high_severity_count' => $highSeverityCount,
                    'medium_severity_count' => $mediumSeverityCount,
                    'low_severity_count' => $lowSeverityCount,
                    'recent_critical_events' => $recentCriticalEvents
                ],
                'debug' => [
                    'severity_raw' => $severityData,
                    'timeline_raw' => $timelineData->toArray()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get audit log chart data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load chart data',
                'severity' => [
                    'labels' => ['Low', 'Medium', 'High'],
                    'data' => [0, 0, 0],
                    'colors' => ['#387057', '#ffc107', '#c82333']
                ],
                'timeline' => [
                    'labels' => array_map(function($i) { return sprintf('%02d:00', $i); }, range(0, 23)),
                    'data' => array_fill(0, 24, 0)
                ]
            ], 500);
        }
    }

    /**
     * Get notifications for the current super admin
     */
    public function getNotifications()
    {
        try {
            $notifications = Notification::where('recipient_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $unreadCount = Notification::where('recipient_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load notifications'
            ], 500);
        }
    }

    /**
     * Mark a notification as read
     */
    public function markNotificationAsRead($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            if ((int)$notification->recipient_id !== (int)Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden'
                ], 403);
            }

            $notification->markAsRead(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read'
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        try {
            Notification::where('recipient_id', Auth::id())
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                    'read_by' => Auth::id()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark all notifications as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read'
            ], 500);
        }
    }
}
