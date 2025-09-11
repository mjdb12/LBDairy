<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Livestock extends Model
{
    use HasFactory;

    protected $table = 'livestock';

    protected $fillable = [
        'tag_number',
        'name',
        'type',
        'breed',
        'birth_date',
        'gender',
        'weight',
        'health_status',
        'farm_id',
        'owner_id',
        'status',
        'owned_by',
        'dispersal_from',
        'registry_id',
        'sire_id',
        'dam_id',
        'sire_name',
        'dam_name',
        'natural_marks',
        'property_no',
        'acquisition_date',
        'acquisition_cost',
        'remarks',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'acquisition_date' => 'date',
        'weight' => 'decimal:2',
        'acquisition_cost' => 'decimal:2',
    ];

    /**
     * Get the farm where the livestock is located.
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * Get the owner of the livestock.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the production records for the livestock.
     */
    public function productionRecords()
    {
        return $this->hasMany(ProductionRecord::class);
    }
}
