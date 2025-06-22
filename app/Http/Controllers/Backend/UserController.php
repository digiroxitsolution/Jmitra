<?php


namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;


use DB;
use Hash;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Designation;
use App\Models\DivisonMaster;
use App\Models\CompanyMaster;
use App\Models\State;
use App\Models\City;
use App\Models\LocationMaster;

use App\Models\Hod;
use App\Models\PolicySettings;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{


    // function __construct()
    // {
    //      $this->middleware('permission:users-list');
    //      $this->middleware('permission:users-show', ['only' => ['show']]);
    //      $this->middleware('permission:users-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:users-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $title = "Users Master";
        
        $roles = Role::all();
        $data = User::with([
            'roles',
            'userDetail.designation',
            'userDetail.companyMaster',
            'userDetail.state',
            'userDetail.city',
            'userDetail.locationMaster',
            'userDetail.hod',
            'userDetail.policySetting',
        ])->orderBy('id', 'ASC')->get();
        foreach ($data as $user) {
                // Assuming the password has already been hashed, you can't get the plain password from the database.
                // You can save the plain password temporarily before hashing it.
                if ($user->plain_password) {
                    $user->password = $user->plain_password; // Temporarily set the plain password for display
                }
            }
        $designations = Designation::all();
        $divisons = DivisonMaster::all();
        $company_masters = CompanyMaster::all();
        $states = State::all();
        $cities = City::all();
        $location_masters = LocationMaster::all();
        $hods = Hod::all();
        $policy_settings = PolicySettings::all();

        return view('dash.users.index', compact('title', 'data', 'roles', 'designations', 'company_masters', 'states', 'cities', 'location_masters', 'hods', 'policy_settings', 'divisons'));
    }


    public function store(Request $request)
    {
        // **Validation Section for User and UserDetails**
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'status' => 'required',

            'designation_id' => 'required|integer|exists:designations,id', // Assuming designation_id should be valid
            'divison_master_id' => 'required|integer|exists:divison_master,id',
            'company_master_id' => 'required|integer|exists:company_master,id', // Ensure this is a valid company ID
            'location_master_id' => 'required|integer|exists:location_master,id', // Ensure location exists
            'hod_id' => 'required|integer|exists:users,id', // HOD should be a valid user ID
            'policy_setting_id' => 'required|integer|exists:policy_settings,id', // Policy setting must exist
            'city_id' => 'required|integer|exists:cities,id', // Ensure city exists
            'state_id' => 'required|integer|exists:states,id', // Ensure state exists
        ]);

        // **Create the User ONLY if all validation passes**
        try {
            // Start a transaction to ensure both user and user details are inserted together
            DB::beginTransaction();

            // Create User
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->password = Hash::make($request->password);
            $user->save();

            // **Assign Roles to the User**
            $user->assignRole($request->roles);
            // $employee_id = uniqid()
            // **Saving User Details**
            $userDetail = new UserDetails();
            // $userDetail->employee_id  = $employee_id ;
            $userDetail->user_id = $user->id;
            $userDetail->designation_id = $request->designation_id;
            $userDetail->divison_master_id = $request->divison_master_id;
            $userDetail->company_master_id = $request->company_master_id;
            $userDetail->location_master_id = $request->location_master_id;
            $userDetail->hod_id = $request->hod_id;
            $userDetail->policy_setting_id = $request->policy_setting_id;
            $userDetail->state_id = $request->state_id;
            $userDetail->city_id = $request->city_id;
            $userDetail->save();

            // **Commit the transaction if everything is fine**
            DB::commit();

            return redirect()->route('users.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            // **Rollback transaction if something goes wrong**
            DB::rollback();
            return back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }




   
    public function update(Request $request, $id)
    {
        // **Validation Section for User and UserDetails**
        $validated = $request->validate([
            // User fields
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id, // Ignore email uniqueness for the current user
            'password' => 'nullable|same:confirm-password', // Password is nullable and must match confirm password if provided
            'roles' => 'required',
            'status' => 'required',

            // UserDetails fields
            'designation_id' => 'required|integer|exists:designations,id',
            'divison_master_id' => 'required|integer|exists:divison_master,id',
            'company_master_id' => 'required|integer|exists:company_master,id',
            'location_master_id' => 'required|integer|exists:location_master,id',
            'hod_id' => 'required|integer|exists:users,id',
            'policy_setting_id' => 'required|integer|exists:policy_settings,id',
            'city_id' => 'required|integer|exists:cities,id',
            'state_id' => 'required|integer|exists:states,id',
        ]);

        // **Prepare User Input for Updating**
        $input = $request->only(['name', 'email', 'password', 'status', 'roles']);

        // **Handle password update**: Only hash if the password is provided
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            // Remove password from the input if it is not provided
            $input = Arr::except($input, ['password']);
        }

        // **Find the user and update basic user fields**
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // **Update User Information**
        $user->update($input);

        // **Sync User Roles**: Sync the roles (ensure roles are updated correctly)
        $user->syncRoles($request->input('roles'));

        // **Prepare UserDetails Input for Updating**
        $userDetailsInput = $request->only([
            'designation_id',
            'divison_master_id',
            'company_master_id', 
            'location_master_id', 
            'hod_id', 
            'policy_setting_id', 
            'city_id', 
            'state_id'
        ]);

        // **Update UserDetails**
        $userDetail = $user->userDetail;

        if ($userDetail) {
            $userDetail->update($userDetailsInput);
        } else {
            // If the user details don't exist, you can create them
            $userDetail = new UserDetails($userDetailsInput);
            $user->userDetail()->save($userDetail);
        }

        // **Redirect back with success message**
        return redirect()->route('users.index')
                         ->with('success', 'User updated successfully');
    }


    
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    
    public function show($id)
    {
        $user = User::with([
            'userDetail.designation',
            'userDetail.companyMaster',
            'userDetail.state',
            'userDetail.city',
            'userDetail.locationMaster',
            'userDetail.hod',
            'userDetail.policySetting',
        ])->find($id);

        if ($user) {
            // Status mapping
            $statusMapping = [
                1 => 'Active',
                2 => 'Inactive',
                3 => 'Banned',
                0 => 'Undefined'
            ];


            return response()->json([
                
                'name' => $user->name,
                'emails' => $user->email,
                'designation' => $user->userDetail->designation->name ?? 'N/A',
                'statuss' => $user->status, // Default to 0 if status doesn't match
                'employee_id' => $user->userDetail->employee_id,
                'state' => $user->userDetail->state->name ?? 'N/A',
                'city' => $user->userDetail->city->name ?? 'N/A',
                'company_name' => $user->userDetail->companyMaster->company_name ?? 'N/A',
                'working_location' => $user->userDetail->locationMaster->working_location ?? 'N/A',
                'hod_name' => $user->userDetail->hod->name ?? 'N/A',
                'roles' => $user->roles->pluck('name')->join(', ') ?? 'N/A',
                'policy_applicable' => $user->userDetail->policySetting->policy_name ?? 'N/A',
            ]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }



    public function download()
    {
        $filePath = public_path('assets/user_sample.xlsx');  // Path to the file inside the assets folder
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }

    public function upload(Request $request)
    {
        // Validate the file input
        $request->validate([
            'fileUpload' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('fileUpload');

        try {
            // Read the file content into an array (First sheet)
            $usersData = Excel::toArray([], $file)[0]; // [0] to get the first sheet

            // Separate the first row as headers and the rest as data rows
            $headers = $usersData[0];
            $dataRows = array_slice($usersData, 1);

            // Map the data using headers
            $mappedData = [];
            foreach ($dataRows as $row) {
                $mappedRow = [];
                foreach ($headers as $index => $header) {
                    $mappedRow[$header] = $row[$index] ?? null;
                }
                $mappedData[] = $mappedRow;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error reading Excel file: ' . $e->getMessage()], 500);
        }

        DB::beginTransaction();
        try {
            foreach ($mappedData as $row) {
                // Check if required fields are present
                if (isset($row['Name'], $row['Email'], $row['Password'], $row['Confirm-Password'], $row['Designation'], $row['Status'], $row['Divison'], $row['Company Name'], $row['State'], $row['City'], $row['Working Location'], $row['Role'], $row['HOD Name'], $row['Policy Applicable'])) {
                    // Retrieve values and trim spaces
                    $name = trim($row['Name']);
                    $email = trim($row['Email']);
                    $password = trim($row['Password']);
                    $confirmPassword = trim($row['Confirm-Password']);
                    $roleName = trim($row['Role']);

                    // Ensure passwords match
                    if ($password !== $confirmPassword) {
                        Log::error("Passwords do not match for email: $email");
                        continue;
                    }

                    // Find or validate related entities
                    $designation = Designation::where(DB::raw('LOWER(name)'), strtolower(trim($row['Designation'])))->first();
                    $division = DivisonMaster::where(DB::raw('LOWER(name)'), strtolower(trim($row['Divison'])))->first();
                    $company = CompanyMaster::where(DB::raw('LOWER(company_name)'), strtolower(trim($row['Company Name'])))->first();
                    $state = State::where(DB::raw('LOWER(name)'), strtolower(trim($row['State'])))->first();
                    $city = City::where(DB::raw('LOWER(name)'), strtolower(trim($row['City'])))->first();
                    $workingLocation = LocationMaster::where(DB::raw('LOWER(working_location)'), strtolower(trim($row['Working Location'])))->first();
                    $hodName = Hod::where(DB::raw('LOWER(name)'), strtolower(trim($row['HOD Name'])))->first();
                    $policyApplicable = PolicySettings::where(DB::raw('LOWER(policy_name)'), strtolower(trim($row['Policy Applicable'])))->first();
                    $role = Role::where(DB::raw('LOWER(name)'), strtolower($roleName))->first();

                    // Determine status
                    $status = match (strtolower(trim($row['Status']))) {
                        'active' => 1,
                        'inactive' => 2,
                        'banned' => 3,
                        default => null,
                    };

                    // Ensure all required entities exist
                    if ($status && $designation && $division && $company && $state && $city && $workingLocation && $hodName && $policyApplicable && $role) {
                        // Check if the email already exists
                        if (User::where('email', $email)->exists()) {
                            Log::error("Email already exists: $email");
                            continue;
                        }

                        // Create user
                        $user = User::create([
                            'name' => $name,
                            'email' => $email,
                            'password' => Hash::make($password),
                            'status' => $status,
                        ]);

                        // Assign role to the user
                        $user->assignRole($role);

                        // Create user details
                        UserDetails::create([
                            'user_id' => $user->id,
                            'state_id' => $state->id,
                            'city_id' => $city->id,
                            'designation_id' => $designation->id,
                            'company_master_id' => $company->id,
                            'location_master_id' => $workingLocation->id,
                            'hod_id' => $hodName->id,
                            'policy_setting_id' => $policyApplicable->id,
                            'divison_master_id' => $division->id,
                        ]);
                    } else {
                        Log::error('One or more required entities not found or invalid status for email: ' . $email);
                        // session()->flash('error', 'Something went wrong. Please check all your data in the Excel file. If the same data does not exist in the master, it may cause an issue.');
                        // session()->save(); // Explicitly save the session
                        // // dd(session()->all());
                        // return back();

                        // return redirect()->route('users.index')->with('errors', 'Something went wrong. Please check all your data in the Excel file. If the same data not exists in the master, it may cause an issue.');
                        return redirect()->route('users.index')->with('success', 'Something went wrong. Please check all your data in the Excel file. If the same data does not exist in the master, it may cause an issue.');

                    }
                } else {
                    Log::error('Missing required fields in row: ' . json_encode($row));
                }
            }

            DB::commit();
            return redirect()->route('users.index')->with('success', 'Users created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to upload Users: ' . $e->getMessage());
        }
    }

}