<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Models\UserDetails;

class UserDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert data using the UserDetails model
        UserDetails::create([
            'user_id' => 1,  // Super Admin
            'designation_id' => 1,  // Add actual designation ID
            'company_master_id' => 1,  // Add actual company ID
            'location_master_id' => 1,  // Add actual location ID
            'hod_id' => 1,  // Add actual HOD ID
            'policy_setting_id' => 1,  // Add actual policy setting ID
            'city_id' => 1,  // Add actual city ID
            'state_id' => 1,  // Add actual state ID
            'divison_master_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        UserDetails::create([
            'user_id' => 2,  // Sales Admin Hod
            'designation_id' => 1,
            'company_master_id' => 1,
            'location_master_id' => 1,
            'hod_id' => 1,
            'policy_setting_id' => 1,
            'city_id' => 1,
            'state_id' => 1,
            'divison_master_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        UserDetails::create([
            'user_id' => 3,  // Sales Admin
            'designation_id' => 1,
            'company_master_id' => 1,
            'location_master_id' => 1,
            'hod_id' => 1,
            'policy_setting_id' => 1,
            'city_id' => 1,
            'state_id' => 1,
            'divison_master_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        UserDetails::create([
            'user_id' => 4,  // Sales
            'designation_id' => 1,
            'company_master_id' => 1,
            'location_master_id' => 1,
            'hod_id' => 1,
            'policy_setting_id' => 1,
            'city_id' => 1,
            'state_id' => 1,
            'divison_master_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
