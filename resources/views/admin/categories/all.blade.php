@extends('admin.dashboard')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">All Categories</h4>
                        <div class="page-title-right">
                            @if (Auth::guard('admin')->user()->can('Category.Add'))
                                <ol class="breadcrumb m-0">
                                    <a href="{{ Route('admin.add_category') }}"
                                        class="btn btn-primary waves-effect waves-light">Add Category</a>
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
                                        <th>Category Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($categories as $key => $category)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ asset($category->image) }}" alt=""
                                                    style="width: 70px; height:40px;">
                                            </td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                @if (Auth::guard('admin')->user()->can('Category.Edit'))
                                                    <a href="{{ Route('admin.edit_category', ['category' => $category]) }}"
                                                        class="btn btn-success waves-effect waves-light">Edit</a>
                                                @endif
                                                @if (Auth::guard('admin')->user()->can('Category.Delete'))
                                                    <a href="{{ Route('admin.destroy_category', ['category' => $category]) }}"
                                                        class="btn btn-danger waves-effect waves-light"
                                                        id="delete">Delete</a>
                                                @endif
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
    <!-- End Page-content -->
@endsection
