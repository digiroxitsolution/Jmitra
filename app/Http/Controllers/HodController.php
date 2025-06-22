<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hod;
use Illuminate\Validation\Rule;
class HodController extends Controller
{
    
    // Display a listing of the resource.
    public function index()
    {
        $title = "HOD Master";

        $hods = Hod::all();
        return view('dash.hods.index', compact('title', 'hods'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.hods.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        

        try {
            // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:hods,name',
            // 'email' => 'nullable|max:255',
            // 'department' => 'nullable|string|max:255',

        ]);
            // Save data to the database
            $hod = Hod::create([
                'name' => $request->name,
                // 'email' => $request->email,
                // 'department' => $request->department,
                // 'remarks' => $request->remarks,
            ]);

            // Return JSON response
            return response()->json([
                'message' => 'Hod created successfully!',
                'data' => $hod,
            ], 201); // Status code 201: Created
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        $hod = Hod::find($id);
        if ($hod) {
            return response()->json($hod);
        }
        return response()->json(['error' => 'Hod not found'], 404);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                // 'name' => 'required|string|max:255|unique:hods,name',
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('hods', 'name')->ignore($id),
                ],
                // 'email' => 'nullable',
                // 'department' => 'nullable|string|max:255',
            ]);

            $hod = Hod::findOrFail($id);
            $hod->update($request->only('name'));

            return response()->json(['message' => 'Hod updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $hods = Hod::findOrFail($id);
        return view('dash.hods.edit', compact('hods'));
    }



    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $rejection = Hod::findOrFail($id);
        $rejection->delete();

        return redirect()->route('hods.index')->with('success', 'Hod  deleted successfully!');
    }
}
