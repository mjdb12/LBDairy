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
        Schema::create('livestock', function (Blueprint $table) {
            $table->id();
            $table->string('tag_number')->unique();
            $table->string('name')->nullable();
            $table->enum('type', ['cow', 'buffalo', 'goat', 'sheep']);
            $table->enum('breed', ['holstein', 'jersey', 'guernsey', 'ayrshire', 'brown_swiss', 'other']);
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->decimal('weight', 8, 2)->nullable(); // in kg
            $table->string('health_status')->default('healthy');
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock');
    }
};
