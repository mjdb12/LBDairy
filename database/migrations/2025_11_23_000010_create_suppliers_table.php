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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('supplier_code', 64)->nullable();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('contact', 32)->nullable();
            $table->string('status', 32)->default('active');
            $table->string('source_type', 32)->default('manual');
            $table->string('source_key', 128)->nullable();
            $table->timestamps();

            $table->index(['farmer_id', 'source_type', 'source_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
