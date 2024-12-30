<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientManageOrderController extends Controller
{
    public function allClientOrders()
    {
        $clientId = Auth::guard('client')->id();

        $orderItemGroupData = OrderItem::with(['product', 'order'])->where('client_id', $clientId)
            ->orderBy('order_id', 'desc')
            ->get()
            ->groupBy('order_id');

        return view('client.order.all_orders', compact('orderItemGroupData'));
    }
    //End Method

    public function clientOrderDetails(Order $order)
    {
        $client_id = Auth::guard('client')->id();

        $orderItem = OrderItem::with(['product', 'product.resturant'])->where('order_id', $order->id)->where('client_id', $client_id)->orderBy('id', 'desc')->get();
        $order->load('coupon');
        $order->load('resturant');

        if ($order->resturant) {
            if ($order->resturant->id != $client_id) {
                return back();
            }
        } else {
            return back();
        }

        return view('client.order.client_order_details', compact('order', 'orderItem'));
    }
    //End Method

    public function pendingToConfirm(Order $order)
    {
        $client_id = Auth::guard('client')->id();

        $order->load('resturant');

        if ($order->resturant) {
            if ($order->resturant->id != $client_id) {
                return back();
            }
        } else {
            return back();
        }

        $order->update([
            'status' => 'confirm',
            'confirmed_date' => now()
        ]);

        $notification = array(
            'message' => 'Order Confirm Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.client.orders')->with($notification);
    }
    //End Method 

    public function confirmToProcessing(Order $order)
    {
        $client_id = Auth::guard('client')->id();

        $order->load('resturant');

        if ($order->resturant) {
            if ($order->resturant->id != $client_id) {
                return back();
            }
        } else {
            return back();
        }

        $order->update([
            'status' => 'processing',
            'processing_date' => now(),
            'shipped_date' => now()

        ]);

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.client.orders')->with($notification);
    }
    //End Method 

    public function processingToDiliverd(Order $order)
    {
        $client_id = Auth::guard('client')->id();

        $order->load('resturant');

        $order->load('items');
        // هقلل العدد
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            $new_quantity_stock = $product->quantity - $item->quantity;

            $product->update([
                'quantity' => $new_quantity_stock,
            ]);
        }

        // هخلي مدفوع
        Payment::where('order_id', $order->id)->update([
            'status' => 'successful',
            'currency' => 'usd',
        ]);


        if ($order->resturant) {
            if ($order->resturant->id != $client_id) {
                return back();
            }
        } else {
            return back();
        }

        $order->update([
            'status' => 'deliverd',
            'delivered_date' => now()
        ]);

        if($order->payment_method == 'Cash On Delivery') {
            OrderController::addBalanceToResturant($order->resturant->resturant_balance_id, $order->total_amount);
        }

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.client.orders')->with($notification);
    }
    //End Method 


}