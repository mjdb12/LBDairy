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
            // Additional master/basic-info fields
            $table->text('distinct_characteristics')->nullable()->after('remarks');
            $table->string('sire_breed')->nullable()->after('sire_name');
            $table->string('dam_breed')->nullable()->after('dam_name');

            // Cooperative / cooperator info (plain text)
            $table->string('cooperator_name')->nullable()->after('owned_by');
            $table->date('date_released')->nullable()->after('cooperator_name');
            $table->string('cooperative_name')->nullable()->after('date_released');
            $table->string('cooperative_address')->nullable()->after('cooperative_name');
            $table->string('cooperative_contact_no')->nullable()->after('cooperative_address');
            $table->string('in_charge')->nullable()->after('cooperative_contact_no');

            // Source / Origin
            $table->string('source_origin')->nullable()->after('acquisition_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock', function (Blueprint $table) {
            $table->dropColumn([
                'distinct_characteristics',
                'sire_breed',
                'dam_breed',
                'cooperator_name',
                'date_released',
                'cooperative_name',
                'cooperative_address',
                'cooperative_contact_no',
                'in_charge',
                'source_origin',
            ]);
        });
    }
};
