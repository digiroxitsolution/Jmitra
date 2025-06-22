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
use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon;

class ExpensesSlipReportController extends Controller
{
    public function ExpensesSlipReport(){
        $title = "Expense Slip Report";

        return view('dash.HODExpenseSlipReport.index', compact('title'));
    }

    public function searchEmployee(Request $request)
    {
        $request->validate([
            'employee_id' => 'required_without:employee_name',
            'employee_name' => 'required_without:employee_id',
            'MonthOfYear' => 'required',
        ]);

        $MonthOfYear = $request->MonthOfYear;
        $employee_id = $request->employee_id;
        // dd($employee_id);
        $employee_name = $request->employee_name;

        // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($MonthOfYear, 5, 2); // Extract month
        $year = (int)substr($MonthOfYear, 0, 4); // Extract year
        // dd($month);
        // dd($year);


        $monthName = Carbon::create()->month($month)->format('F');
        // dd($monthName);

        if (!$month || !$year) {
            return redirect()->route('expenses_slip_report')->with('error', 'Month and year are required');
        }

        
        
        // Initialize the query
        $query = MonthlyExpense::query();

        // Join with UserDetails, Users, and UserExpenseOtherRecords tables
        $query = $query->join('user_details', 'monthly_expenses.user_id', '=', 'user_details.user_id')
                       ->join('users', 'users.id', '=', 'user_details.user_id')
                       ->leftJoin('user_expense_other_records', 'monthly_expenses.user_expense_other_records_id', '=', 'user_expense_other_records.id')
                       ->select(
                            'monthly_expenses.*',
                            'user_details.employee_id',
                            'users.name as employee_name',
                            'user_expense_other_records.advance_taken',
                            'user_expense_other_records.justification'
                        );

        // Add the filtering conditions based on the input values
        if ($employee_id) {
            $query = $query->where('user_details.employee_id', $employee_id);
        }

        if ($employee_name) {
            $query = $query->where('users.name', 'LIKE', '%' . $employee_name . '%');
        }


        // Filter by month and year
        $query = $query->whereMonth('monthly_expenses.expense_date', $month)
                       ->whereYear('monthly_expenses.expense_date', $year)->where('user_expense_other_records.is_submitted', 1)->where('user_expense_other_records.is_verified', 1);
        // dd($query->count());

        $firstMonthlyExpense = (clone $query)->first();


        if (!$firstMonthlyExpense) {
            return redirect()->route('expenses_slip_report')->with('error', 'Expense Record Not Found');
        }
        $expense_id = $firstMonthlyExpense->expense_id;
        // dd($expense_id);

        
        // dd($query->toSql(), $query->getBindings());

        $monthly_expenses = $query->get();
        // dd($monthly_expenses->count());

        

        // Check if any record was found
        if ($monthly_expenses->isEmpty()) {
            return redirect()->route('expenses_slip_report')->with('error', 'Expense Record Not Found');
        }

        // Separate expenses where `expense_type_master_id` is not equal to 3
        $monthly_expenses_headquarters = $monthly_expenses->filter(function ($expense) {
            return $expense->expense_type_master_id != 3 && $expense->expense_type_master_id !== null;
        });        

        // Separate expenses where `expense_type_master_id` is equal to 3
        $monthly_expenses_outstation = $monthly_expenses->filter(function ($expense) {
            return $expense->expense_type_master_id == 3;
        });


        $monthly_expenses_headquarters_days = count($monthly_expenses_headquarters);
        $monthly_expenses_outstation_days = count($monthly_expenses_outstation);

        // dd($monthly_expenses_headquarters, $monthly_expenses_outstation);


        // Define the start and end of the first 15 days
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $middleOfMonth = Carbon::create($year, $month, 15)->endOfDay();

        // Define the start of the 16th day and the end of the month
        $startOfRestOfMonth = Carbon::create($year, $month, 16)->startOfDay();
        $endOfMonth = Carbon::create($year, $month)->endOfMonth()->endOfDay();

        // Query for the first 15 days
        $fifteen_days_of_monthly_expenses = $monthly_expenses->filter(function ($exp) use ($startOfMonth, $middleOfMonth) {
                $expenseDate = Carbon::parse($exp->expense_date);
                return $expenseDate->between($startOfMonth, $middleOfMonth);
            });

            // Query for the rest of the month
            $rest_of_monthly_expenses = $monthly_expenses->filter(function ($exp) use ($startOfRestOfMonth, $endOfMonth) {
                $expenseDate = Carbon::parse($exp->expense_date);
                return $expenseDate->between($startOfRestOfMonth, $endOfMonth);
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


        $fifteen_days_of_monthly_expenses_headquarters_days = count($fifteen_days_of_monthly_expenses_headquarters);
        $fifteen_days_of_monthly_expenses_outstation_days = count($fifteen_days_of_monthly_expenses_outstation);


        $rest_of_monthly_expenses_headquarters_days = count($rest_of_monthly_expenses_headquarters);    
        $rest_of_monthly_expenses_outstation_days = count($rest_of_monthly_expenses_outstation);



        $fifteen_days_of_monthly_expenses_headquarters = $fifteen_days_of_monthly_expenses_headquarters->sum('da_total');
        $fifteen_days_of_monthly_expenses_outstation = $fifteen_days_of_monthly_expenses_outstation->sum('da_total');


        $rest_of_monthly_expenses_headquarters = $rest_of_monthly_expenses_headquarters->sum('da_total');
        $rest_of_monthly_expenses_outstation = $rest_of_monthly_expenses_outstation->sum('da_total');
        

        




        $fifteen_days_of_monthly_expenses_da = $fifteen_days_of_monthly_expenses_headquarters + $fifteen_days_of_monthly_expenses_outstation;
        $rest_of_monthly_expenses_da = $rest_of_monthly_expenses_headquarters + $rest_of_monthly_expenses_outstation;       
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
        // dd($grand_total);

        // Retrieve the user based on employee_id or employee_name
        $user = User::whereHas('userDetail', function ($query) use ($employee_id, $employee_name) {
            // Search by employee_id if provided, otherwise search by employee_name
            if ($employee_id) {
                $query->where('employee_id', $employee_id);
            }

            if ($employee_name) {
                $query->whereHas('user', function ($subQuery) use ($employee_name) {
                    $subQuery->where('name', 'LIKE', '%' . $employee_name . '%');
                });
            }
        })->with('userDetail')->first();

        // If user is not found, redirect with an error
        if (!$user) {
            return redirect()->route('expenses_slip_report')->with('error', 'User Not Found');
        }

        $MonthOfYearPass = $MonthOfYear;
        $employee_idPass = $employee_id;
        $employee_namePass = $employee_name;
        

        return view('dash.HODExpenseSlipReport.searchResult', compact('user', 'monthName', 'monthly_expenses', 'fifteen_days_of_fare_amount', 'rest_of_other_fare_amount', 'total_other_fare_amount', 'total', 'grand_total', 'fifteen_days_of_other_expenses_amount', 'rest_of_other_expenses_amount', 'total_other_expenses_amount', 'fifteen_days_of_monthly_expenses_postage', 'rest_of_monthly_expenses_postage', 'total_postage', 'fifteen_days_of_mobile_internet', 'rest_of_monthly_mobile_internet', 'total_mobile_internet', 'fifteen_days_of_print_stationery', 'rest_of_print_stationery', 'total_print_stationery', 'total_da', 'fifteen_days_of_monthly_expenses_da', 'rest_of_monthly_expenses_da', 'fifteen_days_of_monthly_expenses_headquarters_days', 'fifteen_days_of_monthly_expenses_outstation_days', 'rest_of_monthly_expenses_headquarters_days', 'rest_of_monthly_expenses_outstation_days', 'expense_id', 'MonthOfYearPass', 'employee_idPass', 'employee_namePass' ));
    }
    public function generatePdf(Request $request)
    {

        $data = $request->all(); // Get the data from the request
        \Log::info($data); // Log the data for debugging

        // Load the view with the data for PDF generation
        $pdf = PDF::loadView('dash.HODExpenseSlipReport.pdf', $data);

        // Return the generated PDF as a response
        return $pdf->download('expense_slip_report.pdf');
    }

    public function print(Request $request)
    {
        $request->validate([
            'employee_idPass' => 'required_without:employee_namePass',
            'employee_namePass' => 'required_without:employee_idPass',
            'MonthOfYearPass' => 'required',
        ]);

        $MonthOfYear = $request->MonthOfYearPass;
        $employee_id = $request->employee_idPass;
        $employee_name = $request->employee_namePass;

        // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($MonthOfYear, 5, 2); // Extract month
        $year = (int)substr($MonthOfYear, 0, 4); // Extract year

        $monthName = Carbon::create()->month($month)->format('F');

        if (!$month || !$year) {
            return redirect()->route('expenses_slip_report')->with('error', 'Month and year are required');
        }

        
        
        // Initialize the query
        $query = MonthlyExpense::query();

        // Join with UserDetails, Users, and UserExpenseOtherRecords tables
        $query = $query->join('user_details', 'monthly_expenses.user_id', '=', 'user_details.user_id')
                       ->join('users', 'users.id', '=', 'user_details.user_id')
                       ->leftJoin('user_expense_other_records', 'monthly_expenses.user_expense_other_records_id', '=', 'user_expense_other_records.id')
                       ->select(
                            'monthly_expenses.*',
                            'user_details.employee_id',
                            'users.name as employee_name',
                            'user_expense_other_records.advance_taken',
                            'user_expense_other_records.justification'
                        );

        // Add the filtering conditions based on the input values
        if ($employee_id) {
            $query = $query->where('user_details.employee_id', $employee_id);
        }

        if ($employee_name) {
            $query = $query->where('users.name', 'LIKE', '%' . $employee_name . '%');
        }


        // Filter by month and year
        $query = $query->whereMonth('monthly_expenses.expense_date', $month)
                       ->whereYear('monthly_expenses.expense_date', $year);

        $firstMonthlyExpense = (clone $query)->first();
        $expense_id = $firstMonthlyExpense->expense_id;

        if (!$firstMonthlyExpense) {
            return redirect()->route('expenses_slip_report')->with('error', 'Expense Record Not Found');
        }

        // Check related record conditions
        $userExpenseOtherRecord = UserExpenseOtherRecords::where('id', $firstMonthlyExpense->user_expense_other_records_id)
            ->where('is_submitted', 1)
            ->where('is_verified', 1)
            ->first();

        $monthly_expenses = $userExpenseOtherRecord ? $query->get() : collect();
        

        // Check if any record was found
        if ($monthly_expenses->isEmpty()) {
            return redirect()->route('expenses_slip_report')->with('error', 'Expense Record Not Found');
        }

        // Separate expenses where `expense_type_master_id` is not equal to 3
        $monthly_expenses_headquarters = $monthly_expenses->filter(function ($expense) {
            return $expense->expense_type_master_id != 3 && $expense->expense_type_master_id !== null;
        });        

        // Separate expenses where `expense_type_master_id` is equal to 3
        $monthly_expenses_outstation = $monthly_expenses->filter(function ($expense) {
            return $expense->expense_type_master_id == 3;
        });


        $monthly_expenses_headquarters_days = count($monthly_expenses_headquarters);
        $monthly_expenses_outstation_days = count($monthly_expenses_outstation);


        // Define the start and end of the first 15 days
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $middleOfMonth = Carbon::create($year, $month, 15)->endOfDay();

        // Define the start of the 16th day and the end of the month
        $startOfRestOfMonth = Carbon::create($year, $month, 16)->startOfDay();
        $endOfMonth = Carbon::create($year, $month)->endOfMonth()->endOfDay();

        // Query for the first 15 days
        $fifteen_days_of_monthly_expenses = $monthly_expenses->filter(function ($exp) use ($startOfMonth, $middleOfMonth) {
                $expenseDate = Carbon::parse($exp->expense_date);
                return $expenseDate->between($startOfMonth, $middleOfMonth);
            });

            // Query for the rest of the month
            $rest_of_monthly_expenses = $monthly_expenses->filter(function ($exp) use ($startOfRestOfMonth, $endOfMonth) {
                $expenseDate = Carbon::parse($exp->expense_date);
                return $expenseDate->between($startOfRestOfMonth, $endOfMonth);
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


        $fifteen_days_of_monthly_expenses_headquarters_days = count($fifteen_days_of_monthly_expenses_headquarters);
        $fifteen_days_of_monthly_expenses_outstation_days = count($fifteen_days_of_monthly_expenses_outstation);


        $rest_of_monthly_expenses_headquarters_days = count($rest_of_monthly_expenses_headquarters);    
        $rest_of_monthly_expenses_outstation_days = count($rest_of_monthly_expenses_outstation);



        $fifteen_days_of_monthly_expenses_headquarters = $fifteen_days_of_monthly_expenses_headquarters->sum('da_total');
        $fifteen_days_of_monthly_expenses_outstation = $fifteen_days_of_monthly_expenses_outstation->sum('da_total');


        $rest_of_monthly_expenses_headquarters = $rest_of_monthly_expenses_headquarters->sum('da_total');
        $rest_of_monthly_expenses_outstation = $rest_of_monthly_expenses_outstation->sum('da_total');
        

        




        $fifteen_days_of_monthly_expenses_da = $fifteen_days_of_monthly_expenses_headquarters + $fifteen_days_of_monthly_expenses_outstation;
        $rest_of_monthly_expenses_da = $rest_of_monthly_expenses_headquarters + $rest_of_monthly_expenses_outstation;       
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

        // Retrieve the user based on employee_id or employee_name
        $user = User::whereHas('userDetail', function ($query) use ($employee_id, $employee_name) {
            // Search by employee_id if provided, otherwise search by employee_name
            if ($employee_id) {
                $query->where('employee_id', $employee_id);
            }

            if ($employee_name) {
                $query->whereHas('user', function ($subQuery) use ($employee_name) {
                    $subQuery->where('name', 'LIKE', '%' . $employee_name . '%');
                });
            }
        })->with('userDetail')->first();

        // If user is not found, redirect with an error
        if (!$user) {
            return redirect()->route('expenses_slip_report')->with('error', 'User Not Found');
        }


        $data = [
            'monthly_expenses' => $monthly_expenses,
            'employee_name' => $employee_name,
            'employee_id' => $employee_id,
            'fifteen_days_of_monthly_expenses' => $fifteen_days_of_monthly_expenses,
            'rest_of_monthly_expenses' => $rest_of_monthly_expenses,
            'fifteen_days_of_monthly_expenses_headquarters' => $fifteen_days_of_monthly_expenses_headquarters,
            'fifteen_days_of_monthly_expenses_outstation' => $fifteen_days_of_monthly_expenses_outstation,
            'rest_of_monthly_expenses_headquarters' => $rest_of_monthly_expenses_headquarters,
            'rest_of_monthly_expenses_outstation' => $rest_of_monthly_expenses_outstation,
            'month' => $monthName,
            'year' => $year,
            'total' => $total,
            'fifteen_days_of_monthly_expenses_da' => $fifteen_days_of_monthly_expenses_da,
            'rest_of_monthly_expenses_da' => $rest_of_monthly_expenses_da,
            'total_da' => $total_da,
            'total_other_fare_amount' => $total_other_fare_amount,
            'total_other_expenses_amount' => $total_other_expenses_amount,
            'total_postage' => $total_postage,
            'total_mobile_internet' => $total_mobile_internet,
            'total_print_stationery' => $total_print_stationery,
            'user' => $user,
            'expense_id' => $expense_id,
            'fifteen_days_of_fare_amount' => $fifteen_days_of_fare_amount,
            'rest_of_other_fare_amount' => $rest_of_other_fare_amount,
            'grand_total' => $grand_total,
            'fifteen_days_of_other_expenses_amount' => $fifteen_days_of_other_expenses_amount,
            'rest_of_other_expenses_amount' => $rest_of_other_expenses_amount,
            'fifteen_days_of_monthly_expenses_postage' => $fifteen_days_of_monthly_expenses_postage,
            'rest_of_monthly_expenses_postage' => $rest_of_monthly_expenses_postage,
            'fifteen_days_of_mobile_internet' => $fifteen_days_of_mobile_internet,
            'rest_of_monthly_mobile_internet' => $rest_of_monthly_mobile_internet,
            'fifteen_days_of_print_stationery' => $fifteen_days_of_print_stationery,
            'rest_of_print_stationery' => $rest_of_print_stationery,
            'fifteen_days_of_monthly_expenses_headquarters_days' => $fifteen_days_of_monthly_expenses_headquarters_days,
            'fifteen_days_of_monthly_expenses_outstation_days' => $fifteen_days_of_monthly_expenses_outstation_days,
            'rest_of_monthly_expenses_headquarters_days' => $rest_of_monthly_expenses_headquarters_days,
            'rest_of_monthly_expenses_outstation_days' => $rest_of_monthly_expenses_outstation_days,
        ];

        // $data = ['user', 'monthName', 'monthly_expenses', 'fifteen_days_of_fare_amount', 'rest_of_other_fare_amount', 'total_other_fare_amount', 'total', 'grand_total', 'fifteen_days_of_other_expenses_amount', 'rest_of_other_expenses_amount', 'total_other_expenses_amount', 'fifteen_days_of_monthly_expenses_postage', 'rest_of_monthly_expenses_postage', 'total_postage', 'fifteen_days_of_mobile_internet', 'rest_of_monthly_mobile_internet', 'total_mobile_internet', 'fifteen_days_of_print_stationery', 'rest_of_print_stationery', 'total_print_stationery', 'total_da', 'fifteen_days_of_monthly_expenses_da', 'rest_of_monthly_expenses_da', 'fifteen_days_of_monthly_expenses_headquarters_days', 'fifteen_days_of_monthly_expenses_outstation_days', 'rest_of_monthly_expenses_headquarters_days', 'rest_of_monthly_expenses_outstation_days' ];
        
        $pdf = PDF::loadView('dash.HODExpenseSlipReport.print', $data);

        // Return the generated PDF as a response
        return $pdf->download('expense_slip_report.pdf');
    }


    public function getEmployeeSuggestions(Request $request)
    {
        // Validate the input
        $request->validate([
            'employee' => 'nullable|string|max:255',
        ]);

        $query = UserDetails::query();

        // Check if input matches employee_id or employee_name
        if ($request->employee) {
            $query->where(function ($query) use ($request) {
                $query->where('employee_id', 'LIKE', '%' . $request->employee . '%')
                      ->orWhereHas('user', function($q) use ($request) {
                          $q->where('name', 'LIKE', '%' . $request->employee . '%');
                      });
            });
        }

        // Fetch the matching employee details along with associated user (employee_name)
        $employeeDetails = $query->with('user')->get();

        // Format the results to show employee_name (employee_id)
        $results = $employeeDetails->map(function ($detail) {
            return [
                'employee_id' => $detail->employee_id,
                'employee_name' => $detail->user->name,
                'formatted' => $detail->user->name . ' (' . $detail->employee_id . ')', // Display name and employee_id
            ];
        });

        return response()->json($results);
    }
}
