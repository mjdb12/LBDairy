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
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('recipient_id')->nullable()->after('metadata');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['recipient_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['recipient_id']);
            $table->dropIndex(['recipient_id', 'is_read']);
            $table->dropColumn('recipient_id');
        });
    }
};
