<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HardResetDatabase extends Command
{
    protected $signature = 'app:hard-reset-database
        {--keep=role : Keep "role" (all superadmins) or "email" (specific email)}
        {--email= : Superadmin email to keep when --keep=email}
        {--confirm= : Type YES to confirm}';

    protected $description = 'Danger: Truncate all domain tables and keep only superadmin account(s) per options';

    public function handle()
    {
        $keep = strtolower((string) ($this->option('keep') ?? 'role'));
        $email = (string) ($this->option('email') ?? '');
        $confirm = (string) ($this->option('confirm') ?? '');

        if ($confirm !== 'YES') {
            $this->error('Confirmation required. Re-run with --confirm=YES');
            return self::FAILURE;
        }

        if (!in_array($keep, ['role','email'], true)) {
            $this->error('Invalid --keep option. Use role|email');
            return self::FAILURE;
        }

        if ($keep === 'email' && $email === '') {
            $this->error('When using --keep=email you must provide --email=<address>');
            return self::FAILURE;
        }

        // Collect table names (MySQL)
        $tables = array_map('current', DB::select('SHOW TABLES'));
        $exclude = ['migrations', 'users'];
        $toTruncate = array_values(array_diff($tables, $exclude));

        $this->warn('About to truncate the following tables:');
        foreach ($toTruncate as $t) { $this->line(" - {$t}"); }
        $this->warn('Users table will be pruned according to the keep policy.');

        DB::beginTransaction();
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach ($toTruncate as $table) {
                try {
                    DB::table($table)->truncate();
                } catch (\Throwable $e) {
                    $this->warn("Could not truncate {$table}: " . $e->getMessage());
                }
            }

            if ($keep === 'role') {
                // Keep all superadmins
                DB::table('users')->where('role', '!=', 'superadmin')->delete();
                $kept = DB::table('users')->where('role', 'superadmin')->pluck('id')->all();
            } else {
                // Keep only specified email
                $exists = DB::table('users')->where('email', $email)->exists();
                if (!$exists) {
                    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                    DB::rollBack();
                    $this->error("User with email {$email} not found. Aborting.");
                    return self::FAILURE;
                }
                DB::table('users')->where('email', '!=', $email)->delete();
                $kept = DB::table('users')->where('email', $email)->pluck('id')->all();
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();

            $this->info('Hard reset completed successfully.');
            $this->line('Kept user IDs: [' . implode(',', $kept) . ']');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            try { DB::statement('SET FOREIGN_KEY_CHECKS=1;'); } catch (\Throwable $e2) {}
            DB::rollBack();
            $this->error('Failed to hard reset: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
