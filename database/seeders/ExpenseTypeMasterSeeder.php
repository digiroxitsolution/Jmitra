<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ExpenseTypeMasterSeeder extends Seeder
{
    public function run()
    {
        DB::table('expense_type_master')->insert([
            [
                'id' => 1,
                'expense_type' => 'Location',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'expense_type' => 'Ex-Location',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'expense_type' => 'Outstation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'expense_type' => 'No Work',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'expense_type' => 'Sunday',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'expense_type' => 'Holiday',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'expense_type' => 'Boarding and Lodging Arranged by Co',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'expense_type' => 'Official Meeting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'expense_type' => 'Others',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}