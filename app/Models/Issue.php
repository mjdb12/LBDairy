<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'livestock_id',
        'farm_id',
        'issue_type',
        'description',
        'priority',
        'status',
        'date_reported',
        'resolved_date',
        'notes',
        'reported_by',
        'assigned_to',
    ];

    protected $casts = [
        'date_reported' => 'date',
        'resolved_date' => 'date',
    ];

    /**
     * Get the livestock for this issue.
     */
    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    /**
     * Get the farm for this issue.
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * Get the user who reported this issue.
     */
    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the user assigned to this issue.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
