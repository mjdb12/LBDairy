<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreedingRecord extends Model
{
    use HasFactory;

    protected $table = 'breeding_records';

    protected $fillable = [
        'livestock_id',
        'breeding_date',
        'breeding_type',
        'partner_livestock_id',
        'expected_birth_date',
        'pregnancy_status',
        'breeding_success',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'breeding_date' => 'date',
        'expected_birth_date' => 'date',
    ];

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }
}
