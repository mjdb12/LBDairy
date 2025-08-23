<?php

namespace App\Helpers;

class AuditLogHelper
{
    /**
     * Get badge class for action type
     */
    public static function getActionBadgeClass($action)
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

    /**
     * Get badge class for severity level
     */
    public static function getSeverityBadgeClass($severity)
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

    /**
     * Get human-readable action description
     */
    public static function getActionDescription($action, $tableName = null)
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

    /**
     * Format audit log data for display
     */
    public static function formatAuditLogData($auditLog)
    {
        $formatted = [
            'id' => $auditLog->id,
            'action' => $auditLog->action,
            'description' => $auditLog->description ?: self::getActionDescription($auditLog->action, $auditLog->table_name),
            'severity' => $auditLog->severity ?: 'info',
            'ip_address' => $auditLog->ip_address ?: 'N/A',
            'user_agent' => $auditLog->user_agent ?: 'N/A',
            'table_name' => $auditLog->table_name ?: 'N/A',
            'record_id' => $auditLog->record_id ?: 'N/A',
            'created_at' => $auditLog->created_at->format('M d, Y H:i:s'),
            'old_values' => $auditLog->old_values,
            'new_values' => $auditLog->new_values,
        ];

        return $formatted;
    }
}
