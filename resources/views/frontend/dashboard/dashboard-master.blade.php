<!doctype html>
<html lang="en">

@include('frontend.dashboard.partials.head')

<body>

    @if (Auth::check())
        @include('frontend.dashboard.partials.modals')
    @endif

    @include('frontend.dashboard.partials.header')


    @yield('dashboard-content')

    @include('frontend.dashboard.partials.footer')


    @include('frontend.dashboard.partials.scripts')

</body>

</html>
