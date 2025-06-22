<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use App\Models\Attendance;
use App\Models\State;
use App\Models\UserDetails;

class AttendanceController extends Controller
{
    public function index()
    {
        $title = "Attendance Master";

        $attendances = Attendance::all();

        return view('dash.admin.attendance.index', compact('title', 'attendances'));
        // return view('dash.admin.attendance.index');


    }

    public function create()
    {
        return view('dash.attendance.create');
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'fileUpload' => 'required|mimes:xlsx,xls,csv',
            
        ]);

        $file = $request->file('fileUpload'); // Get the uploaded file
        $requiredHeaders = [
            'Employee Id', 'Employee Name', 'State', 'Zone', 'Name', 'Customer Type', 
            'Check In Time', 'Check Out Time', 'Purpose', 'Description', 'Joint Purpose Details', 
            'Check In Address', 'Check In Remarks', 'Check Out Remarks'
        ];
        

        try {
            // Read the file content directly without storing
            // Read the file content into an array (First sheet)
            $attendanceData = Excel::toArray([], $file)[0]; // [0] to get the first sheet

            // Separate the first row as headers and the rest as data rows
            $headers = $attendanceData[0]; // First row is the header
            // Check if all required headers are present
            $missingHeaders = array_diff($requiredHeaders, $headers);
            if (!empty($missingHeaders)) {
                return back()->with('error', 'Missing headers: ' . implode(', ', $missingHeaders));
            }
            $dataRows = array_slice($attendanceData, 1); // All remaining rows are the data

            // Now map the data using headers
            $mappedData = [];
            foreach ($dataRows as $row) {
                $mappedRow = [];
                foreach ($headers as $index => $header) {
                    $mappedRow[$header] = $row[$index]; // Map the header to the row data
                }
                $mappedData[] = $mappedRow; // Add the mapped row to the result
            }

            // Return the mapped data for debugging
            // return response()->json($mappedData);

        } catch (\Exception $e) {
            // Log::error('Error reading Excel file: ' . $e->getMessage());
            return back()->with('error', 'Error reading Excel file');
        }
        $errors = [];

        // Start transaction
        DB::beginTransaction();
        try {

            foreach ($mappedData as $row) {
                // Log the entire row data for debugging
                // Log::debug('Row data:', ['row' => $row]);
                if (!isset($row['Employee Id']) || empty($row['Employee Id'])) {
                    // Log::error('Missing Employee Id in row:', ['row' => $row]);
                    continue; // Skip this row
                }
                // Check if the necessary fields exist
                if (isset($row['Employee Id'])) {
                    // Log the state name being searched
                    // Log::debug('Looking for state:', ['state_name' => $row['State']]);

                    // Find the state in the database
                    $state = State::whereRaw('LOWER(name) = ?', [strtolower(trim($row['State']))])->first(); // Trim any spaces
                    $user_detail = UserDetails::where('employee_id', trim($row['Employee Id']))->first();
                    if (!$user_detail) {
                        // Log::error('User detail not found for Employee Id:', ['Employee Id' => $row['Employee Id']]);
                        continue; // Skip this row and process the next one
                        // return back()->with('error', 'employee_id not found');
                    }
                    $user = User::find($user_detail->user_id);

                    

                    if ($user_detail) {
                        try {
                             // Handle Check In and Check Out Time
                            $checkIn = null;
                            $checkOut = null;

                            // Only set Check In and Check Out if they exist
                            if (isset($row['Check In Time']) && !empty($row['Check In Time'])) {
                                $checkInTime = str_replace(',', '', $row['Check In Time']);
                                $checkIn = Carbon::createFromFormat('d M Y h:i A', $checkInTime);
                            }

                            if (isset($row['Check Out Time']) && !empty($row['Check Out Time'])) {
                                $checkOutTime = str_replace(',', '', $row['Check Out Time']);
                                $checkOut = Carbon::createFromFormat('d M Y h:i A', $checkOutTime);
                            }

                            // Create a Sales record
                            Attendance::create([
                                'user_id' => $user_detail->user_id,
                                'state_id' => $state->id ?? null,
                                'customer_name' => $row['Name'] ?? null,
                                'customer_type' => $row['Customer Type'] ?? null,
                                'zone' => $row['Zone'] ?? null,
                                'check_in' => $checkIn,
                                'check_out' => $checkOut,
                                'check_in_address' => $row['Check In Address'] ?? null,
                                'check_in_remarks' => $row['Check In Remarks'] ?? null,
                                'check_out_remarks' => $row['Check Out Remarks'] ?? null,
                                'purpose' => $row['Purpose'] ?? null,
                                'description' => $row['Description'] ?? null,
                                'joint_purpose_details' => $row['Joint Purpose Details'] ?? null,
                            ]);
                        } catch (\Exception $e) {
                            // Log::error('Error parsing datetime or creating record', ['error' => $e->getMessage(), 'row' => $row]);
                            continue; // Skip this row
                        }
                    } else {
                        // Log error if state is not found
                        // Log::error('State or Employee Id not found for: ' . $row['State']);
                        return back()->with('error', 'State or Employee Id not found for: ' . $row['State']);
                    }
                } else {
                    // Log error if any required field is missing in the row
                    // Log::error('Missing required fields in row: ' . json_encode($row));
                    // return back()->with('error', 'Missing Column or mismatched Row Name: ');
                }
            }
        

            DB::commit();
            $message = 'Attendance uploaded successfully!';
            if (!empty($errors)) {
                $message .= ' However, some rows were skipped: ' . implode(', ', $errors);
            }
            return redirect()->route('attendance.index')->with('success', $message);
        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollback();
            return back()->with('error', 'Failed to upload Attendance: ' . $e->getMessage());
        }
        
        
    }
   

    public function edit(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        return view('dash.attendance.index', compact('attendance'));
        
    }

    public function update()
    {
        return;
        
    }

    public function show(Request $request, $id)
    {
        return;
        
    }

    public function destroy(Request $request, $id)
    {
        return;
        
    }

    public function download()
    {
        $filePath = public_path('assets/attendance_sample.xlsx');  // Path to the file inside the assets folder
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }

    public function GetAttendance(Request $request)
    {
        $user_id = auth()->user()->id;
        $check_in = $request->check_in;

        if (!$check_in) {
            return response()->json(['error' => 'Check-in date is required.'], 400);
        }

        try {
            $date = \Carbon\Carbon::parse($check_in)->format('Y-m-d');

            // Debugging
            // \Log::info("Fetching attendance for date: " . $date);

            $attendances = Attendance::where('user_id', $user_id)->whereDate('check_in', $date)->get();

            if ($attendances->isEmpty()) {
                return response()->json(['error' => 'No attendance records found for this date.'], 404);
            }

            return response()->json($attendances);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }
    }
    public function GetUserAttendance(Request $request, $attendance_date)
    {
        $user_id = auth()->user()->id;
        $check_in = $attendance_date;

        if (!$check_in) {
            return response()->json(['error' => 'Check-in date is required.'], 400);
        }

        try {
            $date = \Carbon\Carbon::parse($check_in)->format('Y-m-d');

            // Debugging
            // \Log::info("Fetching attendance for date: " . $date);

            $attendances = Attendance::where('user_id', $user_id)->whereDate('check_in', $date)->get();

            if ($attendances->isEmpty()) {
                return response()->json(['error' => 'No attendance records found for this date.'], 404);
            }

            return response()->json($attendances);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }
    }
 
    public function GetOtherAttendance($id, $attendance_date)
    {
        try {
            $date = \Carbon\Carbon::parse($attendance_date)->format('Y-m-d');

            $attendances = Attendance::where('user_id', $id)
                ->whereDate('check_in', $date)
                ->get();

            if ($attendances->isEmpty()) {
                return response()->json(['error' => 'No attendance records found for this user and date.'], 404);
            }

            return response()->json($attendances);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }
    }
}