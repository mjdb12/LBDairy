<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\ProductionRecord;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@lbdairy.com',
            'username' => 'superadmin',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'phone' => '+1234567890',
            'address' => '123 Main St, City, Country',
            'is_active' => true,
        ]);

        // Create admin users
        $admin1 = User::create([
            'name' => 'John Admin',
            'email' => 'admin1@lbdairy.com',
            'username' => 'admin1',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+1234567891',
            'address' => '456 Admin St, City, Country',
            'is_active' => true,
        ]);

        $admin2 = User::create([
            'name' => 'Jane Manager',
            'email' => 'admin2@lbdairy.com',
            'username' => 'admin2',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+1234567892',
            'address' => '789 Manager Ave, City, Country',
            'is_active' => true,
        ]);

        // Create farmer users
        $farmer1 = User::create([
            'name' => 'Bob Farmer',
            'email' => 'farmer1@lbdairy.com',
            'username' => 'farmer1',
            'password' => Hash::make('password123'),
            'role' => 'farmer',
            'phone' => '+1234567893',
            'address' => '321 Farm Rd, Rural Area, Country',
            'is_active' => true,
        ]);

        $farmer2 = User::create([
            'name' => 'Alice Rancher',
            'email' => 'farmer2@lbdairy.com',
            'username' => 'farmer2',
            'password' => Hash::make('password123'),
            'role' => 'farmer',
            'phone' => '+1234567894',
            'address' => '654 Ranch Blvd, Rural Area, Country',
            'is_active' => true,
        ]);

        // Create additional farmer users
        $farmer3 = User::create([
            'name' => 'Charlie Farmer',
            'email' => 'farmer3@lbdairy.com',
            'username' => 'farmer3',
            'password' => Hash::make('password123'),
            'role' => 'farmer',
            'phone' => '+1234567895',
            'address' => '987 Farm Way, City, Country',
            'is_active' => true,
        ]);

        $farmer4 = User::create([
            'name' => 'Diana Rancher',
            'email' => 'farmer4@lbdairy.com',
            'username' => 'farmer4',
            'password' => Hash::make('password123'),
            'role' => 'farmer',
            'phone' => '+1234567896',
            'address' => '147 Ranch St, City, Country',
            'is_active' => true,
        ]);

        // Create farms
        $farm1 = Farm::create([
            'name' => 'Green Valley Farm',
            'owner_id' => $farmer1->id,
            'location' => 'Green Valley, Rural Area',
            'size' => 50.5,
            'status' => 'active',
            'description' => 'A productive dairy farm with modern facilities',
        ]);

        $farm2 = Farm::create([
            'name' => 'Sunset Ranch',
            'owner_id' => $farmer2->id,
            'location' => 'Sunset Hills, Rural Area',
            'size' => 75.2,
            'status' => 'active',
            'description' => 'Large ranch with mixed livestock operations',
        ]);

        // Create livestock
        $livestock1 = Livestock::create([
            'tag_number' => 'LS001',
            'name' => 'Bessie',
            'farm_id' => $farm1->id,
            'owner_id' => $farmer1->id,
            'type' => 'cow',
            'breed' => 'holstein',
            'birth_date' => now()->subMonths(36),
            'gender' => 'female',
            'weight' => 650,
            'health_status' => 'healthy',
            'status' => 'active',
        ]);

        $livestock2 = Livestock::create([
            'tag_number' => 'LS002',
            'name' => 'Daisy',
            'farm_id' => $farm1->id,
            'owner_id' => $farmer1->id,
            'type' => 'cow',
            'breed' => 'jersey',
            'birth_date' => now()->subMonths(24),
            'gender' => 'female',
            'weight' => 450,
            'health_status' => 'healthy',
            'status' => 'active',
        ]);

        $livestock3 = Livestock::create([
            'tag_number' => 'LS003',
            'name' => 'Molly',
            'farm_id' => $farm2->id,
            'owner_id' => $farmer2->id,
            'type' => 'cow',
            'breed' => 'holstein',
            'birth_date' => now()->subMonths(48),
            'gender' => 'female',
            'weight' => 700,
            'health_status' => 'healthy',
            'status' => 'active',
        ]);

        // Create sample production records
        ProductionRecord::create([
            'livestock_id' => $livestock1->id,
            'farm_id' => $farm1->id,
            'production_date' => now()->subDays(1),
            'milk_quantity' => 25.5,
            'milk_quality_score' => 8.5,
            'notes' => 'Good production day',
            'recorded_by' => $farmer1->id,
        ]);

        ProductionRecord::create([
            'livestock_id' => $livestock1->id,
            'farm_id' => $farm1->id,
            'production_date' => now()->subDays(2),
            'milk_quantity' => 24.8,
            'milk_quality_score' => 8.0,
            'notes' => 'Slightly lower than usual',
            'recorded_by' => $farmer1->id,
        ]);

        ProductionRecord::create([
            'livestock_id' => $livestock2->id,
            'farm_id' => $farm1->id,
            'production_date' => now()->subDays(1),
            'milk_quantity' => 18.2,
            'milk_quality_score' => 7.5,
            'notes' => 'Normal production',
            'recorded_by' => $farmer1->id,
        ]);

        ProductionRecord::create([
            'livestock_id' => $livestock3->id,
            'farm_id' => $farm2->id,
            'production_date' => now()->subDays(1),
            'milk_quantity' => 30.1,
            'milk_quality_score' => 9.0,
            'notes' => 'Excellent production',
            'recorded_by' => $farmer2->id,
        ]);

        // Audit logs will be created automatically by the system

        $this->command->info('Database seeded successfully!');
        $this->command->info('Super Admin: superadmin@lbdairy.com / password123');
        $this->command->info('Admin 1: admin1@lbdairy.com / password123');
        $this->command->info('Admin 2: admin2@lbdairy.com / password123');
        $this->command->info('Farmer 1: farmer1@lbdairy.com / password123');
        $this->command->info('Farmer 2: farmer2@lbdairy.com / password123');
        $this->command->info('Farmer 3: farmer3@lbdairy.com / password123');
        $this->command->info('Farmer 4: farmer4@lbdairy.com / password123');
    }
}
