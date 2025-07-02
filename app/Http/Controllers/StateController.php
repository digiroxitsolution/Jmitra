<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use DB;
use Hash;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\zone;
use Illuminate\Validation\Rule;

class StateController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "State Master";

      $states = State::with('Zone:id,zone_name')->get();
        return view('State.index', compact('title', 'states'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('State.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'state_name' => 'required|string|max:255|unique:states,name',
                'short_name' => 'required|string|max:255|unique:states,short',

            ]);

            // Create a new state
            State::create([
                'name' => $request->state_name,
                'short' => $request->short_name,

            ]);

            return response()->json(['message' => 'State Master created successfully!']);
        } catch (\Exception $e) {
            \Log::error("Error creating state: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Display the specified resource.
    public function show($id)
    {
        $states = State::find($id);

        if ($states) {
            return response()->json($states);
        } else {
            return response()->json(['error' => 'State not found'], 404);
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $states = State::findOrFail($id);
        return view('State.edit', compact('states'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        try {
            $states = State::find($id);

            if (!$states) {
                return response()->json(['error' => 'State not found'], 404);
            }

            // Validate incoming request
            $request->validate([

                // 'name' => 'required|string|max:255|unique:states,name',
                // 'short' => 'required|string|max:255|unique:states,short',
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('states', 'name')->ignore($id),
                ],
                'short' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('states', 'short')->ignore($id),
                ],


            ]);

            // Update states details
            $states->update([
                'name' => $request->name,
                'short' => $request->short,

            ]);

            return response()->json(['message' => 'State updated successfully']);
        } catch (\Exception $e) {
            \Log::error("Error updating state: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $states = State::findOrFail($id);
        $states->delete();

        return redirect()->route('state.index')->with('success', 'State deleted successfully!');
    }
}
