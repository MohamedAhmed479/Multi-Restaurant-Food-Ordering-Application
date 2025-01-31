@extends('client.dashboard')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">All Search By Date Order</h4>

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
                            <h3 class="text-danger">Search By Date: {{ $formatDate }}</h3>
                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Invoice</th>
                                        <th>Coupon</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action </th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @php $key = 1; @endphp
                                    @foreach ($orderItemGroupData as $orderGroup)
                                        @foreach ($orderGroup as $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->order->invoice_no }}</td>

                                                @if ($item->order->coupon)
                                                    <td align="center" valign="middle">
                                                        <img src="{{ asset('frontend/img/earn-score-icon.png') }}"
                                                            alt="Product Icon"
                                                            style="width: 40px; height: 40px; margin-right: 10px;">
                                                        <p>{{ $item->order->coupon->coupon_name }} -
                                                            {{ $item->order->coupon->discount }}%</p>

                                                    </td>
                                                @else
                                                    <td align="center" valign="middle">No Coupon</td>
                                                @endif

                                                <td>{{ $item->order->total_amount }}</td>
                                                <td>{{ $item->order->payment_method }}</td>
                                                <td>{{ $item->order->order_date }}</td>
                                                <td><span class="badge bg-primary">{{ $item->order->status }}</span></td>

                                                <td><a href="{{ route('client.order.details', ['order' => $item->order]) }}"
                                                        class="btn btn-info waves-effect waves-light"> <i
                                                            class="fas fa-eye"></i> </a>

                                                </td>
                                            </tr>
                                        @break
                                    @endforeach
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
