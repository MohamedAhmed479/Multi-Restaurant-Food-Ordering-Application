<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function allCategories()
    {
        $categories = Category::latest()->get();

        return view('admin.categories.all', get_defined_vars());
    }

    public function addCategory()
    {
        return view('admin.categories.add');
    }

    public function storeCategory(Request $request)
    {
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
            $img->resize(300, 300)->save(public_path('upload/categories/' . $name_gen)); // Save the resized image to the specified path in the public directory.

            // Store the image's relative URL to use later when saving it in the database.
            $save_url = 'upload/categories/' . $name_gen;

            // Add the image URL to the data array under the 'image' key.
            $data['image'] = $save_url;
        }

        // Create a new category record in the database with the prepared data.
        Category::create($data);

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Category Created Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->route('admin.categories')->with($notification);
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', get_defined_vars());
    }

    public function updateCategory(Category $category, Request $request)
    {
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
            $img->resize(300, 300)->save(public_path('upload/categories/' . $name_gen)); // Save the resized image to the specified path in the public directory.

            // Store the image's relative URL to use later when saving it in the database.
            $save_url = 'upload/categories/' . $name_gen;

            // Add the image URL to the data array under the 'image' key.
            $data['image'] = $save_url;

            // Delete The Old Image If exist
            $fullPath = public_path($category->image);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // Create a new category record in the database with the prepared data.
        $category->update($data);

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Category Updated Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->route('admin.categories')->with($notification);
    }

    public function destroyCategory(Category $category)
    {
        // Delete The Old Image If exist
        $fullPath = public_path($category->image);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $category->delete();

        // Prepare a notification message for the user to indicate the category was created successfully.
        $notification = array(
            'message' => 'Category Dleted Successfully', // The message content.
            'alert-type' => 'success', // The type of alert (success, error, etc.).
        );

        // Redirect the user to the 'admin.categories' route with the notification.
        return redirect()->back()->with($notification);
    }
}
