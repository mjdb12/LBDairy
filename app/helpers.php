<?php

if (!function_exists('getActionBadgeClass')) {
    /**
     * Get badge class for action type
     */
    function getActionBadgeClass($action)
    {
        switch ($action) {
            case 'create':
                return 'success';
            case 'update':
                return 'primary';
            case 'delete':
                return 'danger';
            case 'login':
                return 'info';
            case 'logout':
                return 'secondary';
            case 'export':
                return 'warning';
            case 'import':
                return 'info';
            default:
                return 'secondary';
        }
    }
}

if (!function_exists('getSeverityBadgeClass')) {
    /**
     * Get badge class for severity level
     */
    function getSeverityBadgeClass($severity)
    {
        switch ($severity) {
            case 'critical':
                return 'danger';
            case 'error':
                return 'danger';
            case 'warning':
                return 'warning';
            case 'info':
                return 'info';
            case 'debug':
                return 'secondary';
            default:
                return 'secondary';
        }
    }
}

if (!function_exists('getActionDescription')) {
    /**
     * Get human-readable action description
     */
    function getActionDescription($action, $tableName = null)
    {
        $descriptions = [
            'create' => 'Created new record',
            'update' => 'Updated record',
            'delete' => 'Deleted record',
            'login' => 'User logged in',
            'logout' => 'User logged out',
            'export' => 'Exported data',
            'import' => 'Imported data',
        ];

        $description = $descriptions[$action] ?? 'Performed action';
        
        if ($tableName) {
            $description .= ' in ' . ucfirst(str_replace('_', ' ', $tableName));
        }

        return $description;
    }
}

if (!function_exists('notifySuperAdmins')) {
    /**
     * Send notification to all super admins
     */
    function notifySuperAdmins($type, $title, $message, $icon = 'fas fa-bell', $actionUrl = null, $severity = 'info', $metadata = [])
    {
        // Get all super admin users
        $superAdmins = \App\Models\User::where('role', 'superadmin')->get();
        
        foreach ($superAdmins as $superAdmin) {
            \App\Models\Notification::create([
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'icon' => $icon,
                'action_url' => $actionUrl,
                'severity' => $severity,
                'is_read' => false,
                'recipient_id' => $superAdmin->id,
                'metadata' => $metadata
            ]);
        }
    }
}