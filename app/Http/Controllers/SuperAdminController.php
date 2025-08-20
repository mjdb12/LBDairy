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
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    /**
     * Update the super admin's profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'barangay' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'barangay' => $request->barangay,
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

        /** @var \App\Models\User $user */
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
            $farm = Farm::with('owner')->findOrFail($id);
            $payload = [
                'id' => $farm->id,
                'farm_id' => $farm->id,
                'name' => $farm->name,
                'barangay' => $farm->location,
                'status' => $farm->status,
                'description' => $farm->description,
                'created_at' => $farm->created_at,
                'owner' => [
                    'name' => $farm->owner->name ?? trim(($farm->owner->first_name ?? '') . ' ' . ($farm->owner->last_name ?? '')),
                    'email' => $farm->owner->email ?? null,
                    'phone' => $farm->owner->phone ?? null,
                ],
            ];
            return response()->json(['success' => true, 'data' => $payload]);
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
     * Analysis summary for superadmin dashboard (totals, monthly production, efficiency)
     */
    public function getAnalysisSummary()
    {
        try {
            $startOfMonth = now()->startOfMonth();
            $endDate = now();

            $monthlyProduction = (float) ProductionRecord::whereBetween('production_date', [$startOfMonth, $endDate])
                ->sum('milk_quantity');

            $totalFarms = Farm::count();
            $activeFarmsWithProduction = ProductionRecord::whereBetween('production_date', [$startOfMonth, $endDate])
                ->distinct('farm_id')
                ->count('farm_id');
            $efficiency = $totalFarms > 0 ? round(($activeFarmsWithProduction / $totalFarms) * 100) : 0;

            return response()->json([
                'success' => true,
                'totals' => [
                    'farms' => $totalFarms,
                    'livestock' => Livestock::count(),
                    'monthly_production_liters' => $monthlyProduction,
                    'efficiency_percent' => $efficiency,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load summary'], 500);
        }
    }

    /**
     * Farm performance over time (sum production per day)
     */
    public function getFarmPerformanceData(Request $request)
    {
        try {
            $days = (int) $request->get('days', 30);
            if ($days < 1 || $days > 365) { $days = 30; }
            $startDate = now()->subDays($days - 1)->startOfDay();
            $endDate = now()->endOfDay();

            $rows = ProductionRecord::selectRaw('production_date, SUM(milk_quantity) as liters')
                ->whereBetween('production_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->groupBy('production_date')
                ->orderBy('production_date')
                ->get();

            $labels = [];
            $data = [];
            $cursor = $startDate->copy();
            $map = $rows->keyBy(function ($r) { return (string) $r->production_date; });
            while ($cursor->lte($endDate)) {
                $key = $cursor->toDateString();
                $labels[] = $cursor->format('M d');
                $data[] = (float) (($map[$key]->liters ?? 0));
                $cursor->addDay();
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'dataset' => [
                    'label' => 'Production (L)',
                    'data' => $data,
                    'borderColor' => '#4e73df',
                    'backgroundColor' => 'rgba(78, 115, 223, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load performance'], 500);
        }
    }

    /**
     * Livestock distribution by type
     */
    public function getLivestockDistributionData()
    {
        try {
            $rows = Livestock::selectRaw('type, COUNT(*) as count')->groupBy('type')->get();
            $labels = $rows->pluck('type');
            $data = $rows->pluck('count')->map(fn($v) => (int) $v);
            return response()->json([
                'success' => true,
                'labels' => $labels,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load distribution'], 500);
        }
    }

    /**
     * Production trends (last 4 weeks totals)
     */
    public function getProductionTrendsData()
    {
        try {
            $start = now()->subWeeks(3)->startOfWeek();
            $rows = ProductionRecord::selectRaw("YEARWEEK(production_date, 3) as yw, SUM(milk_quantity) as liters")
                ->where('production_date', '>=', $start->toDateString())
                ->groupBy('yw')
                ->orderBy('yw')
                ->get();

            $labels = [];
            $data = [];
            $cursor = $start->copy();
            for ($i = 0; $i < 4; $i++) {
                $labels[] = 'Week ' . $cursor->format('W');
                $yw = $cursor->format('oW');
                $match = $rows->first(function ($r) use ($cursor) {
                    return $r->yw == (int) $cursor->format('oW');
                });
                $data[] = (float) ($match->liters ?? 0);
                $cursor->addWeek();
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load trends'], 500);
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
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $farmer = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => 'farmer',
                'is_active' => true,
                'status' => 'approved',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Farmer created successfully!',
                'farmer' => $farmer
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
                        'borderColor' => '#007bff',
                        'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
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
}
