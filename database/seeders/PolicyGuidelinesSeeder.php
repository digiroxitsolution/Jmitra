<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PolicyGuidelinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('policy_guidelines')->insert([
            [
                'policy_setting_id' => 1,
                'file_name' => 'privacy_policy.pdf',
                'policy_description' => 'Details about the privacy policy of our organization.',
                'uploaded_file' => 'uploads/privacy_policy.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // [
            //     'policy_setting_id' => 2,
            //     'file_name' => 'terms_of_service.pdf',
            //     'policy_description' => 'Details about the terms of service.',
            //     'uploaded_file' => 'uploads/terms_of_service.pdf',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ]);
    }
}
