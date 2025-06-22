<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolicyGuideliness;
use App\Models\PolicySettings;
use App\Http\Controllers\FileController;
use Illuminate\Validation\Rule;
class PolicyGuidelinesController extends Controller
{
    public function index()
    {   
        $title = "Policy Guideliness Master";

        $policyGuidelines = PolicyGuideliness::all(); // Adjust relations as needed
        $policySettings = PolicySettings::all();
        
        return view('dash.PolicyGuidelines.index', compact('title', 'policyGuidelines', 'policySettings'));
    }

    public function show($id)
    {
        $guideline = PolicyGuideliness::findOrFail($id);

        return response()->json([
            'policy_setting_id' => $guideline->policy_setting_id, // Corrected field
            'policy_descriptions' => $guideline->policy_description,
            'file_names' => $guideline->file_name,
            'uploaded_files' => $guideline->uploaded_file
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'policy_setting_id' => 'required|exists:policy_settings,id',
            'file_name' => 'required|string|max:255',
            'policy_description' => 'nullable|string|max:65535',
            'uploaded_file' => 'required|file|mimes:pdf,docx,doc,jpg,png|max:10240', // Add file validation rules
        ]);

        // Create the policy guideline record
        $policyGuidelines = new PolicyGuideliness();
        $policyGuidelines->policy_setting_id = $validated['policy_setting_id'];
        $policyGuidelines->file_name = $validated['file_name'];
        $policyGuidelines->policy_description = $validated['policy_description'] ?? null;

        // Call the FileController to handle file upload and get the file path
        $fileController = new FileController();
        $uploadedFilePath = $fileController->uploadAndRenameFile($request);  // Pass the request to the method

        // Save the file path to the database
        $policyGuidelines->uploaded_file = $uploadedFilePath;

        // Save the record to the database
        $policyGuidelines->save();

        return redirect()->back()->with('success', 'Policy Guideline Added Successfully');
    }

    public function update(Request $request, $id)
    {
        // Fetch the existing policy guideline by ID
        $policyGuidelines = PolicyGuideliness::find($id);
        
        // Check if the record exists
        if (!$policyGuidelines) {
            return redirect()->back()->with('error', 'Policy Guideline not found.');
        }
        

        $validated = $request->validate([
            'policy_setting_id' => 'required|exists:policy_settings,id',
            'file_name' => 'required|string|max:255',
            'policy_description' => 'nullable|string|max:65535',            
            'uploaded_file' => 'nullable|file|mimes:pdf,docx,doc,jpg,png|max:10240',
        ]);

        // Assuming $validated contains all the validated data

            $policyGuidelines->policy_setting_id = $validated['policy_setting_id'];
            $policyGuidelines->file_name = $validated['file_name'];
            $policyGuidelines->policy_description = $validated['policy_description'];


            if ($request->hasFile('uploaded_file')) {
                // Call the FileController to handle file upload and get the file path
                $fileController = new FileController();
                $uploadedFilePath = $fileController->uploadAndRenameFile($request);  // Pass the uploaded file to the method

                // Save the file path to the database
                $policyGuidelines->uploaded_file = $uploadedFilePath;
            } else {
                // If no file is uploaded, keep the existing file (if any)
                $policyGuidelines->uploaded_file = $policyGuidelines->uploaded_file ?? null;
            }

            
            

            // Save the updated policy
            $policyGuidelines->save();
        return redirect()->back()->with('success', 'Policy Guideline Updated Successfully');
    }

    public function destroy($id)
    {
        $guideline = PolicyGuideliness::findOrFail($id);
        $guideline->delete();
        return redirect()->back()->with('success', 'Policy Guideline Deleted Successfully');
    }
    
    
}
