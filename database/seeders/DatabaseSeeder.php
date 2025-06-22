<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            
            
            RoleAndPermissionSeeder::class, // Seeding permissions
            AllTablesSeeder::class, //
            HodSeeder::class,
            DesignationsTableSeeder::class,
            PolicySettingsSeeder::class,
            PolicyGuidelinesSeeder::class,
            UserDetailsSeeder::class,
            ExpenseTypeMasterSeeder::class,
            // MonthlyExpensesTableSeeder::class,
            
            
        ]);


        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }}


