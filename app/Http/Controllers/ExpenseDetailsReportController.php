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

class ExpenseDetailsReportController extends Controller
{
    public function derf(Request $request)
    {
        $title = 'Expense Details Report';

        // Validate the inputs
        $request->validate([
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
        ]);

        // Retrieve and filter data
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        // Join the tables
        $ExpenseDetailsReports = UserExpenseOtherRecords::with('MonthlyExpense')
            ->where('is_submitted', 1)  
            ->where('is_verified', 1) 
            ->where('is_approved', 1)
            ->where('status', 1);

    //     $ExpenseDetailsReports = MonthlyExpense::join('user_expense_other_records', function($join) {
    //     $join->on('user_expense_other_records.expense_id', '=', 'monthly_expenses.expense_id')
    //          ->where('user_expense_other_records.id', '=', 'monthly_expenses.user_expense_other_record_id');
    // })
    // ->where('user_expense_other_records.is_submitted', 1) 
    // ->where('user_expense_other_records.is_verified', 1)
    // ->where('user_expense_other_records.is_approved', 1)
    // ->where('user_expense_other_records.status', 1); 

        // Filter by fromDate and toDate if provided
        if ($fromDate) {
            $ExpenseDetailsReports->whereDate('expense_date', '>=', $fromDate);
        }

        if ($toDate) {
            $ExpenseDetailsReports->whereDate('expense_date', '<=', $toDate);
        }

        // Get the filtered data
        $ExpenseDetailsReports = $ExpenseDetailsReports->get();

        // Return view with data
        return view('dash.HODExpenseDetailReport.index', compact('title', 'fromDate', 'toDate', 'ExpenseDetailsReports'));
    }
    public function ExpenseDetailsReport(Request $request)
    {
        $title = 'Expense Details Report';

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
        $print_data =[
            'monthName' => $monthName,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ];

        if ((!$fromDate && !$toDate) && !$monthName) {
            return view('dash.HODExpenseDetailReport.index', compact('title'));
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
        $total_fare_amount = $monthlyExpenses->sum('fare_amount');

        $all_total_genex = $monthlyExpenses->sum('other_expenses_amount');
        $all_da_total = $monthlyExpenses->sum('da_total');
        $all_total_month = $total_fare_amount + $all_da_total + $all_total_genex;
        $all_total_other_expenses_amount = $monthlyExpenses->sum('other_expenses_amount');
        $all_total_da_working_location = $monthlyExpenses->sum('da_location');
        $all_total_working_da_outstation = $monthlyExpenses->sum('da_outstation');

        $all_total_mobile_internet = $monthlyExpenses->sum('mobile_internet');
        $all_total_postage = $monthlyExpenses->sum('postage');
        $all_total_print_stationery = $monthlyExpenses->sum('print_stationery');

        $all_fifteen_days_of_fare_amount = 0;
        $all_rest_of_other_fare_amount = 0;
        $all_fifteen_days_of_monthly_expenses_headquarters_days = 0;
        $all_fifteen_days_of_monthly_expenses_outstation_days = 0;
        $all_rest_of_monthly_expenses_headquarters_days = 0;
        $all_rest_of_monthly_expenses_outstation_days = 0;



        $results = [];

        $groupedmonthlyExpenses = $monthlyExpenses->groupBy('expense_id');

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
            // dd($rest_of_monthly_expenses_outstation_days);

            $all_fifteen_days_of_monthly_expenses_headquarters_days += $fifteen_days_of_monthly_expenses_headquarters_days;
            $all_fifteen_days_of_monthly_expenses_outstation_days += $fifteen_days_of_monthly_expenses_outstation_days;
            $all_rest_of_monthly_expenses_headquarters_days += $rest_of_monthly_expenses_headquarters_days;
            $all_rest_of_monthly_expenses_outstation_days += $rest_of_monthly_expenses_outstation_days;



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

        $all_fifteen_days_of_fare_amount += $fifteen_days_of_fare_amount;
        $all_rest_of_other_fare_amount += $rest_of_other_fare_amount;


        $fifteen_days_of_other_expenses_amount = $fifteen_days_of_monthly_expenses->sum('other_expenses_amount');
        $rest_of_other_expenses_amount = $rest_of_monthly_expenses->sum('other_expenses_amount');
        $total_other_expenses_amount = $fifteen_days_of_other_expenses_amount + $rest_of_other_expenses_amount; 


        $fifteen_days_of_monthly_expenses_postage = $fifteen_days_of_monthly_expenses->sum('postage');
        $rest_of_monthly_expenses_postage = $rest_of_monthly_expenses->sum('postage');
        $total_postage = $fifteen_days_of_monthly_expenses_postage + $rest_of_monthly_expenses_postage;  


        $fifteen_days_of_mobile_internet = $fifteen_days_of_monthly_expenses->sum('mobile_internet');
        $rest_of_monthly_mobile_internet = $rest_of_monthly_expenses->sum('mobile_internet');
        $total_mobile_internet =  $fifteen_days_of_mobile_internet + $rest_of_monthly_mobile_internet;


        $fifteen_days_of_print_stationery = $fifteen_days_of_monthly_expenses->sum('print_stationery');
        $rest_of_print_stationery = $rest_of_monthly_expenses->sum('print_stationery');        
        $total_print_stationery = $fifteen_days_of_print_stationery + $rest_of_print_stationery;


        $total_fare = $expenseGroup->sum('fare_amount');
        $total_genex = $expenseGroup->sum('other_expenses_amount');
        $da_total = $expenseGroup->sum('da_total');
        $total_month = $total_fare + $da_total + $total_genex;
        $total_other_expenses_amount = $expenseGroup->sum('other_expenses_amount');
        $total_da_working_location = $expenseGroup->sum('da_location');
        $total_working_da_outstation = $expenseGroup->sum('da_outstation');


        $total = $total_other_fare_amount + $total_other_expenses_amount + $total_postage + $total_mobile_internet + $total_print_stationery;

        $grand_total = $total + $total_da;

        $one_months_genex_remarks = [];
        foreach($expenseGroup as $one_day_expense){
            $one_months_genex_remarks[] = $one_day_expense->OtherExpenseMaster->other_expense ?? '';
        }
        $genex_remarks = array_unique(
            array_filter($one_months_genex_remarks, function ($value) {
                return $value !== null && $value !== "";
            })
        );
        // dd(array_unique($one_months_genex_remarks));


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


                'fifteen_days_of_other_expenses_amount' => $fifteen_days_of_other_expenses_amount,
                'rest_of_other_expenses_amount' => $rest_of_other_expenses_amount,
                'total_other_expenses_amount' => $total_other_expenses_amount,


                'fifteen_days_of_monthly_expenses_postage' => $fifteen_days_of_monthly_expenses_postage,
                'rest_of_monthly_expenses_postage' => $rest_of_monthly_expenses_postage,
                'total_postage' => $total_postage,


                'fifteen_days_of_mobile_internet' => $fifteen_days_of_mobile_internet,
                'rest_of_monthly_mobile_internet' => $rest_of_monthly_mobile_internet,
                'total_mobile_internet' => $total_mobile_internet,
                'total_genex' => $total_genex,
                'total_fare' => $total_fare,
                'total_other_expenses_amount' => $total_other_expenses_amount,
                'da_total' => $da_total,
                'total_month' => $total_month,
                'total_da_working_location' => $total_da_working_location,
                'total_working_da_outstation' => $total_working_da_outstation,
                'genex_remarks' => $genex_remarks,



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

        // Return view with data
        return view('dash.HODExpenseDetailReport.index', compact('title', 'fromDate', 'toDate', 'ExpenseDetailsReports', 'monthlyExpenses', 'results', 'total_fare_amount', 'all_total_genex', 'all_da_total', 'all_total_month', 'all_total_other_expenses_amount', 'all_total_da_working_location', 'all_total_working_da_outstation', 'all_total_mobile_internet', 'all_total_postage', 'all_total_print_stationery', 'all_fifteen_days_of_fare_amount', 'all_rest_of_other_fare_amount', 'all_fifteen_days_of_monthly_expenses_headquarters_days', 'all_fifteen_days_of_monthly_expenses_outstation_days',  'all_rest_of_monthly_expenses_headquarters_days', 'all_rest_of_monthly_expenses_outstation_days', 'print_data'));
    }

    public function Print(Request $request)
    {
        $title = 'Expense Details Report';

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
        $print_data =[
            'monthName' => $monthName,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ];

        if ((!$fromDate && !$toDate) && !$monthName) {
            return view('dash.HODExpenseDetailReport.index', compact('title'));
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
        $total_fare_amount = $monthlyExpenses->sum('fare_amount');

        $all_total_genex = $monthlyExpenses->sum('other_expenses_amount');
        $all_da_total = $monthlyExpenses->sum('da_total');
        $all_total_month = $total_fare_amount + $all_da_total + $all_total_genex;
        $all_total_other_expenses_amount = $monthlyExpenses->sum('other_expenses_amount');
        $all_total_da_working_location = $monthlyExpenses->sum('da_location');
        $all_total_working_da_outstation = $monthlyExpenses->sum('da_outstation');

        $all_total_mobile_internet = $monthlyExpenses->sum('mobile_internet');
        $all_total_postage = $monthlyExpenses->sum('postage');
        $all_total_print_stationery = $monthlyExpenses->sum('print_stationery');

        $all_fifteen_days_of_fare_amount = 0;
        $all_rest_of_other_fare_amount = 0;
        $all_fifteen_days_of_monthly_expenses_headquarters_days = 0;
        $all_fifteen_days_of_monthly_expenses_outstation_days = 0;
        $all_rest_of_monthly_expenses_headquarters_days = 0;
        $all_rest_of_monthly_expenses_outstation_days = 0;



        $results = [];

        $groupedmonthlyExpenses = $monthlyExpenses->groupBy('expense_id');

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
            // dd($rest_of_monthly_expenses_outstation_days);

            $all_fifteen_days_of_monthly_expenses_headquarters_days += $fifteen_days_of_monthly_expenses_headquarters_days;
            $all_fifteen_days_of_monthly_expenses_outstation_days += $fifteen_days_of_monthly_expenses_outstation_days;
            $all_rest_of_monthly_expenses_headquarters_days += $rest_of_monthly_expenses_headquarters_days;
            $all_rest_of_monthly_expenses_outstation_days += $rest_of_monthly_expenses_outstation_days;



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

        $all_fifteen_days_of_fare_amount += $fifteen_days_of_fare_amount;
        $all_rest_of_other_fare_amount += $rest_of_other_fare_amount;


        $fifteen_days_of_other_expenses_amount = $fifteen_days_of_monthly_expenses->sum('other_expenses_amount');
        $rest_of_other_expenses_amount = $rest_of_monthly_expenses->sum('other_expenses_amount');
        $total_other_expenses_amount = $fifteen_days_of_other_expenses_amount + $rest_of_other_expenses_amount; 


        $fifteen_days_of_monthly_expenses_postage = $fifteen_days_of_monthly_expenses->sum('postage');
        $rest_of_monthly_expenses_postage = $rest_of_monthly_expenses->sum('postage');
        $total_postage = $fifteen_days_of_monthly_expenses_postage + $rest_of_monthly_expenses_postage;  


        $fifteen_days_of_mobile_internet = $fifteen_days_of_monthly_expenses->sum('mobile_internet');
        $rest_of_monthly_mobile_internet = $rest_of_monthly_expenses->sum('mobile_internet');
        $total_mobile_internet =  $fifteen_days_of_mobile_internet + $rest_of_monthly_mobile_internet;


        $fifteen_days_of_print_stationery = $fifteen_days_of_monthly_expenses->sum('print_stationery');
        $rest_of_print_stationery = $rest_of_monthly_expenses->sum('print_stationery');        
        $total_print_stationery = $fifteen_days_of_print_stationery + $rest_of_print_stationery;


        $total_fare = $expenseGroup->sum('fare_amount');
        $total_genex = $expenseGroup->sum('other_expenses_amount');
        $da_total = $expenseGroup->sum('da_total');
        $total_month = $total_fare + $da_total + $total_genex;
        $total_other_expenses_amount = $expenseGroup->sum('other_expenses_amount');
        $total_da_working_location = $expenseGroup->sum('da_location');
        $total_working_da_outstation = $expenseGroup->sum('da_outstation');


        $total = $total_other_fare_amount + $total_other_expenses_amount + $total_postage + $total_mobile_internet + $total_print_stationery;

        $grand_total = $total + $total_da;

        $one_months_genex_remarks = [];
        foreach($expenseGroup as $one_day_expense){
            $one_months_genex_remarks[] = $one_day_expense->OtherExpenseMaster->other_expense ?? '';
        }
        $genex_remarks = array_unique(
            array_filter($one_months_genex_remarks, function ($value) {
                return $value !== null && $value !== "";
            })
        );
        // dd(array_unique($one_months_genex_remarks));


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


                'fifteen_days_of_other_expenses_amount' => $fifteen_days_of_other_expenses_amount,
                'rest_of_other_expenses_amount' => $rest_of_other_expenses_amount,
                'total_other_expenses_amount' => $total_other_expenses_amount,


                'fifteen_days_of_monthly_expenses_postage' => $fifteen_days_of_monthly_expenses_postage,
                'rest_of_monthly_expenses_postage' => $rest_of_monthly_expenses_postage,
                'total_postage' => $total_postage,


                'fifteen_days_of_mobile_internet' => $fifteen_days_of_mobile_internet,
                'rest_of_monthly_mobile_internet' => $rest_of_monthly_mobile_internet,
                'total_mobile_internet' => $total_mobile_internet,
                'total_genex' => $total_genex,
                'total_fare' => $total_fare,
                'total_other_expenses_amount' => $total_other_expenses_amount,
                'da_total' => $da_total,
                'total_month' => $total_month,
                'total_da_working_location' => $total_da_working_location,
                'total_working_da_outstation' => $total_working_da_outstation,
                'genex_remarks' => $genex_remarks,



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

        // Return view with data
        $view = view('dash.HODExpenseDetailReport.print', compact('title', 'fromDate', 'toDate', 'ExpenseDetailsReports', 'monthlyExpenses', 'results', 'total_fare_amount', 'all_total_genex', 'all_da_total', 'all_total_month', 'all_total_other_expenses_amount', 'all_total_da_working_location', 'all_total_working_da_outstation', 'all_total_mobile_internet', 'all_total_postage', 'all_total_print_stationery', 'all_fifteen_days_of_fare_amount', 'all_rest_of_other_fare_amount', 'all_fifteen_days_of_monthly_expenses_headquarters_days', 'all_fifteen_days_of_monthly_expenses_outstation_days',  'all_rest_of_monthly_expenses_headquarters_days', 'all_rest_of_monthly_expenses_outstation_days', 'print_data'));

        $pdf = PDF::loadHTML($view)->setPaper([0, 0, 793, 1404], 'landscape');

        // Return the generated PDF as a response
        // return $pdf->download('expense_detail_reports.pdf');
        return $pdf->stream('expense_detail_reports.pdf');
    }


}
