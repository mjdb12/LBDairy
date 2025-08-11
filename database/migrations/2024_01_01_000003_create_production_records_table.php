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
        Schema::create('production_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained('livestock')->onDelete('cascade');
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->date('production_date');
            $table->decimal('milk_quantity', 8, 2); // in liters
            $table->decimal('milk_quality_score', 3, 1)->nullable(); // 1-10 scale
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
        Schema::dropIfExists('production_records');
    }
};
