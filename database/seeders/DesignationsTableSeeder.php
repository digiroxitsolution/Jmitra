<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Designation::create(['name' => 'Manager']);
        Designation::create(['name' => 'Developer']);
        Designation::create(['name' => 'Designer']);
    }
}
