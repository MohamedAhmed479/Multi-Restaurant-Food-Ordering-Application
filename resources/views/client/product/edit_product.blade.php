@extends('client.dashboard')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Edit Product</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Edit Product </li>
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
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger" role="alert">
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                            <form id="myForm" action="{{ route('client.products.update', ['product' => $product]) }}"
                                method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="example-text-input" class="form-label">Category Name</label>
                                            <select name="category_id" class="form-select">
                                                <option>Select</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="example-text-input" class="form-label">Menu Name</label>
                                            <select name="menu_id" class="form-select">
                                                <option selected="" disabled="">Select</option>
                                                @foreach ($menus as $menu)
                                                    <option value="{{ $menu->id }}"
                                                        {{ $menu->id == $product->menu_id ? 'selected' : '' }}>
                                                        {{ $menu->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="example-text-input" class="form-label">City Name</label>
                                            <select name="city_id" class="form-select">
                                                <option>Select</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        {{ $city->id == $product->city_id ? 'selected' : '' }}>
                                                        {{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="example-text-input" class="form-label">Product Name</label>
                                            <input class="form-control" type="text" name="name"
                                                id="example-text-input" value="{{ $product->name }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="example-text-input" class="form-label">Price</label>
                                            <input class="form-control" type="number" name="price"
                                                id="example-text-input" value="{{ $product->price }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="example-text-input" class="form-label">Discount Price</label>
                                            <input class="form-control" type="number" name="discount_price"
                                                id="example-text-input" value="{{ $product->discount_price }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="example-text-input" class="form-label">Size</label>
                                            <input class="form-control" type="number" name="size"
                                                id="example-text-input" value="{{ $product->size }}">
                                        </div>
                                    </div>


                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="example-text-input" class="form-label">Product Quantity </label>
                                            <input class="form-control" type="number" name="quantity"
                                                id="example-text-input" value="{{ $product->quantity }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="example-text-input" class="form-label">Product Image </label>
                                            <input class="form-control" name="image" type="file" id="image">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group mb-3">
                                            <img id="showImage" src="{{ asset($product->image) }}" alt=""
                                                class="rounded-circle p-1 bg-primary" width="110">
                                        </div>
                                    </div>

                                    <div class="form-check mt-2">
                                        <input class="form-check-input" name="best_seller" type="checkbox"
                                            id="formCheck2" value="1"
                                            {{ $product->best_seller == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="formCheck2">
                                            Best Seller
                                        </label>
                                    </div>

                                    <div class="form-check mt-2">
                                        <input class="form-check-input" name="most_populer" type="checkbox"
                                            id="formCheck2" value="1"
                                            {{ $product->most_populer == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="formCheck2">
                                            Most Populer
                                        </label>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save
                                            Changes</button>
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            })
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    menu_id: {
                        required: true,
                    },

                },
                messages: {
                    name: {
                        required: 'Please Enter Name',
                    },
                    menu_id: {
                        required: 'Please Select One Menu',
                    },


                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>
@endsection
