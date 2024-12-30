<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Add a product to the user's shopping cart.
     *
     * This method handles both adding a new product to the cart and updating 
     * the quantity of an existing product in the cart. It also updates the 
     * total price of the cart. If the product quantity exceeds the available stock, 
     * an warning message is shown.
     *
     * @param Product $product The product to be added to the cart.
     * 
     * @return \Illuminate\Http\RedirectResponse Redirects back with a success or error message.
     */
    public function addToCart(Product $product)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Retrieve the user's cart or create a new one if it doesn't exist
        // If created, set default values: session_id as null and total_price as 0.00
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],  // Search for a cart associated with the user's ID
            ['session_id' => null, 'total_price' => 0.00]  // Set defaults for a new cart
        );

        // Load coupon relationship if cart exists
        if ($cart) {
            $cart->load('coupon');
        }

        // Determine the product price (use discount price if available, else use regular price)
        $product_price = $product->discount_price ?: $product->price;

        // Efficiently check if the product is already in the cart using the loaded products
        $productInCart = $cart->products->firstWhere('id', $product->id);

        if (!$productInCart) {

            // Check if the product's stock quantity is sufficient to accommodate the new quantity
            if ($product->quantity < 1) {
                // If the stock is insufficient, return an warning message
                return redirect()->back()->with([
                    'message' => "This Product Not Available Right Now", // Warning message
                    'alert-type' => 'warning',  // Type of alert (error)
                ]);
            }

            // If product is not found in the cart, attach it to the cart with quantity = 1
            // Attach the product with its price and set the initial quantity and total_price
            $cart->products()->attach($product->id, [
                'quantity' => 1,           // Set the initial quantity to 1
                'price' => $product_price, // Set the price (either discounted or regular)
                'total_price' => $product_price, // Set the total price (initially equal to price)
            ]);
        } else {
            // If the product is already in the cart, update its quantity and total price
            // Increment the quantity and calculate the new total price
            $new_quantity = $productInCart->pivot->quantity + 1;
            $new_total_price = $new_quantity * $product_price;

            // Check if the product's stock quantity is sufficient to accommodate the new quantity
            if ($product->quantity < $new_quantity) {
                // If the stock is insufficient, return an warning message
                return redirect()->back()->with([
                    'message' => "Sorry, but we only have $product->quantity products of this type at the moment.", // Warning message
                    'alert-type' => 'warning',  // Type of alert (error)
                ]);
            }

            // Update the product in the cart with the new quantity and total price
            $cart->products()->updateExistingPivot($product->id, [
                'quantity' => $new_quantity,       // Updated quantity
                'price' => $product_price,         // Price remains unchanged
                'total_price' => $new_total_price, // Updated total price
            ]);
        }

        // Update the cart's total price after adding or updating a product
        $this->updateCartTotalPrice($cart);

        // Redirect back with a success message
        return redirect()->back()->with([
            'message' => 'Product added to cart successfully!', // Success message
            'alert-type' => 'success',  // Type of alert (success)
        ]);
    }

    /**
     * Update the total price of the cart and apply any discounts from a coupon.
     *
     * This method calculates the total price of the cart, taking into account any 
     * discounts from an associated coupon. It differentiates between products that 
     * belong to the client associated with the coupon and other products in the cart. 
     * The net total price is then updated accordingly.
     *
     * @param Cart $cart The cart object whose total price needs to be updated.
     * 
     * @return void
     */
    public static function updateCartTotalPrice(Cart $cart)
    {
        // Calculate the total price of all products in the cart
        $totalPrice = $cart->products()->sum('cart_product.total_price');
        $net_total_price = $totalPrice;

        // Collect the client IDs associated with the products in the cart
        $clientIds = $cart->products->pluck('client_id')->toArray();

        // If there is a coupon applied to the cart
        if ($cart->coupon) {
            // Find the client associated with the coupon
            $client = Client::find($cart->coupon->client_id);

            // If the cart contains products from more than one client
            if (count($clientIds) > 1) {
                // Calculate the total price for the products belonging to the client with the coupon, applying the discount
                $totalPriceForTheClientHasCoupon = $cart->products()->where('client_id', $client->id)->sum('cart_product.total_price');
                $netTotalPriceForTheClientHasCoupon = $totalPriceForTheClientHasCoupon - ($totalPriceForTheClientHasCoupon * ($cart->coupon->discount / 100));

                // Calculate the total price for the products not belonging to the client with the coupon
                $totalPriceForTheClientHasNotCoupon = $cart->products()->where('client_id', '!=', $client->id)->sum('cart_product.total_price');

                // Combine the prices to get the final total price after applying the discount
                $net_total_price = $netTotalPriceForTheClientHasCoupon + $totalPriceForTheClientHasNotCoupon;
            } else {
                // If the cart contains products from only one client, apply the discount to the total price
                $net_total_price = $totalPrice - ($totalPrice * ($cart->coupon->discount / 100));
            }
        }

        // Update the cart with the total price and the net total price after discount
        $cart->update([
            'total_price' => $totalPrice,
            'net_total_price' => $net_total_price,
        ]);
    }

    /**
     * Update the quantity of a product in the user's cart.
     *
     * This method updates the quantity of a product in the user's cart. It first checks 
     * if the product exists in the cart, and if enough stock is available. The total 
     * price of the cart is updated after the quantity change.
     *
     * @param Request $request The incoming request containing the product id and quantity.
     * 
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success or failure.
     */
    public function updateQuantity(Request $request)
    {
        // Retrieve the product ID and the new quantity from the request
        $productId = $request->id;
        $quantity = $request->quantity;

        // Retrieve the cart for the authenticated user
        $cart = Cart::where('user_id', Auth::id())->with('coupon')->first();

        // Return error if the cart is not found
        if (!$cart) {
            return response()->json(['error' => 'Cart Not Found'], 400);
        }

        // Retrieve the product in the cart by product ID
        $productInCart = $cart->products()->where('product_id', $productId)->first();

        // Return error if the product is not in the cart
        if (!$productInCart) {
            return response()->json(['error' => 'Product not available In Your Cart'], 400);
        }

        // Retrieve the actual product from the database
        $product = Product::find($productId);

        // Return error if there is not enough stock available
        if ($product->quantity < $quantity) {
            return response()->json(['error' => 'Not enough stock available'], 400);
        }

        // If the quantity hasn't changed, return an error
        if ($productInCart->pivot->quantity == $quantity) {
            return response()->json(['error' => 'No change in quantity'], 400);
        }

        // Calculate the new total price for the product
        $new_total_price = $quantity * $productInCart->pivot->price;

        // Update the product's quantity and total price in the cart
        $cart->products()->updateExistingPivot($productId, [
            'quantity' => $quantity,
            'total_price' => $new_total_price,
        ]);

        // Update the total price of the cart
        $this->updateCartTotalPrice($cart);

        // Return a success response
        return response()->json(['success' => true]);
    }
    /**
     * Remove a product from the user's cart.
     *
     * This method handles the removal of a specific product from the authenticated user's cart. 
     * It performs the following steps:
     * 
     * - Validates the existence of the cart for the authenticated user.
     * - Verifies that the specified product exists in the cart.
     * - Detaches the product from the cart.
     * - Checks if the cart's applied coupon is still valid after the product removal. If no products 
     *   associated with the coupon's client remain in the cart, the coupon is removed.
     * - Updates the total price of the cart after the product and (if applicable) the coupon are removed.
     * - Returns a JSON response indicating success or failure.
     *
     * @param Request $request The incoming request containing the `id` of the product to be removed.
     * 
     * @return \Illuminate\Http\JsonResponse A JSON response with the following structure:
     * - `success`: A boolean indicating whether the operation was successful.
     * - `message`: A string describing the result of the operation.
     */
    public function removeFromCart(Request $request)
    {
        // Retrieve the product ID from the request
        $productId = $request->id;

        // Retrieve the cart for the authenticated user
        $cart = Cart::where('user_id', Auth::id())->with('coupon')->first();

        // Return an error response if the cart does not exist
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found.']);
        }

        // Retrieve the specified product in the cart
        $productInCart = $cart->products()->where('product_id', $productId)->first();

        // Return an error response if the product does not exist in the cart
        if (!$productInCart) {
            return response()->json(['success' => false, 'message' => 'Product not found in cart.']);
        }

        // Detach the product from the cart
        $cart->products()->detach($productId);

        // Check if the applied coupon should be removed
        if ($cart->coupon) {
            $couponClientId = $cart->coupon->client_id;

            // Determine if any remaining products belong to the coupon's client
            $remainingProductsFromCouponClient = $cart->products()->where('client_id', $couponClientId)->exists();

            // If no products from the coupon's client remain, remove the coupon
            if (!$remainingProductsFromCouponClient) {
                $cart->update(['coupon_id' => null]);
            }
        }

        // Update the cart's total price after the removal
        $this->updateCartTotalPrice($cart);

        // Return a success response
        return response()->json(['success' => true]);
    }

    /**
     * Apply a coupon to the user's cart if it is valid and belongs to the correct vendor.
     *
     * This method validates the coupon, checks the cart contents, and applies the coupon if the conditions are met.
     * The conditions for applying the coupon are:
     * - The coupon must exist and be valid (not expired).
     * - The cart must contain at least one product.
     * - The coupon must belong to the same vendor as the products in the cart.
     * 
     * After the validation, the coupon is applied to the cart, and the `coupon_id` is updated in the cart.
     * 
     * @param Request $request The request object containing the coupon name.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a success or error message.
     */
    public function applyCoupon(Request $request)
    {
        // Search for the coupon and check if it's valid
        $coupon = Coupon::where('coupon_name', $request->coupon_name)
            ->where('validity', '>=', Carbon::now()->format('Y-m-d'))
            ->first();

        // Retrieve the user's cart
        $cart = Cart::where('user_id', Auth::id())->with(['products', 'coupon', 'products.resturant'])->first();

        if (!$cart || $cart->products()->count() === 0) {
            return response()->json(['error' => 'Your cart is empty'], 400);
        }

        // Calculate the total amount of the cart
        $totalAmount = $cart->products()->sum('cart_product.total_price');

        // Collect client IDs (vendors) associated with the products in the cart
        $clientIds = $cart->products->pluck('client_id')->toArray();

        // Validate the coupon's existence
        if (!$coupon) {
            return response()->json(['error' => 'Invalid Coupon'], 400);
        }

        // Validate that the coupon belongs to the same vendor as the products in the cart
        $cvendorId = $coupon->client_id;
        if ($cvendorId != $clientIds[0]) {
            return response()->json(['error' => 'This Coupon is not valid for this Restaurant'], 400);
        }

        // Update the cart with the coupon ID
        $cart->update([
            'coupon_id' => $coupon->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon Applied Successfully',
        ]);
    }

    /**
     * Remove the applied coupon from the user's cart.
     *
     * This method removes the coupon from the cart by setting the `coupon_id` to `NULL`.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating the success of the operation.
     */
    public function couponRemove()
    {
        // Retrieve the user's cart
        $cart = Cart::where('user_id', Auth::id())->first();

        // Remove the coupon by setting the coupon_id to NULL
        $cart->update([
            'coupon_id' => NULL,
        ]);

        return response()->json(['success' => 'Coupon Removed Successfully']);
    }

    /**
     * Handle the shop checkout process.
     *
     * This method manages the user's checkout experience by verifying their authentication, 
     * retrieving their cart, and checking for products. It ensures that:
     * - Authenticated users with at least one product in their cart are redirected to the checkout page.
     * - Authenticated users with empty carts are notified to add products before proceeding.
     * - Unauthenticated users are redirected to the login page with a notification.
     *
     * @return \Illuminate\Http\Response
     *
     * Scenarios:
     * - **Authenticated user with products in cart**:
     *      Redirects to the checkout view with the cart data.
     * - **Authenticated user with an empty cart**:
     *      Redirects to the home page with an error notification.
     * - **Unauthenticated user**:
     *      Redirects to the login page with a success notification.
     */
    public function shopCheckout()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Retrieve the cart for the authenticated user and its related data
            $cart = Cart::where('user_id', Auth::id())
                ->with(['products', 'coupon', 'products.resturant']) // Load related products, coupon, and restaurant
                ->first();

            // Update the total price of the cart if applicable
            $this->updateCartTotalPrice($cart);

            // Check if the cart contains products
            if ($cart && $cart->products->count() > 0) {
                // Redirect to the checkout view with the cart data
                return view('frontend.checkout.view_checkout', compact('cart'));
            } else {
                // Redirect to the home page with an error notification
                $notification = [
                    'message' => 'You must have at least one item in your cart to proceed to checkout.',
                    'alert-type' => 'error',
                ];
                return redirect()->to('/')->with($notification);
            }
        } else {
            // Redirect unauthenticated users to the login page with a success notification
            $notification = [
                'message' => 'Please log in first to proceed to checkout.',
                'alert-type' => 'success',
            ];
            return redirect()->route('login')->with($notification);
        }
    }

    /**
     * Clears the user's shopping cart by detaching products and resetting cart totals.
     *
     * This method performs the following actions:
     * - Detaches all products from the cart (removes the many-to-many relationships between the cart and products).
     * - Resets the total price and net total price of the cart to 0.00.
     * - Removes any applied coupon from the cart.
     *
     * @param Cart $cart The cart to be cleared.
     * 
     * @return void
     */
    public static function clearCart($cart)
    {
        // Step 1: Detach all products from the cart (remove the many-to-many relationship between cart and products).
        $cart->products()->detach();  // Remove all the associated products from the cart.

        // Step 2: Reset the cart fields to reflect an empty cart.
        $cart->update([  // Update the cart's total price and coupon ID.
            'total_price' => 0.00,  // Reset the total price to 0.
            'net_total_price' => 0.00,  // Reset the net total price to 0.
            'coupon_id' => null,  // Clear any applied coupon.
        ]);
    }
}
