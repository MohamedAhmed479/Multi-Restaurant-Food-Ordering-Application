<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function allPayments()
    {
        $payments = Payment::with('order.resturant')->orderBy('created_at', 'DESC')->get();
        return view('admin.payments.payments', get_defined_vars());
    }

    public function paymentDetails(Payment $payment)
    {
        return view('admin.payments.payment_details', get_defined_vars());
    }

    public function allPaymentsForClient()
    {
        $client_id = Auth::guard('client')->id();
        $payments = Payment::with('order.resturant')->where('client_id', $client_id)->orderBy('created_at', 'DESC')->get();
        return view('client.payments.payments', get_defined_vars());
    }

    public function paymentDetailsForClient(Payment $payment)
    {
        $client_id = Auth::guard('client')->id();
        if ($client_id != $payment->client_id) {
            return back();
        }
        return view('client.payments.payment_details', get_defined_vars());
    }
}
