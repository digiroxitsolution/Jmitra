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
// use Dompdf\Dompdf;

use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class SalesExpensesController extends Controller
{
    // Function to calculate GCD using the Euclidean algorithm
    public function gcd($a, $b)
    {
        while ($b != 0) {
            $temp = $b;
            $b = $a % $b;
            $a = $temp;
        }
        return $a;
    }
    public function SalesExpenses(Request $request)
    {
        $title = 'Sale Expenses';

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
        $print_data = [
            'monthName' => $monthName,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ];
        if ((!$fromDate && !$toDate) && !$monthName) {
            return view('dash.SalesExpenses.SalesExpense.index', compact('title'));
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

            return view('dash.SalesExpenses.SalesExpense.index', compact('title', 'fromDate', 'toDate', 'monthName', 'groupedByStateExpenses', 'groupedByStateSales', 'combined_sales_expenses', 'print_data'));
        }
    }
    public function SalesExpensesPrint(Request $request)
    {
        $title = 'Sale Expenses';

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
            return view('dash.SalesExpenses.SalesExpense.index', compact('title'));
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

            $view = view('dash.SalesExpenses.SalesExpense.print', compact('title', 'fromDate', 'toDate', 'monthName', 'groupedByStateExpenses', 'groupedByStateSales', 'combined_sales_expenses'));
            $pdf = PDF::loadHTML($view)->setPaper([0, 0, 793, 1404], 'landscape');

            // Return the generated PDF as a response
            return $pdf->download('sale_expense.pdf');
            // return $pdf->stream('sale_expense.pdf');
        }
    }

    public function Fare(Request $request)
    {
        $title = 'Monthly Expense Fare';

        // Validate the inputs
        $request->validate([
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
            'monthName' => 'nullable|date_format:Y-m|required_if:fromDate,toDate,NULL',
        ]);

        $monthName = $request->monthName;

        // // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($monthName, 5, 2); // Extract month (09)
        $year = (int)substr($monthName, 0, 4); // Extract year (2024)

        // Retrieve input data
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        if ((!$fromDate && !$toDate) && !$monthName) {

            return view('dash.SalesExpenses.Fare.index', compact('title'));
        }

        // Join the tables
        $ExpenseFareReports = UserExpenseOtherRecords::with('MonthlyExpense')
            ->where('is_submitted', 1)
            ->where('is_verified', 1);
        // ->where('is_approved', 1)
        // ->where('status', 1);



        // Filter by fromDate and toDate if provided
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

        // Get the filtered data
        $ExpenseFareReports = $ExpenseFareReports->get();

        // Extract MonthlyExpense data and store it in a variable
        $monthlyExpenses = $ExpenseFareReports->pluck('MonthlyExpense')->flatten();


        $groupedByState = $monthlyExpenses->groupBy(function ($expense) {
            // return $expense->state ? $expense->state->name : 'Unknown';
            return $expense->state ? $expense->state->short : 'Unknown';
        });
        $stateNames = [];
        $totalExpenses = [];

        foreach ($groupedByState as $stateName => $expenses) {
            $stateNames[] = $stateName;

            $totalExpenses[] = $expenses->sum('fare_amount');
        }
        // dd($totalExpenses);

        // Total fare calculation
        $totalFare = array_sum($totalExpenses);





        return view('dash.SalesExpenses.Fare.index', compact('title', 'fromDate', 'toDate', 'ExpenseFareReports', 'stateNames', 'totalExpenses', 'totalFare'));
    }

    public function Pax(Request $request)
    {
        $title = 'Total PAX';
        // Validate the inputs
        $request->validate([
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
            'monthName' => 'nullable|date_format:Y-m|required_if:fromDate,toDate,NULL',
        ]);

        $monthName = $request->monthName;

        // // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($monthName, 5, 2); // Extract month (09)
        $year = (int)substr($monthName, 0, 4); // Extract year (2024)

        // Retrieve input data
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        if ((!$fromDate && !$toDate) && !$monthName) {

            return view('dash.SalesExpenses.Pax.index', compact('title'));
        }
        // Join the tables
        $ExpenseFareReports = UserExpenseOtherRecords::with('MonthlyExpense')
            ->where('is_submitted', 1)
            ->where('is_verified', 1);
        // ->where('is_approved', 1)
        // ->where('status', 1);



        // Filter by fromDate and toDate if provided
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

        // Get the filtered data
        $ExpenseFareReports = $ExpenseFareReports->get();

        // Extract MonthlyExpense data and store it in a variable
        $monthlyExpenses = $ExpenseFareReports->pluck('MonthlyExpense')->flatten();


        $groupedByState = $monthlyExpenses->groupBy(function ($expense) {
            // return $expense->state ? $expense->state->name : 'Unknown';
            return $expense->state ? $expense->state->short : 'Unknown';
        });
        // dd($groupedByState);
        // $groupedByState = [
        //     'AP' => collect([
        //         (object)['fare_amount' => 148627],
        //     ]),
        //     'TEL' => collect([
        //         (object)['fare_amount' => 120436],
        //     ]),
        //     'TNL' => collect([
        //         (object)['fare_amount' => 71960],
        //     ]),
        //     'KTK' => collect([
        //         (object)['fare_amount' => 66680],
        //     ]),
        //     'OR' => collect([
        //         (object)['fare_amount' => 25939],
        //     ]),
        //     'KRL' => collect([
        //         (object)['fare_amount' => 55848],
        //     ]),
        //     'WB' => collect([
        //         (object)['fare_amount' => 105749],
        //     ]),
        //     'NE' => collect([
        //         (object)['fare_amount' => 123485],
        //     ]),
        //     'MH' => collect([
        //         (object)['fare_amount' => 129784],
        //     ]),
        //     'GUJ' => collect([
        //         (object)['fare_amount' => 32430],
        //     ]),
        //     'PUN' => collect([
        //         (object)['fare_amount' => 88506],
        //     ]),
        //     'DEL' => collect([
        //         (object)['fare_amount' => 114730],
        //     ]),
        //     'RAL' => collect([
        //         (object)['fare_amount' => 117677],
        //     ]),
        //     'UP' => collect([
        //         (object)['fare_amount' => 216832],
        //     ]),
        //     'JHA' => collect([
        //         (object)['fare_amount' => 10480],
        //     ]),
        //     'MP' => collect([
        //         (object)['fare_amount' => 54169],
        //     ]),
        //     'BIH' => collect([
        //         (object)['fare_amount' => 58446],
        //     ]),
        // ];
        // Prepare data for the chart

        $stateNames = [];
        $totalExpenses = [];

        foreach ($groupedByState as $stateName => $expenses) {
            $stateNames[] = $stateName;

            $uniqueUsers = $expenses->pluck('user_id')->unique();
            $no_of_pax = $uniqueUsers->count();

            $totalExpenses[] = $no_of_pax;
        }
        // dd($totalExpenses);

        // Total fare calculation
        $totalFare = array_sum($totalExpenses);





        return view('dash.SalesExpenses.Pax.index', compact('title', 'fromDate', 'toDate', 'ExpenseFareReports', 'stateNames', 'totalExpenses', 'totalFare'));
    }

    // public function SEAnalysis(Request $request)
    // {
    //     $title = 'Sales And Expense Analysis';

    //     // Validate the inputs
    //     $request->validate([
    //         'fromDate' => 'nullable|date',
    //         'toDate' => 'nullable|date',
    //         'monthName' => 'nullable|date_format:Y-m|required_if:fromDate,toDate,NULL',
    //     ]);

    //     $monthName = $request->monthName;

    //     // // Extract month and year from the input string (e.g., "2024-09")
    //     $month = (int)substr($monthName, 5, 2); // Extract month (09)
    //     $year = (int)substr($monthName, 0, 4); // Extract year (2024)

    //     // Retrieve input data
    //     $fromDate = $request->input('fromDate');
    //     $toDate = $request->input('toDate');
    //     $print_data =[
    //         'monthName' => $monthName,
    //         'fromDate' => $fromDate,
    //         'toDate' => $toDate,
    //     ];
    //     if ((!$fromDate && !$toDate) && !$monthName) {
    //         return view('dash.SalesExpenses.SEAnalysis.index', compact('title'));
    //     }
    //     // Retrieve and sort states by short name
    //     $states = DB::table('states')->orderBy('short', 'ASC')->get();
    //     $stateNames = $states->pluck('short')->toArray();
    //     $stateMap = $states->pluck('name', 'short')->toArray();
    //     // Fake data for testing
    //     // State Names
    //     // $stateNames = [
    //     //     'AP', 'AR', 'AS', 'BR', 'CG', 'GA', 'GJ', 
    //     //     'HR', 'HP', 'JH', 'MP', 'MH', 'MZ', 'DL', 
    //     //     'DRH', 'TRY'
    //     // ];
    //     //         // State Map
    //     // $stateMap = [
    //     //     'AP' => 'Andhra Pradesh',
    //     //     'AR' => 'Arunachal Pradesh',
    //     //     'AS' => 'Assam',
    //     //     'BR' => 'Bihar',
    //     //     'CG' => 'Chhattisgarh',
    //     //     'GA' => 'Goa',
    //     //     'GJ' => 'Gujarat',
    //     //     'HR' => 'Haryana',
    //     //     'HP' => 'Himachal Pradesh',
    //     //     'JH' => 'Jharkhand',
    //     //     'MP' => 'Madhya Pradesh',
    //     //     'MH' => 'Maharashtra',
    //     //     'MZ' => 'Mizoram',
    //     //     'DL' => 'New Delhi',
    //     //     'DRH' => 'drhth', // Assuming 'drhth' is a valid state name placeholder
    //     //     'TRY' => 'trytr', // Assuming 'trytr' is a valid state name placeholder
    //     // ];



    //     // $totalExpenses = [];
    //     // $totalSales = [];

    //     if ($fromDate && $toDate || $monthName) {
    //         // Fetch Expense Data
    //         $ExpenseFareReports = UserExpenseOtherRecords::with('MonthlyExpense')
    //             ->where('is_submitted', 1)
    //             ->where('is_verified', 1);
    //             // ->where('status', 1);

    //         if (!$monthName) {
    //             if ($fromDate) {
    //                 $ExpenseFareReports->whereDate('expense_date', '>=', $fromDate);
    //             }

    //             if ($toDate) {
    //                 $ExpenseFareReports->whereDate('expense_date', '<=', $toDate);
    //             }
    //         }
    //         if ($monthName) {
    //             $ExpenseFareReports->whereMonth('expense_date', $month)->whereYear('expense_date', $year);
    //         }

    //         $ExpenseFareReports = $ExpenseFareReports->get();
    //         $monthlyExpenses = $ExpenseFareReports->pluck('MonthlyExpense')->flatten();

    //         // Group expenses by state
    //         $groupedByStateExpenses = $monthlyExpenses->groupBy(function ($expense) use ($stateMap) {
    //             return $expense->state && isset($stateMap[$expense->state->short]) 
    //                 ? $stateMap[$expense->state->short] 
    //                 : 'Unknown';
    //         });

    //         $expenseData = [];
    //         foreach ($groupedByStateExpenses as $stateName => $expenses) {
    //             $expenseData[$stateName] = $expenses->sum('fare_amount');
    //         }
    //         // $expenseData = [
    //         //     'AP' => 35564, // Andhra Pradesh
    //         //     'AR' => 4345,  // Arunachal Pradesh
    //         //     'AS' => 554,  // Assam
    //         //     'BR' => 45445, // Bihar
    //         //     'CG' => 454445,  // Chhattisgarh
    //         //     'GA' => 4544,  // Goa
    //         //     'GJ' => 4545,  // Gujarat
    //         //     'HR' => 45, // Haryana
    //         //     'HP' => 45, // Himachal Pradesh
    //         //     'JH' => 54,  // Jharkhand
    //         //     'MP' => 783,  // Madhya Pradesh
    //         //     'MH' => 63, // Maharashtra
    //         //     'MZ' => 787,  // Mizoram
    //         //     'DL' => 7877, // New Delhi
    //         //     'DRH' => 787, // drhth (placeholder)
    //         //     'TRY' => 5545, // trytr (placeholder)
    //         // ];

    //         // Fetch Sales Data
    //         $salesQuery = Sales::query();
    //         if (!$monthName) {
    //             if ($fromDate) {
    //                 $salesQuery->whereDate('date_of_sales', '>=', $fromDate);
    //             }
    //             if ($toDate) {
    //                 $salesQuery->whereDate('date_of_sales', '<=', $toDate);
    //             }
    //         }

    //         if ($monthName) {
    //             $salesQuery->whereMonth('date_of_sales', $month)->whereYear('date_of_sales', $year);
    //         }
    //         $sales = $salesQuery->get();

    //         // Group sales by state
    //         $groupedByStateSales = $sales->groupBy(function ($sale) use ($stateMap) {
    //             return $stateMap[$sale->state_id] ?? 'Unknown';
    //         });

    //         $salesData = [];
    //         foreach ($groupedByStateSales as $stateName => $salesGroup) {
    //             $salesData[$stateName] = $salesGroup->sum('sales_amount');
    //         }
    //         // $salesData = [
    //         //     'AP' => 105000, // Andhra Pradesh
    //         //     'AR' => 98000,  // Arunachal Pradesh
    //         //     'AS' => 87000,  // Assam
    //         //     'BR' => 112000, // Bihar
    //         //     'CG' => 75000,  // Chhattisgarh
    //         //     'GA' => 45000,  // Goa
    //         //     'GJ' => 95000,  // Gujarat
    //         //     'HR' => 123000, // Haryana
    //         //     'HP' => 129000, // Himachal Pradesh
    //         //     'JH' => 32500,  // Jharkhand
    //         //     'MP' => 88000,  // Madhya Pradesh
    //         //     'MH' => 115000, // Maharashtra
    //         //     'MZ' => 78500,  // Mizoram
    //         //     'DL' => 102000, // New Delhi
    //         //     'DRH' => 48000, // drhth (placeholder)
    //         //     'TRY' => 67000, // trytr (placeholder)
    //         // ];

    //         // Prepare chart data
    //         $totalExpenses = [];
    //         $totalSales = [];
    //         foreach ($stateNames as $shortName) {
    //             $state = $stateMap[$shortName] ?? 'Unknown';
    //             $totalExpenses[] = $expenseData[$state] ?? 0;
    //             $totalSales[] = $salesData[$state] ?? 0;
    //         }

    //         // foreach ($stateNames as $shortName) {
    //         //     $totalExpenses[] = $expenseData[$shortName] ?? 0; // Use short name directly
    //         //     $totalSales[] = $salesData[$shortName] ?? 0;     // Use short name directly
    //         // }
    //     }

    //     // Pass data to view
    //     return view('dash.SalesExpenses.SEAnalysis.index', compact('title', 'fromDate', 'toDate', 'monthName','stateNames', 'totalExpenses', 'totalSales','print_data'));
    // }

    // above old function  below new function by avinash
    public function SEAnalysis(Request $request)
    {
        $title = 'Sales And Expense Analysis';

        // Validate input
        $request->validate([
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
            'monthName' => 'nullable|date_format:Y-m|required_if:fromDate,toDate,NULL',
        ]);

        $monthName = $request->monthName;
        $month = $monthName ? (int)substr($monthName, 5, 2) : null;
        $year = $monthName ? (int)substr($monthName, 0, 4) : null;

        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $print_data = [
            'monthName' => $monthName,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ];

        if ((!$fromDate && !$toDate) && !$monthName) {
            return view('dash.SalesExpenses.SEAnalysis.index', compact('title'));
        }

        // Fetch states and build mappings
        $states = DB::table('states')->orderBy('short', 'ASC')->get();
        $stateNames = $states->pluck('short')->toArray(); // e.g., ['UP', 'MH']
        $stateMap = $states->pluck('name', 'short')->toArray(); // e.g., ['UP' => 'Uttar Pradesh']

        $groupedByStateExpenses = collect();
        $groupedByStateSales = collect();

        // ------------------ EXPENSES ------------------
        $ExpenseFareReports = UserExpenseOtherRecords::with('MonthlyExpense')
            ->where('is_submitted', 1)
            ->where('is_verified', 1);

        if ($month && $year) {
            $ExpenseFareReports->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year);
        } else {
            if ($fromDate) {
                $ExpenseFareReports->whereDate('expense_date', '>=', $fromDate);
            }
            if ($toDate) {
                $ExpenseFareReports->whereDate('expense_date', '<=', $toDate);
            }
        }

        $ExpenseFareReports = $ExpenseFareReports->get();
        $monthlyExpenses = $ExpenseFareReports->pluck('MonthlyExpense')->flatten();

        $groupedByStateExpenses = $monthlyExpenses->groupBy(function ($expense) use ($stateMap) {
            return $expense->state && isset($stateMap[$expense->state->short])
                ? $expense->state->short
                : 'Unknown';
        });

        // ------------------ SALES ------------------
        $salesQuery = Sales::with('state');

        if ($month && $year) {
            $salesQuery->whereMonth('date_of_sales', $month)
                ->whereYear('date_of_sales', $year);
        } else {
            if ($fromDate) {
                $salesQuery->whereDate('date_of_sales', '>=', $fromDate);
            }
            if ($toDate) {
                $salesQuery->whereDate('date_of_sales', '<=', $toDate);
            }
        }

        $sales = $salesQuery->get();

        $groupedByStateSales = $sales->groupBy(function ($sale) use ($stateMap) {
            return $sale->state && isset($stateMap[$sale->state->short])
                ? $sale->state->short
                : 'Unknown';
        });

        // ------------------ Combine by Short Code ------------------
        $totalExpenses = [];
        $totalSales = [];
        $salesExpenseRatio = [];
        $averageRatio = 0;

        foreach ($stateNames as $shortName) {
            $stateDisplayName = $stateMap[$shortName] ?? $shortName;

            $expense = $groupedByStateExpenses[$shortName] ?? collect([]);
            $sales = $groupedByStateSales[$shortName] ?? collect([]);

            $expenseAmount = $expense->sum('fare_amount');
            $salesAmount = $sales->sum('sales_amount');

            $totalExpenses[] = $expenseAmount;
            $totalSales[] = $salesAmount;

            if ($expenseAmount > 0) {
                $ratio = round(($salesAmount / $expenseAmount) * 100, 2);
            } elseif ($salesAmount > 0) {
                $ratio = 100.00;
            } else {
                $ratio = 0;
            }

            $salesExpenseRatio[] = $ratio;
        }

        // ------------------ AVERAGE RATIO ------------------
        $nonZeroRatios = array_filter($salesExpenseRatio, fn($val) => $val > 0);
        if (count($nonZeroRatios) > 0) {
            $averageRatio = round(array_sum($nonZeroRatios) / count($nonZeroRatios), 2);
        }

        return view('dash.SalesExpenses.SEAnalysis.index', compact(
            'title',
            'fromDate',
            'toDate',
            'monthName',
            'stateNames',
            'totalExpenses',
            'totalSales',
            'print_data',
            'salesExpenseRatio',
            'averageRatio'
        ));
    }

    // public function SEAnalysis(Request $request)
    // {
    //     $title = 'Sales And Expense Analysis';

    //     // Validate input
    //     $request->validate([
    //         'fromDate' => 'nullable|date',
    //         'toDate' => 'nullable|date',
    //         'monthName' => 'nullable|date_format:Y-m|required_if:fromDate,toDate,NULL',
    //     ]);

    //     $monthName = $request->monthName;
    //     $month = $monthName ? (int)substr($monthName, 5, 2) : null;
    //     $year = $monthName ? (int)substr($monthName, 0, 4) : null;

    //     $fromDate = $request->input('fromDate');
    //     $toDate = $request->input('toDate');

    //     $print_data = [
    //         'monthName' => $monthName,
    //         'fromDate' => $fromDate,
    //         'toDate' => $toDate,
    //     ];

    //     if ((!$fromDate && !$toDate) && !$monthName) {
    //         return view('dash.SalesExpenses.SEAnalysis.index', compact('title'));
    //     }

    //     // Fetch state names and mapping
    //     $states = DB::table('states')->orderBy('short', 'ASC')->get();
    //    $stateNames = $states->pluck('short')->toArray();
    //    $stateMap = $states->pluck('name', 'short')->toArray();



    //     $totalExpenses = [];
    //     $totalSales = [];
    //     $salesExpenseRatio = [];
    //     $averageRatio = 0;

    //     if ($fromDate && $toDate || $monthName) {
    //         // ------------------ EXPENSES ------------------
    //         $ExpenseFareReports = UserExpenseOtherRecords::with('MonthlyExpense')
    //             ->where('is_submitted', 1)
    //             ->where('is_verified', 1);

    //         if (!$monthName) {
    //             if ($fromDate) {
    //                 $ExpenseFareReports->whereDate('expense_date', '>=', $fromDate);
    //             }
    //             if ($toDate) {
    //                 $ExpenseFareReports->whereDate('expense_date', '<=', $toDate);
    //             }
    //         } else {
    //             $ExpenseFareReports->whereMonth('expense_date', $month)
    //                 ->whereYear('expense_date', $year);
    //         }

    //         $ExpenseFareReports = $ExpenseFareReports->get();
    //         $monthlyExpenses = $ExpenseFareReports->pluck('MonthlyExpense')->flatten();

    //         $groupedByStateExpenses = $monthlyExpenses->groupBy(function ($expense) use ($stateMap) {
    //             return $expense->state && isset($stateMap[$expense->state->short]) 
    //                 ? $stateMap[$expense->state->short] 
    //                 : 'Unknown';
    //         });

    //         $expenseData = [];
    //         foreach ($groupedByStateExpenses as $stateName => $expenses) {
    //             $expenseData[$stateName] = $expenses->sum('fare_amount');
    //         }

    //         // ------------------ SALES ------------------
    //         $salesQuery = Sales::query();

    //         if (!$monthName) {
    //             if ($fromDate) {
    //                 $salesQuery->whereDate('date_of_sales', '>=', $fromDate);
    //             }
    //             if ($toDate) {
    //                 $salesQuery->whereDate('date_of_sales', '<=', $toDate);
    //             }
    //         } else {
    //             $salesQuery->whereMonth('date_of_sales', $month)
    //                 ->whereYear('date_of_sales', $year);
    //         }

    //         $sales = $salesQuery->get();

    //         $groupedByStateSales = $sales->groupBy(function ($sale) use ($stateMap) {
    //             return $stateMap[$sale->state_id] ?? 'Unknown';
    //         });

    //         $salesData = [];
    //         foreach ($groupedByStateSales as $stateName => $salesGroup) {
    //             $salesData[$stateName] = $salesGroup->sum('sales_amount');
    //         }


    //         // ------------------ AGGREGATE BY STATE ------------------
    //         foreach ($stateNames as $shortName) {
    //             $state = $stateMap[$shortName] ?? 'Unknown';
    //             $totalExpense = $expenseData[$state] ?? 0;
    //             $totalSale = $salesData[$state] ?? 0;

    //             $totalExpenses[] = $totalExpense;
    //             $totalSales[] = $totalSale;

    //             // Calculate sales-expense ratio as a percentage
    //             $ratio = ($totalExpense > 0) ? round(($totalSale / $totalExpense) * 100, 2) : 0;
    //             $salesExpenseRatio[] = $ratio;
    //         }

    //         // ------------------ AVERAGE RATIO ------------------
    //         $nonZeroRatios = array_filter($salesExpenseRatio, fn($val) => $val > 0);
    //         if (count($nonZeroRatios) > 0) {
    //             $averageRatio = round(array_sum($nonZeroRatios) / count($nonZeroRatios), 2);
    //         }
    //     }

    //     // ------------------ RETURN VIEW ------------------
    //     return view('dash.SalesExpenses.SEAnalysis.index', compact(
    //         'title', 'fromDate', 'toDate', 'monthName', 'stateNames',
    //         'totalExpenses', 'totalSales', 'print_data',
    //         'salesExpenseRatio', 'averageRatio'
    //     ));
    // }



    public function SEAnalysisPrint(Request $request)
    {
        // Path to the chart in the public folder
        $chartPath = public_path('assets/generated_chart_image/se_analysis.png');
        // Check if the file exists
        if (!file_exists($chartPath)) {
            return redirect()->route('sale_se_analysis')->with('error', 'Image not found.');
        }
        // dd($chartPath);
        // Render the HTML for the PDF
        $view = view('dash.SalesExpenses.SEAnalysis.print', ['chartPath' => $chartPath])->render();
        // return view('dash.SalesExpenses.SEAnalysis.print', compact('chartPath'));

        // Initialize Dompdf
        $pdf = \PDF::loadHTML($view)->setPaper([0, 0, 793, 1404], 'landscape');

        // Return the generated PDF
        // return $pdf->stream('se_analysis.pdf');
        return $pdf->download('se_analysis.pdf');
    }

    public function TotalExpense(Request $request)
    {
        $title = 'Sale Total Expense';
        // Validate the inputs
        $request->validate([
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
            'monthName' => 'nullable|date_format:Y-m|required_if:fromDate,toDate,NULL',
        ]);

        $monthName = $request->monthName;

        // // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($monthName, 5, 2); // Extract month (09)
        $year = (int)substr($monthName, 0, 4); // Extract year (2024)

        // Retrieve input data
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        if ((!$fromDate && !$toDate) && !$monthName) {

            return view('dash.SalesExpenses.TotalExpense.index', compact('title'));
        }
        // Join the tables
        $ExpenseFareReports = UserExpenseOtherRecords::with('MonthlyExpense')
            ->where('is_submitted', 1)
            ->where('is_verified', 1);
        // ->where('is_approved', 1)
        // ->where('status', 1);



        // Filter by fromDate and toDate if provided
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

        // Get the filtered data
        $ExpenseFareReports = $ExpenseFareReports->get();

        // Extract MonthlyExpense data and store it in a variable
        $monthlyExpenses = $ExpenseFareReports->pluck('MonthlyExpense')->flatten();


        $groupedByState = $monthlyExpenses->groupBy(function ($expense) {
            // return $expense->state ? $expense->state->name : 'Unknown';
            return $expense->state ? $expense->state->short : 'Unknown';
        });
        // dd($groupedByState);
        // $groupedByState = [
        //     'AP' => collect([
        //         (object)['fare_amount' => 148627],
        //     ]),
        //     'TEL' => collect([
        //         (object)['fare_amount' => 120436],
        //     ]),
        //     'TNL' => collect([
        //         (object)['fare_amount' => 71960],
        //     ]),
        //     'KTK' => collect([
        //         (object)['fare_amount' => 66680],
        //     ]),
        //     'OR' => collect([
        //         (object)['fare_amount' => 25939],
        //     ]),
        //     'KRL' => collect([
        //         (object)['fare_amount' => 55848],
        //     ]),
        //     'WB' => collect([
        //         (object)['fare_amount' => 105749],
        //     ]),
        //     'NE' => collect([
        //         (object)['fare_amount' => 123485],
        //     ]),
        //     'MH' => collect([
        //         (object)['fare_amount' => 129784],
        //     ]),
        //     'GUJ' => collect([
        //         (object)['fare_amount' => 32430],
        //     ]),
        //     'PUN' => collect([
        //         (object)['fare_amount' => 88506],
        //     ]),
        //     'DEL' => collect([
        //         (object)['fare_amount' => 114730],
        //     ]),
        //     'RAL' => collect([
        //         (object)['fare_amount' => 117677],
        //     ]),
        //     'UP' => collect([
        //         (object)['fare_amount' => 216832],
        //     ]),
        //     'JHA' => collect([
        //         (object)['fare_amount' => 10480],
        //     ]),
        //     'MP' => collect([
        //         (object)['fare_amount' => 54169],
        //     ]),
        //     'BIH' => collect([
        //         (object)['fare_amount' => 58446],
        //     ]),
        // ];
        // Prepare data for the chart

        $stateNames = [];
        $totalExpenses = [];

        foreach ($groupedByState as $stateName => $expenses) {
            $stateNames[] = $stateName;
            // $totalExpenses[] = $expenses->sum('fare_amount');

            // for Total Expenses
            $one_month_of_total_fare_amount = $expenses->sum('fare_amount');
            $total_da =  $expenses->sum('da_total');
            $total_other_expenses_amount = $expenses->sum('other_expenses_amount');

            $totalExpenses[] = $one_month_of_total_fare_amount + $total_da + $total_other_expenses_amount;
        }
        // dd($totalExpenses);

        // Total fare calculation
        $totalFare = array_sum($totalExpenses);



        return view('dash.SalesExpenses.TotalExpense.index', compact('title', 'fromDate', 'toDate', 'ExpenseFareReports', 'stateNames', 'totalExpenses', 'totalFare'));
    }

    public function TotalExpensePrint(Request $request)
    {
        $totalFare = $request->totalFare;
        // Path to the chart in the public folder
        $chartPath = public_path('assets/generated_chart_image/total_expense.png');
        // Check if the file exists
        if (!file_exists($chartPath)) {
            return redirect()->route('sale_total_expense')->with('error', 'Image not found.');
        }
        // dd($chartPath);
        // Render the HTML for the PDF
        $view = view('dash.SalesExpenses.TotalExpense.print', ['chartPath' => $chartPath, 'totalFare' => $totalFare])->render();
        // return view('dash.SalesExpenses.SEAnalysis.print', compact('chartPath'));

        // Initialize Dompdf
        $pdf = \PDF::loadHTML($view)->setPaper([0, 0, 793, 1404], 'landscape');

        // Return the generated PDF
        // return $pdf->stream('total_expense.pdf');
        return $pdf->download('total_expense.pdf');
    }

    public function FarePrint(Request $request)
    {
        $totalFare = $request->totalFare;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $monthName = $request->monthName;
        // Path to the chart in the public folder
        $chartPath = public_path('assets/generated_chart_image/fare.png');
        // Check if the file exists
        if (!file_exists($chartPath)) {
            return redirect()->route('sale_fare')->with('error', 'Image not found.');
        }
        // dd($chartPath);
        // Render the HTML for the PDF
        $view = view('dash.SalesExpenses.Fare.print', ['chartPath' => $chartPath, 'totalFare' => $totalFare, 'toDate' => $toDate, 'fromDate' => $fromDate, 'monthName' => $monthName])->render();
        // return view('dash.SalesExpenses.SEAnalysis.print', compact('chartPath'));

        // Initialize Dompdf
        $pdf = \PDF::loadHTML($view)->setPaper([0, 0, 793, 1404], 'landscape');

        // Return the generated PDF
        // return $pdf->stream('fare.pdf');
        return $pdf->download('fare.pdf');
    }

    public function PaxPrint(Request $request)
    {
        $totalFare = $request->totalFare;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $monthName = $request->monthName;
        // Path to the chart in the public folder
        $chartPath = public_path('assets/generated_chart_image/PAX.png');
        // Check if the file exists
        if (!file_exists($chartPath)) {
            return redirect()->route('sale_pax')->with('error', 'Image not found.');
        }
        // dd($chartPath);
        // Render the HTML for the PDF
        $view = view('dash.SalesExpenses.Pax.print', ['chartPath' => $chartPath, 'totalFare' => $totalFare, 'toDate' => $toDate, 'fromDate' => $fromDate, 'monthName' => $monthName])->render();
        // return view('dash.SalesExpenses.SEAnalysis.print', compact('chartPath'));

        // Initialize Dompdf
        $pdf = \PDF::loadHTML($view)->setPaper([0, 0, 793, 1404], 'landscape');

        // Return the generated PDF
        // return $pdf->stream('pax.pdf');
        return $pdf->download('pax.pdf');
    }
}
