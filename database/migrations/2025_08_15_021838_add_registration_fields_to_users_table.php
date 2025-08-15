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
        Schema::table('users', function (Blueprint $table) {
            // Admin-specific fields
            $table->string('admin_code')->nullable()->after('role');
            $table->string('position')->nullable()->after('admin_code');
            $table->string('barangay')->nullable()->after('position');
            
            // Farmer-specific fields
            $table->string('farmer_code')->nullable()->after('barangay');
            $table->string('farm_name')->nullable()->after('farmer_code');
            $table->string('farm_address')->nullable()->after('farm_name');
            
            // Additional fields
            $table->string('first_name')->nullable()->after('farm_address');
            $table->string('last_name')->nullable()->after('first_name');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('last_name');
            $table->text('terms_accepted')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'admin_code',
                'position', 
                'barangay',
                'farmer_code',
                'farm_name',
                'farm_address',
                'first_name',
                'last_name',
                'status',
                'terms_accepted'
            ]);
        });
    }
};
