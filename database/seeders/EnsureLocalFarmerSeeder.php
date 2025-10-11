<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\ProductionRecord;
use App\Models\Expense;

class EnsureLocalFarmerSeeder extends Seeder
{
    /**
     * Ensure there is at least one farmer with a farm, livestock, and sample data
     */
    public function run(): void
    {
        // Find any farmer; if none, create one
        $farmer = User::where('role', 'farmer')->first();
        if (!$farmer) {
            $farmer = User::create([
                'name' => 'Local Farmer',
                'email' => 'localfarmer@lbdairy.local',
                'username' => 'localfarmer',
                'password' => Hash::make('password123'),
                'role' => 'farmer',
                'phone' => '+639000000000',
                'address' => 'Local Address',
                'is_active' => true,
                'status' => 'approved',
            ]);
        }

        // Ensure the farmer owns at least one farm
        $farm = Farm::where('owner_id', $farmer->id)->first();
        if (!$farm) {
            $farm = Farm::create([
                'name' => 'Local Demo Farm',
                'owner_id' => $farmer->id,
                'location' => 'Local Area',
                'size' => 25,
                'status' => 'active',
                'description' => 'Auto-created demo farm for local setup',
            ]);
        }

        // Ensure at least one livestock exists
        $livestock = Livestock::where('farm_id', $farm->id)->first();
        if (!$livestock) {
            $livestock = Livestock::create([
                'tag_number' => 'LSD001',
                'name' => 'Demo Cow',
                'farm_id' => $farm->id,
                'owner_id' => $farmer->id,
                'type' => 'cow',
                'breed' => 'holstein',
                'birth_date' => now()->subMonths(30),
                'gender' => 'female',
                'weight' => 500,
                'health_status' => 'healthy',
                'status' => 'active',
            ]);
        }

        // Create a recent production record if none in last 7 days
        $hasRecentProduction = ProductionRecord::where('farm_id', $farm->id)
            ->where('production_date', '>=', now()->subDays(7))
            ->exists();
        if (!$hasRecentProduction) {
            ProductionRecord::create([
                'livestock_id' => $livestock->id,
                'farm_id' => $farm->id,
                'production_date' => now()->subDay(),
                'milk_quantity' => 22.5,
                'milk_quality_score' => 8,
                'notes' => 'Auto-seeded record',
                'recorded_by' => $farmer->id,
            ]);
        }

        // Create a recent expense if none in last 30 days
        $hasRecentExpense = Expense::where('farm_id', $farm->id)
            ->where('expense_date', '>=', now()->subDays(30))
            ->exists();
        if (!$hasRecentExpense) {
            Expense::create([
                'description' => 'Premium Feed Mix',
                'amount' => 3500,
                'expense_type' => 'feed',
                'expense_date' => now()->subDays(3),
                'farm_id' => $farm->id,
                'payment_method' => 'cash',
                'receipt_number' => 'DEMO-001',
                'notes' => 'Auto-seeded expense',
                'recorded_by' => $farmer->id,
            ]);
        }

        $this->command?->info('EnsureLocalFarmerSeeder: Local demo farmer and data ensured.');
        $this->command?->info('Login suggestion -> username: localfarmer / password: password123');
    }
}
