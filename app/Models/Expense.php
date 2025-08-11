<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'expense_type',
        'description',
        'amount',
        'expense_date',
        'payment_method',
        'receipt_number',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the farm for this expense.
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * Get the user who recorded this expense.
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
