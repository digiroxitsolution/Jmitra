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


class ProcessReportController extends Controller
{
    public function ProcessReport(Request $request){
        $title = 'Process Report';

        // Validate the inputs
        $request->validate([
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
            'monthName' => 'nullable|date_format:Y-m|required_if:fromDate,toDate,NULL',
        ]);

        $monthName = $request->monthName;

        // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($monthName, 5, 2); // Extract month (09)
        $year = (int)substr($monthName, 0, 4); // Extract year (2024)

        // Retrieve input data
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        
        if ((!$fromDate && !$toDate) && !$monthName) {
            return view('dash.HODProcessReport.index', compact('title'));
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
        // Filter by fromDate and toDate if provided
        if (!$monthName) {

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


        $total_fare_amount = $monthlyExpenses->sum('fare_amount');
        $all_total_other_expenses_amount = $monthlyExpenses->sum('other_expenses_amount');

        $all_total_da =  $monthlyExpenses->sum('da_total');

        $all_fifteen_days_of_fare_amount = 0;




        $all_total_postage = $monthlyExpenses->sum('postage');  


        $all_total_mobile_internet =  $monthlyExpenses->sum('mobile_internet');

        
        $all_total_print_stationery = $monthlyExpenses->sum('print_stationery');



        $all_one_month_total = $total_fare_amount + $all_total_da + $all_total_other_expenses_amount;

        


        $results = [];
        $stateWiseDatas = [];

        $groupedmonthlyExpenses = $monthlyExpenses->groupBy('expense_id');
        $groupedByState = $monthlyExpenses->groupBy(function ($expense) {
            return $expense->state ? $expense->state->name : 'Unknown';
        });

        $all_total_expense = 0;
        $all_no_of_pax = 0;

        foreach ($groupedByState as $expenseState) {
            $firstExpenseGroup = $expenseState->first();
            $stateName = $firstExpenseGroup->user->userDetail->state->name;
            $total_fare = $expenseState->sum('fare_amount');

            // for Total Expenses
            $one_month_of_total_fare_amount = $expenseState->sum('fare_amount');
            $total_da =  $expenseState->sum('da_total');
            $total_other_expenses_amount = $expenseState->sum('other_expenses_amount');

            $total_expense = $one_month_of_total_fare_amount + $total_da + $total_other_expenses_amount;
            $all_total_expense = $all_total_expense + $total_expense;

             // Get unique user IDs for this state
            $uniqueUsers = $expenseState->pluck('user_id')->unique();
            $no_of_pax = $uniqueUsers->count();

            // Update total number of unique users across all states
            $all_no_of_pax += $no_of_pax;
            // dd($expenseState);

            

            $stateWiseDatas[] = [
                
                'stateName' => $stateName,
                'total_fare' => $total_fare,

                'total_expense' => $total_expense,

                'no_of_pax' => $no_of_pax,
            ];

        }

        // dd($groupedByState);

        // Loop through each grouped set of expenses (one group corresponds to one month)
        foreach ($groupedmonthlyExpenses as $expenseGroup) {
            // Get the employee name and calculate totals for this specific month
            $firstExpenseGroup = $expenseGroup->first();            
            $monthName = Carbon::parse($firstExpenseGroup->expense_date)->format('F');
            $company = $firstExpenseGroup->user->userDetail->companyMaster->company_name;
            $state = $firstExpenseGroup->user->userDetail->state->name;
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
              
        $total_da =  $expenseGroup->sum('da_total');

        $fifteen_days_of_fare_amount = $fifteen_days_of_monthly_expenses->sum('fare_amount');
        $all_fifteen_days_of_fare_amount = $all_fifteen_days_of_fare_amount + $fifteen_days_of_fare_amount;

        $one_month_of_total_fare_amount = $expenseGroup->sum('fare_amount');
        
        $total_other_expenses_amount = $expenseGroup->sum('other_expenses_amount');

        $total_postage = $expenseGroup->sum('postage');  


        $total_mobile_internet =  $expenseGroup->sum('mobile_internet');

        
        $total_print_stationery = $expenseGroup->sum('print_stationery');

        $calc = $total_da + $fifteen_days_of_fare_amount + (($total_postage + $total_mobile_internet + $total_print_stationery)/2);

        $one_month_total = $one_month_of_total_fare_amount + $total_da + $total_other_expenses_amount;


            // Add result to the array
            $results[] = [
                
                'monthName' => $monthName,
                'company' => $company,
                'state' => $state,
                
                'total_da' => $total_da,                                       
                'fifteen_days_of_fare_amount' => $fifteen_days_of_fare_amount,
                'one_month_of_total_fare_amount' => $one_month_of_total_fare_amount,
                'total_other_expenses_amount' => $total_other_expenses_amount,   
                'total_postage' => $total_postage,               
                'total_mobile_internet' => $total_mobile_internet,                  
                'total_print_stationery' => $total_print_stationery,
                'calc' => $calc,
                'one_month_total' => $one_month_total,
                'expenses_count' => $expenseGroup->count(),
            ];
        }
        $all_calc = $all_total_da + $all_fifteen_days_of_fare_amount + (($all_total_postage + $all_total_mobile_internet + $all_total_print_stationery)/2);
        
        return view('dash.HODProcessReport.index', compact('results', 'title', 'fromDate', 'toDate', 'ExpenseDetailsReports', 'monthlyExpenses', 'total_fare_amount', 'all_calc', 'all_total_other_expenses_amount', 'all_one_month_total', 'stateWiseDatas', 'all_total_expense', 'all_no_of_pax'));
    }
}
