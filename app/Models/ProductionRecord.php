<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'livestock_id',
        'farm_id',
        'production_date',
        'milk_quantity',
        'milk_quality_score',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'production_date' => 'date',
        'milk_quantity' => 'decimal:2',
        'milk_quality_score' => 'decimal:1',
    ];

    /**
     * Get the livestock for this production record.
     */
    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    /**
     * Get the farm for this production record.
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * Get the user who recorded this production record.
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
