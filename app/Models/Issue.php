<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'category',
        'farm_id',
        'reported_by',
        'assigned_to',
        'reported_date',
        'resolved_date',
        'resolution_notes',
    ];

    protected $casts = [
        'reported_date' => 'date',
        'resolved_date' => 'date',
    ];

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
