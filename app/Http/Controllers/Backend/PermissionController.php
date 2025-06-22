<?php


namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use App\Models\User;
use App\Models\Career;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Todo;

use App\Admin\Notification;
use App\Admin\Session;
use App\Admin\Websitesetting;


class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         // $this->middleware('permission:permissions-list');
         // $this->middleware('permission:users-show', ['only' => ['show']]);
         // $this->middleware('permission:permissions-create', ['only' => ['create','store']]);
         // $this->middleware('permission:permissions-edit', ['only' => ['edit','update']]);
         // $this->middleware('permission:permissions-delete', ['only' => ['destroy']]);
         
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     $permissions = Permission::orderBy('id','DESC')->paginate(15);

    //     return view('dash.permissions.index', compact('permissions'))->with('i', ($request->input('page', 1) - 1) * 5);
    // }

    public function index(Request $request)
    {
        $title = "Permission Master";

        $permissions = Permission::orderBy('id','DESC')->get();

        return view('dash.permissions.index', compact('title', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $role = Role::get();

    //     return view('dash.permissions.create')->with('role', $role);
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'name' => 'required|unique:permissions,name',

    //         'role' => 'required',
    //     ]);

        
    //     $permission = Permission::create(['name' => $request->input('name')]);
    //     $permission->syncRoles($request->input('role'));

        
    //     return redirect()->route('permissions.index')
    //                     ->with('success','Permission '. $permission->name.' added successfully');
         
        

        
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     $permission = Permission::find($id);
    //     $rolePermissions = Role::join("role_has_permissions","role_has_permissions.role_id","=","roles.id")
    //         ->where("role_has_permissions.permission_id",$id)
    //         ->get();


    //     return view('dash.permissions.show',compact('permission','rolePermissions'));
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     $permission = Permission::find($id);
    //     $role = Role::get();
    //     $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.permission_id",$id)
    //         ->pluck('role_has_permissions.role_id','role_has_permissions.role_id')
    //         ->all();
       
        
    //     return view('dash.permissions.edit',compact('role','permission','rolePermissions'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
    //         'name' => 'required',
            
    //         'role' => 'required',
    //     ]);


    //     $permission = Permission::find($id);
    //     $permission->name = $request->input('name');
        
    //     $permission->save();


    //     $permission->syncRoles($request->input('role'));



    //     return redirect()->route('permissions.index')
    //                     ->with('success','Permission'. $permission->name.' updated successfully');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     $permission = Permission::findOrFail($id);
        
    //     if ($permission->name == "Administer roles & permissions") {
    //         return redirect()->route('permissions.index')
    //         ->with('message',
    //          'Cannot delete this Permission!');
    //     }
        
    //     $permission->delete();

    //     return redirect()->route('permissions.index')
    //         ->with('flash_message',
    //          'Permission deleted!');
    // }
}
