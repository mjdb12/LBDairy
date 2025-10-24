<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_id');
            $table->date('health_date')->nullable();
            $table->string('health_status', 50)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->text('symptoms')->nullable();
            $table->text('treatment')->nullable();
            $table->unsignedBigInteger('veterinarian_id')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->timestamps();

            $table->index('livestock_id');
            $table->foreign('livestock_id')->references('id')->on('livestock')->onDelete('cascade');
            $table->foreign('veterinarian_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
