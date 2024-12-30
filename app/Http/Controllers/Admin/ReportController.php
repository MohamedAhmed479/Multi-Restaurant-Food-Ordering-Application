<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function aminAllReports()
    {
        return view('admin.report.all_report');
    }
    // End Method

    public function aminSearchByDate(Request $request)
    {
        $date = new DateTime($request->date);
        $formatDate = $date->format('d F Y');

        $orderDate = Order::with('coupon')->where('order_date', $formatDate)->latest()->get();
        return view('admin.report.search_by_date', compact('orderDate', 'formatDate'));
    }
    // End Method 

    public function adminSearchByMonth(Request $request)
    {
        $month = $request->month;
        $years = $request->year_name;

        $orderMonth = Order::with('coupon')->where('order_month', $month)->where('order_year', $years)->latest()->get();
        return view('admin.report.search_by_month', compact('orderMonth', 'month', 'years'));
    }
    // End Method 

    public function adminSearchByYear(Request $request)
    {
        $years = $request->year;

        $orderYear = Order::with('coupon')->where('order_year', $years)->latest()->get();
        return view('admin.report.search_by_year', compact('orderYear', 'years'));
    }
    // End Method 

    public function clientAllReports()
    {
        return view('client.report.all_report');
    }
    // End Method 

    public function clientSearchByDate(Request $request)
    {
        $date = new DateTime($request->date);
        $formatDate = $date->format('d F Y');

        $cid = Auth::guard('client')->id();

        $orders = Order::where('order_date', $formatDate)
            ->whereHas('items', function ($query) use ($cid) {
                $query->where('client_id', $cid);
            })
            ->latest()
            ->get();

        $orderItemGroupData = OrderItem::with(['order.coupon', 'product'])
            ->whereIn('order_id', $orders->pluck('id'))
            ->where('client_id', $cid)
            ->orderBy('order_id', 'desc')
            ->get()
            ->groupBy('order_id');

        return view('client.report.search_by_date', compact('orderItemGroupData', 'formatDate'));
    }
    // End Method 


    public function clientSearchByMonth(Request $request)
    {
        $month = $request->month;
        $years = $request->year_name;

        $cid = Auth::guard('client')->id();

        $orders = Order::where('order_month', $month)->where('order_year', $years)
            ->whereHas('items', function ($query) use ($cid) {
                $query->where('client_id', $cid);
            })
            ->latest()
            ->get();

        $orderItemGroupData = OrderItem::with(['order.coupon', 'product'])
            ->whereIn('order_id', $orders->pluck('id'))
            ->where('client_id', $cid)
            ->orderBy('order_id', 'desc')
            ->get()
            ->groupBy('order_id');

        return view('client.report.search_by_month', compact('orderItemGroupData', 'month', 'years'));
    }
    // End Method 

    public function clientSearchByYear(Request $request)
    {

        $years = $request->year;

        $cid = Auth::guard('client')->id();

        $orders = Order::where('order_year', $years)
            ->whereHas('items', function ($query) use ($cid) {
                $query->where('client_id', $cid);
            })
            ->latest()
            ->get();

        $orderItemGroupData = OrderItem::with(['order.coupon', 'product'])
            ->whereIn('order_id', $orders->pluck('id'))
            ->where('client_id', $cid)
            ->orderBy('order_id', 'desc')
            ->get()
            ->groupBy('order_id');

        return view('client.report.search_by_year', compact('orderItemGroupData', 'years'));
    }
    // End Method 


}
