<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Check if the password is true and then update data
        $UserId = Auth::user()->id;
        $profileData = User::find($UserId);

        if (! Hash::check($request->password, $profileData->password)) {
            return redirect()->back()->with('error', 'Password Does Not Match');

        }

        if ($request->email != $profileData->email) {
            $profileData->email_verified_at = null;
            $profileData->save();
        }

        $profileData->name = $request->name;
        $profileData->phone = $request->phone;
        $profileData->address = $request->address;
        $profileData->email = $request->email;
        if ($request->new_password) {
            $profileData->password = Hash::make($request->new_password);
        }

        $oldPhotoPath = $profileData->photo;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/user_images'), $file_name);
            $profileData->photo = $file_name;

            if ($oldPhotoPath && $oldPhotoPath !==  $file_name) {
                $fullPath = public_path('upload/user_images/' . $oldPhotoPath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        }

        $profileData->save();

        return redirect()->back()->with('success', 'Profile Updated Successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
