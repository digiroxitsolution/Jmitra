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

class slaReportController extends Controller
{
    public function SlaReport(Request $request)
    {
        $title = "Sla Reports";

        $user = auth()->user();
        $user_id = $user->id;

        // Get `sla_status` from the request, default to 0 (Non Violated) if not provided
        $sla_status = $request->input('sla_status', 0);

        // Base query
        $query = UserExpenseOtherRecords::where('is_submitted', 1)
            ->where('sla_status', $sla_status); // Filter by `sla_status`

        // Apply user role-specific conditions
        if ($user->hasRole('Super Admin')) {
            $sla_reports = $query->orderBy('expense_date', 'desc')->get();
        } elseif ($user->hasRole('Sales Admin')) {
            $sla_reports = $query->orderBy('expense_date', 'desc')->get();
        } elseif ($user->hasRole('Sales Admin Hod')) {
            $sla_reports = $query->orderBy('expense_date', 'desc')->get();
        } else {
            $sla_reports = $query->where('user_id', $user_id)->orderBy('expense_date', 'desc')->get();
        }

        return view('dash.slaReport.index', compact('title', 'sla_reports'));
    }

    public function CheckStatus(Request $request)
    {
        $user = auth()->user();
        $user_id = $user->id;

        // Get `sla_status` from the request, default to 0 (Non Violated) if not provided
        $sla_status_of_approval = $request->input('sla_status_of_approval', 0);

        // Base query
        $query = UserExpenseOtherRecords::where('is_submitted', 1)
            ->where('sla_status_of_approval', $sla_status_of_approval); // Filter by `sla_status_of_approval`

        // Apply user role-specific conditions
        if ($user->hasRole('Super Admin')) {
            $sla_reports = $query->orderBy('expense_date', 'desc')->get();
        } elseif ($user->hasRole('Sales Admin')) {
            $sla_reports = $query->orderBy('expense_date', 'desc')->get();
        } elseif ($user->hasRole('Sales Admin Hod')) {
            $sla_reports = $query->orderBy('expense_date', 'desc')->get();
        } else {
            $sla_reports = $query->where('user_id', $user_id)->orderBy('expense_date', 'desc')->get();
        }

        return view('dash.slaReport.index', compact('sla_reports'));
    }
}
