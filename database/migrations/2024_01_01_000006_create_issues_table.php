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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->nullable()->constrained('livestock')->onDelete('cascade');
            $table->foreignId('farm_id')->nullable()->constrained('farms')->onDelete('cascade');
            $table->string('issue_type');
            $table->text('description');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('status', ['Pending', 'In Progress', 'Resolved', 'Closed'])->default('Pending');
            $table->date('date_reported');
            $table->date('resolved_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
