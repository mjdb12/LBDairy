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

        // Create client users
        $client1 = User::create([
            'name' => 'Charlie Client',
            'email' => 'client1@lbdairy.com',
            'username' => 'client1',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'phone' => '+1234567895',
            'address' => '987 Client Way, City, Country',
            'is_active' => true,
        ]);

        $client2 = User::create([
            'name' => 'Diana Customer',
            'email' => 'client2@lbdairy.com',
            'username' => 'client2',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'phone' => '+1234567896',
            'address' => '147 Customer St, City, Country',
            'is_active' => true,
        ]);

        // Create farms
        $farm1 = Farm::create([
            'name' => 'Green Valley Farm',
            'owner_id' => $farmer1->id,
            'location' => 'Green Valley, Rural Area',
            'size_hectares' => 50.5,
            'status' => 'active',
            'description' => 'A productive dairy farm with modern facilities',
        ]);

        $farm2 = Farm::create([
            'name' => 'Sunset Ranch',
            'owner_id' => $farmer2->id,
            'location' => 'Sunset Hills, Rural Area',
            'size_hectares' => 75.2,
            'status' => 'active',
            'description' => 'Large ranch with mixed livestock operations',
        ]);

        // Create livestock
        $livestock1 = Livestock::create([
            'farm_id' => $farm1->id,
            'owner_id' => $farmer1->id,
            'type' => 'dairy_cow',
            'breed' => 'Holstein',
            'age_months' => 36,
            'weight_kg' => 650,
            'health_status' => 'healthy',
            'estimated_value' => 2500,
            'notes' => 'High-producing dairy cow',
        ]);

        $livestock2 = Livestock::create([
            'farm_id' => $farm1->id,
            'owner_id' => $farmer1->id,
            'type' => 'dairy_cow',
            'breed' => 'Jersey',
            'age_months' => 24,
            'weight_kg' => 450,
            'health_status' => 'healthy',
            'estimated_value' => 1800,
            'notes' => 'Young Jersey cow with good potential',
        ]);

        $livestock3 = Livestock::create([
            'farm_id' => $farm2->id,
            'owner_id' => $farmer2->id,
            'type' => 'dairy_cow',
            'breed' => 'Holstein',
            'age_months' => 48,
            'weight_kg' => 700,
            'health_status' => 'healthy',
            'estimated_value' => 3000,
            'notes' => 'Mature Holstein with excellent production',
        ]);

        // Create production records
        $production1 = ProductionRecord::create([
            'farm_id' => $farm1->id,
            'livestock_id' => $livestock1->id,
            'recorded_by' => $farmer1->id,
            'type' => 'milk',
            'quantity' => 25.5,
            'unit' => 'liters',
            'date' => now()->subDays(1),
            'notes' => 'Morning milking session',
        ]);

        $production2 = ProductionRecord::create([
            'farm_id' => $farm1->id,
            'livestock_id' => $livestock2->id,
            'recorded_by' => $farmer1->id,
            'type' => 'milk',
            'quantity' => 18.2,
            'unit' => 'liters',
            'date' => now()->subDays(1),
            'notes' => 'Morning milking session',
        ]);

        $production3 = ProductionRecord::create([
            'farm_id' => $farm2->id,
            'livestock_id' => $livestock3->id,
            'recorded_by' => $farmer2->id,
            'type' => 'milk',
            'quantity' => 30.0,
            'unit' => 'liters',
            'date' => now()->subDays(1),
            'notes' => 'Excellent production today',
        ]);

        // Create sales records
        $sale1 = Sale::create([
            'farm_id' => $farm1->id,
            'recorded_by' => $farmer1->id,
            'type' => 'milk',
            'quantity' => 43.7,
            'unit' => 'liters',
            'amount' => 87.40,
            'date' => now()->subDays(2),
            'customer_name' => 'Local Dairy Co.',
            'notes' => 'Regular milk delivery',
        ]);

        $sale2 = Sale::create([
            'farm_id' => $farm2->id,
            'recorded_by' => $farmer2->id,
            'type' => 'milk',
            'quantity' => 30.0,
            'unit' => 'liters',
            'amount' => 60.00,
            'date' => now()->subDays(2),
            'customer_name' => 'Farm Fresh Market',
            'notes' => 'Premium milk sale',
        ]);

        // Create expense records
        $expense1 = Expense::create([
            'farm_id' => $farm1->id,
            'recorded_by' => $farmer1->id,
            'category' => 'feed',
            'description' => 'Dairy cow feed pellets',
            'amount' => 150.00,
            'date' => now()->subDays(3),
            'status' => 'paid',
            'notes' => 'Monthly feed purchase',
        ]);

        $expense2 = Expense::create([
            'farm_id' => $farm1->id,
            'recorded_by' => $farmer1->id,
            'category' => 'veterinary',
            'description' => 'Health check and vaccinations',
            'amount' => 200.00,
            'date' => now()->subDays(5),
            'status' => 'paid',
            'notes' => 'Annual health maintenance',
        ]);

        $expense3 = Expense::create([
            'farm_id' => $farm2->id,
            'recorded_by' => $farmer2->id,
            'category' => 'equipment',
            'description' => 'Milking machine maintenance',
            'amount' => 300.00,
            'date' => now()->subDays(7),
            'status' => 'paid',
            'notes' => 'Regular equipment service',
        ]);

        // Create audit logs
        $auditLog1 = AuditLog::create([
            'user_id' => $superAdmin->id,
            'action' => 'user_created',
            'severity' => 'info',
            'description' => 'Created new admin user: John Admin',
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $auditLog2 = AuditLog::create([
            'user_id' => $admin1->id,
            'action' => 'farm_created',
            'severity' => 'info',
            'description' => 'Created new farm: Green Valley Farm',
            'ip_address' => '192.168.1.2',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $auditLog3 = AuditLog::create([
            'user_id' => $farmer1->id,
            'action' => 'production_recorded',
            'severity' => 'info',
            'description' => 'Recorded milk production: 25.5 liters',
            'ip_address' => '192.168.1.3',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $auditLog4 = AuditLog::create([
            'user_id' => $farmer2->id,
            'action' => 'livestock_added',
            'severity' => 'info',
            'description' => 'Added new livestock: Holstein cow #3',
            'ip_address' => '192.168.1.4',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $auditLog5 = AuditLog::create([
            'user_id' => $admin1->id,
            'action' => 'system_backup',
            'severity' => 'info',
            'description' => 'Completed daily system backup',
            'ip_address' => '192.168.1.5',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Super Admin: superadmin@lbdairy.com / password123');
        $this->command->info('Admin 1: admin1@lbdairy.com / password123');
        $this->command->info('Admin 2: admin2@lbdairy.com / password123');
        $this->command->info('Farmer 1: farmer1@lbdairy.com / password123');
        $this->command->info('Farmer 2: farmer2@lbdairy.com / password123');
        $this->command->info('Client 1: client1@lbdairy.com / password123');
        $this->command->info('Client 2: client2@lbdairy.com / password123');
    }
}
