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
use App\Models\MonthlyExpenseHistory2;
use App\Models\UserExpenseOtherRecords;
use App\Models\ExpenseTypeMaster;
use App\Models\OtherExpenseMaster;
use App\Models\RejectionMaster;
use App\Models\ReOpenMaster;
use App\Models\PolicySettings;
use Illuminate\Support\Facades\Http;


use Barryvdh\DomPDF\Facade\Pdf;


use Carbon\Carbon;

class HODExpensePendingForVerificationController extends Controller
{
    public function index(){   
        $title = "Pending Expense";   

        $user = auth()->user();
        $user_id = $user->id;
        
        if ($user->hasRole('Sales Admin')) {
            $title = "Expense Pending For Verification";
            $pending_monthly_expenses = UserExpenseOtherRecords::where('is_submitted', 1)->where('is_verified', 0)->where('is_approved', 0)->where('status', 2)->orderBy('expense_date', 'desc')->get();
            
        } elseif ($user->hasRole('Sales Admin Hod')) {
            $title = "Expense Pending For Approval";
            $pending_monthly_expenses = UserExpenseOtherRecords::where('is_submitted', 1)->where('is_verified', 1)->where('is_approved', 0)->where('status', 2)->orderBy('expense_date', 'desc')->get();
        } else {
            $title = "Expense Pending";
            $pending_monthly_expenses = UserExpenseOtherRecords::where('is_submitted', 1)
            ->where('status', 2)  // Apply the status filter directly
            ->orderBy('expense_date', 'desc')
            ->get();
        }
        
        

        return view('dash.HODexpensePendingForVerification.index', compact('title', 'pending_monthly_expenses', 'title')); 
        
    }


    public function edit(Request $request, $id)
    {   
        //dd("gfg");
        $reason_of_rejections = RejectionMaster::all();
        $reason_of_re_opens = ReOpenMaster::all();

        $expense_modes = ModeofExpenseMaster::all();
        $expense_type_master = ExpenseTypeMaster::all();
        $other_expense_master = OtherExpenseMaster::all();
        // Filter based on the provided status or get all if no status is specified
        $pending_monthly_expenses = UserExpenseOtherRecords::find($id);
        if (!$pending_monthly_expenses){
            abort(404, 'Expense not found');

        }
        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

        $user_id = $pending_monthly_expenses->user_id;
        $expense_id = $pending_monthly_expenses->expense_id;
        $pending_monthly_expenses_id = $pending_monthly_expenses->id;
        $pending_monthly_expenses_date = Carbon::parse($pending_monthly_expenses->expense_date);
        // Extract the month and year from the expense date
        $expense_month = $pending_monthly_expenses_date->format('m'); // Month (01-12)
        $expense_year = $pending_monthly_expenses_date->format('Y'); // Year (e.g., 2025)


        $user = User::find($user_id);
        if (!$user){
            return redirect()->route('pending_expense_verification.index')->with('success', 'User Not Found');

        }



       $monthly_expenses = MonthlyExpense::with('monthlyExpenseHistory','ExpenseTypeMaster')->where('user_id', $user_id)->where('expense_id', $expense_id)->whereMonth('expense_date', $expense_month)->whereYear('expense_date', $expense_year)->where('user_expense_other_records_id', $id)->get();

        if (!$monthly_expenses){
            return redirect()->route('pending_expense_verification.index')->with('success', 'Monthly Sales Not Found');

        }

        $total_fare_amount = $monthly_expenses->sum('fare_amount');
        $total_da_location = $monthly_expenses->sum('da_location');
        $total_da_ex_location = $monthly_expenses->sum('da_ex_location');
        $total_postage = $monthly_expenses->sum('postage');
        $total_mobile_internet = $monthly_expenses->sum('mobile_internet');
        $total_print_stationery = $monthly_expenses->sum('print_stationery');        
        $total_other_expense_amount = $monthly_expenses->sum('other_expenses_amount');
        $total_other_expense_purpose = $monthly_expenses->sum('other_expense_purpose');


        $grand_total = $total_fare_amount + $total_da_location + $total_da_ex_location + $total_other_expense_amount + $total_other_expense_purpose;



        // Convert the number to words
        $grand_total_in_words = $formatter->format($grand_total);
        

        $total_da_location_working = $monthly_expenses->where('other_expense_master_id', '!=', 8)->sum('da_total');
        $total_da_location_not_working = $monthly_expenses->where('other_expense_master_id', 8)->sum('da_total');

        // To get the corresponding MonthlyExpenseHistory records
        $monthly_expense_histories = $monthly_expenses->map(function ($monthly_expense) {
            return $monthly_expense->monthlyExpenseHistory;  // This will return the history related to each monthly_expense
        });

        $monthly_expense_histories2 = $monthly_expenses->map(function ($monthly_expense) {
            return $monthly_expense->monthlyExpenseHistory2;  // This will return the history related to each monthly_expense
        });
        // dd($monthly_expense_histories2);

        // Now you have a collection of collections for each monthly_expense and its corresponding history
        // You can loop through it to get all the history records

        // Now you have a collection of collections for each monthly_expense and its corresponding history
        // You can loop through it to get all the history records
        foreach ($monthly_expense_histories as $historyCollection) {
            foreach ($historyCollection as $history) {
                // Assuming $monthly_expense is the parent of the history collection,
                // you should have access to it like this
                $monthly_expense = $history->monthlyExpense;  // Replace with the correct relation if needed
                
                // Example: Compare the history and current monthly_expense, and store the result if they are different
                $history_diff = $history->isDifferent($monthly_expense);  // Assuming isDifferent method compares them
                
                // Now you can assign history_diff to the current expense, if needed
                // You can process the difference or store it as needed, for example:
                $monthly_expense->history_diff = $history_diff;
            }
        }
        // dd($monthly_expenses->toArray(), $monthly_expense_histories->toArray());
        $user_detail = UserDetails::where('user_id',$user_id)->get()->first();
        $designation_id = $user_detail->designation_id;
        $policySetting = PolicySettings::where('designation_id', $designation_id)->get()->first();
        // dd($policySetting->four_wheelers_charges);
        
        $countryCode = 'IN'; // or 'US', 'GB', etc.
$response = Http::get("https://date.nager.at/api/v3/PublicHolidays/{$expense_year}/{$countryCode}");

$holidays = collect($response->json())
    ->mapWithKeys(fn($holiday) => [$holiday['date'] => $holiday['localName']]);

        return view('dash.HODexpensePendingForVerification.edit', compact('holidays','reason_of_rejections', 'reason_of_re_opens', 'expense_modes', 'expense_type_master', 'other_expense_master','pending_monthly_expenses', 'monthly_expenses', 'user', 'total_fare_amount', 'total_da_location', 'total_da_ex_location', 'total_postage', 'total_mobile_internet', 'total_print_stationery', 'total_other_expense_amount', 'total_other_expense_purpose', 'grand_total', 'grand_total_in_words', 'total_da_location_working', 'total_da_location_not_working', 'monthly_expense_histories', 'monthly_expense_histories2', 'policySetting'));

        
    }

    
    public function update(Request $request, $id)
    {
        // try {
            $user = auth()->user();
            
            // Filter based on the provided status or get all if no status is specified
            $pending_monthly_expenses = UserExpenseOtherRecords::find($request->pending_monthly_expense_id);
            if (!$pending_monthly_expenses){
                return redirect()->back()->with('error', 'Pending Expense not found');

            }
            

            $user_id = $pending_monthly_expenses->user_id;
            $expense_id = $pending_monthly_expenses->expense_id;
            $pending_monthly_expenses_id = $pending_monthly_expenses->id;
            $pending_monthly_expenses_date = Carbon::parse($pending_monthly_expenses->expense_date);
            // Extract the month and year from the expense date
            $expense_month = $pending_monthly_expenses_date->format('m');
            $expense_year = $pending_monthly_expenses_date->format('Y'); 

            $old_monthly_expenses = MonthlyExpense::where('user_id', $user_id)
                ->where('expense_id', $expense_id)
                ->whereMonth('expense_date', $expense_month)
                ->whereYear('expense_date', $expense_year)
                ->where('user_expense_other_records_id', $pending_monthly_expenses->id)
                ->get();

            

            if ($old_monthly_expenses->isEmpty()) {
                return redirect()->route('pending_expense_verification.index')->with('error', 'Old Monthly Expenses Not Found');
            }
            // for history table
            $expenseIds = $old_monthly_expenses->pluck('id');
            
            if ($user->hasRole('Sales Admin')) {
                $existingHistories = MonthlyExpenseHistory::whereIn('monthly_expense_id', $expenseIds)->get()->keyBy('monthly_expense_id');
            }

            if ($user->hasRole('Sales Admin Hod')) {
                $existingHistories2 = MonthlyExpenseHistory2::whereIn('monthly_expense_id', $expenseIds)->get()->keyBy('monthly_expense_id');
            }

            foreach ($old_monthly_expenses as $old_monthly_expense) {
                if ($user->hasRole('Sales Admin')) {

                    $existingHistory = $existingHistories->get($old_monthly_expense->id);

                    if (!$existingHistory) {
                        \Log::info("Creating history record for expense ID: " . $old_monthly_expense->id);

                        $old_expenseRecord = new MonthlyExpenseHistory([
                            'user_id' => $old_monthly_expense->user_id,
                            'monthly_expense_id' => $old_monthly_expense->id,
                            'expense_id' => $old_monthly_expense->expense_id,
                            'expense_type_master_id' => $old_monthly_expense->expense_type_master_id,
                            'mode_of_expense_master_id' => $old_monthly_expense->mode_of_expense_master_id,
                            'expense_date' => $old_monthly_expense->expense_date,
                            'one_way_two_way_multi_location' => $old_monthly_expense->one_way_two_way_multi_location,
                            'from' => $old_monthly_expense->from,
                            'to' => $old_monthly_expense->to,
                            'departure_time' => $old_monthly_expense->departure_time,
                            'arrival_time' => $old_monthly_expense->arrival_time,
                            'km_as_per_user' => $old_monthly_expense->km_as_per_user,
                            'km_as_per_google_map' => $old_monthly_expense->km_as_per_google_map,
                            'fare_amount' => $old_monthly_expense->fare_amount,
                            'da_location' => $old_monthly_expense->da_location,
                            'da_ex_location' => $old_monthly_expense->da_ex_location,
                            'da_outstation' => $old_monthly_expense->da_outstation,
                            'da_total' => $old_monthly_expense->da_total,
                            'postage' => $old_monthly_expense->postage,
                            'mobile_internet' => $old_monthly_expense->mobile_internet,
                            'print_stationery' => $old_monthly_expense->print_stationery,
                            'other_expense_master_id' => $old_monthly_expense->other_expense_master_id,
                            'other_expenses_amount' => $old_monthly_expense->other_expenses_amount,
                            'pre_approved' => $old_monthly_expense->pre_approved,
                            'approved_date' => $old_monthly_expense->approved_date,
                            'approved_by' => $old_monthly_expense->approved_by,
                            'hod_id' => $old_monthly_expense->hod_id,
                            'upload_of_approvals_documents' => $old_monthly_expense->upload_of_approvals_documents,
                            'status' => $old_monthly_expense->status,
                            'is_submitted' => $old_monthly_expense->is_submitted,
                            'accept_policy' => $old_monthly_expense->accept_policy,
                            'user_expense_other_records_id' => $old_monthly_expense->user_expense_other_records_id,
                            'remarks' => $old_monthly_expense->remarks,
                        ]);

                        $old_expenseRecord->save();
                        
                    // } else {
                    //     $existingHistory->update([                       
                            
                    //     'user_id' => $old_monthly_expense->user_id,
                    //         'monthly_expense_id' => $old_monthly_expense->id,
                    //         'expense_id' => $old_monthly_expense->expense_id,
                    //         'expense_type_master_id' => $old_monthly_expense->expense_type_master_id,
                    //         'mode_of_expense_master_id' => $old_monthly_expense->mode_of_expense_master_id,
                    //         'expense_date' => $old_monthly_expense->expense_date,
                    //         'one_way_two_way_multi_location' => $old_monthly_expense->one_way_two_way_multi_location,
                    //         'from' => $old_monthly_expense->from,
                    //         'to' => $old_monthly_expense->to,
                    //         'departure_time' => $old_monthly_expense->departure_time,
                    //         'arrival_time' => $old_monthly_expense->arrival_time,
                    //         'km_as_per_user' => $old_monthly_expense->km_as_per_user,
                    //         'km_as_per_google_map' => $old_monthly_expense->km_as_per_google_map,
                    //         'fare_amount' => $old_monthly_expense->fare_amount,
                    //         'da_location' => $old_monthly_expense->da_location,
                    //         'da_ex_location' => $old_monthly_expense->da_ex_location,
                    //         'da_outstation' => $old_monthly_expense->da_outstation,
                    //         'da_total' => $old_monthly_expense->da_total,
                    //         'postage' => $old_monthly_expense->postage,
                    //         'mobile_internet' => $old_monthly_expense->mobile_internet,
                    //         'print_stationery' => $old_monthly_expense->print_stationery,
                    //         'other_expense_master_id' => $old_monthly_expense->other_expense_master_id,
                    //         'other_expenses_amount' => $old_monthly_expense->other_expenses_amount,
                    //         'pre_approved' => $old_monthly_expense->pre_approved,
                    //         'approved_date' => $old_monthly_expense->approved_date,
                    //         'approved_by' => $old_monthly_expense->approved_by,
                    //         'hod_id' => $old_monthly_expense->hod_id,
                    //         'upload_of_approvals_documents' => $old_monthly_expense->upload_of_approvals_documents,
                    //         'status' => $old_monthly_expense->status,
                    //         'is_submitted' => $old_monthly_expense->is_submitted,
                    //         'accept_policy' => $old_monthly_expense->accept_policy,
                    //         'user_expense_other_records_id' => $old_monthly_expense->user_expense_other_records_id,
                    //         'remarks' => $old_monthly_expense->remarks,


                    //     ]);
                        
                    }
                }

                if ($user->hasRole('Sales Admin Hod')) {

                    $existingHistory2 = $existingHistories2->get($old_monthly_expense->id);

                    if (!$existingHistory2) {
                        \Log::info("Creating history record for expense ID: " . $old_monthly_expense->id);

                        $old_expenseRecord = new MonthlyExpenseHistory2([
                            'user_id' => $old_monthly_expense->user_id,
                            'monthly_expense_id' => $old_monthly_expense->id,
                            'expense_id' => $old_monthly_expense->expense_id,
                            'expense_type_master_id' => $old_monthly_expense->expense_type_master_id,
                            'mode_of_expense_master_id' => $old_monthly_expense->mode_of_expense_master_id,
                            'expense_date' => $old_monthly_expense->expense_date,
                            'one_way_two_way_multi_location' => $old_monthly_expense->one_way_two_way_multi_location,
                            'from' => $old_monthly_expense->from,
                            'to' => $old_monthly_expense->to,
                            'departure_time' => $old_monthly_expense->departure_time,
                            'arrival_time' => $old_monthly_expense->arrival_time,
                            'km_as_per_user' => $old_monthly_expense->km_as_per_user,
                            'km_as_per_google_map' => $old_monthly_expense->km_as_per_google_map,
                            'fare_amount' => $old_monthly_expense->fare_amount,
                            'da_location' => $old_monthly_expense->da_location,
                            'da_ex_location' => $old_monthly_expense->da_ex_location,
                            'da_outstation' => $old_monthly_expense->da_outstation,
                            'da_total' => $old_monthly_expense->da_total,
                            'postage' => $old_monthly_expense->postage,
                            'mobile_internet' => $old_monthly_expense->mobile_internet,
                            'print_stationery' => $old_monthly_expense->print_stationery,
                            'other_expense_master_id' => $old_monthly_expense->other_expense_master_id,
                            'other_expenses_amount' => $old_monthly_expense->other_expenses_amount,
                            'pre_approved' => $old_monthly_expense->pre_approved,
                            'approved_date' => $old_monthly_expense->approved_date,
                            'approved_by' => $old_monthly_expense->approved_by,
                            'hod_id' => $old_monthly_expense->hod_id,
                            'upload_of_approvals_documents' => $old_monthly_expense->upload_of_approvals_documents,
                            'status' => $old_monthly_expense->status,
                            'is_submitted' => $old_monthly_expense->is_submitted,
                            'accept_policy' => $old_monthly_expense->accept_policy,
                            'user_expense_other_records_id' => $old_monthly_expense->user_expense_other_records_id,
                            'remarks' => $old_monthly_expense->remarks,
                        ]);

                        $old_expenseRecord->save();
                        
                    // } else {
                    //     $existingHistory->update([                       
                            
                    //     'user_id' => $old_monthly_expense->user_id,
                    //         'monthly_expense_id' => $old_monthly_expense->id,
                    //         'expense_id' => $old_monthly_expense->expense_id,
                    //         'expense_type_master_id' => $old_monthly_expense->expense_type_master_id,
                    //         'mode_of_expense_master_id' => $old_monthly_expense->mode_of_expense_master_id,
                    //         'expense_date' => $old_monthly_expense->expense_date,
                    //         'one_way_two_way_multi_location' => $old_monthly_expense->one_way_two_way_multi_location,
                    //         'from' => $old_monthly_expense->from,
                    //         'to' => $old_monthly_expense->to,
                    //         'departure_time' => $old_monthly_expense->departure_time,
                    //         'arrival_time' => $old_monthly_expense->arrival_time,
                    //         'km_as_per_user' => $old_monthly_expense->km_as_per_user,
                    //         'km_as_per_google_map' => $old_monthly_expense->km_as_per_google_map,
                    //         'fare_amount' => $old_monthly_expense->fare_amount,
                    //         'da_location' => $old_monthly_expense->da_location,
                    //         'da_ex_location' => $old_monthly_expense->da_ex_location,
                    //         'da_outstation' => $old_monthly_expense->da_outstation,
                    //         'da_total' => $old_monthly_expense->da_total,
                    //         'postage' => $old_monthly_expense->postage,
                    //         'mobile_internet' => $old_monthly_expense->mobile_internet,
                    //         'print_stationery' => $old_monthly_expense->print_stationery,
                    //         'other_expense_master_id' => $old_monthly_expense->other_expense_master_id,
                    //         'other_expenses_amount' => $old_monthly_expense->other_expenses_amount,
                    //         'pre_approved' => $old_monthly_expense->pre_approved,
                    //         'approved_date' => $old_monthly_expense->approved_date,
                    //         'approved_by' => $old_monthly_expense->approved_by,
                    //         'hod_id' => $old_monthly_expense->hod_id,
                    //         'upload_of_approvals_documents' => $old_monthly_expense->upload_of_approvals_documents,
                    //         'status' => $old_monthly_expense->status,
                    //         'is_submitted' => $old_monthly_expense->is_submitted,
                    //         'accept_policy' => $old_monthly_expense->accept_policy,
                    //         'user_expense_other_records_id' => $old_monthly_expense->user_expense_other_records_id,
                    //         'remarks' => $old_monthly_expense->remarks,


                    //     ]);
                        
                    }
                }
            }


            $monthly_expenses = $request->input('monthly_expense', []);

            if (empty($monthly_expenses)) {
                
                return redirect()->back()->with('error', 'No expenses to update.');
            }

            foreach ($monthly_expenses as $expense) {
                if (!isset($expense['id']) || empty($expense['id'])) {
                    \Log::warning("Expense ID is missing or empty");
                    continue;
                }

                $expenseRecord = MonthlyExpense::find($expense['id']);

                if ($expenseRecord) {
                    $expenseRecord->update([                       
                        
                    // 'mode_of_expense_master_id' => $expense['mode_expense'],
                    'expense_date' => \Carbon\Carbon::createFromFormat('Y-m-d', $expense['expense_date'])->format('Y-m-d'),
                    'from' => $expense['from'],
                    'to' => $expense['to'],
                    'departure_time' => $expense['departure_time'],
                    'arrival_time' => $expense['arrival_time'],
                    'km_as_per_user' => $expense['km_as_per_user'],
                    'km_as_per_google_map' => $expense['km_as_per_google_map'],
                    'fare_amount' => $expense['fare_amount'],
                   'da_total' => $expense['da_total'],
                   'postage' => $expense['postage'],
                   'mobile_internet' => $expense['mobile_internet'],
                   // 'other_expense_master_id' => $expense['other_expense'],
                   'other_expenses_amount' => $expense['other_expenses_amount'],
                   'print_stationery' => $expense['print_stationery'],


                    ]);
                    
                } else {
                    \Log::error("Expense record not found");
                    continue;
                }
            }
         return redirect()->back()->with('success', 'Expenses updated successfully.');
            return redirect()->route('pending_expense_verification.index')->with('success', 'Expenses updated successfully.');

    //     } catch (\Exception $e) {
    //         \Log::error("Error updating expenses: " . $e->getMessage());
    //         \Log::error($e->getTraceAsString());
    //         return redirect()->back()->with('error', $e->getMessage());
    //     }
    }


    public function show(Request $request, $id)
    {

    }

    public function destroy(Request $request, $id)
    {
        $pending_monthly_expense = UserExpenseOtherRecords::findOrFail($id);

        $user_id = $pending_monthly_expense->user_id;
        $expense_id = $pending_monthly_expense->expense_id;
        $pending_monthly_expenses_id = $pending_monthly_expense->id;

        $monthly_expenses = MonthlyExpense::where('user_id', $user_id)->where('expense_id', $expense_id)->where('user_expense_other_records_id', $id)->get();
        
        
        // Delete each Sales record
        foreach ($monthly_expenses as $monthly_expense) {
            $monthly_expense->delete(); // Delete each sale
        }

        // Now delete the SalesMaster record
        $pending_monthly_expense->delete();

        // Redirect back to the sales master index with a success message
       return redirect()->route('sales_master.index')->with('success', 'Sales deleted successfully!');

    }

    public function changeStatus(Request $request, $id)
    {
        $user = auth()->user();
        $user_id = $user->id;

        // Validate the inputs
        $request->validate([
           
            'status' => 'required|integer|in:0,1,2,3', // Ensure status is valid
        ]);

        // Find the record
        $pending_monthly_expense = UserExpenseOtherRecords::findOrFail($request->id);

        // Parse the date_of_submission using Carbon
        $currentDate = \Carbon\Carbon::parse($pending_monthly_expense->expense_date);

        // Extract current year, month, and day
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;
        $nextMonth = $currentMonth + 1;
        $currentDay = $currentDate->day;

        // Calculate the 20th day of the current month
        $last_date_to_verify = \Carbon\Carbon::create($currentYear, $nextMonth, 20);

        // Calculate the difference in days between now and the last date to verify
        $verification_days_elapsed = $last_date_to_verify->diffInDays(now(), false);

        $verified_date = \Carbon\Carbon::parse($pending_monthly_expense->verified_time);
        $approval_days_elapsed = $verified_date->diffInDays(now(), false);


        // Adjust for negative difference if necessary
        if ($last_date_to_verify->isBefore(now())) {
            $verification_days_elapsed = -$verification_days_elapsed;
        }       

        $status = $request->status;

        // Handle status 0 (unsubmitted)
        if ($status == 0) {
            $pending_monthly_expense->status = $status;
            $pending_monthly_expense->is_submitted = 0;
            
            $pending_monthly_expense->is_approved = 0;
            $pending_monthly_expense->approved_time = null;
            $pending_monthly_expense->approved_by = null;
            $pending_monthly_expense->approval_days_elapsed = null;
            $pending_monthly_expense->approval_deadline = null;


            $pending_monthly_expense->is_verified = 0;
            $pending_monthly_expense->verified_time = null;            
            $pending_monthly_expense->verified_by = null;
            $pending_monthly_expense->verification_days_elapsed = null;

            
            
            
            $pending_monthly_expense->accept_policy = 0;
            $pending_monthly_expense->days_elapsed = 0;
            
            

            $pending_monthly_expense->sla_status = 0;
            $pending_monthly_expense->sla_status_of_submission = 0;
            $pending_monthly_expense->sla_status_of_approval = 0;
            $pending_monthly_expense->reason_of_rejection = null;

            


        } 
        // Handle status 1 (verified) or 2 (approved)
        elseif ($status == 1 || $status == 2) {
            $pending_monthly_expense->status = $status;

            if ($user->hasRole('Sales Admin')) {
                $pending_monthly_expense->verified_by = $user_id;
                $pending_monthly_expense->verified_time = now();
                $pending_monthly_expense->verification_days_elapsed = $verification_days_elapsed;
                $pending_monthly_expense->is_verified = 1;
                $pending_monthly_expense->is_approved = 0;
            }

            if ($user->hasRole('Sales Admin Hod')) {
                $pending_monthly_expense->approved_by = $user_id;
                $pending_monthly_expense->approved_time = now();
                $pending_monthly_expense->approval_days_elapsed = $approval_days_elapsed;
                $pending_monthly_expense->is_approved = 1;
            }
        } 
        // Handle other statuses
        else {
            $pending_monthly_expense->status = $status;
        }

        // Save the changes
        $pending_monthly_expense->save();

        // Redirect with a success message

        if ($status == 0) {
            $message = "Pending Monthly Expense Re-opened successfully";
        } elseif($status == 3) {
            $message = "Pending Monthly Expense Rejected successfully";

        } elseif($status == 1 || $status == 2) {
            if ($user->hasRole('Sales Admin')) {
                $message = "Pending Monthly Expense Verified successfully";
            }

            if ($user->hasRole('Sales Admin Hod')) {
                $message = "Pending Monthly Expense Approved successfully";
            }  

        } else{
             $message = "Pending Monthly Expense status changed successfully";

        }

        return redirect()->route('pending_expense_verification.index')->with('success', $message);
    }

    public function print(Request $request, $id)
    {   
       // $id = $request->id;
        $expense_modes = ModeofExpenseMaster::all();
        $expense_type_master = ExpenseTypeMaster::all();
        $other_expense_master = OtherExpenseMaster::all();
        // Filter based on the provided status or get all if no status is specified
        $pending_monthly_expenses = UserExpenseOtherRecords::find($id);
        if (!$pending_monthly_expenses){
            abort(404, 'Expense not found');

        }
        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

        $user_id = $pending_monthly_expenses->user_id;
        $expense_id = $pending_monthly_expenses->expense_id;
        $pending_monthly_expenses_id = $pending_monthly_expenses->id;
        $pending_monthly_expenses_date = Carbon::parse($pending_monthly_expenses->expense_date);
        // Extract the month and year from the expense date
        $expense_month = $pending_monthly_expenses_date->format('m'); // Month (01-12)
        $expense_year = $pending_monthly_expenses_date->format('Y'); // Year (e.g., 2025)


        $user = User::find($user_id);
        if (!$user){
            return redirect()->route('pending_expense_verification.index')->with('success', 'User Not Found');

        }



        $monthly_expenses = MonthlyExpense::with('monthlyExpenseHistory')->where('user_id', $user_id)->where('expense_id', $expense_id)->whereMonth('expense_date', $expense_month)->whereYear('expense_date', $expense_year)->where('user_expense_other_records_id', $id)->get();

        if (!$monthly_expenses){
            return redirect()->route('pending_expense_verification.index')->with('success', 'Monthly Sales Not Found');

        }

        $total_fare_amount = $monthly_expenses->sum('fare_amount');
        $total_da_location = $monthly_expenses->sum('da_location');
        $total_da_ex_location = $monthly_expenses->sum('da_ex_location');
        $total_postage = $monthly_expenses->sum('postage');
        $total_mobile_internet = $monthly_expenses->sum('mobile_internet');
        $total_print_stationery = $monthly_expenses->sum('print_stationery');        
        $total_other_expense_amount = $monthly_expenses->sum('other_expenses_amount');
        $total_other_expense_purpose = $monthly_expenses->sum('other_expense_purpose');


        $total_Da = $total_da_location + $total_da_ex_location;


        

 

        

        $total_da_location_working = $monthly_expenses->where('other_expense_master_id', '!=', 8)->sum('da_total');

        
        $total_da_location_not_working = $monthly_expenses->where('other_expense_master_id', 8)->sum('da_total');
        
        $total  = $total_da_location_working + $total_da_location_not_working;
        
        $da_outstation = abs($total_da_location_working - $total_Da);
        
        $grand_total = $total_fare_amount + $total_da_location_working + $total_da_location_not_working + $total_other_expense_amount + $total_other_expense_purpose + $total_postage + $total_mobile_internet + $total_print_stationery;



        // Convert the number to words
        $grand_total_in_words = $formatter->format($grand_total);
        

        $total_da_location_working = $monthly_expenses->where('other_expense_master_id', '!=', 8)->sum('da_total');
        $total_da_location_not_working = $monthly_expenses->where('other_expense_master_id', 8)->sum('da_total');

        // To get the corresponding MonthlyExpenseHistory records
        $monthly_expense_histories = $monthly_expenses->map(function ($monthly_expense) {
            return $monthly_expense->monthlyExpenseHistory;  // This will return the history related to each monthly_expense
        });

        // Now you have a collection of collections for each monthly_expense and its corresponding history
        // You can loop through it to get all the history records

        // Now you have a collection of collections for each monthly_expense and its corresponding history
        // You can loop through it to get all the history records
        foreach ($monthly_expense_histories as $historyCollection) {
            foreach ($historyCollection as $history) {
                // Assuming $monthly_expense is the parent of the history collection,
                // you should have access to it like this
                $monthly_expense = $history->monthlyExpense;  // Replace with the correct relation if needed
                
                // Example: Compare the history and current monthly_expense, and store the result if they are different
                $history_diff = $history->isDifferent($monthly_expense);  // Assuming isDifferent method compares them
                
                // Now you can assign history_diff to the current expense, if needed
                // You can process the difference or store it as needed, for example:
                $monthly_expense->history_diff = $history_diff;
            }
        }
        // dd($monthly_expenses->toArray(), $monthly_expense_histories->toArray());

        $view = view('dash.HODexpensePendingForVerification.print', compact('expense_modes', 'expense_type_master', 'other_expense_master','pending_monthly_expenses', 'monthly_expenses', 'user', 'total_fare_amount', 'total_da_location', 'total_da_ex_location', 'total_postage', 'total_mobile_internet', 'total_print_stationery', 'total_other_expense_amount', 'total_other_expense_purpose', 'grand_total', 'grand_total_in_words', 'total_da_location_working', 'total_da_location_not_working', 'monthly_expense_histories'));

        // Generate PDF from the view
        $pdf = Pdf::loadHTML($view)->setPaper([0, 0, 793, 1404], 'landscape'); // Set paper size to A4 and orientation to landscape
        // return $pdf->stream('pending_monthly_expense.pdf');
        return $pdf->download('pending_monthly_expense.pdf');


        
    }

    public function Reject(Request $request, $id)
    {
        $user_expense_other_record = UserExpenseOtherRecords::find($id);

        if (!$user_expense_other_record) {
            return redirect()->back()->with('error', 'No pending expenses found.');
        }

        // Validate the inputs
        $request->validate([
            'rejection_master_id' => 'required|exists:rejection_master,id', // Ensure the rejection_master_id is valid
        ]);

        // Update the record with rejection details
        $user_expense_other_record->update([
            'rejection_master_id' => $request->rejection_master_id,
            'status' => 3, // Status for "Rejected"
        ]);

        return redirect()->route('pending_expense_verification.index')->with('success', "Pending Monthly Expense Rejected");
    }

    public function ReOpen(Request $request, $id)
    {
        $user_expense_other_record = UserExpenseOtherRecords::find($id);

        if (!$user_expense_other_record) {
            return redirect()->back()->with('error', 'No pending expenses found.');
        }

        // Validate the inputs
        $request->validate([
            're_open_master_id' => 'required|exists:re_open_master,id', // Ensure the re_open_master_id is valid
        ]);

        // Update the record with re-open details
        $user_expense_other_record->update([
            're_open_master_id' => $request->re_open_master_id,
            'status' => 0, // Status for "Re-Opened"
            'is_submitted' => 0,           
            'is_approved' => 0,
            'date_of_submission' => null,
            'approved_time' => null,
            'approved_by' => null,
            'approval_days_elapsed' => null,
            'approval_deadline' => null,

            'is_verified' => 0,
            'verified_time' => null,            
            'verified_by' => null,
            'verification_days_elapsed' => null,              
            
            'accept_policy' => 0,
            'days_elapsed' => 0,          
            
            'sla_status' => 0,
            'sla_status_of_submission' => 0,
            'sla_status_of_approval' => 0,
            'reason_of_rejection' => null,
        ]);

        return redirect()->route('pending_expense_verification.index')->with('success', "Pending Monthly Expense Re-Opened");
    }





}
