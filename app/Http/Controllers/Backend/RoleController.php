<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:roles-list', ['only' => ['index']]);
        // $this->middleware('permission:roles-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:roles-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $title = "Roles Master";

        $roles = Role::orderBy('id', 'ASC')->get();
        return view('dash.roles.index', compact('title', 'roles'));
    }

    public function create()
    {
        $permission = Permission::get();
        return view('dash.roles.create', compact('permission'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
            ->with('success', 'Role ' . $role->name . ' added successfully');
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('dash.roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('dash.roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    // public function update(Request $request, $id)
    // {
    //     // Validate the incoming request
    //     $request->validate([
    //         'name' => 'required|unique:roles,name,' . $id,  // Ensure that role name is unique
    //         'permission' => 'required|array',  // Validate that permissions are an array
    //     ]);

    //     // Fetch the role to be updated
    //     $role = Role::findOrFail($id);
        
    //     // Update role name if needed
    //     $role->name = $request->input('name');
    //     $role->save();

    //     // Sync the selected permissions with the role
    //     $role->syncPermissions($request->input('permission'));

    //     // Redirect with success message
    //     return redirect()->route('roles.index')
    //         ->with('success', 'Role updated successfully');
    // }

    public function update(Request $request, $id)
    {
        // Define the roles whose names should not be updated
        $protectedRoles = ['Sales Admin', 'Super Admin', 'Sales Admin Hod', 'Sales'];

        // Fetch the role to be updated
        $role = Role::findOrFail($id);

        // Check if the role is protected (case-insensitive check)
        $protectedRolesLower = array_map('strtolower', $protectedRoles); // Convert all to lowercase
        $isProtectedRole = in_array(strtolower($role->name), $protectedRolesLower);

        // Validate the incoming request
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,  // Ensure that role name is unique
            'permission' => 'required|array',  // Validate that permissions are an array
        ]);

        // Prevent role name updates for protected roles
        if (!$isProtectedRole) {
            // Update the role name only if it's not protected
            $role->name = $request->input('name');
        }

        // Save the role (this updates the name if allowed or leaves it as is for protected roles)
        $role->save();

        // Sync the selected permissions with the role (allowed for all roles)
        $role->syncPermissions($request->input('permission'));

        // Redirect with success message
        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully (name update restricted for protected roles).');
    }



    public function destroy($id)
    {
        // Define the roles that should not be deleted
        $protectedRoles = ['Sales Admin', 'Super Admin', 'Sales Admin Hod', 'Sales'];

        // Find the role by ID or fail
        $role = Role::findOrFail($id);

        // Check if the role's name is in the protected roles (case-insensitive check)
        $protectedRolesLower = array_map('strtolower', $protectedRoles); // Convert all to lowercase
        if (in_array(strtolower($role->name), $protectedRolesLower)) {
            return redirect()->route('roles.index')
                ->with('error', 'This role cannot be deleted.');
        }

        // If not protected, delete the role
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
