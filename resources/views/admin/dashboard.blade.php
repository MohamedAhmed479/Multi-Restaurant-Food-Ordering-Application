{{-- <html>
<title>
    Admin Dashboard Page
</title>


<body>
    <h1>Admin Dashboard Page</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    @endif
    @if (Session::has('success'))
        <li>{{ Session::get('success') }}</li>
    @endif


    <a href="{{ Route("admin.logout") }}">Logout</a>

</body>

</html> --}} 




<!doctype html>
<html lang="en">

@include('admin.body.head')

<body>

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('admin.body.header')

        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.body.sidebar')
        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            @yield('content')
            <!-- End Page-content -->

            @include('admin.body.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!-- Right Sidebar -->
    @include('admin.body.right-sidebar')
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    @include('admin.body.scripts')
</body>

</html>
