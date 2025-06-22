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
use Illuminate\Validation\Rule;
class CityController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {   $title = "Cities";
        $states = State::all();
        $cities = City::all();

        return view('City.index', compact('title', 'states', 'cities'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('City.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cities,name',
            'state_id' => 'required|exists:states,id',
        ]);

        // Split the comma-separated city names into an array
        $cityNames = array_map('trim', explode(',', $validated['name']));

        // Prepare the data for insertion
        $cities = [];
        foreach ($cityNames as $name) {
            $cities[] = [
                'name' => $name,
                'state_id' => $validated['state_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the cities into the database
        City::insert($cities);

        return response()->json([
            'success' => true,
            'message' => count($cities) . ' cities added successfully!',
            'data' => $cities,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // 'name' => 'required|string|max:255|unique:cities,name',
            'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('cities', 'name')->ignore($id),
                ],
            'state_id' => 'required|exists:states,id',
        ]);

        $city = City::findOrFail($id);
        $city->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'City updated successfully!',
            'data' => $city,
        ]);
    }

    public function show($id)
    {
        $city = City::with('state')->findOrFail($id);

        return response()->json($city);
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $city = City::findOrFail($id); // Get the city being edited
        $states = State::all(); // Get all states

        // Pass both the states and the selected state id for the city being edited
        return view('City.edit', compact('city', 'states'));
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('city.index')->with('success', 'City deleted successfully!');
    }

}
