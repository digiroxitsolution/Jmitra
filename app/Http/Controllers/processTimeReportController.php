<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\DivisonMaster;
use App\Models\ModeofExpenseMaster;
use App\Models\MonthlyExpense;
use App\Models\UserExpenseOtherRecords;

use Carbon\Carbon;
class processTimeReportController extends Controller
{
    public function ProcessTimeReport(){
        $title = "Process Time Report";

        $user = auth()->user();
        $user_id = $user->id;
        
        $status = $request->status ?? 2;

        
        if ($user->hasRole('Super Admin')) {
            
            $monthly_expenses = UserExpenseOtherRecords::where('is_submitted', 1)
            ->whereIn('status', [1, 2, 3])  // Apply the status filter directly
            ->orderBy('expense_date', 'desc')
            ->get();
        } elseif ($user->hasRole('Sales Admin')) {
            
            $monthly_expenses = UserExpenseOtherRecords::where('is_submitted', 1)
            ->whereIn('status', [1, 2, 3])  // Apply the status filter directly
            ->orderBy('expense_date', 'desc')
            ->get();
        } elseif ($user->hasRole('Sales Admin Hod')) {
            $monthly_expenses = UserExpenseOtherRecords::where('is_submitted', 1)
            ->whereIn('status', [1, 2, 3])  // Apply the status filter directly
            ->orderBy('expense_date', 'desc')
            ->get();
        } elseif ($user->hasRole('Sales')) {
            
            $monthly_expenses = UserExpenseOtherRecords::where('user_id', $user_id)->where('is_submitted', 1)
            ->whereIn('status', [1, 2, 3])  // Apply the status filter directly
            ->orderBy('expense_date', 'desc')
            ->get();
        } else {
            
            $monthly_expenses = UserExpenseOtherRecords::where('user_id', $user_id)->where('is_submitted', 1)
            ->whereIn('status', [1, 2, 3])  // Apply the status filter directly
            ->orderBy('expense_date', 'desc')
            ->get();
        }
        
        
        return view('dash.processTimeReport.index', compact('title', 'monthly_expenses'));
    }
}
