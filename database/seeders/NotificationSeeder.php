<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some test notifications
        Notification::create([
            'type' => 'test',
            'title' => 'Test Notification 1',
            'message' => 'This is a test notification to verify the system works',
            'icon' => 'fas fa-bell',
            'action_url' => '#',
            'severity' => 'info',
            'is_read' => false,
            'metadata' => ['identifier' => 'test_notification_1']
        ]);

        Notification::create([
            'type' => 'test',
            'title' => 'Test Notification 2',
            'message' => 'Another test notification for testing purposes',
            'icon' => 'fas fa-info-circle',
            'action_url' => '#',
            'severity' => 'warning',
            'is_read' => false,
            'metadata' => ['identifier' => 'test_notification_2']
        ]);

        Notification::create([
            'type' => 'test',
            'title' => 'Test Notification 3',
            'message' => 'A third test notification to ensure proper functionality',
            'icon' => 'fas fa-exclamation-triangle',
            'action_url' => '#',
            'severity' => 'danger',
            'is_read' => false,
            'metadata' => ['identifier' => 'test_notification_3']
        ]);
    }
}
