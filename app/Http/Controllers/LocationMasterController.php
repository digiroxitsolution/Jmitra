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
use App\Models\State;
use App\Models\City;
use App\Models\LocationMaster;

class LocationMasterController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {   
        $title = "Location Master";

        $states = State::all();
        $cities = City::all();
        $locations = LocationMaster::all();
        return view('dash.LocationMaster.index', compact('title', 'states', 'cities', 'locations'));
    }

    public function create()
    {
        $states = State::all(); // Get all states
        $cities = City::all();
        return view('dash.LocationMaster.create', compact('states', 'cities'));
    }

    // public function edit($id)
    // {
    //     $locations = LocationMaster::findOrFail($id); // Fetch the specific location
    //     $states = State::all(); // Get all states
    //     $cities = City::where('state_id', $locations->city->state_id)->get(); // Get cities based on the location's state
    //     return view('dash.LocationMaster.edit', compact('locations', 'states', 'cities'));
    // }
    // public function edit($id)
    // {
    //     try {
    //         $location = Location::with('city.state')->findOrFail($id);
    //         return response()->json([
    //             'state_id' => $location->city->state->id,
    //             'city_id' => $location->city->id,
    //             'cities' => $location->city->state->cities, // Fetch all cities for the state
    //             'working_location' => $location->working_location,
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Error fetching location details: ' . $e->getMessage());
    //         return response()->json(['error' => 'Unable to fetch location details'], 500);
    //     }
    // }
    public function edit($id)
    {
        $states = State::all(); // Get all states
        $cities = City::all();
        $locations = LocationMaster::findOrFail($id);
        return view('dash.LocationMaster.edit', compact('states', 'cities', 'locations'));
        
    }
    public function store(Request $request)
    {
        

        try {
            // Validate incoming request data
            $request->validate([
                
                'city_id' => 'required|exists:cities,id',
                'state_id' => 'required|exists:states,id',
                'working_location' => 'required|string|max:255|unique:location_master,working_location',
            ]);
            // Create and store the location
            $location = LocationMaster::create([
                
                'city_id' => $request->city_id,
                'state_id' => $request->state_id,
                'working_location' => $request->working_location,
            ]);

            return response()->json(['message' => 'Location added successfully!']);
        } catch (\Exception $e) {
            // Handle any errors that occur during the creation process
            return response()->json(['error' => 'Error creating location master: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validate([
                // 'working_location' => 'required|string|max:255|unique:location_master,working_location',
                'working_location' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('location_master', 'working_location')->ignore($id),
                ],
                'city_id' => 'required|exists:cities,id',
                'state_id' => 'required|exists:states,id',
            ]);

            $locations = LocationMaster::findOrFail($id);
            $locations->update($validated);
            // **Commit the transaction if everything is fine**
            DB::commit();

            return redirect()->route('location_master.index')->with('success', 'Location updated successfully!');
        } catch (\Exception $e) {
            // **Rollback transaction if something goes wrong**
            DB::rollback();
            return back()->with('error', 'Failed to update location: ' . $e->getMessage());
        }

        // return response()->json(['message' => 'Location updated successfully!']);

            
        // } catch (\Exception $e) {
        //     // Handle any errors that occur during the creation process
        //     return response()->json(['error' => 'Error updating location master: ' . $e->getMessage()]);
        // }
    }
    

    

    public function show($id)
    {
        $location = LocationMaster::with('city.state')->findOrFail($id); // Include city and state
        $cities = City::where('state_id', $location->state_id)->get(); // Fetch cities based on the state

        return response()->json([
            'state_id' => $location->state_id,
            'city_id' => $location->city_id,
            'working_location' => $location->working_location,
            'cities' => $cities, // Return cities as well
        ]);
    }





    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $locations = LocationMaster::findOrFail($id);
        $locations->delete();

        return redirect()->route('location_master.index')->with('success', 'Location deleted successfully!');
    }
}
