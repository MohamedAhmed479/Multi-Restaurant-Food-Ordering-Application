<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Mail\WebsiteMail;
use App\Models\Admin;
use App\Models\City;
use App\Models\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ClientController extends Controller
{
    public function clientDashboard()
    {
        return view('client.index');
    }
    // End Method

    public function clientRegister()
    {
        return view('client.register');
    }
    // End Method

    public function clientRegisterStore(StoreClientRequest $request)
    {

        $clientData = $request->validated();

        $client = Client::Create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
        ]);

        event(new Registered($client));

        Auth::guard('client')->login($client);

        return redirect()->route('client.dashboard');
    }
    // End Method

    public function clientLogin()
    {
        return view('client.login');
    }
    // End Method

    public function clientLoginSubmit(Request $request)
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

        if (Auth::guard('client')->attempt($data)) {
            return redirect()->route('client.dashboard')->with('success', 'Your loggin successfully');
        } else {
            return redirect()->route('client.login')->with('error', 'Invalid Creadentials');
        }
    }
    // End Method

    public function clientLogout()
    {
        Auth::guard('client')->logout();
        return redirect()->route('client.login')->with('success', 'Your logout successfully');
    }
    // End Method

    public function clientForgetPassword()
    {
        return view('client.forget_password');
    }
    // End Method

    public function clientPasswordSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',

        ]);

        $client = Client::where('email', $request->email)->first();

        if (! $client) {
            return redirect()->back()->with('error', 'Email Not Found');
        }

        $token = hash('sha256', time());
        $client->token = $token;
        $client->update();

        $reset_link = url('client/reset-password/' . $token . '/' . $request->email);
        $subject = "Reset Password";
        $message = "Please Click on below link to reset password<br>";
        $message .= "<a href='" . $reset_link . " '> Click Here </a>";

        Mail::to($request->email)->send(new WebsiteMail($subject, $message));

        return redirect()->back()->with('success', 'Reset Password Link Sent Successfully To Your Email');
    }
    // End Method

    public function clientResetPassword($token, $email)
    {

        $client = Client::where('token', $token)
            ->where('email', $email)
            ->first();

        if (! $client) {
            return redirect()->route('client.login')->with('error', 'Invalid Token or Email');
        }

        return view('client.reset_password', compact('token', 'email'));
    }
    // End Method

    public function clientResetPasswordSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $client = Client::where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (! $client) {
            return redirect()->route('client.login')->with('error', 'Invalid Token or Email');
        }

        $client->password = Hash::make($request->password);
        $client->token = null;
        $client->update();

        return redirect()->route('client.login')->with('success', 'Password Reste Successfully');
    }
    // End Method

    public function clientProfile()
    {
        $clientId = Auth::guard('client')->id();
        $profileData = Client::find($clientId);

        $cities = City::get();

        return view('client.profile', get_defined_vars());
    }
    // End Method

    public function clientProfileStore(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:255',
            'shop_info' => 'nullable|string',
            'email' => 'required|email|max:255|unique:clients,email,' . Auth::guard('client')->id(),
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4000',
            'city_id' => 'required|exists:cities,id',
            'cover_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4000',
        ]);


        $clientId = Auth::guard('client')->id();
        $profileData = Client::find($clientId);
        $profileData->name = $request->name;
        $profileData->phone = $request->phone;
        $profileData->address = $request->address;
        $profileData->email = $request->email;
        $profileData->shop_info = $request->shop_info;

        $profileData->city_id = $request->city_id;

        $oldCoverPhotoPath = $profileData->cover_photo;
        $oldPhotoPath = $profileData->photo;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/client_images'), $file_name);
            $profileData->photo = $file_name;

            if ($oldPhotoPath && $oldPhotoPath !==  $file_name) {
                $this->deleteOldImage($oldPhotoPath);
            }
        }

        if ($request->hasFile('cover_photo')) {
            $file = $request->file('cover_photo');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/client_images'), $file_name);
            $profileData->cover_photo = $file_name;

            if ($oldCoverPhotoPath && $oldCoverPhotoPath !==  $file_name) {
                $this->deleteOldImage($oldCoverPhotoPath);
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
        $fullPath = public_path('upload/client_images/' . $oldPhotoPath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    // End Method

    public function clientChangePassword()
    {
        $clientId = Auth::guard('client')->id();
        $profileData = Client::find($clientId);


        return view('client.client_change_password', get_defined_vars());
    }
    // End Method

    public function clientPasswordUpdate(Request $request)
    {
        $client = Auth::guard('client')->user();

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        if (! Hash::check($request->old_password, $client->password)) {
            $notification = array(
                'message' => 'Old Password Does Not Match!',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }

        // Update new password
        Client::whereId($client->id)->update([
            'password' => Hash::make($request->password),
        ]);

        $notification = array(
            'message' => 'Password Changed Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }
    // End Method

}
