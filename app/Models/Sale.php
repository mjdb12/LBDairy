<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'quantity',
        'unit_price',
        'total_amount',
        'payment_status',
        'payment_method',
        'sale_date',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the farm for this sale.
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * Get the user who recorded this sale.
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
