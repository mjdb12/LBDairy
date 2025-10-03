<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'action_url',
        'severity',
        'is_read',
        'read_at',
        'read_by',
        'metadata',
        'recipient_id'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array'
    ];

    /**
     * Get the user who read this notification
     */
    public function readBy()
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    /**
     * Get the recipient of this notification
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for recent notifications (last 30 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($userId = null)
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
            'read_by' => $userId ?? (Auth::check() ? Auth::id() : null)
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
            'read_by' => null
        ]);
    }
}
