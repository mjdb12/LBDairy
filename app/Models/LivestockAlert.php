<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LivestockAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'livestock_id',
        'issued_by',
        'alert_date',
        'topic',
        'description',
        'severity',
        'status',
        'resolution_notes',
        'resolved_at'
    ];

    protected $casts = [
        'alert_date' => 'date',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the livestock this alert is for.
     */
    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    /**
     * Get the admin who issued the alert.
     */
    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get the severity badge class.
     */
    public function getSeverityBadgeClassAttribute()
    {
        switch($this->severity) {
            // New severity set
            case 'acute': return 'warning';
            case 'chronic': return 'info';
            case 'severe': return 'danger';

            // Backward compatibility for existing records
            case 'low': return 'info';
            case 'medium': return 'warning';
            case 'high': return 'danger';
            case 'critical': return 'dark';

            default: return 'secondary';
        }
    }

    /**
     * Get a human-readable severity label aligned with the current
     * Acute / Chronic / Severe scale, while keeping backward
     * compatibility for older low/medium/high/critical values.
     */
    public function getSeverityLabelAttribute()
    {
        switch ($this->severity) {
            case 'acute':
                return 'Acute';
            case 'chronic':
                return 'Chronic';
            case 'severe':
                return 'Severe';

            // Map legacy severities onto the new scale
            case 'low':
                return 'Acute';
            case 'medium':
                return 'Chronic';
            case 'high':
            case 'critical':
                return 'Severe';

            default:
                return $this->severity
                    ? ucfirst($this->severity)
                    : 'Unknown';
        }
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        switch($this->status) {
            case 'active': return 'danger';
            case 'resolved': return 'success';
            case 'dismissed': return 'secondary';
            default: return 'secondary';
        }
    }

    /**
     * Scope to get active alerts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get alerts by severity.
     */
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Mark alert as resolved.
     */
    public function markAsResolved($notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolution_notes' => $notes,
            'resolved_at' => now()
        ]);
    }

    /**
     * Mark alert as dismissed.
     */
    public function dismiss($notes = null)
    {
        $this->update([
            'status' => 'dismissed',
            'resolution_notes' => $notes,
            'resolved_at' => now()
        ]);
    }
}
