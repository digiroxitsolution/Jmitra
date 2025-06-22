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
use App\Models\ReOpenMaster;

class ReOpenMasterController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "Re Open Master";

        $reOpenForms = ReOpenMaster::all();
        return view('dash.ReOpenMaster.index', compact('title', 'reOpenForms'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.ReOpenMaster.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'reason_of_re_open' => 'required|string|max:255|unique:re_open_master,reason_of_re_open',
            ]);
            // Save data to the database
            $reOpenForms = ReOpenMaster::create([
                'reason_of_re_open' => $request->reason_of_re_open,
            ]);

            // Return JSON response
            return response()->json([
                'message' => 'Reason Of Re-Open created successfully!',
                'data' => $reOpenForms,
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
            $reOpenForms = ReOpenMaster::findOrFail($id);
            return response()->json($reOpenForms);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Re-Open not found'], 404);
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $reOpenForms = ReOpenMaster::findOrFail($id);
        return view('dash.ReOpenMaster.edit', compact('reOpenForms'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {

        try {
            $validated = $request->validate([
                // 'reason_of_re_open' => 'required|string|max:255|unique:re_open_master,reason_of_re_open',  
                'reason_of_re_open' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('re_open_master', 'reason_of_re_open')->ignore($id),
                ],
            ]);
            $reOpenForms = ReOpenMaster::findOrFail($id);
            $reOpenForms->update($validated);

            return response()->json([
                'message' => 'Reason of Re-Open updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $reOpenForms = ReOpenMaster::findOrFail($id);
        $reOpenForms->delete();

        return redirect()->route('re_open_master.index')->with('success', 'Reason Of Re-Open  deleted successfully!');
    }
}
