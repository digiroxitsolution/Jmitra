<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use DB;
use Hash;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SalesImport;
use App\Models\SalesMaster;
use App\Models\Sales;
use App\Models\State;
use Illuminate\Support\Facades\Log;



// Excel::import(new SalesImport, public_path('file_path.xlsx'));

class SalesMasterController extends Controller
{
    // Display a listing of the resource.
    public function index()
    { 
        $title = "Sales Master";

        $states = State::all();
        $sales = SalesMaster::all();
        return view('dash.SalesMaster.index', compact('title', 'sales','states'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.SalesMaster.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        

        try {
            // Validate the request
            $validate = $request->validate([
                // 'file_name' => 'required|string|max:255|unique:sales_master,file_name',
                'file_name' => 'required|string|max:255',

                'date_of_upload' => 'required',
                'state_id' => 'required',
            ]);
            // Check if file_name already exists in the database (manual check)
            $existingSales = SalesMaster::where('file_name', $request->file_name)->first();

            if ($existingSales) {
                // Return custom error if file_name already exists
                return response()->json([
                    'error' => 'Duplicate file name detected. Please choose a different file name.',
                ], 400); // Status code 400: Bad Request
            }

            // Save data to the database
            $sales = SalesMaster::create([
                'file_name' => $request->file_name,
                'date_of_upload' => $request->date_of_upload,
                'state_id' => $request->state_id,
            ]);

            // Return JSON response
            return response()->json([
                'message' => 'Sales Master created successfully!',
                'data' => $sales,
            ], 201); // Status code 201: Created
         } catch (\Illuminate\Database\QueryException $e) {
            // Catch the duplicate entry error (SQLSTATE[23000] refers to a unique constraint violation)
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'Duplicate file name detected. Please choose a different file name.');
            }

            // Handle other exceptions
            return redirect()->back()->with('error', 'Failed to create Sales Master! ' . $e->getMessage());
        }
    }


    // Display the specified resource.
    public function show(Request $request, $id)
    {
        // Fetch Sales Master Data
        $salesMaster = SalesMaster::find($id);

        if (!$salesMaster) {
            return back()->with('error', 'Sales Master not found');
        }

        // Fetch Sales Data related to the Sales Master
        $salesData = Sales::where('sales_master_id', $id)->get();
        if ($salesData->isEmpty()) {
            return back()->with('error', 'Sales Data not found');
        }

        // Returning the sales master data along with sales data
        return view('dash.SalesMaster.show', compact('salesData', 'salesMaster'));
    }


    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $sales = SalesMaster::findOrFail($id);
        return view('dash.SalesMaster.edit', compact('sales'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // 'file_name' => 'required|string|max:255',
            'file_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('sales_master', 'file_name')->ignore($id),
                ],
            'date_of_upload' => 'required', // Adjust validation as per your requirements
        ]);

        try {
            $sales = SalesMaster::findOrFail($id);
            $sales->update($validated);

            return response()->json([
                'message' => 'Sales Master updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error updating Sales Master: ' . $e->getMessage()
            ], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        // Find the SalesMaster record by its ID
        $sales_master = SalesMaster::findOrFail($id);
        
        // Find related Sales records using the correct foreign key (e.g., 'sales_master_id')
        $sales = Sales::where('sales_master_id', $id)->get(); // Use 'get()' to retrieve all sales entries
        
        // Delete each Sales record
        foreach ($sales as $sale) {
            $sale->delete(); // Delete each sale
        }

        // Now delete the SalesMaster record
        $sales_master->delete();

        // Redirect back to the sales master index with a success message
        return redirect()->route('sales_master.index')->with('success', 'Sales deleted successfully!');
    }

    

    // public function upload(Request $request)
    // {
    //     // Log request data for debugging
    //     Log::debug('File Upload Request:', $request->all());

    //     // Validate the file and name input
    //     $request->validate([
    //         'fileUpload' => 'required|mimes:xlsx,xls,csv',
    //         'file_name' => 'required|string',
    //     ]);

    //     $file = $request->file('fileUpload'); // Get the uploaded file
    //     $fileName = $request->file_name; // Get file name input

    //     try {
    //         // Read the file content directly without storing
    //         // Read the file content into an array (First sheet)
    //         $salesData = Excel::toArray([], $file)[0]; // [0] to get the first sheet
    //     } catch (\Exception $e) {
    //         Log::error('Error reading Excel file: ' . $e->getMessage());
    //         return response()->json(['error' => 'Error reading Excel file: ' . $e->getMessage()], 500);
    //     }
    //     DB::beginTransaction(); // Start transaction
    //     try {

    //         // Create a SalesMaster record
    //         $salesMaster = SalesMaster::create(['file_name' => $fileName, 'date_of_upload' => now(),]);

            
    //         foreach ($salesData as $row) {
    //             // Log the entire row data for debugging
    //             Log::debug('Row data:', ['row' => $row]);

    //             // Check if the necessary fields exist
    //             if (isset($row['state'], $row['sales_amount'], $row['date_of_sales'])) {
    //                 // Log the state name being searched
    //                 Log::debug('Looking for state:', ['state_name' => $row['state']]);

    //                 // Find the state in the database
    //                 $state = State::where('name', trim($row['state']))->first(); // Trim any spaces

    //                 // Log the found state
    //                 Log::debug('Found state:', ['state' => $state]);

    //                 if ($state) {
    //                     // Create a Sales record
    //                     Sales::create([
    //                         'sales_master_id' => $salesMaster->id,
    //                         'state_id' => $state->id,
    //                         'sales_amount' => $row['sales_amount'],
    //                         'date_of_sales' => Carbon::parse($row['date_of_sales']), // Convert date
    //                     ]);
    //                 } else {
    //                     // Log error if state is not found
    //                     Log::error('State not found for: ' . $row['state']);
    //                 }
    //             } else {
    //                 // Log error if any required field is missing in the row
    //                 Log::error('Missing required fields in row: ' . json_encode($row));
    //             }
    //         }
    //         DB::commit();
    //         return redirect()->route('sales_master.index')->with('success', 'Sales  created successfully!');

    //         // return response()->json(['success' => true, 'message' => 'Sales data processed successfully.']);
    //     } catch (\Exception $e) {
    //         // **Rollback transaction if something goes wrong**
    //         DB::rollback();
    //         return back()->with('error', 'Failed to upload Sales: ' . $e->getMessage());
    //     }
    // }

    // public function upload(Request $request)
    // {
    //     // Log request data for debugging
    //     Log::debug('File Upload Request:', $request->all());

    //     // Validate the file and name input
    //     $request->validate([
    //         'fileUpload' => 'required|mimes:xlsx,xls,csv',
    //         'file_name' => 'required|string',
    //     ]);

    //     $file = $request->file('fileUpload'); // Get the uploaded file
    //     $fileName = $request->file_name; // Get file name input

    //     try {
    //         // Read the file content directly without storing
    //         // Read the file content into an array (First sheet)
    //         $salesData = Excel::toArray([], $file)[0]; // [0] to get the first sheet
    //         return $salesData;
    //     } catch (\Exception $e) {
    //         Log::error('Error reading Excel file: ' . $e->getMessage());
    //         return response()->json(['error' => 'Error reading Excel file: ' . $e->getMessage()], 500);
    //     }
    //     // if($salesData){
    //     //     return redirect()->route('sales_master.index')->with('success', 'Sales successfully!');
    //     // }

    //     DB::beginTransaction(); // Start transaction
    //     try {
    //         // Create a SalesMaster record
    //         $salesMaster = SalesMaster::create(['file_name' => $fileName, 'date_of_upload' => now(),]);

    //         // Log first few rows of data for debugging
    //         Log::debug('Sales Data Preview:', ['salesData' => array_slice($salesData, 0, 5)]);

    //         foreach ($salesData as $row) {
    //             // Log the entire row data for debugging
    //             Log::debug('Row data:', ['row' => $row]);

    //             // Check if the necessary fields exist
    //             if (isset($row['state'], $row['sales_amount'], $row['date_of_sales'])) {
    //                 // Log the state name being searched
    //                 Log::debug('Looking for state:', ['state_name' => $row['state']]);

    //                 // Find the state in the database
    //                 $state = State::where('name', trim($row['state']))->first(); // Trim any spaces

    //                 // Log the found state
    //                 Log::debug('Found state:', ['state' => $state]);

    //                 if ($state) {
    //                     // Create a Sales record
    //                     Sales::create([
    //                         'sales_master_id' => $salesMaster->id,
    //                         'state_id' => $state->id,
    //                         'sales_amount' => $row['sales_amount'],
    //                         'date_of_sales' => Carbon::parse($row['date_of_sales']), // Convert date
    //                     ]);
    //                 } else {
    //                     // Log error if state is not found
    //                     Log::error('State not found for: ' . $row['state']);
    //                 }
    //             } else {
    //                 // Log error if any required field is missing in the row
    //                 Log::error('Missing required fields in row: ' . json_encode($row));
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->route('sales_master.index')->with('success', 'Sales created successfully!');
    //     } catch (\Exception $e) {
    //         // **Rollback transaction if something goes wrong**
    //         DB::rollback();
    //         return back()->with('error', 'Failed to upload Sales: ' . $e->getMessage());
    //     }
    // }
    public function upload(Request $request)
    {
        // Log request data for debugging
        Log::debug('File Upload Request:', $request->all());

        // Validate the file and name input
        $request->validate([
            'fileUpload' => 'required|mimes:xlsx,xls,csv',
            'file_name' => 'required|string|max:255|unique:sales_master,file_name',
        ]);

        $file = $request->file('fileUpload'); // Get the uploaded file
        $fileName = $request->file_name; // Get file name input
        // Check if file_name already exists in the database (manual check)
            $existingSales = SalesMaster::where('file_name', $fileName)->first();

            if ($existingSales) {
                // Return custom error if file_name already exists
                return redirect()->back()->with('error', 'Duplicate file name detected. Please choose a different file name.');
            }

        try {
            // Read the file content directly without storing
            // Read the file content into an array (First sheet)
            $salesData = Excel::toArray([], $file)[0]; // [0] to get the first sheet

            // Separate the first row as headers and the rest as data rows
            $headers = $salesData[0]; // First row is the header
            $dataRows = array_slice($salesData, 1); // All remaining rows are the data

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
            Log::error('Error reading Excel file: ' . $e->getMessage());
            return response()->json(['error' => 'Error reading Excel file: ' . $e->getMessage()], 500);
        }

        // Start transaction
        DB::beginTransaction();
        try {
            // Create a SalesMaster record
            $salesMaster = SalesMaster::create([
                'file_name' => $fileName,
                'date_of_upload' => now(),
            ]);

            // Log first few rows of data for debugging
            Log::debug('Sales Data Preview:', ['salesData' => array_slice($mappedData, 0, 5)]);

            foreach ($mappedData as $row) {
                // Log the entire row data for debugging
                Log::debug('Row data:', ['row' => $row]);

                // Check if the necessary fields exist
                if (isset($row['state'], $row['sales_amount'], $row['date_of_sales'])) {
                    // Log the state name being searched
                    Log::debug('Looking for state:', ['state_name' => $row['state']]);

                    // Find the state in the database
                    $state = State::where('name', trim($row['state']))->first(); // Trim any spaces

                    // Log the found state
                    Log::debug('Found state:', ['state' => $state]);

                    if ($state) {

                        $excelSerialNumber = intval($row['date_of_sales']);
                        $date = date('Y-m-d', ($excelSerialNumber - 25569) * 86400);
                        // Create a Sales record
                        Sales::create([
                            'sales_master_id' => $salesMaster->id,
                            'state_id' => $state->id,
                            'sales_amount' => $row['sales_amount'],
                            'date_of_sales' => $date,
                        ]);
                    } else {
                        // Log error if state is not found
                        Log::error('State not found for: ' . $row['state']);
                    }
                } else {
                    // Log error if any required field is missing in the row
                    Log::error('Missing required fields in row: ' . json_encode($row));
                }
            }

            DB::commit();
            return redirect()->route('sales_master.index')->with('success', 'Sales created successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            // Catch the duplicate entry error (SQLSTATE[23000] refers to a unique constraint violation)
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'Duplicate file name detected. Please choose a different file name.');
            }

            // Handle other exceptions
            return redirect()->back()->with('error', 'Failed to upload Sales: ' . $e->getMessage());
        }

    }



    public function download()
    {
        $filePath = public_path('assets/sales_samples.xlsx');  // Path to the file inside the assets folder
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }





}
