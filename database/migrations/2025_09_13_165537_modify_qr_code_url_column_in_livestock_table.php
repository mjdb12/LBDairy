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
        Schema::table('livestock', function (Blueprint $table) {
            // Change qr_code_url from VARCHAR to TEXT to accommodate longer URLs
            $table->text('qr_code_url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock', function (Blueprint $table) {
            // Revert back to VARCHAR(255)
            $table->string('qr_code_url')->nullable()->change();
        });
    }
};
