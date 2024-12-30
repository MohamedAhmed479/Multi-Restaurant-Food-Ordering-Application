@extends('admin.dashboard')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Select The Resturant</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Select The Resturant </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-body p-4">

                            <form id="myForm" action="{{ route('admin.products.add') }}" method="get"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="example-text-input" class="form-label">Resturant Name</label>
                                        <select name="client_id" class="form-select">
                                            <option>Select</option>
                                            @foreach ($clients as $clie)
                                                <option value="{{ $clie->id }}">{{ $clie->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Go To Add
                                        Product</button>
                                </div>

                        </div>
                        </form>
                    </div>
                </div>

                <!-- end tab content -->
            </div>
            <!-- end col -->


            <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container-fluid -->
    </div>
@endsection
