<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DivisonMaster;
use App\Models\ModeofExpenseMaster;
use App\Models\MonthlyExpense;
use App\Models\MonthlyExpenseHistory;
use App\Models\UserExpenseOtherRecords;
use App\Models\ExpenseTypeMaster;
use App\Models\OtherExpenseMaster;


use Carbon\Carbon;


 
class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";

        $user = auth()->user();
        $user_id = $user->id;
        
        $totalUsers = User::count(); // Custom logic to get total users.

        $query = UserExpenseOtherRecords::where('is_submitted', 1);
        

        // Apply user role-specific conditions
        if ($user->hasRole('Super Admin')) {
            $UserExpenseOtherRecords = $query->get();
        } elseif ($user->hasRole('Sales Admin')) {
            $UserExpenseOtherRecords = $query->get();
        } elseif ($user->hasRole('Sales Admin Hod')) {
            $UserExpenseOtherRecords = $query->get();
        } else {
            $UserExpenseOtherRecords = $query->where('user_id', $user_id)->get();
        }

        $rejected_status_of_expense = $UserExpenseOtherRecords->filter(function ($record) {
            return $record->status == 3;
        });

        $in_progress_status_of_expense = $UserExpenseOtherRecords->filter(function ($record) {
            return $record->status == 2;
        });

        $completed_status_of_expense = $UserExpenseOtherRecords->filter(function ($record) {
            return $record->status == 1;
        });

        $no_of_rejected_status_of_expense = $rejected_status_of_expense->count();
        $no_of_in_progress_status_of_expense = $in_progress_status_of_expense->count();
        $no_of_completed_status_of_expense = $completed_status_of_expense->count();

        
        

        
        // Get the current date and determine the previous month
        // $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        // $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $startOfLastMonth = Carbon::now()->startOfMonth()->subMonth();
        $endOfLastMonth = Carbon::now()->endOfMonth()->subMonth();
        // Base query
        $sla_query = UserExpenseOtherRecords::where('is_submitted', 1)->where('status', 1)->whereBetween('expense_date', [$startOfLastMonth, $endOfLastMonth]);      

        if ($user->hasRole('Super Admin')) {
            $sla_voilated = (clone $sla_query)->where('sla_status', 1)->count();
            $sla_non_voilated = (clone $sla_query)->where('sla_status', 0)->count();
        } elseif ($user->hasRole('Sales Admin')) {
            $sla_voilated = (clone $sla_query)->where('sla_status', 1)->count();
            $sla_non_voilated = (clone $sla_query)->where('sla_status', 0)->count();
        } elseif ($user->hasRole('Sales Admin Hod')) {
            $sla_voilated = (clone $sla_query)->where('sla_status', 1)->count();
            $sla_non_voilated = (clone $sla_query)->where('sla_status', 0)->count();
            // dd($sla_non_voilated);
        } else {
            $sla_voilated = (clone $sla_query)->where('user_id', $user_id)->where('sla_status', 1)->count();
            $sla_non_voilated = (clone $sla_query)->where('user_id', $user_id)->where('sla_status', 0)->count();
        }     

        // Sla Compliance By Sales And Admin
        $admin_hod_sla_voilated = (clone $sla_query)->where('sla_status_of_approval', 1)->count();
        // dd($admin_hod_sla_voilated);
        $admin_hod_sla_non_voilated = (clone $sla_query)->where('sla_status_of_approval', 0)->count();
        // dd($admin_hod_sla_non_voilated);

        // $sla_voilated = 10;
        // $sla_non_voilated = 5;        
        // $admin_hod_sla_voilated = 10;
        // $admin_hod_sla_non_voilated = 20;

        
        $pending_for_verification = UserExpenseOtherRecords::where('is_submitted', 1)->where('is_verified', 0)->where('is_approved', 0)->where('status', 2)->count();
        

        $pending_for_approval = UserExpenseOtherRecords::where('is_submitted', 1)->where('is_verified', 1)->where('is_approved', 0)->where('status', 2)->count();
        

        return view('dashboard', compact('title', 'totalUsers', 'no_of_rejected_status_of_expense', 'no_of_in_progress_status_of_expense', 'no_of_completed_status_of_expense', 'sla_voilated', 'sla_non_voilated', 'admin_hod_sla_voilated', 'admin_hod_sla_non_voilated', 'pending_for_verification', 'pending_for_approval'));
    }
}
