<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\AuditLog;
use App\Models\Issue;
use App\Models\User;
use App\Models\Farm;
use App\Models\Notification;
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

            // Check if notifications table exists
            if (!Schema::hasTable('notifications')) {
                // Return empty notifications if table doesn't exist
                return response()->json([
                    'success' => true,
                    'notifications' => [],
                    'unread_count' => 0
                ]);
            }

            // Get unread notifications from database
            $notifications = Notification::unread()
                ->recent()
                ->orderBy('created_at', 'desc')
                ->limit(10) // Limit to prevent performance issues
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->severity,
                        'icon' => $notification->icon,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'time' => $notification->created_at->diffForHumans(),
                        'action_url' => $notification->action_url,
                        'is_read' => $notification->is_read
                    ];
                });

            // Only generate system notifications if there are no unread notifications
            // and we haven't generated any in the last 5 minutes
            $lastGeneratedNotification = Notification::where('metadata->identifier', 'like', '%_generated')
                ->where('created_at', '>=', now()->subMinutes(5))
                ->first();

            if ($notifications->isEmpty() && !$lastGeneratedNotification) {
                try {
                    $this->generateSystemNotifications();
                    
                    // Get notifications again after generation
                    $notifications = Notification::unread()
                        ->recent()
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get()
                        ->map(function ($notification) {
                            return [
                                'id' => $notification->id,
                                'type' => $notification->severity,
                                'icon' => $notification->icon,
                                'title' => $notification->title,
                                'message' => $notification->message,
                                'time' => $notification->created_at->diffForHumans(),
                                'action_url' => $notification->action_url,
                                'is_read' => $notification->is_read
                            ];
                        });
                } catch (\Exception $e) {
                    Log::error('Error generating system notifications: ' . $e->getMessage());
                    // Continue with empty notifications if generation fails
                }
            }
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $notifications->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load notifications',
                'notifications' => [],
                'unread_count' => 0
            ], 500);
        }
    }

    /**
     * Generate system notifications for super admin and store in database
     */
    private function generateSystemNotifications()
    {
        try {
            // Only generate notifications if there are no existing unread notifications
            $existingUnreadCount = Notification::unread()->count();
            
            if ($existingUnreadCount > 0) {
                // If there are already unread notifications, don't generate new ones
                return;
            }

            // Critical system alerts - only if there are new critical logs since last notification
            $lastCriticalNotification = Notification::where('metadata->identifier', 'critical_logs')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $criticalLogsQuery = AuditLog::where('severity', 'critical');
            if ($lastCriticalNotification) {
                $criticalLogsQuery->where('created_at', '>', $lastCriticalNotification->created_at);
            } else {
                $criticalLogsQuery->whereDate('created_at', '>=', now()->subDays(7));
            }
            
            $newCriticalLogs = $criticalLogsQuery->count();
            
            if ($newCriticalLogs > 0) {
                $this->createOrUpdateNotification(
                    'critical_logs',
                    'system',
                    'Critical System Events',
                    "{$newCriticalLogs} new critical system events detected",
                    'fas fa-exclamation-triangle',
                    route('superadmin.audit-logs'),
                    'danger'
                );
            }

            // Pending admin approvals - only if there are new pending admins
            $lastPendingAdminsNotification = Notification::where('metadata->identifier', 'pending_admins')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $pendingAdminsQuery = User::where('role', 'admin')->where('status', 'pending');
            if ($lastPendingAdminsNotification) {
                $pendingAdminsQuery->where('created_at', '>', $lastPendingAdminsNotification->created_at);
            }
            
            $newPendingAdmins = $pendingAdminsQuery->count();
            
            if ($newPendingAdmins > 0) {
                $this->createOrUpdateNotification(
                    'pending_admins',
                    'admin',
                    'Pending Admin Approvals',
                    "{$newPendingAdmins} new admin registration(s) awaiting approval",
                    'fas fa-user-clock',
                    route('superadmin.admins'),
                    'warning'
                );
            }

            // New user registrations - only if there are new users since last notification
            $lastNewUsersNotification = Notification::where('metadata->identifier', 'new_users')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $newUsersQuery = User::query();
            if ($lastNewUsersNotification) {
                $newUsersQuery->where('created_at', '>', $lastNewUsersNotification->created_at);
            } else {
                $newUsersQuery->where('created_at', '>=', now()->subDay());
            }
            
            $newUsers = $newUsersQuery->count();
            
            if ($newUsers > 0) {
                $this->createOrUpdateNotification(
                    'new_users',
                    'user',
                    'New User Registrations',
                    "{$newUsers} new user(s) registered",
                    'fas fa-user-plus',
                    route('superadmin.users'),
                    'info'
                );
            }

            // High priority issues - only if there are new urgent issues
            $lastUrgentIssuesNotification = Notification::where('metadata->identifier', 'urgent_issues')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $urgentIssuesQuery = Issue::where('priority', 'High')->whereIn('status', ['Pending', 'In Progress']);
            if ($lastUrgentIssuesNotification) {
                $urgentIssuesQuery->where('created_at', '>', $lastUrgentIssuesNotification->created_at);
            }
            
            $newUrgentIssues = $urgentIssuesQuery->count();
            
            if ($newUrgentIssues > 0) {
                $this->createOrUpdateNotification(
                    'urgent_issues',
                    'issue',
                    'Urgent Issues',
                    "{$newUrgentIssues} new high priority issue(s) require attention",
                    'fas fa-exclamation-circle',
                    route('admin.manage-issues'),
                    'danger'
                );
            }

            // System performance alerts - only if there's unusual activity
            $lastHighActivityNotification = Notification::where('metadata->identifier', 'high_activity')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $recentLogs = AuditLog::where('created_at', '>=', now()->subDay())->count();
            
            if ($recentLogs > 100 && !$lastHighActivityNotification) {
                $this->createOrUpdateNotification(
                    'high_activity',
                    'system',
                    'High System Activity',
                    "{$recentLogs} system events logged in the last 24 hours - unusual activity detected",
                    'fas fa-chart-line',
                    route('superadmin.audit-logs'),
                    'warning'
                );
            }

            // Farm registration alerts - only if there are new pending farms
            $lastPendingFarmsNotification = Notification::where('metadata->identifier', 'pending_farms')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $pendingFarmsQuery = Farm::where('status', 'pending');
            if ($lastPendingFarmsNotification) {
                $pendingFarmsQuery->where('created_at', '>', $lastPendingFarmsNotification->created_at);
            }
            
            $newPendingFarms = $pendingFarmsQuery->count();
            
            if ($newPendingFarms > 0) {
                $this->createOrUpdateNotification(
                    'pending_farms',
                    'farm',
                    'Pending Farm Registrations',
                    "{$newPendingFarms} new farm registration(s) awaiting approval",
                    'fas fa-tractor',
                    route('superadmin.farms.index'),
                    'info'
                );
            }

            // Database performance - only if size has increased significantly
            $lastDbSizeNotification = Notification::where('metadata->identifier', 'db_size')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $dbSize = $this->getDatabaseSize();
            if ($dbSize > 100 && !$lastDbSizeNotification) { // MB
                $this->createOrUpdateNotification(
                    'db_size',
                    'system',
                    'Database Size Alert',
                    "Database size is {$dbSize} MB - consider optimization",
                    'fas fa-database',
                    route('superadmin.settings'),
                    'warning'
                );
            }

        } catch (\Exception $e) {
            Log::error('Error generating specific notifications: ' . $e->getMessage());
            // Create error notification if there's an error
            $this->createOrUpdateNotification(
                'system_error',
                'system',
                'System Error',
                'Unable to load system notifications - check system logs',
                'fas fa-exclamation-triangle',
                route('superadmin.audit-logs'),
                'danger'
            );
        }
    }

    /**
     * Create or update a notification in the database
     */
    private function createOrUpdateNotification($identifier, $type, $title, $message, $icon, $actionUrl, $severity)
    {
        // Check if notification already exists (read or unread)
        $existingNotification = Notification::where('metadata->identifier', $identifier)
            ->first();

        if (!$existingNotification) {
            // Create new notification only if none exists
            Notification::create([
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'icon' => $icon,
                'action_url' => $actionUrl,
                'severity' => $severity,
                'is_read' => false,
                'metadata' => ['identifier' => $identifier]
            ]);
        } else {
            // Only update if the notification is unread
            if (!$existingNotification->is_read) {
                $existingNotification->update([
                    'title' => $title,
                    'message' => $message,
                    'icon' => $icon,
                    'action_url' => $actionUrl,
                    'severity' => $severity,
                    'updated_at' => now()
                ]);
            }
            // If notification is already read, don't create a new one or update it
        }
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
                'notification_id' => 'required|integer'
            ]);

            $user = Auth::user();
            if (!$user || $user->role !== 'superadmin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $notification = Notification::find($request->notification_id);
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'error' => 'Notification not found'
                ], 404);
            }

            $notification->markAsRead($user->id);

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

            // Mark all unread notifications as read
            Notification::unread()->update([
                'is_read' => true,
                'read_at' => now(),
                'read_by' => $user->id
            ]);

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

    /**
     * Get real-time user statistics for notifications
     */
    public function getUserStats()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
                'new_users_24h' => User::where('created_at', '>=', now()->subDay())->count(),
                'pending_admins' => User::where('role', 'admin')->where('status', 'pending')->count(),
                'pending_farmers' => User::where('role', 'farmer')->where('status', 'pending')->count(),
                'active_users' => User::where('is_active', true)->count(),
                'inactive_users' => User::where('is_active', false)->count(),
            ];
            
            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get user stats'], 500);
        }
    }

    /**
     * Clean up old notifications (older than 30 days)
     */
    public function cleanupOldNotifications()
    {
        try {
            $deleted = Notification::where('created_at', '<', now()->subDays(30))->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Cleaned up {$deleted} old notifications"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cleanup old notifications'
            ], 500);
        }
    }
}
