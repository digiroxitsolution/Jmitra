<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use DB;
use Hash;
use App\Models\User;
use App\Models\DivisonMaster;
use Illuminate\Validation\Rule;

class DivisonMasterController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "Divison Master";

        $divions = DivisonMaster::all();
        return view('dash.DivisonMaster.index', compact('title', 'divions'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.DivisonMaster.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'name' => 'required|string|max:255|unique:divison_master,name',
                
            ]);

            // Create a new divison
            DivisonMaster::create([
                'name' => $request->name,
                
            ]);

            return response()->json(['message' => 'Divison Master created successfully!']);
        } catch (\Exception $e) {
            \Log::error("Error creating divison: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Display the specified resource.
    public function show($id)
    {
        $divions = DivisonMaster::find($id);

        if ($divions) {
            return response()->json($divions);
        } else {
            return response()->json(['error' => 'Divison Master not found'], 404);
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $divions = DivisonMaster::findOrFail($id);
        return view('dash.DivisonMaster.edit', compact('divions'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        try {
            $divions = DivisonMaster::find($id);

            if (!$divions) {
                return response()->json(['error' => 'Divison Master not found'], 404);
            }

            // Validate incoming request
            $request->validate([
                // 'name' => 'required|string|max:255|unique:divison_master,name',
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('divison_master', 'name')->ignore($id),
                ],
                
            ]);

            // Update divions details
            $divions->update([
                'name' => $request->name,
                
            ]);

            return response()->json(['message' => 'Divison Master updated successfully']);
        } catch (\Exception $e) {
            \Log::error("Error updating divions: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $divions = DivisonMaster::findOrFail($id);
        $divions->delete();

        return redirect()->route('divison_master.index')->with('success', 'Divison Master deleted successfully!');
    }
}
