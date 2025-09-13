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
            $table->boolean('qr_code_generated')->default(false)->after('status');
            $table->string('qr_code_url')->nullable()->after('qr_code_generated');
            $table->timestamp('qr_code_generated_at')->nullable()->after('qr_code_url');
            $table->unsignedBigInteger('qr_code_generated_by')->nullable()->after('qr_code_generated_at');
            
            $table->foreign('qr_code_generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock', function (Blueprint $table) {
            $table->dropForeign(['qr_code_generated_by']);
            $table->dropColumn(['qr_code_generated', 'qr_code_url', 'qr_code_generated_at', 'qr_code_generated_by']);
        });
    }
};
