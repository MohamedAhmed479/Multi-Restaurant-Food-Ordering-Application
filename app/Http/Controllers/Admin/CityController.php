<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function allCities()
    {
        $cities = City::latest()->get();

        return view('admin.cities.all', get_defined_vars());
    }


    public function storeCities(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        City::create([
            'name' => $request->name,
        ]);

        $notification = array(
            'message' => 'City Inserted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('admin.cities')->with($notification);
    }


    public function editCity($id)
    {
        $city = City::find($id);
        return response()->json($city);
    }


    public function updateCity(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        City::find($request->cat_id)->update([
            'name' => $request->name,
        ]);


        $notification = array(
            'message' => 'City Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('admin.cities')->with($notification);
    }

    public function destroyCity($id)
    {

        City::find($id)->delete();

        $notification = array(
            'message' => 'City Dleted Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        return redirect()->back()->with($notification);
    }
}
