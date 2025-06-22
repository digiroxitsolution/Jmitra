<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PolicySettings;
use Illuminate\Support\Str;

class PolicySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 1; $i <= 2; $i++) {
            PolicySettings::create([
                'policy_id' => 'POLICY-' . Str::upper(Str::random(8)), // Unique policy ID
                'policy_name' => 'Policy ' . $i,
                'designation_id' => 1, // Assuming designations table has IDs from 1 to 10
                'location_da' => 'Location DA ' . $i,
                'ex_location_da' => 'Ex Location DA ' . $i,
                'outstation_da' => 'Outstation DA ' . $i,
                'intercity_travel_ex_location' => 'Intercity Travel Ex Location ' . $i,
                'intercity_travel_outstation' => 'Intercity Travel Outstation ' . $i,
                'charges' => rand(1000, 5000) / 100, // Random financial data
                'expense_submision_date' => now()->subDays($i), // Past dates
                'approved_submission_date' => now()->addDays($i), // Future dates
                'effective_date' => now()->addDays($i),
                'other' => 'Additional policy information ' . $i,
            ]);
        }
    }
}
