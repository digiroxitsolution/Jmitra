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
use App\Models\OtherExpenseMaster;
use Illuminate\Validation\Rule;


class OtherExpenseMAsterController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "Other Expense Master";

        $other_expenses = OtherExpenseMaster::all();
        return view('dash.OtherExpenseMAster.index', compact('title', 'other_expenses'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.OtherExpenseMAster.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        try {
            $request->validate([
                'other_expense' => 'required|string|max:255|unique:other_expense_master,other_expense',
            ]);
            $other_expenses = OtherExpenseMaster::create([
                'other_expense' => $request->other_expense,
            ]);

            return response()->json([
                'message' => 'Other Expenses Created created successfully!',
                'data' => $other_expenses,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create Other Expenses '. $e->getMessage(),
            ], 500);
        }
    }


    // Display the specified resource.
    public function show($id)
    {
        try {
            $other_expenses = OtherExpenseMaster::findOrFail($id);
            return response()->json($other_expenses);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Other Expenses not found'], 404);
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $other_expenses = OtherExpenseMaster::findOrFail($id);
        return view('dash.OtherExpenseMAster.edit', compact('other_expenses'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        
        try {
            $validated = $request->validate([
                // 'other_expense' => 'required|string|max:255|unique:other_expense_master,other_expense',  

                'other_expense' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('other_expense_master', 'other_expense')->ignore($id),
                ],
            ]);

            $other_expenses = OtherExpenseMaster::findOrFail($id);
            $other_expenses->update($validated);

            return response()->json([
                'message' => 'Other Expenses updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create Other Expenses: '. $e->getMessage(),
            ], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $other_expenses = OtherExpenseMaster::findOrFail($id);
        $other_expenses->delete();

        return redirect()->route('other_expense_master.index')->with('success', 'Other Expenses  deleted successfully!');
    }
}
