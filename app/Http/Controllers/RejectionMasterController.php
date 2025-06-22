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
use App\Models\RejectionMaster;

class RejectionMasterController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "Rejection Master";

        $rejections = RejectionMaster::all();
        return view('dash.RejectionMaster.index', compact('title', 'rejections'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.RejectionMaster.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        

        try {

            // Validate the request
            $request->validate([
                'reason_of_rejection' => 'required|string|max:255|unique:rejection_master,reason_of_rejection',
            ]);
            // Save data to the database
            $rejection = RejectionMaster::create([
                'reason_of_rejection' => $request->reason_of_rejection,
            ]);

            // Return JSON response
            return response()->json([
                'message' => 'Reason Of Rejection created successfully!',
                'data' => $rejection,
            ], 201); // Status code 201: Created
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Display the specified resource.
    public function show($id)
    {
        try {
            $rejection = RejectionMaster::findOrFail($id);
            return response()->json($rejection);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Rejection not found'], 404);
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $rejection = RejectionMaster::findOrFail($id);
        return view('dash.RejectionMaster.edit', compact('rejection'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {

        try {
            $validated = $request->validate([
                // 'reason_of_rejection' => 'required|string|max:255|unique:rejection_master,reason_of_rejection',  // Adjust validation as per your requirements

                'reason_of_rejection' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('rejection_master', 'reason_of_rejection')->ignore($id),
                ],
            ]);
            $rejection = RejectionMaster::findOrFail($id);
            $rejection->update($validated);

            return response()->json([
                'message' => 'Reason of Rejection updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $rejection = RejectionMaster::findOrFail($id);
        $rejection->delete();

        return redirect()->route('rejection_master.index')->with('success', 'Reason Of Rejection  deleted successfully!');
    }
}
