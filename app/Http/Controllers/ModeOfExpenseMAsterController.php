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
use App\Models\ModeofExpenseMaster;

class ModeOfExpenseMAsterController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $title = "Mode Of Expense Master";

        $mode_of_expenses = ModeofExpenseMaster::all();
        return view('dash.ModeofExpenseMaster.index', compact('title', 'mode_of_expenses'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('dash.ModeofExpenseMaster.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        try {
            $request->validate([
                'mode_expense' => 'required|string|max:255|unique:mode_of_expense_master,mode_expense',
            ]);
            $mode_expenses = ModeofExpenseMaster::create([
                'mode_expense' => $request->mode_expense,
            ]);

            return response()->json([
                'message' => 'Mode Of Expenses created successfully!',
                'data' => $mode_expenses,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Display the specified resource.
    public function show($id)
    {
        try {
            $mode_expenses = ModeofExpenseMaster::findOrFail($id);
            return response()->json($mode_expenses);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Mode Of Expenses not found'], 404);
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $mode_expenses = ModeofExpenseMaster::findOrFail($id);
        return view('dash.ModeofExpenseMaster.edit', compact('mode_expenses'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                // 'mode_expense' => 'required|string|max:255|unique:mode_of_expense_master,mode_expense',  // Adjust validation as per your requirements
                'mode_expense' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('mode_of_expense_master', 'mode_expense')->ignore($id),
                ],
            ]);
            $mode_expenses = ModeofExpenseMaster::findOrFail($id);
            $mode_expenses->update($validated);

            return response()->json([
                'message' => 'Mode Of Expenses updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $mode_expenses = ModeofExpenseMaster::findOrFail($id);
        $mode_expenses->delete();

        return redirect()->route('mode_of_expense_master.index')->with('success', 'Mode Of Expenses  deleted successfully!');
    }
}
