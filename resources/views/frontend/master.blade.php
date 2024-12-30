<!doctype html>
<html lang="en">

@include('frontend.partials.head')

<body>
    <div class="homepage-header">
        <div class="overlay"></div>

        @include('frontend.partials.navbar')

        @yield('homepage-search-block')

    </div>

    @include('frontend.partials.ads')
    
    @yield('content')

    @include('frontend.partials.static_sections')

    @include('frontend.partials.footer')

    @include('frontend.partials.scripts')
</body>

</html>
