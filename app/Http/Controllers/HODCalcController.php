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
use App\Models\Sales;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
 

class HODCalcController extends Controller
{
    // Function to calculate GCD using the Euclidean algorithm
    public function gcd($a, $b) {
        while ($b != 0) {
            $temp = $b;
            $b = $a % $b;
            $a = $temp;
        }
        return $a;
    }

    public function hODCalc(Request $request)
    {
        $title = 'Calc';

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
            return view('dash.HODCalc.index', compact('title'));
        }

        // Retrieve and sort states by short name
        $states = DB::table('states')->orderBy('short', 'ASC')->get();
        $stateNames = $states->pluck('short')->toArray();
        $stateMap = $states->pluck('name', 'short')->toArray();

        if ($fromDate || $toDate || $monthName) {
            // Fetch Expense Data
            $ExpenseFareReports = UserExpenseOtherRecords::with('MonthlyExpense')
                ->where('is_submitted', 1)
                ->where('is_verified', 1);
                // ->where('status', 1);
            if (!$monthName) {

                if ($fromDate) {
                    $ExpenseFareReports->whereDate('expense_date', '>=', $fromDate);
                }
                if ($toDate) {
                    $ExpenseFareReports->whereDate('expense_date', '<=', $toDate);
                }
            }
            if ($monthName) {
                $ExpenseFareReports->whereMonth('expense_date', $month)->whereYear('expense_date', $year);
            }

            $ExpenseFareReports = $ExpenseFareReports->get();
            $monthlyExpenses = $ExpenseFareReports->pluck('MonthlyExpense')->flatten();

            // Group expenses by state
            $groupedByStateExpenses = $monthlyExpenses->groupBy(function ($expense) use ($stateMap) {
                return $expense->state && isset($stateMap[$expense->state->short]) 
                    ? $stateMap[$expense->state->short] 
                    : 'Unknown';
            });

            // Fetch Sales Data
            $salesQuery = Sales::query();
            if (!$monthName) {
                if ($fromDate) {
                    $salesQuery->whereDate('date_of_sales', '>=', $fromDate);
                }
                if ($toDate) {
                    $salesQuery->whereDate('date_of_sales', '<=', $toDate);
                }
            }
            

            if ($monthName) {
                $salesQuery->whereMonth('date_of_sales', $month)->whereYear('date_of_sales', $year);
            }
            $sales = $salesQuery->get();

            // Group sales by state
            $groupedByStateSales = $sales->groupBy(function ($sale) use ($stateMap) {
                return $sale->state && isset($stateMap[$sale->state->short]) 
                    ? $stateMap[$sale->state->short] 
                    : 'Unknown';
            });

            // Merge sales and expenses data
            $combined_sales_expenses = [];
            foreach ($stateNames as $state) {
                // Get the short state name directly
                $stateName = $state;  // Using the short name directly
                
                // Get expenses and sales for the state
                $expense = $groupedByStateExpenses[$stateMap[$state]] ?? collect([]);
                $sale = $groupedByStateSales[$stateMap[$state]] ?? collect([]);

                // Calculate the sales expense ratio
                $salesAmount = $sale->sum('sales_amount');
                $expenseAmount = $expense->sum('fare_amount');

                // Calculate the sales expense ratio
                $salesAmount = $sale->sum('sales_amount');
                $expenseAmount = $expense->sum('fare_amount');

                // Initialize the sales-expense ratio
                if ($expenseAmount > 0) {
                    // Calculate the sales-expense ratio
                    $sales_expense_ratio = $salesAmount / $expenseAmount; // Ratio as a number
                    $sales_expense_ratio = is_numeric($sales_expense_ratio) ? number_format($sales_expense_ratio, 2) . "%" : $sales_expense_ratio;
                } elseif ($salesAmount > 0) {
                    // If no expenses but sales are present, return "No Expenses"
                    $sales_expense_ratio = "100%";
                } else {
                    // If both sales and expenses are zero, return "0"
                    $sales_expense_ratio = "0";
                }

                // Store the combined data using the short name
                $combined_sales_expenses[$stateName] = [
                    'expenses' => $expenseAmount,
                    'sales' => $salesAmount,
                    'sales_expense_ratio' => $sales_expense_ratio,
                ];
            }
            // dd($combined_sales_expenses);

        
        }
        $results = [];
        // Loop through each grouped set of expenses (one group corresponds to one month)
        foreach ($groupedByStateExpenses as $expenseGroup) {
            // Get the employee name and calculate totals for this specific month
            $firstExpenseGroup = $expenseGroup->first();            
            $monthName = Carbon::parse($firstExpenseGroup->expense_date)->format('F');
            
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
        

        $one_month_of_total_fare_amount = $expenseGroup->sum('fare_amount');
        
        $total_other_expenses_amount = $expenseGroup->sum('other_expenses_amount');

        $total_postage = $expenseGroup->sum('postage');  


        $total_mobile_internet =  $expenseGroup->sum('mobile_internet');
        // for Total Expenses
        
        
        

        $total_expense = $one_month_of_total_fare_amount + $total_da + $total_other_expenses_amount;

        
        $total_print_stationery = $expenseGroup->sum('print_stationery');

        $calc = $total_da + $fifteen_days_of_fare_amount + (($total_postage + $total_mobile_internet + $total_print_stationery)/2);

        $one_month_total = $one_month_of_total_fare_amount + $total_da + $total_other_expenses_amount;


            // Add result to the array
            $results[] = [
                
                'monthName' => $monthName,
                
                'state' => $state,            
                
                'calc' => $calc,
                'total_expense' => $total_expense,
                
            ];
        }

        return view('dash.HODCalc.index', compact('title', 'fromDate', 'toDate', 'monthName', 'groupedByStateExpenses', 'groupedByStateSales', 'combined_sales_expenses', 'results'));
    }

}
