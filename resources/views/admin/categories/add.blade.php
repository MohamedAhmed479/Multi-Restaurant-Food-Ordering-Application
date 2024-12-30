@extends('admin.dashboard')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Add Category</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Categories</a></li>
                                <li class="breadcrumb-item active">Add</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-9 col-lg-8">

                    <div class="card-body p-4">

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger" role="alert">
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif
                        <form action="{{ Route('admin.store_category') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6">
                                    <div>
                                        <div class="mb-3">
                                            <img id="showImage" src="{{ asset('upload/no_image.jpg') }} " alt=""
                                                class="rounded-circle p-1 bg-primary" width="110">
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-text-input" class="form-label">Category Name</label>
                                            <input class="form-control" type="text" name="name"
                                                id="example-text-input">
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-text-input" class="form-label">Category Image</label>
                                            <input class="form-control" name="image" type="file" id="image">
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit"
                                                class="btn btn-primary waves-effect waves-light">Add</button>
                                        </div>


                                    </div>
                                </div>

                            </div>

                        </form>
                    </div>


                    <!-- end tab content -->
                </div>
                <!-- end col -->



            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <script type="text/javascript">
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files[0]); // التصحيح هنا
            });
        });
    </script>
@endsection
