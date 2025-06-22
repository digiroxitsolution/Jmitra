<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use DB;
use Hash;
use App\Models\User;
use App\Models\CompanyMaster;
use Illuminate\Validation\Rule;
class CompanyMasterController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "Company Master";
        $companies = CompanyMaster::all();
        return view('dash.CompanyMaster.index', compact('title', 'companies'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.CompanyMaster.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'company_name' => 'required|string|max:255|unique:company_master,company_name',
                'location' => 'required|string|max:255',
                'address' => 'required|string|max:255',
            ]);

            // Create a new company
            CompanyMaster::create([
                'company_name' => $request->company_name,
                'location' => $request->location,
                'address' => $request->address,
            ]);

            return response()->json(['message' => 'Company created successfully!']);
        } catch (\Exception $e) {
            \Log::error("Error creating company: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Display the specified resource.
    public function show($id)
    {
        $company = CompanyMaster::find($id);

        if ($company) {
            return response()->json($company);
        } else {
            return response()->json(['error' => 'Company not found'], 404);
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $company = CompanyMaster::findOrFail($id);
        return view('dash.CompanyMaster.edit', compact('company'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        try {
            $company = CompanyMaster::find($id);

            if (!$company) {
                return response()->json(['error' => 'Company not found'], 404);
            }

            // Validate incoming request
            $request->validate([
                // 'company_name' => 'required|string|max:255|unique:company_master,company_name',
                'company_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('company_master', 'company_name')->ignore($id),
                ],
                'location' => 'required|string|max:255',
                'address' => 'required|string|max:255',
            ]);

            // Update company details
            $company->update([
                'company_name' => $request->company_name,
                'location' => $request->location,
                'address' => $request->address,
            ]);

            return response()->json(['message' => 'Company updated successfully']);
        } catch (\Exception $e) {
            \Log::error("Error updating company: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $company = CompanyMaster::findOrFail($id);
        $company->delete();

        return redirect()->route('company_master.index')->with('success', 'Company deleted successfully!');
    }
}
