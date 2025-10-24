<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('breeding_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_id');
            $table->date('breeding_date')->nullable();
            $table->string('breeding_type', 50)->nullable();
            $table->string('partner_livestock_id', 255)->nullable();
            $table->date('expected_birth_date')->nullable();
            $table->string('pregnancy_status', 50)->nullable();
            $table->string('breeding_success', 50)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->timestamps();

            $table->index('livestock_id');
            $table->foreign('livestock_id')->references('id')->on('livestock')->onDelete('cascade');
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breeding_records');
    }
};
