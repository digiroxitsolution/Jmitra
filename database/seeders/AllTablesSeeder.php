<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AllTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker::create();

        // Seed for `divison_master`        
        $divisons = [
            ['name' => 'Service'],
            ['name' => 'Diagnostics'],
            
            // Add more divisons as needed
        ];

        foreach ($divisons as $index => $divison) {
            DB::table('divison_master')->insert([
                // 'id' => $index + 1, // Assign a unique ID starting from 1
                'name' => $divison['name'],
                
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        

        // Seed for `mode_of_expense_master`
        $mode_of_expenses = [
            ['mode_expense' => 'Flight'],
            ['mode_expense' => 'Bus'],
            ['mode_expense' => 'Auto'],
            ['mode_expense' => 'CAB'],
            ['mode_expense' => 'Own CAR'],
            ['mode_expense' => 'Two Wheeler'],
            ['mode_expense' => 'Metro'],
            ['mode_expense' => 'Train-2AC'],
            ['mode_expense' => 'Train-3AC'],

            
            // Add more mode_of_expense as needed
        ];

        foreach ($mode_of_expenses as $index => $mode_of_expense) {
            DB::table('mode_of_expense_master')->insert([
                // 'id' => $index + 1, // Assign a unique ID starting from 1
                'mode_expense' => $mode_of_expense['mode_expense'],                
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seeder for `states`
        $states = [
            ['name' => 'Andhra Pradesh', 'short' => 'AP'],
            ['name' => 'Arunachal Pradesh', 'short' => 'AR'],
            ['name' => 'Assam', 'short' => 'AS'],
            ['name' => 'Bihar', 'short' => 'BR'],
            ['name' => 'Chhattisgarh', 'short' => 'CG'],
            ['name' => 'Goa', 'short' => 'GA'],
            ['name' => 'Gujarat', 'short' => 'GJ'],
            ['name' => 'Haryana', 'short' => 'HR'],
            ['name' => 'Himachal Pradesh', 'short' => 'HP'],
            ['name' => 'Jharkhand', 'short' => 'JH'],
            // Add more states as needed
        ];

        foreach ($states as $index => $state) {
            DB::table('states')->insert([
                // 'id' => $index + 1, // Assign a unique ID starting from 1
                'name' => $state['name'],
                'short' => $state['short'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // After states are inserted, insert data into `cities` table
        $cities = [
            ['state_id' => 1, 'name' => 'Visakhapatnam'],
            ['state_id' => 2, 'name' => 'Itanagar'],
            ['state_id' => 3, 'name' => 'Guwahati'],
            ['state_id' => 4, 'name' => 'Patna'],
            ['state_id' => 5, 'name' => 'Raipur'],
            ['state_id' => 6, 'name' => 'Panaji'],
            // Add more cities and ensure state_id exists
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'state_id' => $city['state_id'],
                'name' => $city['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // After cities are inserted, insert data into `location_master` table
        $locations = [
            ['city_id' => 1, 'state_id' => 1, 'working_location' => 'Sector 10, Visakhapatnam'],
            // ['city_id' => 2, 'working_location' => 'Sector 20, Itanagar'],
            // ['city_id' => 3, 'working_location' => 'Guwahati Commercial Zone'],
            // ['city_id' => 4, 'working_location' => 'Patna Business Park'],
            // ['city_id' => 5, 'working_location' => 'Raipur Industrial Area'],
            // ['city_id' => 6, 'working_location' => 'Panaji Tech Park'],
            // Add more locations and ensure city_id exists
        ];

        foreach ($locations as $location) {
            DB::table('location_master')->insert([
                'city_id' => $location['city_id'],
                'state_id' => $location['state_id'],
                'working_location' => $location['working_location'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed for `other_expense_master`
        $other_expense_masters = [
            "TEAM LUNCH", 
            "CLIA SD CARD", 
            "MONTHLY MEETING", 
            "PEN DRIVE", 
            "CME", 
            "SAMPLE TESTING", 
            "SAMPLE TESTING + CME", 
            "JOINING DAY LUNCH", 
            "WORKING BAG", 
            "TOLL", 
            "FLEX FOR CME"
        ];

        foreach ($other_expense_masters as $expense) {
            DB::table('other_expense_master')->insert([
                'other_expense' => $expense,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed for `rejection_master`
        $reason_of_rejections = [
            ['reason_of_rejection' => 'Claim not found as per Policy'],
            
            // Add more reason_of_rejection as needed
        ];

        foreach ($reason_of_rejections as $index => $reason_of_rejection) {
            DB::table('rejection_master')->insert([               
                'reason_of_rejection' => $reason_of_rejection['reason_of_rejection'],               
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed for `re_open_master`
        $reason_of_re_opens = [
            ['reason_of_re_open' => 'Incomplete Data Submission'],
        ];
        foreach ($reason_of_re_opens as $index => $reason_of_re_open) {
            DB::table('re_open_master')->insert([               
                'reason_of_re_open' => $reason_of_re_open['reason_of_re_open'],             
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed for `way_of_location_master`
        $way_of_locations = [
            ['way_of_location' => 'One Way'],
            ['way_of_location' => 'Two Way'],
            ['way_of_location' => 'Multi Way'],

        ];
        foreach ($way_of_locations as $index => $way_of_location) {
            DB::table('way_of_location_master')->insert([               
                'way_of_location' => $way_of_location['way_of_location'],             
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed for `company_master`
        $company_names = [
            ['company_name' => 'Jmitra Pvt Ltd', 'location' => 'Hari Nagar, Delhi', 'address' => 'Hari Nagar, Delhi Delhi, India - 110064'],

        ];
        foreach ($company_names as $index => $company_name) {
            DB::table('company_master')->insert([               
                'company_name' => $company_name['company_name'],
                'location' => $company_name['location'],             
                'address' => $company_name['address'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


    }
}
