<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'supplier_code',
        'name',
        'address',
        'contact',
        'status',
        'source_type',
        'source_key',
    ];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(SupplierLedgerEntry::class);
    }
}
