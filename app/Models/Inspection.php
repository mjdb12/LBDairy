<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'scheduled_by',
        'inspection_date',
        'inspection_time',
        'status',
        'notes',
        'findings',
        'priority'
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'inspection_time' => 'datetime:H:i',
    ];

    /**
     * Get the farmer being inspected.
     */
    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    /**
     * Get the admin who scheduled the inspection.
     */
    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    /**
     * Get the formatted inspection date and time.
     */
    public function getFormattedDateTimeAttribute()
    {
        return $this->inspection_date->format('M d, Y') . ' at ' . $this->inspection_time->format('h:i A');
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        switch($this->status) {
            case 'scheduled': return 'primary';
            case 'completed': return 'success';
            case 'cancelled': return 'danger';
            case 'rescheduled': return 'warning';
            default: return 'secondary';
        }
    }

    /**
     * Get the priority badge class.
     */
    public function getPriorityBadgeClassAttribute()
    {
        switch($this->priority) {
            case 'low': return 'info';
            case 'medium': return 'warning';
            case 'high': return 'danger';
            case 'urgent': return 'dark';
            default: return 'secondary';
        }
    }

    /**
     * Scope to get upcoming inspections.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('inspection_date', '>=', now()->toDateString())
                    ->where('status', 'scheduled');
    }

    /**
     * Scope to get overdue inspections.
     */
    public function scopeOverdue($query)
    {
        return $query->where('inspection_date', '<', now()->toDateString())
                    ->where('status', 'scheduled');
    }
}
