<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\DivisonMaster;
use App\Models\ModeofExpenseMaster;
use App\Models\MonthlyExpense;
use App\Models\MonthlyExpenseHistory;
use App\Models\UserExpenseOtherRecords;
use App\Models\ExpenseTypeMaster;
use App\Models\OtherExpenseMaster;
use App\Models\RejectionMaster;
use App\Models\ReOpenMaster;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class GenExReportController extends Controller
{
    public function GenExReport(Request $request){
        $title = 'Gen-Ex Report';

        // Validate the inputs
        $request->validate([
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
            'monthName' => 'nullable|date_format:Y-m|required_if:fromDate,toDate,NULL',
        ]);

        // Retrieve and filter data
        $monthName = $request->monthName;
        // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($monthName, 5, 2);
        $year = (int)substr($monthName, 0, 4);

        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        if ((!$fromDate && !$toDate) && !$monthName) {
            return view('dash.HODGenExReport.index');
        }

        // Join the tables
        $ExpenseDetailsReports = UserExpenseOtherRecords::with('MonthlyExpense')
            ->where('is_submitted', 1)  
            ->where('is_verified', 1); 
            // ->where('is_approved', 1)
            // ->where('status', 1);

        if ($monthName) {
            $ExpenseDetailsReports->whereMonth('expense_date', $month)->whereYear('expense_date', $year);
        }
      
        if (!$monthName) {           

            // Filter by fromDate and toDate if provided
            if ($fromDate) {
                $ExpenseDetailsReports->whereDate('expense_date', '>=', $fromDate);
            }

            if ($toDate) {
                $ExpenseDetailsReports->whereDate('expense_date', '<=', $toDate);
            }
        }

        // Get the filtered data
        $ExpenseDetailsReports = $ExpenseDetailsReports->get();

        // Extract MonthlyExpense data and store it in a variable
        $monthlyExpenses = $ExpenseDetailsReports->pluck('MonthlyExpense')->flatten();

        $results = [];

        $groupedmonthlyExpenses = $monthlyExpenses->groupBy('expense_id');
        $all_total_genex = 0;

        // Loop through each grouped set of expenses (one group corresponds to one month)
        foreach ($groupedmonthlyExpenses as $expenseGroup) {
            // Get the employee name and calculate totals for this specific month
            $firstExpenseGroup = $expenseGroup->first();
            $employeeName = $firstExpenseGroup->user->name;
            $monthName = Carbon::parse($firstExpenseGroup->expense_date)->format('F');
            $company = $firstExpenseGroup->user->userDetail->companyMaster->company_name;
            $state = $firstExpenseGroup->user->userDetail->state->name;
            $location = $firstExpenseGroup->user->userDetail->LocationMaster->working_location;

            // Initialize calculation variables
            $fifteen_days_of_monthly_expenses_headquarters = 0;
            $fifteen_days_of_monthly_expenses_outstation = 0;
            $rest_of_monthly_expenses_headquarters = 0;
            $rest_of_monthly_expenses_outstation = 0;

            // Separate expenses where `expense_type_master_id` is not equal to 3
            $monthly_expenses_headquarters = $expenseGroup->filter(function ($expense) {
                return $expense->expense_type_master_id != 3 && $expense->expense_type_master_id !== null;
            });        

            // Separate expenses where `expense_type_master_id` is equal to 3
            $monthly_expenses_outstation = $expenseGroup->filter(function ($expense) {
                return $expense->expense_type_master_id == 3;
            });


            


            // Define the start and end of the first 15 days
            $startOfMonth = Carbon::parse($expenseGroup->first()->expense_date)->startOfMonth();
            $middleOfMonth = $startOfMonth->copy()->addDays(14);

            // Define the start of the 16th day and the end of the month
            $restOfMonthStart = $startOfMonth->copy()->addDays(15);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            // Query for the first 15 days
            $fifteen_days_of_monthly_expenses = $expenseGroup->filter(function ($exp) use ($startOfMonth, $middleOfMonth) {
                $expenseDate = Carbon::parse($exp->expense_date);
                return $expenseDate->between($startOfMonth, $middleOfMonth);
            });

            // Query for the rest of the month
            $rest_of_monthly_expenses = $expenseGroup->filter(function ($exp) use ($restOfMonthStart, $endOfMonth) {
                $expenseDate = Carbon::parse($exp->expense_date);
                return $expenseDate->between($restOfMonthStart, $endOfMonth);
            });



            $fifteen_days_of_monthly_expenses_headquarters = $fifteen_days_of_monthly_expenses->filter(function ($expense) {
                return $expense->expense_type_master_id != 3 && $expense->expense_type_master_id !== null;
            });
            // Separate expenses where `expense_type_master_id` is equal to 3
            $fifteen_days_of_monthly_expenses_outstation = $fifteen_days_of_monthly_expenses->filter(function ($expense) {
                return $expense->expense_type_master_id == 3;
            });


            $rest_of_monthly_expenses_headquarters = $rest_of_monthly_expenses->filter(function ($expense) {
                return $expense->expense_type_master_id != 3 && $expense->expense_type_master_id !== null;
            });

            // Separate expenses where `expense_type_master_id` is equal to 3
            $rest_of_monthly_expenses_outstation = $rest_of_monthly_expenses->filter(function ($expense) {
                return $expense->expense_type_master_id == 3;
            });



            $monthly_expenses_headquarters_days = count($monthly_expenses_headquarters);
            $monthly_expenses_outstation_days = count($monthly_expenses_outstation);

            $fifteen_days_of_monthly_expenses_headquarters_days = count($fifteen_days_of_monthly_expenses_headquarters);
            $fifteen_days_of_monthly_expenses_outstation_days = count($fifteen_days_of_monthly_expenses_outstation);


            $rest_of_monthly_expenses_headquarters_days = count($rest_of_monthly_expenses_headquarters);    
            $rest_of_monthly_expenses_outstation_days = count($rest_of_monthly_expenses_outstation);



            $fifteen_days_of_monthly_expenses_headquarter_da = $fifteen_days_of_monthly_expenses_headquarters->sum('da_total');
            $fifteen_days_of_monthly_expenses_outstation_da = $fifteen_days_of_monthly_expenses_outstation->sum('da_total');


            $rest_of_monthly_expenses_headquarter_da = $rest_of_monthly_expenses_headquarters->sum('da_total');
            $rest_of_monthly_expenses_outstation_da = $rest_of_monthly_expenses_outstation->sum('da_total');
            

            




        $fifteen_days_of_monthly_expenses_da = $fifteen_days_of_monthly_expenses_headquarter_da + $fifteen_days_of_monthly_expenses_outstation_da;
        $rest_of_monthly_expenses_da = $rest_of_monthly_expenses_headquarter_da + $rest_of_monthly_expenses_outstation_da;       
        $total_da = $fifteen_days_of_monthly_expenses_da + $rest_of_monthly_expenses_da;  

        $fifteen_days_of_fare_amount = $fifteen_days_of_monthly_expenses->sum('fare_amount');
        $rest_of_other_fare_amount = $rest_of_monthly_expenses->sum('fare_amount');
        $total_other_fare_amount = $fifteen_days_of_fare_amount + $rest_of_other_fare_amount;


        
        $total_other_expenses_amount = $expenseGroup->sum('other_expenses_amount'); 


        $fifteen_days_of_monthly_expenses_postage = $fifteen_days_of_monthly_expenses->sum('postage');
        $rest_of_monthly_expenses_postage = $rest_of_monthly_expenses->sum('postage');
        $total_postage = $fifteen_days_of_monthly_expenses_postage + $rest_of_monthly_expenses_postage;  


        $fifteen_days_of_mobile_internet = $fifteen_days_of_monthly_expenses->sum('mobile_internet');
        $rest_of_monthly_mobile_internet = $rest_of_monthly_expenses->sum('mobile_internet');
        $total_mobile_internet =  $fifteen_days_of_mobile_internet + $rest_of_monthly_mobile_internet;


        $fifteen_days_of_print_stationery = $fifteen_days_of_monthly_expenses->sum('print_stationery');
        $rest_of_print_stationery = $rest_of_monthly_expenses->sum('print_stationery');        
        $total_print_stationery = $fifteen_days_of_print_stationery + $rest_of_print_stationery;

        $total_genex = $expenseGroup->sum('other_expenses_amount');

        $all_total_genex += $total_genex;

        $total = $total_other_fare_amount + $total_other_expenses_amount + $total_postage + $total_mobile_internet + $total_print_stationery;

        $grand_total = $total + $total_da;

        $one_months_genex_remarks = [];
        foreach($expenseGroup as $one_day_expense){
            $one_months_genex_remarks[] = $one_day_expense->OtherExpenseMaster->other_expense ?? null;
        }
        $genex_remarks = array_unique(
            array_filter($one_months_genex_remarks, function ($value) {
                return $value !== null && $value !== "";
            })
        );

        $one_months_approved_by = [];
        foreach($expenseGroup as $one_day_expense){
            $one_months_approved_by[] = $one_day_expense->approvedBy->name ?? null;
        }
        $approved_by = array_unique(
            array_filter($one_months_approved_by, function ($value) {
                return $value !== null && $value !== "";
            })
        );

        $one_months_pre_approved = [];
        foreach($expenseGroup as $one_day_expense){
            $one_months_pre_approved[] = $one_day_expense->pre_approved ?? null;
        }
        $pre_approved = array_unique(
            array_filter($one_months_pre_approved, function ($value) {
                return $value !== null && $value !== "";
            })
        );

        $one_months_pre_approved_date = [];
        foreach($expenseGroup as $one_day_expense){
            $one_months_pre_approved_date[] = $one_day_expense->approved_date ?? null;
        }
        $pre_approved_date = array_unique(
            array_filter($one_months_pre_approved_date, function ($value) {
                return $value !== null && $value !== "";
            })
        );

        // dd($genex_remarks);

            // Add result to the array
            $results[] = [
                'employee_name' => $employeeName,
                'monthName' => $monthName,
                'company' => $company,
                'state' => $state,
                'location' => $location,
                'total_da' => $total_da,
                'fifteen_days_of_monthly_expenses_da' => $fifteen_days_of_monthly_expenses_da,
                'rest_of_monthly_expenses_da' => $rest_of_monthly_expenses_da,
                'total' => $total,
                'grand_total' => $grand_total,
                'fifteen_days_of_fare_amount' => $fifteen_days_of_fare_amount,
                'rest_of_other_fare_amount' => $rest_of_other_fare_amount,
                'total_other_fare_amount' => $total_other_fare_amount,


                
                'total_genex' => $total_genex,
                'genex_remarks' => $genex_remarks,
                'approved_by' => $approved_by,
                'pre_approved' => $pre_approved,
                'pre_approved_date' => $pre_approved_date,



                'fifteen_days_of_monthly_expenses_postage' => $fifteen_days_of_monthly_expenses_postage,
                'rest_of_monthly_expenses_postage' => $rest_of_monthly_expenses_postage,
                'total_postage' => $total_postage,


                'fifteen_days_of_mobile_internet' => $fifteen_days_of_mobile_internet,
                'rest_of_monthly_mobile_internet' => $rest_of_monthly_mobile_internet,
                'total_mobile_internet' => $total_mobile_internet,


                'fifteen_days_of_print_stationery' => $fifteen_days_of_print_stationery,
                'rest_of_print_stationery' => $rest_of_print_stationery,   
                'total_print_stationery' => $total_print_stationery,

                'fifteen_days_of_monthly_expenses_headquarters_days' => $fifteen_days_of_monthly_expenses_headquarters_days,
                'fifteen_days_of_monthly_expenses_outstation_days' => $fifteen_days_of_monthly_expenses_outstation_days,

                'rest_of_monthly_expenses_headquarters_days' => $rest_of_monthly_expenses_headquarters_days,
                'rest_of_monthly_expenses_outstation_days' => $rest_of_monthly_expenses_outstation_days,

                'monthly_expenses_headquarters_days' => $monthly_expenses_headquarters_days,
                'monthly_expenses_outstation_days' => $monthly_expenses_outstation_days,


                'expenses_count' => $expenseGroup->count(),
            ];
        }
        return view('dash.HODGenExReport.index', compact('title', 'fromDate', 'toDate', 'ExpenseDetailsReports', 'monthlyExpenses', 'results', 'all_total_genex'));
    }
}
