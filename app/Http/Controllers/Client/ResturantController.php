<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\City;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ResturantController extends Controller
{
    public function allMenus()
    {
        $client_id = Auth::guard('client')->id();
        $menus = Menu::where('client_id', $client_id)->orderBy('created_at', 'DESC')->get();

        return view('client.menu.all_menu', get_defined_vars());
    }

    public function addMenu()
    {
        return view('client.menu.add_menu');
    }

    public function storeMenu(Request $request)
    {
        // Validate the incoming request to ensure 'name' is required and 'image' meets specific conditions.
        $request->validate([
            'name' => 'required|string|max:255', // 'name' must be a non-empty string with a maximum length of 255 characters.
            'image' => 'required|image|mimes:png,jpg,webp|max:4000', // 'image' is optional, must be an image (png or jpg) with a max size of 4MB.
        ]);

        // Prepare an array to store the data. Initially, it only includes the 'name' field.
        $data = [
            'client_id' =>  Auth::guard('client')->id(),
            'name' => $request->name, // Assign the 'name' value from the request to the data array.
        ];

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
            $img->resize(300, 300)->save(public_path('upload/menu/' . $name_gen)); // Save the resized image to the specified path in the public directory.

            // Store the image's relative URL to use later when saving it in the database.
            $save_url = 'upload/menu/' . $name_gen;

            // Add the image URL to the data array under the 'image' key.
            $data['image'] = $save_url;
        }

        // Create a new category record in the database with the prepared data.
        Menu::create($data);

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Menu Created Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->route('client.menus.all')->with($notification);
    }

    public function editMenu($id)
    {
        $menu = Menu::whereId($id)->where('client_id', Auth::guard('client')->id())->first();

        if (! $menu) {
            $notification = array(
                'message' => 'Please Dont Play In Our Data 😊', // The message content.
                'alert-type' => 'error', // The type of alert (success, error, etc.).
            );

            return redirect()->back()->with($notification);
        }

        return view('client.menu.edit_menu', get_defined_vars());
    }

    public function updateMenu(Menu $menu, Request $request)
    {
        if ($menu->client_id != Auth::guard('client')->id()) {
            $notification = array(
                'message' => 'Please Dont Play In Our Data 😊', // The message content.
                'alert-type' => 'error', // The type of alert (success, error, etc.).
            );

            return redirect()->back()->with($notification);
        }

        // Validate the incoming request to ensure 'name' is required and 'image' meets specific conditions.
        $request->validate([
            'name' => 'required|string|max:255', // 'name' must be a non-empty string with a maximum length of 255 characters.
            'image' => 'nullable|image|mimes:png,jpg,webp|max:4000', // 'image' is optional, must be an image (png or jpg) with a max size of 4MB.
        ]);

        // Prepare an array to store the data. Initially, it only includes the 'name' field.
        $data = [
            'name' => $request->name, // Assign the 'name' value from the request to the data array.
        ];

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
            $img->resize(300, 300)->save(public_path('upload/menu/' . $name_gen)); // Save the resized image to the specified path in the public directory.

            // Store the image's relative URL to use later when saving it in the database.
            $save_url = 'upload/menu/' . $name_gen;

            // Add the image URL to the data array under the 'image' key.
            $data['image'] = $save_url;

            // Delete The Old Image If exist
            $fullPath = public_path($menu->image);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // Create a new category record in the database with the prepared data.
        $menu->update($data);

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Menu Updated Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->route('client.menus.all')->with($notification);
    }

    public function destroyMenu(Menu $menu)
    {
        if ($menu->client_id != Auth::guard('client')->id()) {
            $notification = array(
                'message' => 'Please Dont Play In Our Data 😊', // The message content.
                'alert-type' => 'error', // The type of alert (success, error, etc.).
            );

            return redirect()->back()->with($notification);
        }

        // Delete The Old Image If exist
        $fullPath = public_path($menu->image);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $menu->delete();

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Menu Dleted Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->back()->with($notification);
    }

    // Menu Methods Ended Here


    public function allProducts()
    {
        $client_id = Auth::guard('client')->id();
        $client_with_products = Client::with('products.menu')->find($client_id);

        // foreach ($client_with_products->products as $product) {
        //     echo "Product Name: " . $product->name . "<br>";
        //     echo "Menu: " . $product->menu->name . "<br>"; // Assuming 'name' is a column in the Menu table
        // }

        return view('client.product.all_product', get_defined_vars());
    }

    public function addProduct()
    {
        $categories = Category::get();

        $ceties = City::get();

        $client_id = Auth::guard('client')->id();
        $menus = Menu::where('client_id', $client_id)->orderBy('created_at', 'DESC')->get();

        return view('client.product.add_product', get_defined_vars());
    }

    public function storeProduct(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['client_id'] = Auth::guard('client')->id();

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
        return redirect()->route('client.products.all')->with($notification);
    }

    public function editProduct(Product $product)
    {
        $categories = Category::get();

        $cities = City::get();

        $client_id = Auth::guard('client')->id();
        $menus = Menu::where('client_id', $client_id)->orderBy('created_at', 'DESC')->get();


        return view('client.product.edit_product', get_defined_vars());
    }

    public function updateProduct(Product $product, UpdateProductRequest $request)
    {
        $data = $request->validated();

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
        return redirect()->route('client.products.all')->with($notification);
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


    public function allGallery()
    {
        $client_id = Auth::guard('client')->id();
        $gallery = Gallery::where('client_id', $client_id)->latest()->get();

        return view('client.gallery.all_gallery', get_defined_vars());
    }

    public function addGallery()
    {
        return view('client.gallery.add_gallery');
    }

    public function storeGallery(Request $request)
    {
        $images = $request->file('gallery_img');
        $gallery = [];

        foreach ($images as $image) {
            $manager = new ImageManager(new Driver()); // Replace Driver() with the actual driver (GD or Imagick).

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $img = $manager->read($image);

            $img->resize(800, 800)->save(public_path('upload/gallery/' . $name_gen)); // Save the resized image to the specified path in the public directory.

            $save_url = 'upload/gallery/' . $name_gen;

            $gallery[] = [
                'client_id' => Auth::guard('client')->id(),
                'gallery_img' => $save_url,
            ];
        }
        Gallery::insert($gallery);

        $notification = array(
            'message' => 'Gallery Created Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        return redirect()->route('client.gallery.all')->with($notification);
    }

    public function editGallery(Gallery $gallery)
    {
        if($gallery->client_id != Auth::guard('client')->id()) {
            return back();
        }
        return view('client.gallery.edit_gallery', get_defined_vars());
    }

    public function updateGallery(Gallery $gallery, Request $request)
    {
        $request->validate([
            'gallery_img' => 'required|image|mimes:png,jpg,webp|max:4000', // 'image' is optional, must be an image (png or jpg) with a max size of 4MB.
        ]);

        $old_image = $gallery->gallery_img;

        // Get the uploaded image file.
        $image = $request->file('gallery_img');

        // Initialize an instance of the ImageManager for image processing.
        $manager = new ImageManager(new Driver()); // Replace Driver() with the actual driver (GD or Imagick).

        // Generate a unique name for the image file using a hexadecimal unique ID and its original extension.
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

        // Read the uploaded image into the ImageManager for processing.
        $img = $manager->read($image);

        // Resize the image to 300x300 pixels.
        $img->resize(800, 800)->save(public_path('upload/gallery/' . $name_gen)); // Save the resized image to the specified path in the public directory.

        // Store the image's relative URL to use later when saving it in the database.
        $save_url = 'upload/gallery/' . $name_gen;

        $gallery->gallery_img = $save_url;
        $gallery->save();

        // Delete The Old Image If exist
        $fullPath = public_path($old_image);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }


        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Gallery Updated Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->route('client.gallery.all')->with($notification);
    }

    public function destroyGallery(Gallery $gallery)
    {
        if($gallery->client_id != Auth::guard('client')->id()) {
            return back();
        }
        
        $old_image = $gallery->gallery_img;

        // Delete The Old Image If exist
        $fullPath = public_path($old_image);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $gallery->delete();

        $notification = array(
            'message' => 'Image Deleted Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        return redirect()->route('client.gallery.all')->with($notification);
    }
}
