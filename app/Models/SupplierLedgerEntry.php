<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLedgerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'farmer_id',
        'entry_date',
        'type',
        'payable_amount',
        'paid_amount',
        'due_amount',
        'status',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'payable_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
