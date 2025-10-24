<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\HealthRecord;
use App\Models\BreedingRecord;

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
        'qr_code_generated',
        'qr_code_url',
        'qr_code_generated_at',
        'qr_code_generated_by',
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

    /**
     * Get the health records for the livestock.
     */
    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    /**
     * Get the breeding records for the livestock.
     */
    public function breedingRecords()
    {
        return $this->hasMany(BreedingRecord::class);
    }

    /**
     * Get the user who generated the QR code.
     */
    public function qrCodeGenerator()
    {
        return $this->belongsTo(User::class, 'qr_code_generated_by');
    }

    /**
     * Normalize health_status casing to lowercase on set and get.
     */
    public function setHealthStatusAttribute($value)
    {
        $this->attributes['health_status'] = is_string($value) ? strtolower($value) : $value;
    }

    public function getHealthStatusAttribute($value)
    {
        return is_string($value) ? strtolower($value) : $value;
    }
}
