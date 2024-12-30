<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Frontend\CartController;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\RestaurantBalance;
use App\Notifications\OrderComplete;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;


class OrderController extends Controller
{
    /**
     * Handle a Cash on Delivery (COD) order placement.
     *
     * This method processes the order for the authenticated user using the Cash on Delivery payment method. 
     * The main steps include validating the input, managing the user's cart, creating an order, and clearing the cart.
     *
     * Key Steps:
     * 1. Validate the request data.
     * 2. Retrieve the authenticated user's cart with its associated products and coupon.
     * 3. Ensure the cart contains at least one product.
     * 4. Create a new order and generate a unique order ID.
     * 5. Save the ordered items and adjust prices if a coupon is applied.
     * 6. Remove the coupon from the cart if used.
     * 7. Clear the cart contents after placing the order.
     * 8. Redirect the user to a thank-you page with a success message.
     *
     * @param Request $request The HTTP request containing the order details (name, email, phone, and address).
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Returns a view for the thank-you page or redirects back with an error.
     */
    public function CashOrder(Request $request)
    {
        // Step 1: Validate the order form data
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        // Step 2: Retrieve the authenticated user's cart and its related data
        $cart = Cart::where('user_id', Auth::id())
            ->with(['products', 'coupon', 'products.resturant'])
            ->first();

        // Step 3: Check if the cart contains products
        if ($cart->products()->count() < 1) {
            return redirect()->back()->with([
                'message' => "You must have at least one product in your cart.",
                'alert-type' => 'warning',
            ]);
        }

        $orders = $cart->products->groupBy('client_id');

        foreach ($orders as $resturantId => $products) {
            $amount = 0;
            foreach ($products as $product) {
                $amount += $product->pivot->price * $product->pivot->quantity;
            }

            $total_amount = $amount;
            $coupon_id = NULL;

            if ($cart->coupon) {
                $client_has_coupon = $cart->coupon->client_id;
                if ($client_has_coupon == $resturantId) {
                    // Applay coupon for this order

                    $coupon_id = $cart->coupon->id;
                    $discount = $cart->coupon->discount / 100;
                    $total_amount = $total_amount * (1 - $discount);
                } else {
                    $coupon_id = NULL;
                }
            }

            // Step 4: Create a new order
            $order_id = Order::insertGetId([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'payment_type' => 'Cash On Delivery',
                'payment_method' => 'Cash On Delivery',
                'currency' => 'USD',
                'amount' => $amount,
                'total_amount' => $total_amount,
                'coupon_id' => $coupon_id,
                'client_id' => $resturantId,
                'invoice_no' => 'egypt-' . mt_rand(10000000, 99999999),
                'order_date' => Carbon::now()->format('d F Y'),
                'order_month' => Carbon::now()->format('F'),
                'order_year' => Carbon::now()->format('Y'),
                'status' => 'Pending',
                'created_at' => Carbon::now(),
            ]);

            // Step 5: Save the order items and adjust prices if necessary
            foreach ($products as $product) {
                $price = $product->pivot->price;
                $total_price = $product->pivot->total_price;

                // Apply coupon discount if valid
                if ($cart->coupon && $cart->coupon->client_id == $product->client_id) {
                    $discount = $cart->coupon->discount / 100;
                    $price *= (1 - $discount);
                    $total_price *= (1 - $discount);
                }

                // Insert product details into the order items table
                OrderItem::insert([
                    'order_id' => $order_id,
                    'product_id' => $product->id,
                    'client_id' => $resturantId,
                    'quantity' => $product->pivot->quantity,
                    'price' => $price,
                    'total_price' => $total_price,
                    'created_at' => Carbon::now(),
                ]);
            }

            Payment::create([
                'order_id' => $order_id,
                'client_id' => $resturantId,
                'amount_paid' => $total_amount,
                'payment_method' => 'Cash On Delivery'
            ]);

            // Notifications To Admins
            $users = Admin::where('status', 1)->latest()->get();
            Notification::send($users, new OrderComplete($request->name, $order_id));

            // Notification To Client
            $client = Client::find($resturantId);
            Notification::send($client, new OrderComplete($request->name, $order_id));
        }


        // Step 6: Remove coupon from the cart if applied
        if ($cart->coupon) {
            $cart->update(['coupon_id' => null]);
        }

        // Step 7: Clear the cart contents
        CartController::clearCart($cart);


        // Step 8: Return a success message and thank-you page
        $notification = [
            'message' => 'Order Placed Successfully',
            'alert-type' => 'success',
        ];

        return view('frontend.checkout.thanks')->with($notification);
    }
    //End Method

    public function stripeOrder(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        // Step 2: Retrieve the authenticated user's cart and its related data
        $cart = Cart::where('user_id', Auth::id())
            ->with(['products', 'coupon', 'products.resturant'])
            ->first();


        // Step 3: Check if the cart contains products
        if ($cart->products()->count() < 1) {
            return redirect()->back()->with([
                'message' => "You must have at least one product in your cart.",
                'alert-type' => 'warning',
            ]);
        }

        $amount_cart = $cart->total_price;
        $total_amount_cart = $cart->net_total_price;

        \Stripe\Stripe::setApiKey('sk_test_51QQPtHGIoHjMk8HFmCGf4222dC55oWz73aXp68NZAuHRLuwUM4MKO52HVe2zgXLzehThRJSjKK8Zo0HnUUGoWxzK00CNRGt4ci');

        $token = $_POST['stripeToken'];

        $charge = \Stripe\Charge::create([
            'amount' => $total_amount_cart * 100,
            'currency' => 'usd',
            'description' => 'EasyFood Delivery',
            'source' => $token,
            'metadata' => ['order_id' => uniqid()]
        ]);

        $orders = $cart->products->groupBy('client_id');

        foreach ($orders as $resturantId => $products) {

            $amount = 0;
            foreach ($products as $product) {
                $amount += $product->pivot->price * $product->pivot->quantity;
            }
            $total_amount = $amount;
            $coupon_id = NULL;

            if ($cart->coupon) {
                $client_has_coupon = $cart->coupon->client_id;
                if ($client_has_coupon == $resturantId) {
                    // Applay coupon for this order

                    $coupon_id = $cart->coupon->id;
                    $discount = $cart->coupon->discount / 100;
                    $total_amount = $total_amount * (1 - $discount);
                } else {
                    $coupon_id = NULL;
                }
            }

            // Step 4: Create a new order
            $order_id = Order::insertGetId([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'payment_type' => $charge->payment_method,
                'payment_method' => 'Stripe',
                'amount' => $amount,
                'total_amount' => $total_amount,
                'currency' => $charge->currency,
                'transaction_id' => $charge->balance_transaction,
                'order_number' => $charge->metadata->order_id,
                'coupon_id' => $coupon_id,
                'client_id' => $resturantId,
                'invoice_no' => 'egypt-' . mt_rand(10000000, 99999999),
                'order_date' => Carbon::now()->format('d F Y'),
                'order_month' => Carbon::now()->format('F'),
                'order_year' => Carbon::now()->format('Y'),
                'status' => 'Pending',
                'created_at' => Carbon::now(),
            ]);

            // Step 5: Save the order items and adjust prices if necessary
            foreach ($products as $product) {
                $price = $product->pivot->price;
                $total_price = $product->pivot->total_price;

                // Apply coupon discount if valid
                if ($cart->coupon && $cart->coupon->client_id == $product->client_id) {
                    $discount = $cart->coupon->discount / 100;
                    $price *= (1 - $discount);
                    $total_price *= (1 - $discount);
                }

                // Insert product details into the order items table
                OrderItem::insert([
                    'order_id' => $order_id,
                    'product_id' => $product->id,
                    'client_id' => $resturantId,
                    'quantity' => $product->pivot->quantity,
                    'price' => $price,
                    'total_price' => $total_price,
                    'created_at' => Carbon::now(),
                ]);
            }

            Payment::create([
                'order_id' => $order_id,
                'client_id' => $resturantId,
                'amount_paid' => $total_amount,
                'payment_method' => 'Stripe',
                'payment_type' => $charge->payment_method,
                'currency' => $charge->currency,
                'status' => 'successful',
                'transaction_id' => $charge->balance_transaction,
                'order_number' => $charge->metadata->order_id,
            ]);

            $client = Client::find($resturantId);
            $this->addBalanceToResturant($client->resturant_balance_id, $total_amount,'stripe');

            // Notification To Admins
            $users = Admin::where('status', 1)->latest()->get();
            Notification::send($users, new OrderComplete($request->name, $order_id));

            // Notification To Client
            Notification::send($client, new OrderComplete($request->name, $order_id));
        }


        // Step 6: Remove coupon from the cart if applied
        if ($cart->coupon) {
            $cart->update(['coupon_id' => null]);
        }

        // Step 7: Clear the cart contents
        CartController::clearCart($cart);

        $notification = array(
            'message' => 'Order Placed Successfully',
            'alert-type' => 'success'
        );

        return view('frontend.checkout.thanks')->with($notification);
    }
    //End Method

    public function markAsReadForAdmin(Request $request, $notificationId)
    {
        $user = Auth::guard('admin')->user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['count' => $user->unreadNotifications()->count()]);
    }
    //End Method

    public function markAsReadForClient(Request $request, $notificationId)
    {
        $user = Auth::guard('client')->user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['count' => $user->unreadNotifications()->count()]);
    }
    //End Method

    public static function addBalanceToResturant($resturant_balance_id, $total_new_order, $payment_way = 'COD')
    {
        $resturant_balance = RestaurantBalance::find($resturant_balance_id);
        $new_total_balance = $resturant_balance->total_balance + $total_new_order;

        if ($payment_way == 'stripe') {
            $new_stripe_balance = $resturant_balance->stripe_balance + $total_new_order;
            $resturant_balance->update([
                'total_balance' => $new_total_balance,
                'stripe_balance' => $new_stripe_balance,
            ]);
        } else {
            $new_cash_on_delivery_balance = $resturant_balance->cash_on_delivery_balance + $total_new_order;
            $resturant_balance->update([
                'total_balance' => $new_total_balance,
                'cash_on_delivery_balance' => $new_cash_on_delivery_balance,
            ]);
        }
    }

}
