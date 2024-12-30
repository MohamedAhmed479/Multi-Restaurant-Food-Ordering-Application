@extends('admin.dashboard')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Payments</h4>

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
                                        <th>Order Invoice</th>
                                        <th>Resturant</th>
                                        <th>Amount Paid</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($payments as $key => $payment)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $payment->order->invoice_no }}</td>
                                            <td>{{ $payment->order->resturant->name }}</td>
                                            <td>{{ $payment->amount_paid }} ({{ strToUpper($payment->currency) }})</td>
                                            @if ($payment->status == 'successful')
                                                <td><span class="badge bg-success">{{ $payment->status }}</span></td>
                                            @else
                                                <td><span class="badge bg-danger">{{ $payment->status }}</span></td>
                                            @endif

                                            <td><a href="{{ route('admin.show.payment.details', ['payment' => $payment]) }}"
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
