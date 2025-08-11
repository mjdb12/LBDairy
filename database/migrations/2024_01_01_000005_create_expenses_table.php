<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->string('expense_type'); // feed, medicine, equipment, labor, etc.
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('payment_method')->nullable();
            $table->string('receipt_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
