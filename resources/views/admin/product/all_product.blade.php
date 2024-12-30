@extends('admin.dashboard')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">All Product</h4>

                        <div class="page-title-right">
                            @if (Auth::guard('admin')->user()->can('Product.Add'))
                                <ol class="breadcrumb m-0">
                                    <a href="{{ route('admin.products.select-resturant') }}"
                                        class="btn btn-primary waves-effect waves-light">Add Product</a>
                                </ol>
                            @endif
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
                                        <th>Image</th>
                                        <th>Resturant Name</th>
                                        <th>Name</th>
                                        <th>Menu</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Action </th>
                                        <th>Status</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($products as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><img src="{{ asset($item->image) }}" alt=""
                                                    style="width: 70px; height:40px;"></td>
                                            <td>{{ $item->resturant->name }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->menu->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->price }}</td>
                                            <td>
                                                @if ($item->discount_price == null)
                                                    <span class="badge bg-danger">No Discount</span>
                                                @else
                                                    @php
                                                        $amount = $item->price - $item->discount_price;
                                                        $discount = ($amount / $item->price) * 100;
                                                    @endphp
                                                    <span class="badge bg-danger">{{ round($discount) }}%</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if (Auth::guard('admin')->user()->can('Product.Edit'))
                                                    <a href="{{ route('admin.products.edit', ['product' => $item]) }}"
                                                        class="btn btn-info waves-effect waves-light"> <i
                                                            class="fas fa-edit"></i> </a>
                                                @endif

                                                @if (Auth::guard('admin')->user()->can('Product.Delete'))
                                                    <a href="{{ route('admin.products.destroy', ['product' => $item]) }}"
                                                        class="btn btn-danger waves-effect waves-light" id="delete"><i
                                                            class="fas fa-trash"></i></a>
                                                @endif

                                            </td>
                                            <td>
                                                <input data-id="{{ $item->id }}" class="toggle-class" type="checkbox"
                                                    data-onstyle="success" data-offstyle="danger" data-toggle="toggle"
                                                    data-on="Active" data-off="Inactive"
                                                    {{ $item->status ? 'checked' : '' }}>
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

    <script type="text/javascript">
        $(function() {
            $('.toggle-class').change(function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '/client/products/changeStatus',
                    data: {
                        'status': status,
                        'id': id
                    },
                    success: function(data) {
                        // console.log(data.success)

                        // Start Message 

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        })
                        if ($.isEmptyObject(data.error)) {

                            Toast.fire({
                                type: 'success',
                                title: data.success,
                            })

                        } else {

                            Toast.fire({
                                type: 'error',
                                title: data.error,
                            })
                        }

                        // End Message   


                    }
                });
            })
        })
    </script>
@endsection
