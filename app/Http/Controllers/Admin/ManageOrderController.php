<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;

class ManageOrderController extends Controller
{
    public function pendingOrder()
    {
        $allData = Order::with('coupon', 'items.resturant')->where('status', 'Pending')->orderBy('id', 'desc')->get();

        return view('admin.order.pending_order', compact('allData'));
    }
    //End Method 

    public function confirmOrder()
    {
        $allData = Order::with('coupon', 'items.resturant')->where('status', 'confirm')->orderBy('id', 'desc')->get();

        return view('admin.order.confirm_order', compact('allData'));
    }
    //End Method 

    public function processingOrder()
    {
        $allData = Order::with('coupon', 'items.resturant')->where('status', 'processing')->orderBy('id', 'desc')->get();

        return view('admin.order.processing_order', compact('allData'));
    }
    //End Method 

    public function deliverdOrder()
    {
        $allData = Order::with('coupon', 'items.resturant')->where('status', 'deliverd')->orderBy('id', 'desc')->get();

        return view('admin.order.deliverd_order', compact('allData'));
    }
    //End Method  

    public function adminOrderDetails(Order $order)
    {
        $orderItem = OrderItem::with(['product', 'product.resturant'])->where('order_id', $order->id)->orderBy('id', 'desc')->get();

        $order->load('coupon');

        return view('admin.order.admin_order_details', compact('order', 'orderItem'));
    }
    //End Method 

    public function pendingToConfirm(Order $order)
    {
        $order->update([
            'status' => 'confirm',
            'confirmed_date' => now()
        ]);

        $notification = array(
            'message' => 'Order Confirm Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.orders.confirm')->with($notification);
    }
    //End Method 

    public function confirmToProcessing(Order $order)
    {
        $order->update([
            'status' => 'processing',
            'processing_date' => now(),
            'shipped_date' => now()

        ]);

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.orders.processing')->with($notification);
    }
    //End Method 

    public function processingToDiliverd(Order $order)
    {
        $order->load('items');
        $order->load('resturant');
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

        $order->update([
            'status' => 'deliverd',
            'delivered_date' => now()
        ]);

        if ($order->payment_method == 'Cash On Delivery') {
            OrderController::addBalanceToResturant($order->resturant->resturant_balance_id, $order->total_amount);
        }

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.orders.deliverd')->with($notification);
    }
    // End Method

}
