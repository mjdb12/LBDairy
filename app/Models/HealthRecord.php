<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $table = 'health_records';

    protected $fillable = [
        'livestock_id',
        'health_date',
        'health_status',
        'weight',
        'temperature',
        'symptoms',
        'treatment',
        'veterinarian_id',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'health_date' => 'date',
        'weight' => 'decimal:2',
        'temperature' => 'decimal:2',
    ];

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }
}
