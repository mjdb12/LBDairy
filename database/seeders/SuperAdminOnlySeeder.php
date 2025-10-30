<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminOnlySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'it.mjdrbondoc@gmail.com';

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'is_active' => true,
                'status' => 'approved',
                'email_verified_at' => now(),
            ]
        );
    }
}
