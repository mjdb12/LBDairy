<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'size',
        'owner_id',
        'status',
    ];

    protected $casts = [
        'size' => 'decimal:2',
    ];

    /**
     * Get the owner of the farm.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the livestock on the farm.
     */
    public function livestock()
    {
        return $this->hasMany(Livestock::class);
    }

    /**
     * Get the production records for the farm.
     */
    public function productionRecords()
    {
        return $this->hasMany(ProductionRecord::class);
    }

    /**
     * Get the sales for the farm.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the expenses for the farm.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the issues for the farm.
     */
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
