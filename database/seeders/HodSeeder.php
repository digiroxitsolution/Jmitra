<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hod;

class HodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hod::insert([
            ['name' => 'John John'],
            ['name' => 'Jane Smith'],
            ['name' => 'Alice Johnson'],
        ]);
    }
}
