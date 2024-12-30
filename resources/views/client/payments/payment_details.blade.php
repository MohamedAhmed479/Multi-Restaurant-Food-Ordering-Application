@extends('client.dashboard')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Payment Details</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">

                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row row-cols-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Payment Details
                                <span class="text-danger">Transaction ID: {{ $payment->transaction_id }}</span>
                            </h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered border-primary mb-0">

                                    <tbody>
                                        <tr>
                                            <th width="50%"> Order Invoice: </th>
                                            <td>{{ $payment->order->invoice_no }}</td>
                                        </tr>
                                        <tr>
                                            <th width="50%"> Resturant: </th>
                                            <td>{{ $payment->order->resturant->name }}</td>
                                        </tr>
                                        <tr>
                                            <th width="50%">Payment Method: </th>
                                            <td>{{ $payment->payment_method }}</td>
                                        </tr>
                                        <tr>
                                            <th width="50%">Payment Type: </th>
                                            <td>{{ $payment->payment_type }}</td>
                                        </tr>
                                        <tr>
                                            <th width="50%">Transx Id: </th>
                                            <td>{{ $payment->transaction_id }}</td>
                                        </tr>
                                        <tr>
                                            <th width="50%">Order Number: </th>
                                            <td class="text-danger">{{ $payment->order_number }}</td>
                                        </tr>
                                        <tr>
                                            <th width="50%">Amount Paid: </th>
                                            <td>${{ $payment->amount_paid }}</td>
                                        </tr>
                                        <tr>
                                            <th width="50%">Payment Status: </th>
                                            <td><span class="badge bg-success">{{ $payment->status }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
@endsection
