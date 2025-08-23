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
            $table->string('owned_by')->nullable()->after('status');
            $table->string('dispersal_from')->nullable()->after('owned_by');
            $table->string('registry_id')->nullable()->after('dispersal_from');
            $table->string('sire_id')->nullable()->after('registry_id');
            $table->string('dam_id')->nullable()->after('sire_id');
            $table->string('sire_name')->nullable()->after('dam_id');
            $table->string('dam_name')->nullable()->after('sire_name');
            $table->string('natural_marks')->nullable()->after('dam_name');
            $table->string('property_no')->nullable()->after('natural_marks');
            $table->date('acquisition_date')->nullable()->after('property_no');
            $table->decimal('acquisition_cost', 10, 2)->nullable()->after('acquisition_date');
            $table->text('remarks')->nullable()->after('acquisition_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock', function (Blueprint $table) {
            $table->dropColumn([
                'owned_by',
                'dispersal_from',
                'registry_id',
                'sire_id',
                'dam_id',
                'sire_name',
                'dam_name',
                'natural_marks',
                'property_no',
                'acquisition_date',
                'acquisition_cost',
                'remarks'
            ]);
        });
    }
};
