<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Livestock;
use App\Models\Farm;
use App\Models\User;

class TestLivestockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first farmer user
        $farmer = User::where('role', 'farmer')->first();
        if (!$farmer) {
            $farmer = User::create([
                'first_name' => 'Test',
                'last_name' => 'Farmer',
                'email' => 'testfarmer@example.com',
                'password' => bcrypt('password'),
                'role' => 'farmer',
                'phone' => '1234567890',
                'address' => 'Test Address'
            ]);
        }

        // Get the first farm for this farmer
        $farm = Farm::where('owner_id', $farmer->id)->first();
        if (!$farm) {
            $farm = Farm::create([
                'name' => 'Test Farm',
                'owner_id' => $farmer->id,
                'location' => 'Test Location',
                'size' => 50.0,
                'status' => 'active',
                'description' => 'Test farm for QR scanning'
            ]);
        }

        // Check if livestock with tag_number '6666' already exists
        $existingLivestock = Livestock::where('tag_number', '6666')->first();
        if (!$existingLivestock) {
            // Create livestock with tag_number '6666'
            Livestock::create([
                'tag_number' => '6666',
                'name' => 'Test Buffalo',
                'farm_id' => $farm->id,
                'owner_id' => $farmer->id,
                'type' => 'buffalo',
                'breed' => 'other',
                'birth_date' => now()->subMonths(24),
                'gender' => 'female',
                'weight' => 500,
                'health_status' => 'healthy',
                'status' => 'active',
            ]);
            
            echo "Created livestock with tag_number 6666: Test Buffalo\n";
        } else {
            echo "Livestock with tag_number 6666 already exists: {$existingLivestock->name}\n";
        }

        // Create a few more test livestock for good measure
        $testLivestock = [
            ['tag_number' => '1111', 'name' => 'Test Cow 1', 'type' => 'cow', 'breed' => 'holstein'],
            ['tag_number' => '2222', 'name' => 'Test Cow 2', 'type' => 'cow', 'breed' => 'jersey'],
            ['tag_number' => '3333', 'name' => 'Test Goat', 'type' => 'goat', 'breed' => 'other'],
        ];

        foreach ($testLivestock as $data) {
            $existing = Livestock::where('tag_number', $data['tag_number'])->first();
            if (!$existing) {
                Livestock::create([
                    'tag_number' => $data['tag_number'],
                    'name' => $data['name'],
                    'farm_id' => $farm->id,
                    'owner_id' => $farmer->id,
                    'type' => $data['type'],
                    'breed' => $data['breed'],
                    'birth_date' => now()->subMonths(rand(12, 48)),
                    'gender' => 'female',
                    'weight' => rand(300, 700),
                    'health_status' => 'healthy',
                    'status' => 'active',
                ]);
                echo "Created livestock with tag_number {$data['tag_number']}: {$data['name']}\n";
            }
        }
    }
}