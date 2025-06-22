<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class DesignationController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "Designation Master";

        $designations = Designation::all();
        return view('dash.designation.index', compact('title', 'designations'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.designation.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // Validate the request
        

        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:designations,name',
                // 'description' => 'nullable|max:255',
                

            ]);
            
            // Save data to the database
            $designation = Designation::create([
                'name' => $request->name,
                // 'description' => $request->description,
                
            ]);

            // Return JSON response
            return response()->json([
                'message' => 'Designation created successfully!',
                'data' => $designation,
            ], 201); // Status code 201: Created
        
        } catch (\Exception $e) {
            \Log::error("Failed to create Designation!: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        $designation = Designation::find($id);
        if ($designation) {
            return response()->json($designation);
        }
        return response()->json(['error' => 'Designation not found'], 404);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                // 'name' => 'required|string|max:255|unique:designations,name',
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('designations', 'name')->ignore($id),
                ],
                // 'description' => 'nullable',
                
            ]);

            $designation = Designation::findOrFail($id);
            $designation->update($request->only('name'));

            return response()->json(['message' => 'Designation updated successfully'], 200);
        } catch (\Exception $e) {
            \Log::error("Failed to update Designation!: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        return view('dash.designation.edit', compact('designation'));
    }



    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        return redirect()->route('designation.index')->with('success', 'Designation  deleted successfully!');
    }
}
