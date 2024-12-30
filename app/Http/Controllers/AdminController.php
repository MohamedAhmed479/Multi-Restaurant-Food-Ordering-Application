<?php

namespace App\Http\Controllers;

use App\Mail\WebsiteMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{

    public function adminLogin()
    {
        return view('admin.login');
    }
    // End Method

    public function adminDashboard()
    {
        return view('admin.index');
    }
    // End Method

    public function adminLoginSubmit(Request $request)
    {

        $request->validate([
            'email' => "required|email",
            'password' => "required",
        ]);

        $check = $request->all();

        $data = [
            'email' => $check['email'],
            'password' => $check['password']
        ];

        if (Auth::guard('admin')->attempt($data)) {
            return redirect()->route('admin.dashboard')->with('success', 'Your loggin successfully');
        } else {
            return redirect()->route('admin.login')->with('error', 'Invalid Creadentials');
        }
    }
    // End Method

    public function adminLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'Your logout successfully');
    }
    // End Method

    public function adminForgetPassword()
    {
        return view('admin.forget_password');
    }
    // End Method

    public function adminPasswordSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',

        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin) {
            return redirect()->back()->with('error', 'Email Not Found');
        }

        $token = hash('sha256', time());
        $admin->token = $token;
        $admin->update();

        $reset_link = url('admin/reset-password/' . $token . '/' . $request->email);
        $subject = "Reset Password";
        $message = "Please Click on below link to reset password<br>";
        $message .= "<a href='" . $reset_link . " '> Click Here </a>";

        Mail::to($request->email)->send(new WebsiteMail($subject, $message));

        return redirect()->back()->with('success', 'Reset Password Link Sent Successfully To Your Email');
    }
    // End Method

    public function adminResetPassword($token, $email)
    {

        $admin = Admin::where('token', $token)
            ->where('email', $email)
            ->first();
        if (! $admin) {
            return redirect()->route('admin.login')->with('error', 'Invalid Token or Email');
        }

        return view('admin.reset_password', compact('token', 'email'));
    }
    // End Method

    public function adminResetPasswordSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $admin = Admin::where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (! $admin) {
            return redirect()->route('admin.login')->with('error', 'Invalid Token or Email');
        }

        $admin->password = Hash::make($request->password);
        $admin->token = null;
        $admin->update();

        return redirect()->route('admin.login')->with('success', 'Password Reste Successfully');
    }
    // End Method

    public function adminProfile()
    {
        $adminId = Auth::guard('admin')->id();
        $profileData = Admin::find($adminId);

        return view('admin.profile', get_defined_vars());
    }
    // End Method

    public function adminProfileStore(Request $request)
    {

        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . Auth::guard('admin')->id(),
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $adminId = Auth::guard('admin')->id();
        $profileData = Admin::find($adminId);
        $profileData->name = $request->name;
        $profileData->phone = $request->phone;
        $profileData->address = $request->address;
        $profileData->email = $request->email;
        $oldPhotoPath = $profileData->photo;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/admin_images'), $file_name);
            $profileData->photo = $file_name;

            if ($oldPhotoPath && $oldPhotoPath !==  $file_name) {
                $this->deleteOldImage($oldPhotoPath);
            }
        }

        $profileData->save();

        $notification = array(
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }
    // End Method

    private function deleteOldImage(string $oldPhotoPath): void
    {
        $fullPath = public_path('upload/admin_images/' . $oldPhotoPath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    // End Method

    public function adminChangePassword()
    {
        $adminId = Auth::guard('admin')->id();
        $profileData = Admin::find($adminId);


        return view('admin.admin_change_password', get_defined_vars());
    }
    // End Method

    public function adminPasswordUpdate(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        if (! Hash::check($request->old_password, $admin->password)) {
            $notification = array(
                'message' => 'Old Password Does Not Match!',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }

        // Update new password
        Admin::whereId($admin->id)->update([
            'password' => Hash::make($request->password),
        ]);

        $notification = array(
            'message' => 'Password Changed Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    }
    // End Method


    public function adminLockScreen() {
        
        return view('admin.auth-lock-screen');
    }
}
