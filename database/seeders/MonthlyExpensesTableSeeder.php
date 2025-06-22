<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlyExpensesTableSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        for ($i = 1; $i <= 25; $i++) {
            $data[] = [
                'user_id' => 2,
                'hod_id' => 1,
                'expense_id' => 'EX' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'divison_master_id' => 1, // Random division ID (assuming there are 5 divisions)
                'expense_type_master_id' => 1, // Random expense type ID (assuming there are 5 types)
                'mode_of_expense_master_id' => 1, // Random expense mode ID (assuming there are 5 modes)
                'expense_date' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d H:i:s'), // Random expense date
                'date_of_submission' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d H:i:s'), // Random submission date
                'one_way_two_way_multi_location' => ['One Way', 'Two Way', 'Multi Location'][rand(0, 2)], // Random travel type
                'from' => "Location " . chr(rand(65, 90)), // Random "From" location
                'to' => "Location " . chr(rand(65, 90)), // Random "To" location
                'departure_time' => Carbon::now()->subHours(rand(2, 48))->format('Y-m-d H:i:s'), // Random departure time
                'arrival_time' => Carbon::now()->subHours(rand(1, 47))->format('Y-m-d H:i:s'), // Random arrival time
                'km_as_per_user' => rand(5, 100) + (rand(0, 99) / 100), // Random kilometers by user
                'km_as_per_google_map' => rand(5, 100) + (rand(0, 99) / 100), // Random kilometers by Google
                'fare_amount' => rand(100, 1000) + (rand(0, 99) / 100), // Random fare amount
                'da_location' => 'Hotel ' . chr(rand(65, 90)), // Random DA location
                'da_ex_location' => 'Location ' . chr(rand(65, 90)), // Random DA Ex-Location
                'da_outstation' => rand(50, 500) + (rand(0, 99) / 100), // Random DA Outstation
                'da_total' => rand(100, 1000) + (rand(0, 99) / 100), // Random DA Total
                'postage' => rand(10, 100) + (rand(0, 99) / 100), // Random postage
                'mobile_internet' => rand(50, 500) + (rand(0, 99) / 100), // Random Mobile/Internet expense
                'print_stationery' => rand(20, 200) + (rand(0, 99) / 100), // Random print stationery expense
                'other_expenses_purpose' => 'Purpose ' . rand(1, 10), // Random purpose
                'other_expenses_amount' => rand(10, 500) + (rand(0, 99) / 100), // Random other expense amount
                'pre_approved' => ['Yes', 'No', 'N.A.'][rand(0, 2)], // Random pre-approved status
                'approved_date' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d H:i:s'), // Random approved date or null
                'approved_by' => 1, // Random user ID for approved_by (assuming there are 10 users)
                'upload_of_approvals_documents' => rand(0, 1) ? 'document_' . rand(1, 25) . '.pdf' : null, // Random document name or null
                'status' => 0, // Random status (1 = Pending, 2 = Rejected, 3 = Approved)
                'is_submitted' => 0,
                'reason_of_rejected' => rand(0, 1) ? 'Reason ' . rand(1, 5) : null, // Reason for rejection
                'days_elapsed' => rand(0, 30), // Random days elapsed
                'justification' => rand(0, 1) ? 'Justification ' . rand(1, 10) : null, // Random justification
                'advance_taken' => rand(0, 1) ? 'Advance Taken ' . rand(1, 10) : null, // Random Advance Taken text
                'remark_of_advance_taken' => rand(0, 1) ? 'Remark ' . rand(1, 10) : null, // Random remark of advance taken
                'remarks' => rand(0, 1) ? 'Remark ' . rand(1, 25) : null, // Random remark or null
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert data into the database
        DB::table('monthly_expenses')->insert($data);
    }
}
