<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\City;
use App\Models\Client;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ManageController extends Controller
{
    public function allProducts()
    {
        $products = Product::with(['menu', 'resturant'])->latest()->get();

        return view('admin.product.all_product', get_defined_vars());
    }

    public function selectResturant()
    {
        $clients = Client::latest()->get();
        return view('admin.product.question', get_defined_vars());
    }

    public function addProduct(Request $request)
    {
        $client = Client::findOrFail($request->client_id);

        $categories = Category::get();
        $ceties = City::get();

        $menus = Menu::where('client_id', $client->id)->latest()->get();

        return view('admin.product.add_product', get_defined_vars());
    }

    public function storeProduct(Client $client, StoreProductRequest $request)
    {
        $data = $request->validated();

        $client = $client;

        $menu = Menu::find($data['menu_id']);
        if ($menu->client_id != $client->id) {
            // Prepare a notification message for the user to indicate the category was created successfully.
            $notification = array(
                'message' => "$menu->name menu is not available at $client->name", // The message content.
                'alert-type' => 'error', // The type of alert (success, error, etc.).
            );

            // Redirect the user to the 'admin.categories' route with the notification.
            return redirect()->route('admin.products.all')->with($notification);
        }

        $data['client_id'] = $client->id;

        // Check if an image file is uploaded in the request.
        if ($request->hasFile('image')) {
            // Get the uploaded image file.
            $image = $request->file('image');

            // Initialize an instance of the ImageManager for image processing.
            $manager = new ImageManager(new Driver()); // Replace Driver() with the actual driver (GD or Imagick).

            // Generate a unique name for the image file using a hexadecimal unique ID and its original extension.
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Read the uploaded image into the ImageManager for processing.
            $img = $manager->read($image);

            // Resize the image to 300x300 pixels.
            $img->resize(300, 300)->save(public_path('upload/products/' . $name_gen)); // Save the resized image to the specified path in the public directory.

            // Store the image's relative URL to use later when saving it in the database.
            $save_url = 'upload/products/' . $name_gen;

            // Add the image URL to the data array under the 'image' key.
            $data['image'] = $save_url;
        }

        // Create a new category record in the database with the prepared data.
        Product::create($data);

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Product Created Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->route('admin.products.all')->with($notification);
    }

    public function editProduct(Product $product)
    {
        $categories = Category::get();
        $cities = City::get();
        $menus = Menu::where('client_id', $product->client_id)->latest()->get();

        return view('admin.product.edit_product', get_defined_vars());
    }

    public function updateProduct(Product $product, UpdateProductRequest $request)
    {
        $data = $request->validated();

        $client = Client::find($product->client_id);
        $menu = Menu::find($data['menu_id']);

        if ($menu->client_id != $client->id) {
            // Prepare a notification message for the user to indicate the category was created successfully.
            $notification = array(
                'message' => "$menu->name menu is not available at $client->name", // The message content.
                'alert-type' => 'error', // The type of alert (success, error, etc.).
            );

            // Redirect the user to the 'admin.categories' route with the notification.
            return redirect()->route('admin.products.all')->with($notification);
        }


        // Check if an image file is uploaded in the request.
        if ($request->hasFile('image')) {
            // Get the uploaded image file.
            $image = $request->file('image');

            // Initialize an instance of the ImageManager for image processing.
            $manager = new ImageManager(new Driver()); // Replace Driver() with the actual driver (GD or Imagick).

            // Generate a unique name for the image file using a hexadecimal unique ID and its original extension.
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Read the uploaded image into the ImageManager for processing.
            $img = $manager->read($image);

            // Resize the image to 300x300 pixels.
            $img->resize(300, 300)->save(public_path('upload/products/' . $name_gen)); // Save the resized image to the specified path in the public directory.

            // Store the image's relative URL to use later when saving it in the database.
            $save_url = 'upload/products/' . $name_gen;

            // Add the image URL to the data array under the 'image' key.
            $data['image'] = $save_url;

            // Delete The Old Image If exist
            if ($product->image != NULL) {
                $fullPath = public_path($product->image);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        }

        // Create a new category record in the database with the prepared data.
        $product->update($data);

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Product Updated Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->route('admin.products.all')->with($notification);
    }

    public function destroyProduct(Product $product)
    {

        if ($product->image != NULL) {
            // Delete The Old Image If exist
            $fullPath = public_path($product->image);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $product->delete();

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Product Deleted Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->back()->with($notification);
    }

    public function changeStatus(Request $request)
    {
        $product = Product::find($request->id);
        $product->status = $request->status;
        $product->save();

        return response()->json(['success' => 'Status Updated Successfully']);
    }
    // Product Methods Ended Here

    public function pendingRestaurant()
    {
        $client = Client::where('status', 0)->get();
        return view('admin.restaurant.pending_restaurant', get_defined_vars());
    }

    public function approveRestaurant()
    {
        $client = Client::where('status', 1)->get();
        return view('admin.restaurant.approve_restaurant', get_defined_vars());
    }

    public function clientChangeStatus(Request $request)
    {
        $client = Client::find($request->client_id);
        $client->status = $request->status;
        $client->save();
        return response()->json(['success' => 'Status Change Successfully']);
    }
    // resturant Methods Ended Here

    public function allBanners()
    {
        $banners = Banner::latest()->get();
        return view('admin.banner.all_banner', get_defined_vars());
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4000',
        ]);

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(400, 400)->save(public_path('upload/banner/' . $name_gen));
            $save_url = 'upload/banner/' . $name_gen;

            Banner::create([
                'url' => $request->url,
                'image' => $save_url,
            ]);
        }

        $notification = array(
            'message' => 'Banner Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function editBanner($id)
    {
        $banner = Banner::find($id);
        if ($banner) {
            $banner->image = asset($banner->image);
        }
        return response()->json($banner);
    }

    public function updateBanner(Request $request)
    {

        $request->validate([
            'url' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4000',
        ]);

        $banner_id = $request->banner_id;

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(400, 400)->save(public_path('upload/banner/' . $name_gen));
            $save_url = 'upload/banner/' . $name_gen;


            $banner = Banner::find($banner_id);

            unlink(public_path($banner->image));

            $banner->update([
                'url' => $request->url,
                'image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Banner Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('admin.banners.all')->with($notification);
        } else {

            Banner::find($banner_id)->update([
                'url' => $request->url,
            ]);
            $notification = array(
                'message' => 'Banner Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('admin.banners.all')->with($notification);
        }
    }

    public function destroyBanner($id)
    {
        $item = Banner::find($id);
        $img = $item->image;
        unlink($img);

        Banner::find($id)->delete();

        $notification = array(
            'message' => 'Banner Delete Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    // End Admin Banner methods


}
