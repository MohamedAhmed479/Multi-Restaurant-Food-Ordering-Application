<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;                        
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ManageAdminController extends Controller
{
    ///////// Admin User All Method ////////
    public function allAdmin()
    {
        $alladmin = Admin::with('roles')->where('id', '!=', Auth::guard('admin')->id())->latest()->get();
        return view('admin.pages.admin.all_admin', compact('alladmin'));
    }
    //End Method

    public function addAdmin()
    {
        $roles = Role::all();
        return view('admin.pages.admin.add_admin', compact('roles'));
    }
    //End Method

    public function adminStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'nullable|exists:roles,id',
        ]);

        try {
            DB::beginTransaction();

            $role = Role::where('id', $request->role)
                ->where('guard_name', 'admin')
                ->first();

            $user = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'role' => $role->name,
                'status' => '1',
            ]);

            if ($role) {
                $user->assignRole($role->name);
            }

            DB::commit();

            return redirect()->route('all.admin')->with([
                'message' => 'New Admin Inserted Successfully',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->wit([
                'message' => 'An error occurred while creating the admin. Please try again.',
                'alert-type' => 'error',
            ]);
        }
    }

    public function editadmin($id)
    {
        $admin = Admin::find($id);
        $roles = Role::all();
        return view('admin.pages.admin.edit_admin', compact('roles', 'admin'));
    }
    //End Method

    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'nullable|exists:roles,id',
        ]);

        try {
            DB::beginTransaction();

            $user = Admin::findOrFail($id);

            $role = Role::where('id', $request->role)
                ->where('guard_name', 'admin')
                ->first();

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => $role->name,
                'status' => '1',
            ]);

            $user->roles()->detach();

            if ($role) {
                $user->assignRole($role->name);
            }

            DB::commit();

            return redirect()->route('all.admin')->with([
                'message' => 'Admin Updated Successfully',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'error' => 'An error occurred while updating the admin. Please try again.',
            ]);
        }
    }

    public function deleteAdmin($id)
    {

        $admin = Admin::find($id);
        if (!is_null($admin)) {
            $admin->delete();
        }

        $notification = array(
            'message' => 'Admin Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    //End Method
}
