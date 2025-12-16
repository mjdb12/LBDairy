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
            $table->unsignedInteger('failed_login_attempts')->default(0)->after('last_login_at');
            $table->unsignedInteger('lockout_count')->default(0)->after('failed_login_attempts');
            $table->timestamp('last_failed_login_at')->nullable()->after('lockout_count');
            $table->timestamp('locked_until')->nullable()->after('last_failed_login_at');
            $table->string('last_login_ip', 45)->nullable()->after('locked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'failed_login_attempts',
                'lockout_count',
                'last_failed_login_at',
                'locked_until',
                'last_login_ip',
            ]);
        });
    }
};
