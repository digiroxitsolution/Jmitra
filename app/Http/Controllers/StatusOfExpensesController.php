<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use NumberFormatter;

use App\Models\User;
use App\Models\DivisonMaster;
use App\Models\ModeofExpenseMaster;
use App\Models\MonthlyExpense;
use App\Models\UserExpenseOtherRecords;
use App\Models\ExpenseTypeMaster;
use App\Models\OtherExpenseMaster;

use Carbon\Carbon;
class StatusOfExpensesController extends Controller
{
    public function StatusOfExpenses(Request $request)
    {
        $title = "Status Of Expenses";

        $user = auth()->user();
        $user_id = $user->id;
        
        $status = $request->status ?? 2;

        
        if ($user->hasRole('Super Admin')) {
            
            $monthly_expenses = UserExpenseOtherRecords::where('is_submitted', 1)
                ->where('status', $status)
                ->orderBy('expense_date', 'desc')
                ->get();
        } elseif ($user->hasRole('Sales Admin')) {
            
            $monthly_expenses = UserExpenseOtherRecords::where('is_submitted', 1)
                ->where('status', $status)
                ->orderBy('expense_date', 'desc')
                ->get();
        } elseif ($user->hasRole('Sales Admin Hod')) {
            $monthly_expenses = UserExpenseOtherRecords::where('is_submitted', 1)
                ->where('status', $status)
                ->orderBy('expense_date', 'desc')
                ->get();
        } elseif ($user->hasRole('Sales')) {
            
            $monthly_expenses = UserExpenseOtherRecords::where('user_id', $user_id)->where('is_submitted', 1)
                ->where('status', $status)
                ->orderBy('expense_date', 'desc')
                ->get();
        } else {
            
            $monthly_expenses = UserExpenseOtherRecords::where('user_id', $user_id)->where('is_submitted', 1)
                ->where('status', $status)
                ->orderBy('expense_date', 'desc')
                ->get();
        }

        

        return view('dash.StatusOfExpenses.index', compact('title', 'monthly_expenses'));
    }

    public function statementOfExpense(Request $request, $id)
    {   
        $user = auth()->user();
        $user_id = $user->id;

        $users = User::all();
        $divisons = DivisonMaster::all();
        $expense_modes = ModeofExpenseMaster::all();
        $expense_type_master = ExpenseTypeMaster::all();
        $other_expense_master = OtherExpenseMaster::all();

        $UserExpenseOtherRecords_filter = UserExpenseOtherRecords::find($id);
        if(!$UserExpenseOtherRecords_filter){
            return response()->json(['error' => 'Month and year are required.'], 400);
        }

        // Step 2: Extract IDs from UserExpenseOtherRecords
        $userExpenseOtherRecordsIds = $UserExpenseOtherRecords_filter->id;
        $user_idd = $UserExpenseOtherRecords_filter->user_id;

        $usser = User::find($user_idd);




        
        

        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

        // Fetch records for the specified month and year
        if ($user->hasRole('Super Admin')) {
            
            $statement_of_expense = MonthlyExpense::where('user_expense_other_records_id', $userExpenseOtherRecordsIds)->orderBy('expense_date', 'ASC')->get();

        } elseif ($user->hasRole('Sales Admin')) {
            
            $statement_of_expense = MonthlyExpense::where('user_expense_other_records_id', $userExpenseOtherRecordsIds)->orderBy('expense_date', 'ASC')->get();

        } elseif ($user->hasRole('Sales Admin Hod')) {
            $statement_of_expense = MonthlyExpense::where('user_expense_other_records_id', $userExpenseOtherRecordsIds)->orderBy('expense_date', 'ASC')->get();

        } elseif ($user->hasRole('Sales')) {
            
            $statement_of_expense = MonthlyExpense::where('user_id', $user_id)->where('user_expense_other_records_id', $userExpenseOtherRecordsIds)->orderBy('expense_date', 'ASC')->get();
        } else {
            
            $statement_of_expense = MonthlyExpense::where('user_id', $user_id)->where('user_expense_other_records_id', $userExpenseOtherRecordsIds)->orderBy('expense_date', 'ASC')->get();
        }
        


        if (!$statement_of_expense) {
            return response()->json(['error' => 'Month and year are required.'], 400);
        }

        


        $month = $UserExpenseOtherRecords_filter->expense_date;

        $total_fare_amount = $statement_of_expense->sum('fare_amount');
        $total_da_location = $statement_of_expense->sum('da_location');
        $total_da_ex_location = $statement_of_expense->sum('da_ex_location');
        $total_postage = $statement_of_expense->sum('postage');
        $total_mobile_internet = $statement_of_expense->sum('mobile_internet');
        $total_print_stationery = $statement_of_expense->sum('print_stationery');        
        $total_other_expense_amount = $statement_of_expense->sum('other_expenses_amount');
        $total_other_expense_purpose = $statement_of_expense->sum('other_expense_purpose');
        $total_Da = $total_da_location + $total_da_ex_location;


        

 

        

        $total_da_location_working = $statement_of_expense->where('other_expense_master_id', '!=', 8)->sum('da_total');

        
        $total_da_location_not_working = $statement_of_expense->where('other_expense_master_id', 8)->sum('da_total');
        
        $total  = $total_da_location_working + $total_da_location_not_working;
        
        $da_outstation = abs($total_da_location_working - $total_Da);
        
        $grand_total = $total_fare_amount + $total_da_location_working + $total_da_location_not_working + $total_other_expense_amount + $total_other_expense_purpose + $total_postage + $total_mobile_internet + $total_print_stationery;
        
        // Convert the number to words
        $grand_total_in_words = $formatter->format($grand_total);
        $balance_dues = $grand_total - ($user_expense_other_record->advance_taken ?? 0);


        return view('dash.StatusOfExpenses.preview', compact('statement_of_expense', 'total_fare_amount', 'total_da_location', 'total_da_ex_location', 'total_postage', 'total_mobile_internet', 'total_print_stationery', 'total_other_expense_amount', 'total_other_expense_purpose', 'grand_total', 'grand_total_in_words', 'total_da_location_working', 'total_da_location_not_working', 'month', 'UserExpenseOtherRecords_filter', 'balance_dues', 'usser', 'total_fare_amount', 'total_Da', 'da_outstation'));

    }
}
