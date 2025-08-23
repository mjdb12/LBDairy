<?php

namespace App\View\Composers;

use Illuminate\View\View;

class AuditLogComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('getActionBadgeClass', function($action) {
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
        });

        $view->with('getSeverityBadgeClass', function($severity) {
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
        });
    }
}
