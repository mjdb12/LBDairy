<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AuditLog;
use App\Models\Issue;
use App\Models\User;
use App\Models\Farm;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Get notifications for super admin
     */
    public function getNotifications()
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'superadmin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // If user has cleared notifications this session, return empty
            if (session('notifications_cleared', false) === true) {
                return response()->json([
                    'success' => true,
                    'notifications' => [],
                    'unread_count' => 0
                ]);
            }

            $notifications = $this->generateNotifications();
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => count($notifications)
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load notifications',
                'notifications' => [],
                'unread_count' => 0
            ], 500);
        }
    }

    /**
     * Generate system notifications for super admin
     */
    private function generateNotifications()
    {
        $notifications = [];

        try {
            // Critical system alerts
            $criticalLogs = AuditLog::where('severity', 'critical')
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->count();
            
            if ($criticalLogs > 0) {
                $notifications[] = [
                    'id' => 'critical_logs',
                    'type' => 'critical',
                    'icon' => 'fas fa-exclamation-triangle',
                    'title' => 'Critical System Events',
                    'message' => "{$criticalLogs} critical system events detected in the last 7 days",
                    'time' => now()->diffForHumans(),
                    'action_url' => route('superadmin.audit-logs'),
                    'is_read' => false
                ];
            }

            // Pending admin approvals
            $pendingAdmins = User::where('role', 'admin')
                ->where('status', 'pending')
                ->count();
            
            if ($pendingAdmins > 0) {
                $notifications[] = [
                    'id' => 'pending_admins',
                    'type' => 'warning',
                    'icon' => 'fas fa-user-clock',
                    'title' => 'Pending Admin Approvals',
                    'message' => "{$pendingAdmins} admin registration(s) awaiting approval",
                    'time' => now()->diffForHumans(),
                    'action_url' => route('superadmin.admins'),
                    'is_read' => false
                ];
            }

            // New user registrations
            $newUsers = User::whereDate('created_at', today())->count();
            
            if ($newUsers > 0) {
                $notifications[] = [
                    'id' => 'new_users',
                    'type' => 'info',
                    'icon' => 'fas fa-user-plus',
                    'title' => 'New User Registrations',
                    'message' => "{$newUsers} new user(s) registered today",
                    'time' => now()->diffForHumans(),
                    'action_url' => route('superadmin.users'),
                    'is_read' => false
                ];
            }

            // High priority issues
            $urgentIssues = Issue::where('priority', 'High')
                ->whereIn('status', ['Pending', 'In Progress'])
                ->count();
            
            if ($urgentIssues > 0) {
                $notifications[] = [
                    'id' => 'urgent_issues',
                    'type' => 'danger',
                    'icon' => 'fas fa-exclamation-circle',
                    'title' => 'Urgent Issues',
                    'message' => "{$urgentIssues} high priority issue(s) require attention",
                    'time' => now()->diffForHumans(),
                    'action_url' => route('admin.manage-issues'),
                    'is_read' => false
                ];
            }

            // System performance alerts
            $recentLogs = AuditLog::whereDate('created_at', today())->count();
            
            if ($recentLogs > 100) {
                $notifications[] = [
                    'id' => 'high_activity',
                    'type' => 'warning',
                    'icon' => 'fas fa-chart-line',
                    'title' => 'High System Activity',
                    'message' => "{$recentLogs} system events logged today - unusual activity detected",
                    'time' => now()->diffForHumans(),
                    'action_url' => route('superadmin.audit-logs'),
                    'is_read' => false
                ];
            }

            // Farm registration alerts
            $pendingFarms = Farm::where('status', 'pending')->count();
            
            if ($pendingFarms > 0) {
                $notifications[] = [
                    'id' => 'pending_farms',
                    'type' => 'info',
                    'icon' => 'fas fa-tractor',
                    'title' => 'Pending Farm Registrations',
                    'message' => "{$pendingFarms} farm registration(s) awaiting approval",
                    'time' => now()->diffForHumans(),
                    'action_url' => route('superadmin.farms.index'),
                    'is_read' => false
                ];
            }

            // Database performance
            $dbSize = $this->getDatabaseSize();
            if ($dbSize > 100) { // MB
                $notifications[] = [
                    'id' => 'db_size',
                    'type' => 'warning',
                    'icon' => 'fas fa-database',
                    'title' => 'Database Size Alert',
                    'message' => "Database size is {$dbSize} MB - consider optimization",
                    'time' => now()->diffForHumans(),
                    'action_url' => route('superadmin.settings'),
                    'is_read' => false
                ];
            }

        } catch (\Exception $e) {
            Log::error('Error generating specific notifications: ' . $e->getMessage());
            // Return basic notification if there's an error
            $notifications[] = [
                'id' => 'system_error',
                'type' => 'danger',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'System Error',
                'message' => 'Unable to load system notifications - check system logs',
                'time' => now()->diffForHumans(),
                'action_url' => route('superadmin.audit-logs'),
                'is_read' => false
            ];
        }

        return $notifications;
    }

    /**
     * Get database size in MB
     */
    private function getDatabaseSize()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $result = DB::select("SELECT ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'size' FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
            
            if (!empty($result)) {
                return $result[0]->size;
            }
        } catch (\Exception $e) {
            Log::warning('Unable to get database size: ' . $e->getMessage());
        }
        
        return 0;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        try {
            $request->validate([
                'notification_id' => 'required|string'
            ]);

            $user = Auth::user();
            if (!$user || $user->role !== 'superadmin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // In a real system, you'd store read status in database
            // For now, we'll just return success
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark notification as read'
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            if (!$user || $user->role !== 'superadmin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Persist a session flag to suppress notifications for this session
            session(['notifications_cleared' => true]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark all notifications as read'
            ], 500);
        }
    }
}
