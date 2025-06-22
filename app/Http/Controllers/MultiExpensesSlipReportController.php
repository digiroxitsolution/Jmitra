<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use NumberFormatter;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\DivisonMaster;
use App\Models\ModeofExpenseMaster;
use App\Models\MonthlyExpense;
use App\Models\MonthlyExpenseHistory;
use App\Models\UserExpenseOtherRecords;
use App\Models\ExpenseTypeMaster;
use App\Models\OtherExpenseMaster;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MultiExpensesSlipReportController extends Controller
{
    public function MultiExpensesSlipReport(){
        $title = "Multi Expenses Slip Report";

        return view('dash.HODMultiExpenseSlipReport.index', compact('title'));
    }
    public function search(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'employee_id' => 'nullable|string|max:255',
            'employee_name' => 'nullable|string|max:255',
            'month_of' => 'nullable|date_format:Y-m',
            'expense_id' => 'nullable|string|max:255',
        ]);
        $employee_id = $request->employee_id;
        $employee_name = $request->employee_name;
        $month_of = $request->month_of;
        $expense_id = $request->expense_id;

        $print_data = [
            'employee_id' => $employee_id,
            'employee_name' => $employee_name,
            'month_of' => $month_of,
            'expense_id' => $expense_id
        ];

        // Extract month and year from the 'month_of' input

        $month = null;
        $year = null;
        if (!empty($validated['month_of'])) {
            $month = date('m', strtotime($validated['month_of']));
            $year = date('Y', strtotime($validated['month_of']));
        }

        // Build the query
        $query = MonthlyExpense::query();

        // Join with UserDetails and Users table for additional filtering
        $query = DB::table('monthly_expenses')->join('user_details', 'monthly_expenses.user_id', '=', 'user_details.user_id')->join('users', 'users.id', '=', 'user_details.user_id')->leftJoin('user_expense_other_records', 'monthly_expenses.user_expense_other_records_id', '=', 'user_expense_other_records.id')->select(
                'monthly_expenses.*',
                'user_details.employee_id',
                'users.name as employee_name',
                'user_expense_other_records.advance_taken',
                'user_expense_other_records.justification'
            )->distinct();

        // Apply filters based on the inputs
        if (!empty($validated['employee_id'])) {
            $query->where('user_details.employee_id', $validated['employee_id'])->where('user_expense_other_records.is_submitted', 1)->where('user_expense_other_records.is_verified', 1);
        }

        if (!empty($validated['employee_name'])) {
            $query->where('users.name', 'LIKE', '%' . $validated['employee_name'] . '%')->where('user_expense_other_records.is_submitted', 1)->where('user_expense_other_records.is_verified', 1);
        }

        if (!empty($month) && !empty($year)) {
            // Corrected query to reference the correct table and column
            $query->whereMonth('monthly_expenses.expense_date', $month)
                ->whereYear('monthly_expenses.expense_date', $year)->where('user_expense_other_records.is_submitted', 1)->where('user_expense_other_records.is_verified', 1);
        }

        if (!empty($validated['expense_id'])) {
            $query->where('monthly_expenses.expense_id', $validated['expense_id'])->where('user_expense_other_records.is_submitted', 1)->where('user_expense_other_records.is_verified', 1);
        }

        $expenses = $query->get();

        // Initialize an array to hold calculations
        $results = [];

        // Group expenses by expense_id (which corresponds to one month of expenses)
        $groupedExpenses = $expenses->groupBy('expense_id');

        // Loop through each grouped set of expenses (one group corresponds to one month)
        foreach ($groupedExpenses as $expenseGroup) {
            // Get the employee name and calculate totals for this specific month
            $first_expenseGroup = $expenseGroup->first();
            $employeeName = $first_expenseGroup->employee_name;
            $expense_id = $first_expenseGroup->expense_id;
            $employee_id = $first_expenseGroup->employee_id;

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


        $total = $total_other_fare_amount + $total_other_expenses_amount + $total_postage + $total_mobile_internet + $total_print_stationery;

        $grand_total = $total + $total_da;

            // Add result to the array
            $results[] = [
                'employee_name' => $employeeName,
                'expense_id' => $expense_id,
                'employee_id' =>$employee_id,
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
        // dd($groupedExpenses);

        // Return the results to the view
        return view('dash.HODMultiExpenseSlipReport.search', compact('groupedExpenses','results', 'employee_id', 'employee_name', 'month_of', 'expense_id', 'print_data'));
    }

    // public function search(Request $request)
    // {
    //     // Validate input
    //     $validated = $request->validate([
    //         'employee_id' => 'nullable|string|max:255',
    //         'employee_name' => 'nullable|string|max:255',
    //         'month_of' => 'nullable|date_format:Y-m',
    //         'expense_id' => 'nullable|string|max:255',
    //     ]);

    //     // Extract month and year from the 'month_of' input
    //     $month = null;
    //     $year = null;
    //     if (!empty($validated['month_of'])) {
    //         $month = date('m', strtotime($validated['month_of']));
    //         $year = date('Y', strtotime($validated['month_of']));
    //     }

    //     // Build the query
    //     $query = MonthlyExpense::query();

    //     // Join with UserDetails and Users table for additional filtering
    //     $query = DB::table('monthly_expenses')->join('user_details', 'monthly_expenses.user_id', '=', 'user_details.user_id')->join('users', 'users.id', '=', 'user_details.user_id')->leftJoin('user_expense_other_records', 'monthly_expenses.user_expense_other_records_id', '=', 'user_expense_other_records.id')->select(
    //             'monthly_expenses.*',
    //             'user_details.employee_id',
    //             'users.name as employee_name',
    //             'user_expense_other_records.advance_taken',
    //             'user_expense_other_records.justification'
    //         )->distinct();

    //     // Apply filters based on the inputs
    //     if (!empty($validated['employee_id'])) {
    //         $query->where('user_details.employee_id', $validated['employee_id']);
    //     }

    //     // if (!empty($validated['employee_name'])) {
    //     //     $query->where('users.name', 'LIKE', '%' . $validated['employee_name'] . '%');
    //     // }

    //     if (!empty($month) && !empty($year)) {
    //         $query->whereMonth('monthly_expenses.expense_date', $month)
    //             ->whereYear('monthly_expenses.expense_date', $year);
    //     }

    //     if (!empty($validated['expense_id'])) {
    //         $query->where('monthly_expenses.expense_id', $validated['expense_id']);
    //     }
    //     $firstMonthlyExpense = $query->first();

    //     if (!$firstMonthlyExpense) {
    //         return redirect()->route('multi_expenses_slip_report')->with('error', 'No expenses found matching the given criteria.');
    //     }

    //     // Check related record conditions
    //     $userExpenseOtherRecord = UserExpenseOtherRecords::where('id', $firstMonthlyExpense->user_expense_other_records_id)
    //         ->where('is_submitted', 1)
    //         ->where('is_verified', 1)
    //         ->first();

    //     $expenses = $userExpenseOtherRecord ? $query->get() : collect();

    //     // Check if there are no results
    //     if ($expenses->isEmpty()) {
    //         return redirect()->route('multi_expenses_slip_report')->with('error', 'No expenses found matching the given criteria.');
    //     }

    //     // Initialize an array to hold calculations
    //     $results = [];

    //     // Group expenses by expense_id (which corresponds to one month of expenses)
    //     $groupedExpenses = $expenses->groupBy('expense_id');

    //     // Loop through each grouped set of expenses (one group corresponds to one month)
    //     foreach ($groupedExpenses as $expenseGroup) {
    //         // Get the employee name and calculate totals for this specific month
    //         $employeeName = $expenseGroup->first()->employee_name;

    //         // Initialize calculation variables
    //         $fifteen_days_of_monthly_expenses_headquarters = 0;
    //         $fifteen_days_of_monthly_expenses_outstation = 0;
    //         $rest_of_monthly_expenses_headquarters = 0;
    //         $rest_of_monthly_expenses_outstation = 0;

    //         // Separate expenses where `expense_type_master_id` is not equal to 3
    //         $monthly_expenses_headquarters = $expenseGroup->filter(function ($expense) {
    //             return $expense->expense_type_master_id != 3 && $expense->expense_type_master_id !== null;
    //         });        

    //         // Separate expenses where `expense_type_master_id` is equal to 3
    //         $monthly_expenses_outstation = $expenseGroup->filter(function ($expense) {
    //             return $expense->expense_type_master_id == 3;
    //         });


            


    //         // Define the start and end of the first 15 days
    //         $startOfMonth = Carbon::parse($expenseGroup->first()->expense_date)->startOfMonth();
    //         $middleOfMonth = $startOfMonth->copy()->addDays(14);

    //         // Define the start of the 16th day and the end of the month
    //         $restOfMonthStart = $startOfMonth->copy()->addDays(15);
    //         $endOfMonth = $startOfMonth->copy()->endOfMonth();

    //         // Query for the first 15 days
    //         $fifteen_days_of_monthly_expenses = $expenseGroup->filter(function ($exp) use ($startOfMonth, $middleOfMonth) {
    //             $expenseDate = Carbon::parse($exp->expense_date);
    //             return $expenseDate->between($startOfMonth, $middleOfMonth);
    //         });

    //         // Query for the rest of the month
    //         $rest_of_monthly_expenses = $expenseGroup->filter(function ($exp) use ($restOfMonthStart, $endOfMonth) {
    //             $expenseDate = Carbon::parse($exp->expense_date);
    //             return $expenseDate->between($restOfMonthStart, $endOfMonth);
    //         });



    //         $fifteen_days_of_monthly_expenses_headquarters = $fifteen_days_of_monthly_expenses->filter(function ($expense) {
    //             return $expense->expense_type_master_id != 3 && $expense->expense_type_master_id !== null;
    //         });
    //         // Separate expenses where `expense_type_master_id` is equal to 3
    //         $fifteen_days_of_monthly_expenses_outstation = $fifteen_days_of_monthly_expenses->filter(function ($expense) {
    //             return $expense->expense_type_master_id == 3;
    //         });


    //         $rest_of_monthly_expenses_headquarters = $rest_of_monthly_expenses->filter(function ($expense) {
    //             return $expense->expense_type_master_id != 3 && $expense->expense_type_master_id !== null;
    //         });

    //         // Separate expenses where `expense_type_master_id` is equal to 3
    //         $rest_of_monthly_expenses_outstation = $rest_of_monthly_expenses->filter(function ($expense) {
    //             return $expense->expense_type_master_id == 3;
    //         });



    //         $monthly_expenses_headquarters_days = count($monthly_expenses_headquarters);
    //         $monthly_expenses_outstation_days = count($monthly_expenses_outstation);

    //         $fifteen_days_of_monthly_expenses_headquarters_days = count($fifteen_days_of_monthly_expenses_headquarters);
    //         $fifteen_days_of_monthly_expenses_outstation_days = count($fifteen_days_of_monthly_expenses_outstation);


    //         $rest_of_monthly_expenses_headquarters_days = count($rest_of_monthly_expenses_headquarters);    
    //         $rest_of_monthly_expenses_outstation_days = count($rest_of_monthly_expenses_outstation);



    //         $fifteen_days_of_monthly_expenses_headquarters = $fifteen_days_of_monthly_expenses_headquarters->sum('da_total');
    //         $fifteen_days_of_monthly_expenses_outstation = $fifteen_days_of_monthly_expenses_outstation->sum('da_total');


    //         $rest_of_monthly_expenses_headquarters = $rest_of_monthly_expenses_headquarters->sum('da_total');
    //         $rest_of_monthly_expenses_outstation = $rest_of_monthly_expenses_outstation->sum('da_total');
            

            




    //     $fifteen_days_of_monthly_expenses_da = $fifteen_days_of_monthly_expenses_headquarters + $fifteen_days_of_monthly_expenses_outstation;
    //     $rest_of_monthly_expenses_da = $rest_of_monthly_expenses_headquarters + $rest_of_monthly_expenses_outstation;       
    //     $total_da = $fifteen_days_of_monthly_expenses_da + $rest_of_monthly_expenses_da;  

    //     $fifteen_days_of_fare_amount = $fifteen_days_of_monthly_expenses->sum('fare_amount');
    //     $rest_of_other_fare_amount = $rest_of_monthly_expenses->sum('fare_amount');
    //     $total_other_fare_amount = $fifteen_days_of_fare_amount + $rest_of_other_fare_amount;


    //     $fifteen_days_of_other_expenses_amount = $fifteen_days_of_monthly_expenses->sum('other_expenses_amount');
    //     $rest_of_other_expenses_amount = $rest_of_monthly_expenses->sum('other_expenses_amount');
    //     $total_other_expenses_amount = $fifteen_days_of_other_expenses_amount + $rest_of_other_expenses_amount; 


    //     $fifteen_days_of_monthly_expenses_postage = $fifteen_days_of_monthly_expenses->sum('postage');
    //     $rest_of_monthly_expenses_postage = $rest_of_monthly_expenses->sum('postage');
    //     $total_postage = $fifteen_days_of_monthly_expenses_postage + $rest_of_monthly_expenses_postage;  


    //     $fifteen_days_of_mobile_internet = $fifteen_days_of_monthly_expenses->sum('mobile_internet');
    //     $rest_of_monthly_mobile_internet = $rest_of_monthly_expenses->sum('mobile_internet');
    //     $total_mobile_internet =  $fifteen_days_of_mobile_internet + $rest_of_monthly_mobile_internet;


    //     $fifteen_days_of_print_stationery = $fifteen_days_of_monthly_expenses->sum('print_stationery');
    //     $rest_of_print_stationery = $rest_of_monthly_expenses->sum('print_stationery');        
    //     $total_print_stationery = $fifteen_days_of_print_stationery + $rest_of_print_stationery;


    //     $total = $total_other_fare_amount + $total_other_expenses_amount + $total_postage + $total_mobile_internet + $total_print_stationery;

    //     $grand_total = $total + $total_da;

    //         // Add result to the array
    //         $results[] = [
    //             'employee_name' => $employeeName,
    //             'total_da' => $total_da,
    //             'fifteen_days_of_monthly_expenses_da' => $fifteen_days_of_monthly_expenses_da,
    //             'rest_of_monthly_expenses_da' => $rest_of_monthly_expenses_da,
    //             'total' => $total,
    //             'grand_total' => $grand_total,
    //             'fifteen_days_of_fare_amount' => $fifteen_days_of_fare_amount,
    //             'rest_of_other_fare_amount' => $rest_of_other_fare_amount,
    //             'total_other_fare_amount' => $total_other_fare_amount,


    //             'fifteen_days_of_other_expenses_amount' => $fifteen_days_of_other_expenses_amount,
    //             'rest_of_other_expenses_amount' => $rest_of_other_expenses_amount,
    //             'total_other_expenses_amount' => $total_other_expenses_amount,


    //             'fifteen_days_of_monthly_expenses_postage' => $fifteen_days_of_monthly_expenses_postage,
    //             'rest_of_monthly_expenses_postage' => $rest_of_monthly_expenses_postage,
    //             'total_postage' => $total_postage,


    //             'fifteen_days_of_mobile_internet' => $fifteen_days_of_mobile_internet,
    //             'rest_of_monthly_mobile_internet' => $rest_of_monthly_mobile_internet,
    //             'total_mobile_internet' => $total_mobile_internet,


    //             'fifteen_days_of_print_stationery' => $fifteen_days_of_print_stationery,
    //             'rest_of_print_stationery' => $rest_of_print_stationery,   
    //             'total_print_stationery' => $total_print_stationery,

    //             'fifteen_days_of_monthly_expenses_headquarters_days' => $fifteen_days_of_monthly_expenses_headquarters_days,
    //             'fifteen_days_of_monthly_expenses_outstation_days' => $fifteen_days_of_monthly_expenses_outstation_days,

    //             'rest_of_monthly_expenses_headquarters_days' => $rest_of_monthly_expenses_headquarters_days,
    //             'rest_of_monthly_expenses_outstation_days' => $rest_of_monthly_expenses_outstation_days,

    //             'monthly_expenses_headquarters_days' => $monthly_expenses_headquarters_days,
    //             'monthly_expenses_outstation_days' => $monthly_expenses_outstation_days,


    //             'expenses_count' => $expenseGroup->count(),
    //         ];
    //     }
    //     // dd($groupedExpenses);

    //     // Return the results to the view
    //     return view('dash.HODMultiExpenseSlipReport.search', compact('groupedExpenses','results'));
    // }


    // public function search(Request $request)
    // {
    //     // Validate input
    //     $validated = $request->validate([
    //         'employee_id' => 'nullable|string|max:255',
    //         'employee_name' => 'nullable|string|max:255',
    //         'month_of' => 'nullable|date_format:Y-m',
    //         'expense_id' => 'nullable|string|max:255',
    //     ]);

    //     // Extract month and year from the 'month_of' input
    //     $month = null;
    //     $year = null;
    //     if (!empty($validated['month_of'])) {
    //         $month = date('m', strtotime($validated['month_of']));
    //         $year = date('Y', strtotime($validated['month_of']));
    //     }

    //     // Build the query
    //     $query = MonthlyExpense::query();

    //     // Join with UserDetails and Users table for additional filtering
    //     $query = DB::table('monthly_expenses')
    //         ->join('user_details', 'monthly_expenses.user_id', '=', 'user_details.user_id')
    //         ->join('users', 'users.id', '=', 'user_details.user_id')
    //         ->leftJoin('user_expense_other_records', 'monthly_expenses.user_id', '=', 'user_expense_other_records.user_id')
    //         ->select(
    //             'monthly_expenses.*',
    //             'user_details.employee_id',
    //             'users.name as employee_name',
    //             'user_expense_other_records.advance_taken',
    //             'user_expense_other_records.justification'
    //         );

    //     // Apply filters
    //     if (!empty($validated['employee_id'])) {
    //         $query->where('user_details.employee_id', $validated['employee_id']);
    //     }

    //     if (!empty($validated['employee_name'])) {
    //         $query->where('users.name', 'LIKE', '%' . $validated['employee_name'] . '%');
    //     }

    //     if (!empty($month) && !empty($year)) {
    //         $query->whereMonth('monthly_expenses.expense_date', $month)
    //             ->whereYear('monthly_expenses.expense_date', $year);
    //     }

    //     if (!empty($validated['expense_id'])) {
    //         $query->where('monthly_expenses.expense_id', $validated['expense_id']);
    //     }

    //     $expenses = $query->get();
        

    //     // Pass results to the view
    //     return view('dash.HODMultiExpenseSlipReport.index', compact('expenses'));
    // }

    public function print(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'employee_id' => 'nullable|string|max:255',
            'employee_name' => 'nullable|string|max:255',
            'month_of' => 'nullable|date_format:Y-m',
            'expense_id' => 'nullable|string|max:255',
        ]);

        // Extract month and year from the 'month_of' input
        $month = null;
        $year = null;
        if (!empty($validated['month_of'])) {
            $month = date('m', strtotime($validated['month_of']));
            $year = date('Y', strtotime($validated['month_of']));
        }

        // Build the query
        $query = MonthlyExpense::query();

        // Join with UserDetails and Users table for additional filtering
        $query = DB::table('monthly_expenses')->join('user_details', 'monthly_expenses.user_id', '=', 'user_details.user_id')->join('users', 'users.id', '=', 'user_details.user_id')->leftJoin('user_expense_other_records', 'monthly_expenses.user_expense_other_records_id', '=', 'user_expense_other_records.id')->select(
                'monthly_expenses.*',
                'user_details.employee_id',
                'users.name as employee_name',
                'user_expense_other_records.advance_taken',
                'user_expense_other_records.justification'
            )->distinct();

        // Apply filters based on the inputs
        if (!empty($validated['employee_id'])) {
            $query->where('user_details.employee_id', $validated['employee_id'])->where('user_expense_other_records.is_submitted', 1)->where('user_expense_other_records.is_verified', 1);
        }

        if (!empty($validated['employee_name'])) {
            $query->where('users.name', 'LIKE', '%' . $validated['employee_name'] . '%')->where('user_expense_other_records.is_submitted', 1)->where('user_expense_other_records.is_verified', 1);
        }

        if (!empty($month) && !empty($year)) {
            // Corrected query to reference the correct table and column
            $query->whereMonth('monthly_expenses.expense_date', $month)
                ->whereYear('monthly_expenses.expense_date', $year)->where('user_expense_other_records.is_submitted', 1)->where('user_expense_other_records.is_verified', 1);
        }

        if (!empty($validated['expense_id'])) {
            $query->where('monthly_expenses.expense_id', $validated['expense_id'])->where('user_expense_other_records.is_submitted', 1)->where('user_expense_other_records.is_verified', 1);
        }

        $expenses = $query->get();

        // Initialize an array to hold calculations
        $results = [];

        // Group expenses by expense_id (which corresponds to one month of expenses)
        $groupedExpenses = $expenses->groupBy('expense_id');

        // Loop through each grouped set of expenses (one group corresponds to one month)
        foreach ($groupedExpenses as $expenseGroup) {
            // Get the employee name and calculate totals for this specific month
            $first_expenseGroup = $expenseGroup->first();
            $employeeName = $first_expenseGroup->employee_name;
            $expense_id = $first_expenseGroup->expense_id;
            $employee_id = $first_expenseGroup->employee_id;

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


        $total = $total_other_fare_amount + $total_other_expenses_amount + $total_postage + $total_mobile_internet + $total_print_stationery;

        $grand_total = $total + $total_da;

            // Add result to the array
            $results[] = [
                'employee_name' => $employeeName,
                'expense_id' => $expense_id,
                'employee_id' =>$employee_id,
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
        // dd($groupedExpenses);

        // Return the results to the view
        $view = view('dash.HODMultiExpenseSlipReport.print', compact('groupedExpenses','results'));
        $pdf = PDF::loadHTML($view)->setPaper([0, 0, 793, 1404], 'landscape');

        // Return the generated PDF as a response
        return $pdf->download('multi_expense_slip_report.pdf');
        // return $pdf->stream('multi_expense_slip_report.pdf');

    }
}
