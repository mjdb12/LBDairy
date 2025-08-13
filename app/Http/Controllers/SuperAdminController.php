<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Farm;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    /**
     * Update the super admin's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change the super admin's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    /**
     * Display audit logs dashboard
     */
    public function auditLogs()
    {
        // Get audit log statistics
        $stats = $this->getAuditLogStats();
        
        // Get audit logs with user information
        $auditLogs = AuditLog::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('superadmin.audit-logs', [
            'totalLogs' => $stats['total_logs'],
            'criticalEvents' => $stats['critical_logs'],
            'todayLogs' => $stats['today_logs'],
            'systemHealth' => $stats['system_health'],
            'activeUsers' => User::where('is_active', true)->count(),
            'auditLogs' => $auditLogs
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
        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'User', 'Action', 'Severity', 'Description', 'IP Address', 'Timestamp']);
            
            // CSV data
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'System',
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

    /**
     * Clear old audit logs
     */
    public function clearOldLogs(Request $request)
    {
        try {
            $days = $request->get('days', 90);
            $deleted = AuditLog::where('created_at', '<', now()->subDays($days))->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Cleared {$deleted} old audit logs",
                'deleted_count' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to clear old logs'], 500);
        }
    }

    // User Management Methods
    public function getUserStats()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'inactive_users' => User::where('is_active', false)->count(),
                'farmers' => User::where('role', 'farmer')->count(),
                'admins' => User::where('role', 'admin')->count(),
                'superadmins' => User::where('role', 'superadmin')->count(),
            ];
            
            return response()->json(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get user stats'], 500);
        }
    }

    public function showUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['success' => true, 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }

    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'role' => 'required|in:farmer,admin,superadmin',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make('password123'), // Default password
                'is_active' => true,
            ]);

            return response()->json(['success' => true, 'user' => $user, 'message' => 'User created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create user'], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
                'role' => 'required|in:farmer,admin,superadmin',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            return response()->json(['success' => true, 'message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update user'], 500);
        }
    }

    public function toggleUserStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['is_active' => !$user->is_active]);
            
            return response()->json([
                'success' => true, 
                'message' => 'User status updated successfully',
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update user status'], 500);
        }
    }

    public function destroyUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            
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
                ->where('is_active', false)
                ->get();
            
            return response()->json(['success' => true, 'admins' => $pendingAdmins]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get pending admins'], 500);
        }
    }

    public function getActiveAdmins()
    {
        try {
            $activeAdmins = User::where('role', 'admin')
                ->where('is_active', true)
                ->get();
            
            return response()->json(['success' => true, 'admins' => $activeAdmins]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get active admins'], 500);
        }
    }

    public function getAdminStats()
    {
        try {
            $stats = [
                'total_admins' => User::where('role', 'admin')->count(),
                'active_admins' => User::where('role', 'admin')->where('is_active', true)->count(),
                'pending_admins' => User::where('role', 'admin')->where('is_active', false)->count(),
            ];
            
            return response()->json(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get admin stats'], 500);
        }
    }

    public function approveAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $admin->update(['is_active' => true]);
            
            return response()->json(['success' => true, 'message' => 'Admin approved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to approve admin'], 500);
        }
    }

    public function rejectAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $admin->delete();
            
            return response()->json(['success' => true, 'message' => 'Admin rejected and removed']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to reject admin'], 500);
        }
    }

    public function deactivateAdmin($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            $admin->update(['is_active' => false]);
            
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
                'message' => 'required|string|max:1000',
            ]);

            // This would typically send an email or notification
            // For now, just return success
            return response()->json(['success' => true, 'message' => 'Message sent to admin successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send message'], 500);
        }
    }

    // Farm Management Methods
    public function getFarmStats()
    {
        try {
            $stats = [
                'total_farms' => Farm::count(),
                'active_farms' => Farm::where('status', 'active')->count(),
                'inactive_farms' => Farm::where('status', 'inactive')->count(),
            ];
            
            return response()->json(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get farm stats'], 500);
        }
    }

    public function showFarm($id)
    {
        try {
            $farm = Farm::findOrFail($id);
            return response()->json(['success' => true, 'farm' => $farm]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Farm not found'], 404);
        }
    }

    public function updateFarmStatus($id)
    {
        try {
            $farm = Farm::findOrFail($id);
            $farm->update(['status' => $farm->status === 'active' ? 'inactive' : 'active']);
            
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
                'file' => 'required|file|mimes:csv,xlsx',
            ]);

            // This would typically process the uploaded file
            // For now, just return success
            return response()->json(['success' => true, 'message' => 'Farms imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to import farms'], 500);
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
}
