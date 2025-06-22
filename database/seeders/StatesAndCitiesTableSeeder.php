<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatesAndCitiesTableSeeder extends Seeder
{
     /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['state' => 'ANDAMAN & NICOBAR ISLANDS', 'cities' => ['Nicobars', 'North and Middle Andaman', 'South Andaman']],
            ['state' => 'ANDHRA PRADESH', 'cities' => ['Anantapur', 'Chittoor']],
            ['state' => 'ARUNACHAL PRADESH', 'cities' => ['Tawang', 'Itanagar']],
            // Add other states and cities here...
        ];

        foreach ($data as $entry) {
            // Insert the state
            $stateId = DB::table('states')->insertGetId(['name' => $entry['state']]);

            // Insert the cities for the state
            foreach ($entry['cities'] as $city) {
                DB::table('cities')->insert(['state_id' => $stateId, 'name' => $city]);
            }
        }
    }
}
