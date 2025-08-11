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
            'criticalLogs' => $stats['critical_logs'],
            'todayLogs' => $stats['today_logs'],
            'systemHealth' => $stats['system_health'],
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
}
