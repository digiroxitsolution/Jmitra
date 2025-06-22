<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolicySettings;
use App\Models\Designation;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class PolicySettinigsController extends Controller
{
    public function index()
    {
        $title = "Policy Settings Master";

        $policySettings = PolicySettings::all(); // Adjust relations as needed
        $allDesignations = Designation::all();
        return view('dash.PolicySettings.index', compact('title', 'policySettings', 'allDesignations'));
    }

    public function show($id)
    {
        $policy = PolicySettings::findOrFail($id);

        return response()->json([
            'policy_ids' => $policy->policy_id,
            'policy_names' => $policy->policy_name,
            'designations' => $policy->designation, // Ensure you have the relation loaded if needed
            'location_das' => $policy->location_da,
            'ex_location_das' => $policy->ex_location_da,
            'outstation_das' => $policy->outstation_da,
            'intercity_travel_ex_locations' => $policy->intercity_travel_ex_location,
            'intercity_travel_outstations' => $policy->intercity_travel_outstation,
            'two_wheelers_charges' => $policy->two_wheelers_charges,
            'four_wheelers_charges' => $policy->four_wheelers_charges,            
            'others' => $policy->other
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'policy_id' => 'required|unique:policy_settings,policy_id|max:255',
            'policy_name' => 'required|max:255|unique:policy_settings,policy_name',
            'designation_id' => 'required|exists:designations,id',
            'location_da' => 'required',
            'ex_location_da' => 'required',
            'outstation_da' => 'required',
            'intercity_travel_ex_location' => 'required',
            'intercity_travel_outstation' => 'required',
            'other' => 'nullable|string|max:255',
            'two_wheelers_charges' => 'nullable|numeric|required_without:four_wheelers_charges',
            'four_wheelers_charges' => 'nullable|numeric|required_without:two_wheelers_charges',
            'effective_date' => 'required|date',
        ]);

        // Generate a unique policy_id (e.g., using UUID or custom format)
        // Generate a policy ID
        $prefix = 'POL-';
        $randomNumber = mt_rand(1000, 9999); // Generates a random 4-digit number
        $lastPolicy = PolicySettings::orderBy('id', 'desc')->first(); // Get the last record
        $serialNumber = $lastPolicy ? ((int)substr($lastPolicy->policy_id, -4)) + 1 : 1; // Increment serial number
        $serialNumber = str_pad($serialNumber, 4, '0', STR_PAD_LEFT); // Ensure serial number is 4 digits

        $policy_id = $prefix . $randomNumber . $serialNumber;

        // Create the policy settings record
        $policySetting = new PolicySettings();
        $policySetting->policy_id = $policy_id;
        $policySetting->policy_name = $validated['policy_name'];
        $policySetting->designation_id = $validated['designation_id'];
        $policySetting->location_da = $validated['location_da'];
        $policySetting->ex_location_da = $validated['ex_location_da'];
        $policySetting->outstation_da = $validated['outstation_da'];
        $policySetting->intercity_travel_ex_location = $validated['intercity_travel_ex_location'];
        $policySetting->intercity_travel_outstation = $validated['intercity_travel_outstation'];
        $policySetting->other = $validated['other'] ?? null; // Handle nullable field
        $policySetting->two_wheelers_charges = $validated['two_wheelers_charges'];
        $policySetting->four_wheelers_charges = $validated['four_wheelers_charges'];

        // $policySetting->expense_submision_date = $validated['expense_submision_date'];
        // $policySetting->approved_submission_date = $validated['approved_submission_date'];
        $policySetting->effective_date = $validated['effective_date'];

        // Save the record to the database
        $policySetting->save();
        return redirect()->back()->with('success', 'Policy Setting Added Successfully');
    }

    public function update(Request $request, $id)
    {
        $policy = PolicySettings::findOrFail($id);
        $validated = $request->validate([
            // 'policy_name' => 'required|max:255|unique:policy_settings,policy_name',
            'policy_name' => [
                'required',
                'max:255',
                Rule::unique('policy_settings', 'policy_name')->ignore($id), // Ensure unique while ignoring the current ID
            ],
            'designation_id' => 'required|exists:designations,id',
            'location_da' => 'required',
            'ex_location_da' => 'required',
            'outstation_da' => 'required',
            'intercity_travel_ex_location' => 'required',
            'intercity_travel_outstation' => 'required',
            'other' => 'nullable|string|max:255',
            'two_wheelers_charges' => 'nullable|numeric|required_without:four_wheelers_charges',
            'four_wheelers_charges' => 'nullable|numeric|required_without:two_wheelers_charges',
            'effective_date' => 'required|date',
        ]);

        // Assuming $validated contains all the validated data

            $policy->policy_name = $validated['policy_name'];
            $policy->designation_id = $validated['designation_id'];
            $policy->location_da = $validated['location_da'];
            $policy->ex_location_da = $validated['ex_location_da'];
            $policy->outstation_da = $validated['outstation_da'];
            $policy->intercity_travel_ex_location = $validated['intercity_travel_ex_location'];
            $policy->intercity_travel_outstation = $validated['intercity_travel_outstation'];
            $policy->other = $validated['other'];
            $policy->two_wheelers_charges = $validated['two_wheelers_charges'];
            $policy->four_wheelers_charges = $validated['four_wheelers_charges'];       
            $policy->effective_date = $validated['effective_date'];

            

            // Save the updated policy
            $policy->save();
        return redirect()->back()->with('success', 'Policy Setting Updated Successfully');
    }

    public function destroy($id)
    {
        $policy = PolicySettings::findOrFail($id);
        $policy->delete();
        return redirect()->back()->with('success', 'Policy Setting Deleted Successfully');
    }
}
