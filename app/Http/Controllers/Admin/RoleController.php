<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Imports\PermissionImport;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller
{
    public function allPermission()
    {
        $permissions = Permission::orderBy('group_name')->get();
        return view('admin.pages.permission.all_permission', compact('permissions'));
    }


    public function addPermission()
    {
        return view('admin.pages.permission.add_permission');
    }
    //End Method

    public function storePermission(Request $request)
    {
        Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
            'guard_name' => 'admin'
        ]);

        $notification = array(
            'message' => 'Permission Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.permission.all')->with($notification);
    }
    //End Method


    public function editPermission($id)
    {
        $permission = Permission::find($id);
        return view('admin.pages.permission.edit_permission', compact('permission'));
    }
    //End Method

    public function updatePermission(Request $request)
    {
        $per_id = $request->id;

        Permission::find($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.permission.all')->with($notification);
    }
    //End Method

    public function deletePermission($id)
    {

        Permission::find($id)->delete();

        $notification = array(
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    //End Method

    public function importPermission()
    {
        return view('admin.pages.permission.import_permission');
    }
    //End Method

    public function export()
    {
        return Excel::download(new PermissionExport, 'permission.xlsx');
    }
    //End Method

    public function import(Request $request)
    {

        Excel::import(new PermissionImport, $request->file('import_file'));

        $notification = array(
            'message' => 'Permission Imported Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    //End Method


    public function allRoles()
    {
        $roles = Role::all();
        return view('admin.pages.role.all_roles', compact('roles'));
    }
    //End Method

    public function addRoles()
    {
        return view('admin.pages.role.add_roles');
    }
    //End Method

    public function storeRoles(Request $request)
    {
        Role::create([
            'name' => $request->name,
            'guard_name' => 'admin'
        ]);

        $notification = array(
            'message' => 'Role Creted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);
    }
    //End Method


    public function editRoles($id)
    {

        $roles = Role::find($id);
        return view('admin.pages.role.edit_roles', compact('roles'));
    }
    //End Method

    public function updateRoles(Request $request)
    {
        $role_id = $request->id;

        Role::find($role_id)->update([
            'name' => $request->name
        ]);

        $notification = array(
            'message' => 'Role Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);
    }
    //End Method

    public function deleteRoles($id)
    {

        Role::find($id)->delete();
        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    //End Method

    //////////////// Add Role Permission All Method /////////

    public function allRolesPermission()
    {
        $roles = Role::with('permissions')->get();

        return view('admin.pages.rolesetup.all_roles_permission', compact('roles'));
    }
    //End Method

    public function addRolesPermission()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = Admin::getpermissionGroups();

        return view('admin.pages.rolesetup.add_roles_permission', compact('roles', 'permissions', 'permission_groups'));
    }
    //End Method


    public function rolePermissionStore(Request $request)
    {

        $data = array();
        $permissions = $request->permission;

        foreach ($permissions as $key => $item) {
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        } //end foreach

        $notification = array(
            'message' => 'Role Permission Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles.permission')->with($notification);
    }
    //End Method

    public function adminEditRoles($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();
        $permission_groups = Admin::getpermissionGroups();

        return view('admin.pages.rolesetup.edit_roles_permission', compact('role', 'permissions', 'permission_groups'));
    }
    //End Method

    public function adminRolesUpdate(Request $request, $id)
    {

        $role = Role::find($id);
        $permissions = $request->permission;

        if (!empty($permissions)) {
            $permissionNames = Permission::whereIn('id', $permissions)->pluck('name')->toArray();
            $role->syncPermissions($permissionNames);
        } else {
            $role->syncPermissions([]);
        }

        $notification = array(
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles.permission')->with($notification);
    }
    //End Method

    public function adminDeleteRoles($id)
    {

        $role = Role::find($id);
        if (!is_null($role)) {
            $role->delete();
        }

        $notification = array(
            'message' => 'Role Permission Delected Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    //End Method


}
