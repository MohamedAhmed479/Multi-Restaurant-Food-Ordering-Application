@extends('frontend.master')

@section('homepage-search-block')
    <section class="pt-5 pb-5 homepage-search-block position-relative">
        <div class="banner-overlay"></div>
        <div class="container">
            <div class="row d-flex align-items-center py-lg-4">
                <div class="col-lg-8 mx-auto">
                    <div class="homepage-search-title text-center">
                        <h1 class="mb-2 display-4 text-shadow text-white font-weight-normal"><span
                                class="font-weight-bold">Discover the best food & drinks in Egypt
                            </span></h1>
                        <h5 class="mb-5 text-shadow text-white-50 font-weight-normal">Lists of top restaurants,
                            cafes, pubs, and bars in Cairo, based on trends</h5>
                    </div>
                    <div class="homepage-search-form">
                        <form class="form-noborder">
                            <div class="form-row">
                                <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                    <div class="location-dropdown">
                                        <i class="icofont-location-arrow"></i>
                                        <select class="custom-select form-control-lg">
                                            <option> Quick Searches </option>
                                            <option> Breakfast </option>
                                            <option> Lunch </option>
                                            <option> Dinner </option>
                                            <option> Cafés </option>
                                            <option> Delivery </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-12 form-group">
                                    <input type="text" placeholder="Enter your delivery location"
                                        class="form-control form-control-lg">
                                    <a class="locate-me" href="#"><i class="icofont-ui-pointer"></i> Locate
                                        Me</a>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 form-group">
                                    <a href="listing.html" class="btn btn-primary btn-block btn-lg btn-gradient">Search</a>
                                    <!--<button type="submit" class="btn btn-primary btn-block btn-lg btn-gradient">Search</button>-->
                                </div>
                            </div>
                        </form>
                    </div>
                    <h6 class="mt-4 text-shadow text-white font-weight-normal">E.g. Beverages, Pizzas, Chinese,
                        Bakery...</h6>
                    <div class="owl-carousel owl-carousel-category owl-theme">

                        @php
                            $products = App\Models\Product::latest()->limit(8)->get();
                        @endphp
                        @foreach ($products as $product)
                            <div class="item">
                                <div class="osahan-category-item">
                                    <a href="#">

                                        <img class="img-fluid" src="{{ asset($product->image) }}" alt="">
                                        <h6>{{ Str::limit($product->name, 8) }}</h6>
                                        <p>${{ $product->price }}</p>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@php
    $clients = App\Models\Client::where('status', '1')->latest()->get();
@endphp

@section('content')
    <section class="section pt-5 pb-5 products-section">
        <div class="container">
            <div class="section-header text-center">
                <h2>Popular Resturants</h2>
                <p>Top restaurants, cafes, pubs, and bars in Cairo, based on trends</p>
                <span class="line"></span>
            </div>

            {{-- Items Here --}}
            <div class="row">
                @foreach ($clients as $client)
                    {{-- ========================================================= --}}
                    {{-- ChatGPT Code --}}
                    @php
                        $menuNames = App\Models\Product::where('client_id', $client->id)
                            ->with('menu:id,name')
                            ->limit(3)
                            ->get()
                            ->pluck('menu.name')
                            ->implode(' • ');

                        $coupon = App\Models\Coupon::where('client_id', $client->id)
                            ->where('status', '1')
                            ->first();

                        $reviewcount = App\Models\Review::where('client_id', $client->id)
                            ->where('status', 1)
                            ->latest()
                            ->get();
                        $avarage = App\Models\Review::where('client_id', $client->id)
                            ->where('status', 1)
                            ->avg('rating');
                    @endphp


                    <div class="col-md-3">
                        <div class="item pb-3">
                            <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                <div class="list-card-image">

                                    <div class="star position-absolute"><span class="badge badge-success"><i
                                                class="icofont-star"></i>{{ number_format($avarage, 1) }}
                                            ({{ count($reviewcount) }}+)
                                        </span></div>

                                    <div class="favourite-heart text-danger position-absolute">
                                        <a aria-label="Add to Wishlist" onclick="addWishList({{ $client->id }}, this)">
                                            <i class="icofont-heart"></i>
                                        </a>
                                    </div>




                                    @if ($coupon)
                                        <div class="member-plan position-absolute"><span
                                                class="badge badge-dark">Promoted</span>
                                        @else
                                            <div class="member-plan position-absolute"><span
                                                    class="badge badge-dark"></span>
                                    @endif

                                </div>
                                <a href="{{ route('resturant.details', ['client' => $client]) }}">
                                    {{-- <a href="{{ route('resturant.details', $client->id) }}"> --}}
                                    <img src="{{ asset("upload/client_images/$client->cover_photo") }}"
                                        class="img-fluid item-img" style="width: 400px; height:200px;">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1"><a href="{{ route('resturant.details', ['client' => $client]) }}"
                                            class="text-black">{{ $client->name }}</a>
                                    </h6>
                                    <p class="text-gray mb-3"> <?php if(empty($menuNames)) { ?> <br> <?php } else { echo $menuNames; } ?>
                                    </p>
                                    
                                </div>
                                <div class="list-card-badge">
                                    @if ($coupon)
                                        <span class="badge badge-success">OFFER</span> <small>{{ $coupon->discount }}%
                                            off | Use Coupon
                                            {{ $coupon->coupon_name }}</small>
                                    @else
                                        <span class="badge badge-danger">OFFER</span>
                                        <small>Right Now There Is No Coupon</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endforeach
            {{-- End Col md-3 --}}
        </div>
        </div>
    </section>


    @if (!Auth::check())
        <section class="section pt-5 pb-5 bg-white becomemember-section border-bottom">
            <div class="container">
                <div class="section-header text-center white-text">
                    <h2>Become a Member</h2>
                    <p>Sign up now</p>
                    <span class="line"></span>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <a href="{{ Route('register') }}" class="btn btn-success btn-lg">
                            Create an Account <i class="fa fa-chevron-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
