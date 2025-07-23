<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use NumberFormatter;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\DivisonMaster;
use App\Models\ModeofExpenseMaster;
use App\Models\MonthlyExpense;
use App\Models\UserExpenseOtherRecords;
use App\Models\ExpenseTypeMaster;
use App\Models\OtherExpenseMaster;
use App\Models\PolicySettings;
use App\Models\Attendance;
use Carbon\Carbon;

class MonthlyExpensesController extends Controller
{
    public function index(){
        $title = "Monthly Expenses Master";
        $user = auth()->user();
        $user_id = $user->id;
        $users = User::all();
        $divisons = DivisonMaster::all();
        $expense_modes = ModeofExpenseMaster::all();
        $expense_type_master = ExpenseTypeMaster::all();
        $other_expense_master = OtherExpenseMaster::all();

        $UserExpenseOtherRecords_filter = UserExpenseOtherRecords::where('user_id', $user_id)
        ->where('is_submitted', 0)
        ->orderBy('expense_date', 'asc')
        ->get();

        // Step 2: Extract IDs from UserExpenseOtherRecords
        $userExpenseOtherRecordsIds = $UserExpenseOtherRecords_filter->pluck('id')->toArray();

        $monthly_expenses = MonthlyExpense::where('user_id', $user_id)
        ->whereIn('user_expense_other_records_id', $userExpenseOtherRecordsIds)
        ->orderBy('expense_date', 'asc')
        ->get();

        $year = now()->year;
        $month = now()->month;
        $prevMonth = $month-1;
        // dd($prevMonth);

        $current_month_expenses = MonthlyExpense::where('user_id', $user_id)->whereMonth('expense_date', $prevMonth)->whereYear('expense_date', $year)->first(); // Use first() to get a single record

        // Get the expense_id from the first record, if it exists
        $expense_id = $current_month_expenses?->expense_id;

        $UserExpenseOtherRecords = null; // Initialize the variable

        if ($expense_id) {
            // Get the first matching UserExpenseOtherRecords record for the user, expense_id, month, and year
            $UserExpenseOtherRecords = UserExpenseOtherRecords::where('user_id', $user_id)
                ->where('expense_id', $expense_id)
                ->whereMonth('expense_date', $prevMonth)
                ->whereYear('expense_date', $year)
                ->first(); // Use first() to get a single record
        }

        $user_detail = UserDetails::where('user_id',$user_id)->get()->first();
        $designation_id = $user_detail->designation_id;
        $policySetting = PolicySettings::where('designation_id', $designation_id)->get()->first();

        

        return view('dash.monthlyExpense.index', compact('title', 'monthly_expenses', 'divisons', 'expense_modes', 'expense_type_master', 'other_expense_master', 'users', 'UserExpenseOtherRecords', 'year', 'month', 'policySetting'));
    }


    public function create(){

    }

    public function store(){

    }

    public function edit(Request $request, $id)
    {
        $title = "Monthly Expenses Updates";
        $monthly_expense = MonthlyExpense::findOrFail($id);

        $user = auth()->user();
        $user_id = $user->id;
        $users = User::all();
        $divisons = DivisonMaster::all();
        $expense_modes = ModeofExpenseMaster::all();
        $expense_type_master = ExpenseTypeMaster::all();
        $other_expense_master = OtherExpenseMaster::all();

        $user_detail = UserDetails::where('user_id',$user_id)->get()->first();
        $designation_id = $user_detail->designation_id;
        $policySetting = PolicySettings::where('designation_id', $designation_id)->get()->first();
        $check_in = \Carbon\Carbon::parse($monthly_expense->expense_date)->toDateString();
        $attendances = Attendance::where('user_id', $user_id)->whereDate('check_in', $check_in)->get();

        return view('dash.monthlyExpense.update', compact('title', 'monthly_expense', 'divisons', 'expense_modes', 'expense_type_master', 'other_expense_master', 'users', 'policySetting', 'attendances'));
    }

    public function update(Request $request, $id){
      
        try {
            $monthly_expenses = MonthlyExpense::find($id);

            if (!$monthly_expenses) {
                return response()->json(['error' => 'Monthly Expenses not found'], 404);
            }

            // Validate incoming request
            $request->validate([
                'da_total' => 'required|numeric',
                // 'postage' => 'required|numeric|between:digits_between:0,9',
                // 'mobile_internet' => 'required|numeric|digits_between:0,9',
                // 'print_stationery' => 'required|numeric|digits_between:0,9'
            ], 
            // [
            //         'da_total.required' => 'The DA total field is required.',
            //         'da_total.numeric' => 'The DA total must be a number.',

            //         'postage.required' => 'The postage field is required.',
            //         'postage.numeric' => 'The postage must be a number.',
            //         'postage.digits_between' => 'The postage must be between maximum 9 characters.',

            //         'mobile_internet.required' => 'The mobile internet field is required.',
            //         'mobile_internet.numeric' => 'The mobile internet must be a number.',
            //         'mobile_internet.digits_between' => 'The mobile internet must be maximum 9 characters.',

            //         'print_stationery.required' => 'The print & stationery field is required.',
            //         'print_stationery.numeric' => 'The print & stationery must be a number.',
            //         'print_stationery.digits_between' => 'The print & stationery must be maximum 9 characters.'
            //     ]
            );


            $expenseDate = Carbon::createFromFormat('Y-m-d', $request->expense_date);
            $monthly_expenses->update([
                                
                
                // 'divison_master_id' => $request->divison_master_id,
                'expense_type_master_id' => $request->expense_type_master_id,
                'mode_of_expense_master_id' => $request->mode_of_expense_master_id,
                'expense_date' => $expenseDate,
                'one_way_two_way_multi_location' => $request->one_way_two_way_multi_location,
                'from' => $request->from,
                'to' => $request->to,
                'departure_time' => $request->departure_time,
                'arrival_time' => $request->arrival_time,
                'km_as_per_user' => $request->km_as_per_user,
                'km_as_per_google_map' => $request->km_as_per_google_map,
                'fare_amount' => $request->fare_amount,
                'da_location' => $request->da_location,
                'da_ex_location' => $request->da_ex_location,
               'da_outstation' => $request->da_outstation,
               'da_total' => $request->da_total,
               'postage' => $request->postage,
               'mobile_internet' => $request->mobile_internet,
               'print_stationery' => $request->print_stationery,
               'other_expense_master_id' => $request->other_expense_master_id,
               'other_expenses_amount' => $request->other_expenses_amount,
               'pre_approved' => $request->pre_approved,
               'approved_date' => $request->approved_date,
               'approved_by' => $request->approved_by,
               'hod_id' => $request->hod_id,
               'upload_of_approvals_documents' => $request->upload_of_approvals_documents,
               // 'status' => 0,
               // 'is_submitted' => 0,
               'reason_of_rejected' => $request->reason_of_rejected,
               'days_elapsed' => $request->days_elapsed,
               'justification' => $request->justification,
               'advance_taken' => $request->advance_taken,
               'remark_of_advance_taken' => $request->remark_of_advance_taken,
               'accept_policy' => $request->accept_policy,
               'remarks' => $request->remarks,
                
            ]);

                return redirect()->route('monthly_expenses.index') // Replace with your actual route name
                ->with('success', 'Your Monthly Expenses updated successfully');
        } catch (\Exception $e) {
            
            return back()->with('error', 'Failed to Update Monthly Expenses: ' . $e->getMessage());
        }

    }

    public function show(){

    }
    public function destroy(){

    }

    public function generateMonthlyExpenses(Request $request)
    {
        // Validate the request input for proper format
        $request->validate([
            'generate_monthly_expense' => 'required|date_format:Y-m', // Ensures proper YYYY-MM format
        ]);
        $generate_monthly_expense = $request->generate_monthly_expense;

        // // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($generate_monthly_expense, 5, 2); // Extract month (09)
        $year = (int)substr($generate_monthly_expense, 0, 4); // Extract year (2024)

        // Get the user ID (assuming you have authentication in place)
        $user_id = auth()->user()->id; // Get the authenticated user's ID

        // Calculate the first and last day of the month
        $startDate = Carbon::create($year, $month, 1); // First day of the selected month
        $endDate = $startDate->copy()->endOfMonth(); // Last day of the selected month

        // Retrieve existing records for the selected month
        $existingRecords = MonthlyExpense::where('user_id', $user_id)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->get();

        // If records exist, extract the existing expense_id
        $existingRecord = MonthlyExpense::where('user_id', $user_id)
        ->whereBetween('expense_date', [$startDate, $endDate])
        ->first();
        $existingExpenseId = $existingRecords->first()?->expense_id;

        // Retrieve existing records for the selected month
        $existingDates = MonthlyExpense::where('user_id', $user_id)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->pluck('expense_date')
            ->map(function ($date) {
                return Carbon::parse($date)->toDateString(); // Normalize dates for comparison
            })
            ->toArray();

        // Create an array of all dates in the selected month
        $allDates = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $allDates[] = $date->toDateString();
        }

        // Identify missing dates
        $missingDates = array_diff($allDates, $existingDates);

        // If no missing dates, return with a message
        if (empty($missingDates)) {
            return redirect()->back()->with('success', 'All records for the selected month already exist.');
        }

        
        // Get the current date for comparison
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;  // Get the current year
        $currentMonth = $currentDate->month; // Get the current month
        $currentDay = $currentDate->day; // Get the current day

        // Calculate the days_elapsed based on the month, year, and current date
        if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
            // If the given date is in the past
            if ($year == $currentYear && $month + 1 == $currentMonth) {
                // Case: Last month within the same year
                $days_elapsed = $currentDay - 7; // Adjust based on business rules
            } else {
                // Case: More than one month ago, or a past month from a previous year
                // $startOfNextMonth = Carbon::create($year, $month, 1)->addMonth(); // Start of the next month
                $startOfNextMonth = Carbon::create($year, $month, 1)->addMonth();
                $days_in_next_month = $startOfNextMonth->diffInDays($currentDate, false) + 1;
                $days_elapsed = (int)round($days_in_next_month - 7);

                // $days_in_next_month = $startOfNextMonth->diffInDays($currentDate); // Days until the current date
                // $days_elapsed = $days_in_next_month - 7; // Adjust by 7 days
            }
        } else {
            // Future or current month
            $days_elapsed = 0; // Default value for future months
        }

        // Ensure days_elapsed is non-negative
        $days_elapsed = max(0, $days_elapsed);

        // Determine SLA status
        $sla_status = $days_elapsed > 0 ? 1 : 0;

        // If no existing expense_id, generate a new one
        if (!$existingExpenseId) {
            $existingExpenseId = 'EXP-' . $user_id . '-' . $year . $month . rand(1000, 9999); // Example format: EXP-1-202412XXXX

            // Ensure expense_id is unique by checking if it already exists
            do {
                $existingExpenseId = 'EXP-' . $user_id . '-' . $year . $month . Str::random(4); // Example: EXP-1-202412XXXX
            } while (MonthlyExpense::where('expense_id', $existingExpenseId)->exists());

            // Create a new record in UserExpenseOtherRecords (only once per function call)
            $UserExpenseOtherRecords = UserExpenseOtherRecords::create([
                'user_id' => $user_id,
                'expense_id' => $existingExpenseId,
                'expense_date' => $startDate->toDateString(),
                'hod_id' => $existingRecord->hod_id ?? null,
                'date_of_submission' => null,
                'status' => 0,
                'is_submitted' => 0,
                'days_elapsed' => $days_elapsed, // Adjust as needed
                
                            
                'is_approved' => 0,
                'approved_time' => null,
                'approved_by' => null,
                'approval_days_elapsed' => null,
                'approval_deadline' => null,
                'is_verified' => 0,
                'verified_time' => null,            
                'verified_by' => null,
                'verification_days_elapsed' => null,            
                'accept_policy' => 0,
                'sla_status' => $sla_status,
                'sla_status_of_submission' => 0,
                'sla_status_of_approval' => 0,
                'reason_of_rejection' => null,
            ]);
        } else {
            // Retrieve the corresponding UserExpenseOtherRecords
            $UserExpenseOtherRecords = UserExpenseOtherRecords::where('expense_id', $existingExpenseId)->first();
        }

        // Insert missing records
        foreach ($missingDates as $missingDate) {
            MonthlyExpense::create([
                'user_id' => $user_id,
                'expense_id' => $existingExpenseId,
                'expense_date' => $missingDate,
                'status' => 0,
                'is_submitted' => 0,
                'user_expense_other_records_id' => $UserExpenseOtherRecords->id,
            ]);
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Monthly expenses for the selected month were created successfully!');
    }


    

    public function formSubmitofSelectedMonth(Request $request)
    {   
        $user_id = auth()->user()->id;
        $formSubmitSelectedMonth = $request->formSubmitSelectedMonth;

        // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($formSubmitSelectedMonth, 5, 2); // Extract month (09)
        $year = (int)substr($formSubmitSelectedMonth, 0, 4); // Extract year (2024)

        if (!$month || !$year) {
            return response()->json(['error' => 'Month and year are required.'], 400);
        }

        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

        // Fetch records for the specified month and year
        $monthly_expenses = MonthlyExpense::where('user_id', $user_id)->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->get();
        // Check if any records exist for the selected month and year
        if ($monthly_expenses->isEmpty()) {
            // If no records found, redirect to the index page with a message
            return redirect()->route('monthly_expenses.index')
                             ->with('error', 'No records found for the selected month and year.');
        }

        $first_monthly_expenses = MonthlyExpense::where('user_id', $user_id)->whereMonth('expense_date', $month)->whereYear('expense_date', $year)->first(); // Use first() to get a single record

        // Get the expense_id from the first record, if it exists
        $expense_id = $first_monthly_expenses?->expense_id;

        $user_expense_other_record = null; // Initialize the variable

        if ($expense_id) {
            // Get the first matching UserExpenseOtherRecords record for the user, expense_id, month, and year
            $user_expense_other_record = UserExpenseOtherRecords::where('user_id', $user_id)
                ->where('expense_id', $expense_id)
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year)
                ->first(); // Use first() to get a single record
        }


        $month = $formSubmitSelectedMonth;

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
        $balance_dues = $grand_total - ($user_expense_other_record->advance_taken ?? 0);
        
        

        return view('dash.monthlyExpense.formSubmitSelectedMonth', compact('monthly_expenses', 'month', 'year', 'total_fare_amount', 'total_da_location', 'total_da_ex_location', 'total_postage', 'total_mobile_internet', 'total_print_stationery', 'total_other_expense_amount', 'total_other_expense_purpose', 'grand_total', 'grand_total_in_words', 'user_expense_other_record', 'balance_dues', 'formSubmitSelectedMonth', 'total_da_location_working', 'total_da_location_not_working', 'total_Da', 'da_outstation', 'expense_id'));

    }


    public function UserExpenseOtherRecordsUpdate(Request $request, $id){
        try {

            $user_expense_other_record = UserExpenseOtherRecords::find($id);

            if (!$user_expense_other_record) {
                return response()->json(['error' => 'Monthly Expenses not found'], 404);
            }

            // Validate incoming request
            $request->validate([
                // 'name' => 'required|string|max:255',
                
            ]);
            $formSubmitSelectedMonth = $request->formSubmitSelectedMonth;
            
            $user_expense_other_record->update([                        
                               
                'advance_taken' => $request->advance_taken,
                'remark_of_advance_taken' => $request->remark_of_advance_taken,                
                
            ]);

                return redirect()->route('form_submit_of_selected_month', [
                    'formSubmitSelectedMonth' => $request->formSubmitSelectedMonth,
                ]);
        } catch (\Exception $e) {
            
            return back()->with('error', 'Failed to Monthly Expenses: ' . $e->getMessage());
        }

        

    }

    public function UserExpenseOtherRecordsSubmit(Request $request, $id)
        {
            $user_expense_other_record = UserExpenseOtherRecords::find($id);

            $request->validate([
                // 'accept_policy' => 'required', // Uncomment if validation is required
                // 'justification' => 'required',
            ]);
        $expense_date = $user_expense_other_record->expense_date;

            // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($expense_date, 5, 2); // Extract month (09)
        $year = (int)substr($expense_date, 0, 4); // Extract year (2024)
        // Parse the date string
        $dateString = "2025-01-08 10:30:00";

        // Create a Carbon instance
        // $currentDate = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;  // Get the current year
        $currentMonth = $currentDate->month; // Get the current month
        $currentDay = $currentDate->day; // Get the current day

        // Calculate the days_elapsed based on the month, year, and current date
        if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
            // If the given date is in the past
            if ($year == $currentYear && $month + 1 == $currentMonth) {
                // Case: Last month within the same year
                $days_elapsed = $currentDay - 7; // Adjust based on business rules
            } else {
                // Case: More than one month ago, or a past month from a previous year
                // $startOfNextMonth = Carbon::create($year, $month, 1)->addMonth(); // Start of the next month
                $startOfNextMonth = Carbon::create($year, $month, 1)->addMonth();
                $days_in_next_month = $startOfNextMonth->diffInDays($currentDate, false) + 1;
                $days_elapsed = (int)round($days_in_next_month - 7);

                // $days_in_next_month = $startOfNextMonth->diffInDays($currentDate); // Days until the current date
                // $days_elapsed = $days_in_next_month - 7; // Adjust by 7 days
            }
        } else {
            // Future or current month
            $days_elapsed = 0; // Default value for future months
        }

        // Ensure days_elapsed is non-negative
        $days_elapsed = max(0, $days_elapsed);
        // dd($days_elapsed);

        // Determine SLA status
        $sla_status = $days_elapsed > 0 ? 1 : 0;

            $accept_policy = $request->accept_policy;
            $justification = $request->justification;

            $user_expense_other_record->update([                        
                'is_submitted' => 1,
                'date_of_submission' => now(),
                'accept_policy' => $accept_policy ?? 0, // Corrected here
                'justification' => $justification,
                'days_elapsed' => $days_elapsed,                
                'sla_status' => $sla_status,
                'status' => 2,
            ]);

            return redirect()->route('monthly_expenses.index') // Replace with your actual route name
                ->with('success', 'Your Monthly Expenses successfully submitted');


        }

    public function PriviewSubumission(Request $request)
    {   
        $user_id = auth()->user()->id;
        $formSubmitSelectedMonth = Carbon::now()->subMonth()->format('Y-m');

        // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($formSubmitSelectedMonth, 5, 2); // Extract month (09)
        $year = (int)substr($formSubmitSelectedMonth, 0, 4); // Extract year (2024)

        if (!$month || !$year) {
            return response()->json(['error' => 'Month and year are required.'], 400);
        }

        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

        // Fetch records for the specified month and year
        $monthly_expenses = MonthlyExpense::where('user_id', $user_id)->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->get();
        // Check if any records exist for the selected month and year
        if ($monthly_expenses->isEmpty()) {
            // If no records found, redirect to the index page with a message
            return redirect()->route('monthly_expenses.index')
                             ->with('error', 'No records found for the selected month and year.');
        }

        $first_monthly_expenses = MonthlyExpense::where('user_id', $user_id)->whereMonth('expense_date', $month)->whereYear('expense_date', $year)->first(); // Use first() to get a single record

        // Get the expense_id from the first record, if it exists
        $expense_id = $first_monthly_expenses?->expense_id;

        $user_expense_other_record = null; // Initialize the variable

        if ($expense_id) {
            // Get the first matching UserExpenseOtherRecords record for the user, expense_id, month, and year
            $user_expense_other_record = UserExpenseOtherRecords::where('user_id', $user_id)
                ->where('expense_id', $expense_id)
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year)
                ->first(); // Use first() to get a single record
        }
        


        $month = $formSubmitSelectedMonth;

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
        $balance_dues = $grand_total - ($user_expense_other_record->advance_taken ?? 0);

        $total_da_location_working = $monthly_expenses->where('other_expense_master_id', '!=', 8)->sum('da_total');
        $total_da_location_not_working = $monthly_expenses->where('other_expense_master_id', 8)->sum('da_total');


        return view('dash.monthlyExpense.previewSubmission', compact('monthly_expenses', 'month', 'total_fare_amount', 'total_da_location', 'total_da_ex_location', 'total_postage', 'total_mobile_internet', 'total_print_stationery', 'total_other_expense_amount', 'total_other_expense_purpose', 'grand_total', 'grand_total_in_words', 'user_expense_other_record', 'balance_dues', 'formSubmitSelectedMonth', 'total_da_location_working', 'total_da_location_not_working'));

    }



    public function formSubmitofSelectedMonthFinalPrint(Request $request)
    {   
        $user_id = auth()->user()->id;
        $formSubmitSelectedMonth = Carbon::now()->subMonth()->format('Y-m');
        
        // dd($formSubmitSelectedMonth);

        // Extract month and year from the input string (e.g., "2024-09")
        $month = (int)substr($formSubmitSelectedMonth, 5, 2); // Extract month (09)
        $year = (int)substr($formSubmitSelectedMonth, 0, 4); // Extract year (2024)

        if (!$month || !$year) {
            return response()->json(['error' => 'Month and year are required.'], 400);
        }

        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

        // Fetch records for the specified month and year
        $monthly_expenses = MonthlyExpense::where('user_id', $user_id)->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->get();
        
        
        $first_monthly_expenses = MonthlyExpense::where('user_id', $user_id)->whereMonth('expense_date', $month)->whereYear('expense_date', $year)->first(); // Use first() to get a single record
        
        
        // Get the expense_id from the first record, if it exists
        $expense_id = $first_monthly_expenses?->expense_id;
        
        
        $user_expense_other_record = null; // Initialize the variable

        if ($expense_id) {
            // Get the first matching UserExpenseOtherRecords record for the user, expense_id, month, and year
            $user_expense_other_record = UserExpenseOtherRecords::where('user_id', $user_id)
                ->where('expense_id', $expense_id)
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year)
                ->first(); // Use first() to get a single record
        }
        


        

        $month = \Carbon\Carbon::parse($first_monthly_expenses->expense_date)->format('F');
        // dd($month);

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
        $balance_dues = $grand_total - ($user_expense_other_record->advance_taken ?? 0);


        return view('dash.monthlyExpense.finalPrint', compact('monthly_expenses', 'month', 'total_fare_amount', 'total_da_location', 'total_da_ex_location', 'total_postage', 'total_mobile_internet', 'total_print_stationery', 'total_other_expense_amount', 'total_other_expense_purpose', 'grand_total', 'grand_total_in_words', 'user_expense_other_record', 'balance_dues', 'formSubmitSelectedMonth', 'total_da_location_working', 'total_da_location_not_working', 'expense_id', 'da_outstation', 'total_Da'));

    }

    public function formSubmitofSelectedMonthPrint(Request $request)
    {   
        $user_id = auth()->user()->id;
        
        $expense_id = $request->expense_id;       

        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

        // Fetch records for the specified month and year
        $monthly_expenses = MonthlyExpense::where('expense_id', $expense_id)->get();


        $first_monthly_expenses = $monthly_expenses->first(); // Use first() to get a single record

        $month = \Carbon\Carbon::parse($first_monthly_expenses->expense_date)->format('F');

        $user_expense_other_record = null; // Initialize the variable

        if ($expense_id) {
            // Get the first matching UserExpenseOtherRecords record for the user, expense_id, month, and year
            $user_expense_other_record = UserExpenseOtherRecords::where('expense_id', $expense_id)->first(); // Use first() to get a single record
        }
        
        // dd($user_expense_other_record);

        

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
        $balance_dues = $grand_total - ($user_expense_other_record->advance_taken ?? 0);


        $data = compact('monthly_expenses', 'total_fare_amount', 'total_da_location', 'total_da_ex_location', 'total_postage', 'total_mobile_internet', 'total_print_stationery', 'total_other_expense_amount', 'total_other_expense_purpose', 'grand_total', 'grand_total_in_words', 'user_expense_other_record', 'balance_dues', 'total_da_location_working', 'total_da_location_not_working','month', 'da_outstation', 'total_Da');

        // Load the view and pass data to it
        $pdf = \PDF::loadView('dash.monthlyExpense.print', $data);

        // $html = view('dash.monthlyExpense.print')->render();

        // // Generate PDF from the view
        // $pdf = PDF::loadHTML($html);

        // Download the PDF
        $pdf->setPaper([0, 0, 793, 1404], 'landscape');
        return $pdf->download('expense_report.pdf');


    }
}
