<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class UserManageOrderController extends Controller
{
    public function userOrderList()
    {
        $userId = Auth::user()->id;
        $allUserOrder = Order::where('user_id', $userId)->orderBy('id', 'desc')->get();
        return view('frontend.dashboard.order.order_list', compact('allUserOrder'));
    }
    //End Method 

    public function userOrderDetails($id)
    {
        $order = Order::with('user')->where('id', $id)->where('user_id', Auth::id())->first();
        $orderItem = OrderItem::with(['product', 'resturant'])->where('order_id', $id)->orderBy('id', 'desc')->get();

        return view('frontend.dashboard.order.order_details', compact('order', 'orderItem'));
    }
    //End Method 

    public function userInvoiceDownload($id)
    {
        $order = Order::with('user')->where('id', $id)->where('user_id', Auth::id())->first();
        $orderItem = OrderItem::with(['product', 'resturant'])->where('order_id', $id)->orderBy('id', 'desc')->get();
        $totalPrice = $order->total_amount;

        $pdf = Pdf::loadView('frontend.dashboard.order.invoice_download', compact('order', 'orderItem', 'totalPrice'))->setPaper('a4')->setOption([
            'tempDir' => public_path(),
            'chroot' => public_path(),
        ]);
        return $pdf->download('invoice.pdf');
    }
    //End Method 

}
