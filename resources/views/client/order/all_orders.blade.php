@extends('client.dashboard')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Client All Orders</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">

                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">

                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Invoice</th>
                                        <th>Coupon</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action </th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($orderItemGroupData as $key => $orderitem)
                                        @php
                                            $firstItem = $orderitem->first();
                                            $order = $firstItem->order;
                                            $order->load('coupon');
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $order->invoice_no }}</td>
                                            @if ($order->coupon)
                                                <td align="center" valign="middle">
                                                    <img src="{{ asset('frontend/img/earn-score-icon.png') }}"
                                                        alt="Product Icon"
                                                        style="width: 40px; height: 40px; margin-right: 10px;">
                                                    <p>{{ $order->coupon->coupon_name }} - {{ $order->coupon->discount }}%
                                                    </p>

                                                </td>
                                            @else
                                                <td align="center" valign="middle">No Coupon</td>
                                            @endif
                                            <td>${{ $order->total_amount }}</td>
                                            <td>{{ $order->payment_method }}</td>
                                            <td>{{ $order->order_date }}</td>
                                            <td>
                                                @if ($order->status == 'Pending')
                                                    <span class="badge bg-info">Pending</span>
                                                @elseif ($order->status == 'confirm')
                                                    <span class="badge bg-primary">Confirm</span>
                                                @elseif ($order->status == 'processing')
                                                    <span class="badge bg-warning">Processing</span>
                                                @elseif ($order->status == 'deliverd')
                                                    <span class="badge bg-success">Deliverd</span>
                                                @endif
                                            </td>

                                            <td><a href="{{ route('client.order.details', ['order' => $order]) }}"
                                                    class="btn btn-info waves-effect waves-light"> <i
                                                        class="fas fa-eye"></i> </a>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->


        </div> <!-- container-fluid -->
    </div>
@endsection
