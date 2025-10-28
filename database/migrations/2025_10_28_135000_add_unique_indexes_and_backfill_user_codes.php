<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $adminIdxExists = $this->hasIndex('users', 'users_admin_code_unique');
        $farmerIdxExists = $this->hasIndex('users', 'users_farmer_code_unique');

        // Backfill missing admin codes (A0001, A0002, ...)
        DB::transaction(function () {
            $maxAdmin = DB::table('users')
                ->where('role', 'admin')
                ->whereNotNull('admin_code')
                ->whereRaw("admin_code REGEXP '^A[0-9]+$'")
                ->selectRaw("MAX(CAST(SUBSTRING(admin_code, 2) AS UNSIGNED)) as max_code")
                ->value('max_code');
            $nextAdmin = ((int) $maxAdmin) + 1;

            $admins = DB::table('users')
                ->where('role', 'admin')
                ->whereNull('admin_code')
                ->orderBy('id')
                ->get(['id']);

            foreach ($admins as $row) {
                $code = 'A' . str_pad((string)$nextAdmin, 4, '0', STR_PAD_LEFT);
                DB::table('users')->where('id', $row->id)->update(['admin_code' => $code]);
                $nextAdmin++;
            }

            // Backfill missing farmer codes (F0001, F0002, ...)
            $maxFarmer = DB::table('users')
                ->where('role', 'farmer')
                ->whereNotNull('farmer_code')
                ->whereRaw("farmer_code REGEXP '^F[0-9]+$'")
                ->selectRaw("MAX(CAST(SUBSTRING(farmer_code, 2) AS UNSIGNED)) as max_code")
                ->value('max_code');
            $nextFarmer = ((int) $maxFarmer) + 1;

            $farmers = DB::table('users')
                ->where('role', 'farmer')
                ->whereNull('farmer_code')
                ->orderBy('id')
                ->get(['id']);

            foreach ($farmers as $row) {
                $code = 'F' . str_pad((string)$nextFarmer, 4, '0', STR_PAD_LEFT);
                DB::table('users')->where('id', $row->id)->update(['farmer_code' => $code]);
                $nextFarmer++;
            }
        });

        // Add unique indexes after backfill to avoid conflicts
        Schema::table('users', function (Blueprint $table) use ($adminIdxExists, $farmerIdxExists) {
            if (! $adminIdxExists) {
                $table->unique('admin_code');
            }
            if (! $farmerIdxExists) {
                $table->unique('farmer_code');
            }
        });
    }

    public function down(): void
    {
        $adminIdxExists = $this->hasIndex('users', 'users_admin_code_unique');
        $farmerIdxExists = $this->hasIndex('users', 'users_farmer_code_unique');
        Schema::table('users', function (Blueprint $table) use ($adminIdxExists, $farmerIdxExists) {
            if ($adminIdxExists) {
                $table->dropUnique('users_admin_code_unique');
            }
            if ($farmerIdxExists) {
                $table->dropUnique('users_farmer_code_unique');
            }
        });
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        $dbName = DB::getDatabaseName();
        $rows = DB::select(
            "SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1",
            [$dbName, $table, $indexName]
        );
        return !empty($rows);
    }
};
