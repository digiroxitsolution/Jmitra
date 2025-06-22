<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Validation\Rule;
use DB;
use Hash;
use App\Models\User;
use App\Models\WayOfLocationMaster;

class WayOfLocationMasterController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "Way Of Location Master";

        $way_of_locations = WayOfLocationMaster::all();
        return view('dash.WayOfLocationMaster.index', compact('title', 'way_of_locations'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.WayOfLocationMaster.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'way_of_location' => 'required|string|max:255|unique:way_of_location_master,way_of_location',
            ]);
            // Save data to the database
            $wayOfLocation = WayOfLocationMaster::create([
                'way_of_location' => $request->way_of_location,
            ]);

            // Return JSON response
            return response()->json([
                'message' => 'Way of Location created successfully!',
                'data' => $wayOfLocation,
            ], 201); // Status code 201: Created
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'error' => 'Error updating Way Of Location: ' . $e->getMessage()
            ], 500);
        }
    }


    // Display the specified resource.
    public function show($id)
    {
        try {
            $way_of_location = WayOfLocationMaster::findOrFail($id);
            return response()->json($way_of_location);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Way Of Location not found'], 404);
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $mode_of_expenses = WayOfLocationMaster::findOrFail($id);
        return view('dash.WayOfLocationMaster.edit', compact('mode_of_expenses'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                // 'way_of_location' => 'required|string|max:255|unique:way_of_location_master,way_of_location',
                'way_of_location' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('way_of_location_master', 'way_of_location')->ignore($id),
                ],
            ]);

            // Find and update the record
            $way_of_location = WayOfLocationMaster::findOrFail($id);
            $way_of_location->update($validated);

            // Return success response for AJAX
            return response()->json([
                'message' => 'Way Of Location updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            // Catch any errors and return them in the response
            return response()->json([
                'error' => 'Error updating Way Of Location: ' . $e->getMessage()
            ], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $mode_of_expenses = WayOfLocationMaster::findOrFail($id);
        $mode_of_expenses->delete();

        return redirect()->route('way_of_location_master.index')->with('success', 'Way Of Location Master of Expense deleted successfully!');
    }
}
