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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // system, user, admin, etc.
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable();
            $table->string('action_url')->nullable();
            $table->enum('severity', ['info', 'warning', 'danger', 'success'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->unsignedBigInteger('read_by')->nullable();
            $table->json('metadata')->nullable(); // Store additional data
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['type', 'is_read']);
            $table->index(['created_at']);
            $table->index(['read_by']);
            
            // Foreign key for read_by
            $table->foreign('read_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
