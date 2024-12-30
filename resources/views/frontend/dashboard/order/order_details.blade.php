@extends('frontend.dashboard.dashboard-master')

@section('dashboard-content')
    @php
        $id = Auth::user()->id;
        $profileData = App\Models\User::find($id);
    @endphp

    <section class="section pt-4 pb-4 osahan-account-page">
        <div class="container">
            <div class="row">

                @include('frontend.dashboard.partials.sidebar')

                <div class="col-md-9">
                    <div class="osahan-account-page-right rounded shadow-sm bg-white p-4 h-100">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                <h4 class="font-weight-bold mt-0 mb-4">Order Details </h4>



                                <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2">

                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Shipping Details</h4>
                                            </div>

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered border-primary mb-0">

                                                        <tbody>
                                                            <tr>
                                                                <th width="50%">Shipping Name: </th>
                                                                <td>{{ $order->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%">Shipping Phone: </th>
                                                                <td>{{ $order->phone }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%">Shipping Email: </th>
                                                                <td>{{ $order->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%">Shipping Address: </th>
                                                                <td>{{ $order->address }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%">Order Date: </th>
                                                                <td>{{ $order->order_date }}</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->


                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Order Details
                                                    <span class="text-danger">Invoice: {{ $order->invoice_no }}</span>
                                                </h4>
                                            </div>

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered border-primary mb-0">

                                                        <tbody>
                                                            <tr>
                                                                <th width="50%"> Name: </th>
                                                                <td>{{ $order->user->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%"> Phone: </th>
                                                                <td>{{ $order->user->phone }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%"> Email: </th>
                                                                <td>{{ $order->user->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%">Payment Type: </th>
                                                                <td>{{ $order->payment_method }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%">Transx Id: </th>
                                                                <td>{{ $order->transaction_id }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%">Invoice: </th>
                                                                <td class="text-danger">{{ $order->invoice_no }}</td>
                                                            </tr>
                                                            @if ($order->amount != $order->total_amount)
                                                                <tr>
                                                                    <th width="50%">Order Amount Before Discount: </th>
                                                                    <td>${{ $order->amount }}</td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                <th width="50%">Order Amount: </th>
                                                                <td>${{ $order->total_amount }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="50%">Order Status: </th>
                                                                <td><span
                                                                        class="badge bg-success">{{ $order->status }}</span>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->


                                </div> <!-- end row -->


                                <br>
                                {{-- ============================= --}}

                                <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-1">
                                    <div class="col">
                                        <div class="card">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:center;">Image</th>
                                                            <th style="text-align:center;">Product Name</th>
                                                            <th style="text-align:center;">Restaurant Name</th>
                                                            <th style="text-align:center;">Product Code</th>
                                                            <th style="text-align:center;">Quantity</th>
                                                            <th style="text-align:center;">Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orderItem as $item)
                                                            <tr>
                                                                <td style="text-align:center;">
                                                                    <img src="{{ asset($item->product->image) }}"
                                                                        alt="Product Image" style="width:50px; height:50px">
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    {{ $item->product->name }}
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    @if ($item->client_id == null)
                                                                        Owner
                                                                    @else
                                                                        {{ $item->resturant->name }}
                                                                    @endif
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    {{ $item->product->code }}
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    {{ $item->quantity }}
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    {{ $item->price }} <br>
                                                                    Total = ${{ $item->total_price }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div>
                                                    <hr>
                                                    <h4
                                                        style="text-align:center; font-size: 24px; font-weight: bold; color: #333; padding: 10px; border: 2px solid #ddd; border-radius: 8px; background-color: #f8f8f8;">
                                                        Total Price: ${{ $order->total_amount }}
                                                    </h4>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- ============================= --}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
