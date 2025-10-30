<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'it.mjdrbondoc@gmail.com';

        $this->info('Database verification summary');
        $this->line(str_repeat('-', 40));

        // Users overview
        if (Schema::hasTable('users')) {
            $totalUsers = DB::table('users')->count();
            $super = DB::table('users')->where('email', $email)->first();

            $this->info("Total users: {$totalUsers}");
            if ($super) {
                $this->info("Super admin found: id={$super->id}, email={$super->email}, role={$super->role}, username={$super->username}");
            } else {
                $this->error('Super admin NOT found');
            }

            $this->line('Users:');
            $rows = DB::table('users')->select('id','email','role','username')->orderBy('id')->get();
            foreach ($rows as $r) {
                $this->line(" - id={$r->id} email={$r->email} role={$r->role} username={$r->username}");
            }
        } else {
            $this->error('Table users does not exist');
        }

        $this->line(str_repeat('-', 40));
        $this->info('Domain table row counts (should be 0):');

        $tables = [
            'farms',
            'livestock',
            'production_records',
            'sales',
            'expenses',
            'issues',
            'audit_logs',
            'tasks',
            'inventories',
            'notifications',
            'health_records',
            'breeding_records',
            'inspections',
            'livestock_alerts',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->line(" - {$table}: {$count}");
            } else {
                $this->line(" - {$table}: (table missing)");
            }
        }

        return self::SUCCESS;
    }
}
