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
use App\Models\ExpenseTypeMaster;


class ExpenseTypeController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "Expense Type Master";

        $expense_type = ExpenseTypeMaster::all();
        return view('dash.ExpenseTypeMaster.index', compact('title', 'expense_type'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.ExpenseTypeMaster.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'expense_type' => 'required|string|max:255|unique:expense_type_master,expense_type',                
            ]);

            // Create a new divison
            ExpenseTypeMaster::create([
                'expense_type' => $request->expense_type,                
            ]);
            return response()->json(['message' => 'Expense Type Master created successfully!']);
        } catch (\Exception $e) {
            \Log::error("Error creating expense type: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Display the specified resource.
    public function show($id)
    {
        $expense_type = ExpenseTypeMaster::find($id);

        if ($expense_type) {
            return response()->json($expense_type);
        } else {
            return response()->json(['error' => 'Expense Type Master not found'], 404);
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $expense_type = ExpenseTypeMaster::findOrFail($id);
        return view('dash.DivisonMaster.edit', compact('expense_type'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        try {
            $expense_type = ExpenseTypeMaster::find($id);

            if (!$expense_type) {
                return response()->json(['error' => 'Expense Type Master not found'], 404);
            }

            // Validate incoming request
            $request->validate([
                // 'expense_type' => 'required|string|max:255|unique:expense_type_master,expense_type',
                'expense_type' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('expense_type_master', 'expense_type')->ignore($id),
                ],                
            ]);

            // Update expense_type details
            $expense_type->update([
                'expense_type' => $request->expense_type,                
            ]);
            return response()->json(['message' => 'Expense Type Master updated successfully']);
        } catch (\Exception $e) {
            \Log::error("Error updating expense_type: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $expense_type = ExpenseTypeMaster::findOrFail($id);
        $expense_type->delete();

        return redirect()->route('expense_type.index')->with('success', 'Expense Type Master deleted successfully!');
    }
}
