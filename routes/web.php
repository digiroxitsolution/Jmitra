<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\UserController;

use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;

use App\Http\Controllers\CompanyMasterController;
use App\Http\Controllers\DivisonMasterController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\LocationMasterController;
use App\Http\Controllers\ModeOfExpenseMAsterController;
use App\Http\Controllers\OtherExpenseMAsterController;
use App\Http\Controllers\RejectionMasterController;
use App\Http\Controllers\ReOpenMasterController;
use App\Http\Controllers\SalesMasterController;
use App\Http\Controllers\WayOfLocationMasterController;
use App\Http\Controllers\HodController;
use App\Http\Controllers\DesignationController;


use App\Http\Controllers\PolicySettinigsController;
use App\Http\Controllers\PolicyGuidelinesController;
use App\Http\Controllers\StatusOfExpensesController;
use App\Http\Controllers\processTimeReportController;
use App\Http\Controllers\slaReportController;
use App\Http\Controllers\MonthlyExpensesController;
use App\Http\Controllers\HODCalcController;
use App\Http\Controllers\HODExpensePendingForVerificationController;
use App\Http\Controllers\ExpensesSlipReportController;
use App\Http\Controllers\MultiExpensesSlipReportController;
use App\Http\Controllers\ExpenseDetailsReportController;
use App\Http\Controllers\ProcessReportController;
use App\Http\Controllers\GenExReportController;
use App\Http\Controllers\GeneralExpensesController;
use App\Http\Controllers\SalesExpensesController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\LocationController;
use App\Models\State;
use App\Models\City;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


// Route::group(['middleware' => ['auth']], function() {
//     // Route::group(['prefix' => 'admin'], function () {

       

//         // Resource routes for roles, permissions, and users
//         Route::resource('roles', RoleController::class);
//         Route::resource('permissions', PermissionController::class);
//         Route::resource('users', UserController::class);

//         Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//     // });
// });

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::post('/users/upload', [UserController::class, 'upload'])->name('users_upload');
    Route::post('/users/update', [UserController::class, 'update']);
    Route::get('user_sample/download', [UserController::class, 'download'])->name('user_sample.download');
    // Route::resource('products', ProductController::class);


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('state', StateController::class);
    Route::resource('city', CityController::class);

    Route::resource('company_master', CompanyMasterController::class);
    Route::resource('divison_master', DivisonMasterController::class);
    Route::resource('expense_type', ExpenseTypeController::class);
    Route::resource('location_master', LocationMasterController::class);
    Route::get('/cities/{state_id}', function ($state_id) {
        $cities = City::where('state_id', $state_id)->get();
        return response()->json($cities);
    });
    

    Route::resource('mode_of_expense_master', ModeOfExpenseMAsterController::class);
    Route::resource('other_expense_master', OtherExpenseMAsterController::class);
    Route::resource('rejection_master', RejectionMasterController::class);
    Route::resource('re_open_master', ReOpenMasterController::class);
    Route::resource('sales_master', SalesMasterController::class);

    Route::post('/upload', [SalesMasterController::class, 'upload'])->name('sales.upload');
    Route::post('/sales/upload', [SalesMasterController::class, 'upload'])->name('sales_upload');
    Route::get('sales_sample/download', [SalesMasterController::class, 'download'])->name('sales_sample.download');

    Route::resource('way_of_location_master', WayOfLocationMasterController::class);

    // Route::post('/company_masters/update/{id}', [CompanyController::class, 'update'])->name('company_master.update');

    

    Route::resource('hods', HodController::class);
    Route::resource('designation', DesignationController::class);


    Route::resource('policy_settings', PolicySettinigsController::class);
    Route::resource('policy_guidelines', PolicyGuidelinesController::class);

    Route::get('status_of_expenses', [StatusOfExpensesController::class, 'StatusOfExpenses'])->name('status_of_expenses');

    Route::get('statement_of_expense/{id}', [StatusOfExpensesController::class, 'statementOfExpense'])->name('statement_of_expense');

    Route::get('process_time_report', [processTimeReportController::class, 'ProcessTimeReport'])->name('process_time_report');

    Route::get('sla_report', [slaReportController::class, 'SlaReport'])->name('sla_report');
    Route::get('sla_report_hod_status', [slaReportController::class, 'CheckStatus'])->name('sla_report_hod_status');

    Route::resource('monthly_expenses', MonthlyExpensesController::class);
    Route::post('/generate-monthly-expenses', [MonthlyExpensesController::class, 'generateMonthlyExpenses'])->name('generate.monthly.expenses');

    Route::match(['get', 'post'], '/form-submit-of-selected-month', [MonthlyExpensesController::class, 'formSubmitofSelectedMonth'])->name('form_submit_of_selected_month');
    Route::match(['get', 'post'], '/priview-submission', [MonthlyExpensesController::class, 'PriviewSubumission'])->name('priview_submission');

    Route::post('/form-submit-of-selected-month-final-print', [MonthlyExpensesController::class, 'formSubmitofSelectedMonthFinalPrint'])->name('form_submit_of_selected_month_final_print');

    Route::post('/user_expense_other_record_update/{id}', [MonthlyExpensesController::class, 'UserExpenseOtherRecordsUpdate'])->name('user_expense_other_record_update.update');

    Route::post('/submit_user_monthly_expense/{id}', [MonthlyExpensesController::class, 'UserExpenseOtherRecordsSubmit'])->name('submit_user_monthly_expense');

    Route::match(['get', 'post'], '/print_user_monthly_expense', [MonthlyExpensesController::class, 'formSubmitofSelectedMonthPrint'])->name('print_user_monthly_expense');
    

    Route::match(['get', 'post'], 'hod_calc', [HODCalcController::class, 'hODCalc'])->name('hod_calc');

    Route::resource('pending_expense_verification',HODExpensePendingForVerificationController::class);
    Route::put('pending_expense_verification_update/{id}', [HODExpensePendingForVerificationController::class, 'update'])->name('pending_expense_verification_update.update');
    
    Route::get('/pending_expense_verification/change-status/{id}', [HODExpensePendingForVerificationController::class, 'changeStatus'])->name('pending_expense_verification.changeStatus');

    Route::post('/pending_expense_reject/{id}', [HODExpensePendingForVerificationController::class, 'Reject'])->name('pending_expense_reject');

    Route::post('/pending_expense_re_open/{id}', [HODExpensePendingForVerificationController::class, 'ReOpen'])->name('pending_expense_re_open');



    Route::get('pending_expense_verification_print/{id}', [HODExpensePendingForVerificationController::class, 'print'])->name('pending_expense_verification_print');

    Route::get('/other_attendance_view/{id}/{attendance_date}', [AttendanceController::class, 'GetOtherAttendance'])->name('get_other_attendance');

    Route::get('expenses_slip_report', [ExpensesSlipReportController::class, 'ExpensesSlipReport'])->name('expenses_slip_report');
    Route::get('/employee-suggestions', [ExpensesSlipReportController::class, 'getEmployeeSuggestions'])->name('employee.suggestions');

    Route::get('expenses_slip_print', [ExpensesSlipReportController::class, 'print'])->name('expenses_slip_print');

    Route::post('/search_expenses_slip_report', [ExpensesSlipReportController::class, 'searchEmployee'])->name('search_expenses_slip_report');
    Route::post('expenses_slip_report_print', [ExpensesSlipReportController::class, 'print'])->name('expenses_slip_report_print');
    Route::post('/generate-pdf-report', [ExpensesSlipReportController::class, 'generatePdf'])->name('generate_pdf_report');




    Route::get('multi_expenses_slip_report', [MultiExpensesSlipReportController::class, 'MultiExpensesSlipReport'])->name('multi_expenses_slip_report');

    Route::post('multi_expenses_slip_report_search', [MultiExpensesSlipReportController::class, 'search'])->name('multi_expenses_slip_report_search');
    Route::post('multi_expenses_slip_report_print', [MultiExpensesSlipReportController::class, 'print'])->name('multi_expenses_slip_report_print');



    Route::match(['get', 'post'], 'expense_details_report', [ExpenseDetailsReportController::class, 'ExpenseDetailsReport'])->name('expense_details_report');

    Route::match(['get', 'post'], 'expense_details_report_print', [ExpenseDetailsReportController::class, 'Print'])->name('expense_details_report_print');


    // Route::post('/expense_details_report_search', [
    //     `SQ S   W0D
    //     320QE
    //     30F
    //     T3350Y+
    //     660+608+6`class, 'ExpenseDetailsReport'])->name('expense_details_report_search');

    Route::match(['get', 'post'], 'process_report', [ProcessReportController::class, 'ProcessReport'])->name('process_report');

    Route::match(['get', 'post'], 'genex_report', [GenExReportController::class, 'GenExReport'])->name('genex_report');

    Route::match(['get', 'post'], 'general_expenses', [GeneralExpensesController::class, 'GeneralExpenses'])->name('general_expenses');

    Route::match(['get', 'post'], 'general_expenses_print', [GeneralExpensesController::class, 'Print'])->name('general_expenses_print');

    Route::match(['get', 'post'], 'sale_expenses', [SalesExpensesController::class, 'SalesExpenses'])->name('sale_expenses');

    Route::match(['get', 'post'], 'sale_expenses_print', [SalesExpensesController::class, 'SalesExpensesPrint'])->name('sale_expenses_print');


    Route::match(['get', 'post'], 'sale_fare', [SalesExpensesController::class, 'Fare'])->name('sale_fare');

    Route::post('fare_print', [SalesExpensesController::class, 'FarePrint'])->name('fare_print');
    Route::match(['get', 'post'], 'sale_pax', [SalesExpensesController::class, 'Pax'])->name('sale_pax');
    Route::post('pax_print', [SalesExpensesController::class, 'PaxPrint'])->name('pax_print');

    Route::match(['get', 'post'], 'sale_se_analysis', [SalesExpensesController::class, 'SEAnalysis'])->name('sale_se_analysis');

    Route::get('se_analysis_print', [SalesExpensesController::class, 'SEAnalysisPrint'])->name('se_analysis_print');
    
    Route::match(['get', 'post'], 'sale_total_expense', [SalesExpensesController::class, 'TotalExpense'])->name('sale_total_expense');

    Route::post('total_expense_print', [SalesExpensesController::class, 'TotalExpensePrint'])->name('total_expense_print');

    Route::resource('attendance', AttendanceController::class);
    Route::get('attendance_sample/download', [AttendanceController::class, 'download'])->name('attendance_sample.download');


    Route::get('/attendance_view', [AttendanceController::class, 'GetAttendance'])->name('get_attendance');

    Route::get('/user_attendance/{attendance_date}', [AttendanceController::class, 'GetUserAttendance'])->name('user_attendance');



    

    Route::get('/charts', [ChartController::class, 'index']);
    Route::post('/save-se-analysis-chart-image', [\App\Http\Controllers\ChartController::class, 'saveSeAnalysisChartImage'])->name('save_se_analysis_chart_image');
    Route::post('/save-total-expense-chart-image', [\App\Http\Controllers\ChartController::class, 'saveTotalExpenseChartImage'])->name('save_total_expense_chart_image');

    Route::post('/save-fare-chart-image', [\App\Http\Controllers\ChartController::class, 'saveFareChartImage'])->name('save_fare_chart_image');

    Route::post('/save-pax-chart-image', [\App\Http\Controllers\ChartController::class, 'savePaxChartImage'])->name('save_pax_chart_image');





    // Route::post('/get-distance', function (Request $request) {
    //     $from = $request->input('from');
    //     $to = $request->input('to');
    //     $apiKey = env('GOOGLE_MAPS_API_KEY');

    //     if (empty($from) || empty($to)) {
    //         return response()->json(['error' => 'Both "from" and "to" locations are required.'], 400);
    //     }

    //     $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . urlencode($from) . "&destinations=" . urlencode($to) . "&key={$apiKey}";

    //     try {
    //         $response = Http::get($url);

    //         if ($response->successful()) {
    //             Log::info('Google API Response:', $response->json()); // Log for debugging
    //             return $response->json();
    //         }

    //         return response()->json(['error' => 'Failed to fetch distance from Google API.'], 500);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    //     }
    // });

    Route::post('/get-distance', function (Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        if (empty($from) || empty($to)) {
            return response()->json(['error' => 'Both "from" and "to" locations are required.'], 400);
        }

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . urlencode($from) . "&destinations=" . urlencode($to) . "&key={$apiKey}";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                Log::info('Google API Response:', $response->json()); // Log for debugging
                return $response->json();
            }

            return response()->json(['error' => 'Failed to fetch distance from Google API.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    });


    // Route::get('/location', [LocationController::class, 'showLocationForm']);






});




require __DIR__.'/auth.php';
