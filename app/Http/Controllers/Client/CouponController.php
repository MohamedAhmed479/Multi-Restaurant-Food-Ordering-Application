<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function allCoupons()
    {
        $client_id = Auth::guard('client')->id();
        $coupons = Coupon::where('client_id', $client_id)->orderBy('created_at', 'DESC')->get();

        return view('client.coupon.all_coupon', get_defined_vars());
    }

    public function addCoupon()
    {
        return view('client.coupon.add_coupon');
    }

    public function storeCoupon(Request $request)
    {
        $data = $request->validate([
            'coupon_name' => 'required|string|max:255',
            'coupon_desc' => 'required|string|max:255',
            'discount' => 'required|numeric|max:255',
            'validity' => 'required|date',

        ]);
        $data['client_id'] = Auth::guard('client')->id();

        Coupon::create($data);

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Coupon Created Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->route('client.coupons.all')->with($notification);
    }

    public function editCoupon(Coupon $coupon) {
        if($coupon->client_id != Auth::guard('client')->id()) {
            return back();
        }

        return view('client.coupon.edit_coupon', get_defined_vars());
    }

    public function updateCoupon(Coupon $coupon, Request $request)
    {
        if($coupon->client_id != Auth::guard('client')->id()) {
            return back();
        }

        $data = $request->validate([
            'coupon_name' => 'required|string|max:255',
            'coupon_desc' => 'required|string|max:255',
            'discount' => 'required|numeric|max:255',
            'validity' => 'nullable|date',
        ]);

        $coupon->update($data);

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Coupon Updated Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->route('client.coupons.all')->with($notification);
    }

    public function destroyCoupon(Coupon $coupon)
    {
        if($coupon->client_id != Auth::guard('client')->id()) {
            return back();
        }

        $coupon->delete();
    
        $notification = array(
            'message' => 'Coupon Deleted Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );
    
        return redirect()->route('client.coupons.all')->with($notification);
    
    }

}
