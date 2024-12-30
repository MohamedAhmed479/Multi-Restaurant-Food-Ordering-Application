@extends('frontend.dashboard.dashboard-master')

@section('dashboard-content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    @php
        $menuNamesString = App\Models\Product::where('client_id', $client->id)
            ->with('menu:id,name')
            ->limit(3)
            ->get()
            ->pluck('menu.name')
            ->implode(' • ');

        $coupons = App\Models\Coupon::where('client_id', $client->id)
            ->where('status', '1')
            ->first();
    @endphp

    <section class="restaurant-detailed-banner">
        <div class="text-center">
            <img class="img-fluid cover" src="{{ asset('upload/client_images/' . $client->cover_photo) }}">
        </div>
        <div class="restaurant-detailed-header">
            <div class="container">
                <div class="row d-flex align-items-end">
                    <div class="col-md-8">
                        <div class="restaurant-detailed-header-left">
                            <img class="img-fluid mr-3 float-left" alt="osahan"
                                src="{{ asset('upload/client_images/' . $client->photo) }}">
                            <h2 class="text-white">{{ $client->name }}</h2>
                            <p class="text-white mb-1"><i class="icofont-location-pin"></i>{{ $client->address }} <span
                                    class="badge badge-success">OPEN</span>
                            </p>
                            <p class="text-white mb-0"><i class="icofont-food-cart"></i> {{ $menuNamesString }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="restaurant-detailed-header-right text-right">
                            <h6 class="text-white mb-0 restaurant-detailed-ratings"><span
                                    class="generator-bg rounded text-white"><i class="icofont-star"></i> {{ $averageRating }}</span>
                                {{ $totalReviews }}
                                Ratings <i class="ml-3 icofont-speech-comments"></i> {{ $totalReviews }} reviews</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <section class="offer-dedicated-nav bg-white border-top-0 shadow-sm">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <ul class="nav" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-order-online-tab" data-toggle="pill"
                                href="#pills-order-online" role="tab" aria-controls="pills-order-online"
                                aria-selected="true">Order Online</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-gallery-tab" data-toggle="pill" href="#pills-gallery"
                                role="tab" aria-controls="pills-gallery" aria-selected="false">Gallery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-restaurant-info-tab" data-toggle="pill"
                                href="#pills-restaurant-info" role="tab" aria-controls="pills-restaurant-info"
                                aria-selected="false">Restaurant Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-reviews-tab" data-toggle="pill" href="#pills-reviews"
                                role="tab" aria-controls="pills-reviews" aria-selected="false">Ratings & Reviews</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    <section class="offer-dedicated-body pt-2 pb-2 mt-4 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="offer-dedicated-body-left">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-order-online" role="tabpanel"
                                aria-labelledby="pills-order-online-tab">

                                @php
                                    $populers = App\Models\Product::where('status', 1)
                                        ->where('client_id', $client->id)
                                        ->where('most_populer', 1)
                                        ->orderBy('id', 'desc')
                                        ->limit(5)
                                        ->get();
                                @endphp
                                <div id="#menu" class="bg-white rounded shadow-sm p-4 mb-4 explore-outlets">
                                    <h6 class="mb-3">Most Popular </h6>
                                    <div class="owl-carousel owl-theme owl-carousel-five offers-interested-carousel mb-3">


                                        @foreach ($populers as $populer)
                                            <div class="item">
                                                <div class="mall-category-item">
                                                    <a href="#">
                                                        <img class="img-fluid" src="{{ asset($populer->image) }}">
                                                        <h6>{{ $populer->name }}</h6>
                                                        @if ($populer->discount_price == null)
                                                            ${{ $populer->price }}
                                                        @else
                                                            $<del>{{ $populer->price }}</del>
                                                            ${{ $populer->discount_price }}
                                                        @endif
                                                        <span class="float-right">
                                                            <a class="btn btn-outline-secondary btn-sm"
                                                                href="{{ route('add_to_cart', ['product' => $populer]) }}">ADD</a>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                                @php
                                    $bestsellers = App\Models\Product::where('status', 1)
                                        ->where('client_id', $client->id)
                                        ->where('best_seller', 1)
                                        ->with('city')
                                        ->orderBy('id', 'desc')
                                        ->limit(3)
                                        ->get();
                                @endphp

                                <div class="row">
                                    <h5 class="mb-4 mt-3 col-md-12">Best Sellers</h5>
                                    @foreach ($bestsellers as $bestseller)
                                        <div class="col-md-4 col-sm-6 mb-4">
                                            <div
                                                class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                                <div class="list-card-image">
                                                    <div class="member-plan position-absolute"><span
                                                            class="badge badge-dark">Promoted</span></div>
                                                    <a href="#">
                                                        <img src="{{ asset($bestseller->image) }}"
                                                            class="img-fluid item-img">
                                                    </a>
                                                </div>
                                                <div class="p-3 position-relative">
                                                    <div class="list-card-body">
                                                        <h6 class="mb-1"><a href="#"
                                                                class="text-black">{{ $bestseller->name }}</a></h6>
                                                        <p class="text-gray mb-2"> {{ $bestseller->city->name }}
                                                        </p>

                                                        <p class="text-gray time mb-0">
                                                            @if ($bestseller->discount_price == null)
                                                                <a class="btn btn-link btn-sm text-black"
                                                                    href="#">${{ $bestseller->price }} </a>
                                                            @else
                                                                $<del>{{ $bestseller->price }}</del>
                                                                <a class="btn btn-link btn-sm text-black"
                                                                    href="#">${{ $bestseller->discount_price }} </a>
                                                            @endif

                                                            <span class="float-right">
                                                                <a class="btn btn-outline-secondary btn-sm"
                                                                    href="{{ route('add_to_cart', ['product' => $bestseller]) }}">ADD</a>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                                @foreach ($menus as $menu)
                                    <div class="row">
                                        <h5 class="mb-4 mt-3 col-md-12">{{ $menu->name }} <small
                                                class="h6 text-black-50">{{ $menu->products->count() }} ITEMS</small></h5>
                                        <div class="col-md-12">
                                            <div class="bg-white rounded border shadow-sm mb-4">

                                                @foreach ($menu->products as $product)
                                                    <div class="menu-list p-3 border-bottom">
                                                        <a class="btn btn-outline-secondary btn-sm  float-right"
                                                            href="{{ route('add_to_cart', ['product' => $product]) }}">ADD</a>

                                                        <div class="media">
                                                            <img class="mr-3 rounded-pill"
                                                                src="{{ asset($product->image) }}"
                                                                alt="Generic placeholder image">
                                                            <div class="media-body">
                                                                <h6 class="mb-1">{{ $product->name }}</h6>
                                                                <p class="text-gray mb-0">
                                                                    ${{ $product->discount_price ? $product->discount_price : $product->price }}
                                                                    ({{ $product->size ?? '' }} cm)
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <div class="tab-pane fade" id="pills-gallery" role="tabpanel"
                                aria-labelledby="pills-gallery-tab">
                                <div id="gallery" class="bg-white rounded shadow-sm p-4 mb-4">
                                    <div class="restaurant-slider-main position-relative homepage-great-deals-carousel">
                                        <div class="owl-carousel owl-theme homepage-ad">

                                            @foreach ($gallerys as $index => $gallery)
                                                <div class="item">
                                                    <img class="img-fluid" src="{{ asset($gallery->gallery_img) }}">
                                                    <div
                                                        class="position-absolute restaurant-slider-pics bg-dark text-white">
                                                        {{ $index + 1 }} of {{ $gallerys->count() }} Photos</div>
                                                </div>
                                            @endforeach

                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-restaurant-info" role="tabpanel"
                                aria-labelledby="pills-restaurant-info-tab">
                                <div id="restaurant-info" class="bg-white rounded shadow-sm p-4 mb-4">
                                    <div class="address-map float-right ml-5">
                                        <div class="mapouter">
                                            <div class="gmap_canvas"><iframe width="300" height="170"
                                                    id="gmap_canvas"
                                                    src="https://maps.google.com/maps?q=university%20of%20san%20francisco&t=&z=9&ie=UTF8&iwloc=&output=embed"
                                                    frameborder="0" scrolling="no" marginheight="0"
                                                    marginwidth="0"></iframe></div>
                                        </div>
                                    </div>
                                    <h5 class="mb-4">Restaurant Info</h5>
                                    <p class="mb-3">{{ $client->address }}

                                    </p>
                                    <p class="mb-2 text-black"><i class="icofont-phone-circle text-primary mr-2"></i>
                                        {{ $client->phone }}</p>
                                    <p class="mb-2 text-black"><i class="icofont-email text-primary mr-2"></i>
                                        {{ $client->email }}</p>
                                    <p class="mb-2 text-black"><i class="icofont-clock-time text-primary mr-2"></i>
                                        <span class="badge badge-success"> OPEN NOW </span>
                                    </p>
                                    <p>
                                        {{ $client->shop_info }}
                                    </p>
                                    <hr class="clearfix">
                                    <p class="text-black mb-0">You can also check the 3D view by using our menue map
                                        clicking here &nbsp;&nbsp;&nbsp; <a class="text-info font-weight-bold"
                                            href="#">Venue Map</a></p>
                                    <hr class="clearfix">
                                    <h5 class="mt-4 mb-4">More Info</h5>
                                    <p class="mb-3">Dal Makhani, Panneer Butter Masala, Kadhai Paneer, Raita, Veg Thali,
                                        Laccha Paratha, Butter Naan</p>
                                    <div class="border-btn-main mb-4">
                                        <a class="border-btn text-success mr-2" href="#"><i
                                                class="icofont-check-circled"></i> Breakfast</a>
                                        <a class="border-btn text-danger mr-2" href="#"><i
                                                class="icofont-close-circled"></i> No Alcohol Available</a>
                                        <a class="border-btn text-success mr-2" href="#"><i
                                                class="icofont-check-circled"></i> Vegetarian Only</a>
                                        <a class="border-btn text-success mr-2" href="#"><i
                                                class="icofont-check-circled"></i> Indoor Seating</a>
                                        <a class="border-btn text-success mr-2" href="#"><i
                                                class="icofont-check-circled"></i> Breakfast</a>
                                        <a class="border-btn text-danger mr-2" href="#"><i
                                                class="icofont-close-circled"></i> No Alcohol Available</a>
                                        <a class="border-btn text-success mr-2" href="#"><i
                                                class="icofont-check-circled"></i> Vegetarian Only</a>
                                    </div>
                                </div>
                            </div>


                            {{-- Book Table --}}
                            <div class="tab-pane fade" id="pills-book" role="tabpanel" aria-labelledby="pills-book-tab">
                                <div id="book-a-table"
                                    class="bg-white rounded shadow-sm p-4 mb-5 rating-review-select-page">
                                    <h5 class="mb-4">Book A Table</h5>
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Full Name</label>
                                                    <input class="form-control" type="text"
                                                        placeholder="Enter Full Name">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Email Address</label>
                                                    <input class="form-control" type="text"
                                                        placeholder="Enter Email address">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Mobile number</label>
                                                    <input class="form-control" type="text"
                                                        placeholder="Enter Mobile number">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date And Time</label>
                                                    <input class="form-control" type="text"
                                                        placeholder="Enter Date And Time">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group text-right">
                                            <button class="btn btn-primary" type="button"> Submit </button>
                                        </div>
                                    </form>
                                </div>
                            </div>



                            {{-- Reviews --}}
                            <div class="tab-pane fade" id="pills-reviews" role="tabpanel"
                                aria-labelledby="pills-reviews-tab">
                                <div id="ratings-and-reviews"
                                    class="bg-white rounded shadow-sm p-4 mb-4 clearfix restaurant-detailed-star-rating">
                                    <span class="star-rating float-right">
                                        <a href="#"><i class="icofont-ui-rating icofont-2x active"></i></a>
                                        <a href="#"><i class="icofont-ui-rating icofont-2x active"></i></a>
                                        <a href="#"><i class="icofont-ui-rating icofont-2x active"></i></a>
                                        <a href="#"><i class="icofont-ui-rating icofont-2x active"></i></a>
                                        <a href="#"><i class="icofont-ui-rating icofont-2x"></i></a>
                                    </span>
                                    <h5 class="mb-0 pt-1">Rate this Place</h5>
                                </div>
                                <div class="bg-white rounded shadow-sm p-4 mb-4 clearfix graph-star-rating">
                                    <h5 class="mb-4">Ratings and Reviews</h5>
                                    <div class="graph-star-rating-header">
                                        <div class="star-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <a href="#"><i
                                                        class="icofont-ui-rating {{ $i <= round($roundedAverageRating) ? 'active' : '' }}"></i></a>
                                            @endfor
                                            <b class="text-black ml-2">{{ $totalReviews }}</b>
                                        </div>
                                        <p class="text-black mb-4 mt-2">Rated {{ $roundedAverageRating }} out of 5</p>
                                    </div>

                                    <div class="graph-star-rating-body">

                                        @foreach ($ratingCounts as $star => $count)
                                            <div class="rating-list">
                                                <div class="rating-list-left text-black">
                                                    {{ $star }} Star
                                                </div>
                                                <div class="rating-list-center">
                                                    <div class="progress">
                                                        <div style="width: {{ $ratingPercentages[$star] }}%"
                                                            aria-valuemax="5" aria-valuemin="0" aria-valuenow="5"
                                                            role="progressbar" class="progress-bar bg-primary">
                                                            <span class="sr-only">{{ $ratingPercentages[$star] }}%
                                                                Complete (danger)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="rating-list-right text-black">
                                                    {{ number_format($ratingPercentages[$star], 2) }}%</div>
                                            </div>
                                        @endforeach

                                    </div>


                                    <div class="graph-star-rating-footer text-center mt-3 mb-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm">Rate and
                                            Review</button>
                                    </div>
                                </div>


                                @php
                                    $reviews = App\Models\Review::with(['user', 'likes'])
                                        ->where('client_id', $client->id)
                                        ->where('status', 1)
                                        ->latest()
                                        ->paginate(5); // تحديد عدد المراجعات لكل صفحة
                                @endphp

                                <div class="bg-white rounded shadow-sm p-4 mb-4 restaurant-detailed-ratings-and-reviews">
                                    <a href="#" class="btn btn-outline-primary btn-sm float-right">Top Rated</a>
                                    <h5 class="mb-1">All Ratings and Reviews</h5>

                                    @foreach ($reviews as $review)
                                        <div class="reviews-members pt-4 pb-4">
                                            <div class="media">
                                                <a href="#">
                                                    <img alt="Generic placeholder image"
                                                        src="{{ !empty($review->user->photo) ? url('upload/user_images/' . $review->user->photo) : url('upload/no_image.jpg') }}"
                                                        class="mr-3 rounded-pill">
                                                </a>
                                                <div class="media-body">
                                                    <div class="reviews-members-header">
                                                        <span class="star-rating float-right">
                                                            @php
                                                                $rating = $review->rating ?? 0;
                                                            @endphp
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $rating)
                                                                    <a href="#"><i
                                                                            class="icofont-ui-rating active"></i></a>
                                                                @else
                                                                    <a href="#"><i
                                                                            class="icofont-ui-rating"></i></a>
                                                                @endif
                                                            @endfor
                                                        </span>
                                                        <h6 class="mb-1"><a class="text-black"
                                                                href="#">{{ $review->user->name }}</a></h6>
                                                        <p class="text-gray">
                                                            {{ Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                    <div class="reviews-members-body">
                                                        <p> {{ $review->comment }} </p>
                                                    </div>
                                                    <div
                                                        class="reviews-members-footer d-flex align-items-center justify-content-center mt-3">
                                                        <!-- Like Button -->
                                                        <form method="POST"
                                                            action="{{ route('review.like', $review->id) }}"
                                                            style="display: inline;">
                                                            @csrf
                                                            <button type="submit" name="like" value="1"
                                                                class="like-btn">
                                                                <i class="icofont-thumbs-up"></i>
                                                                <span>{{ $review->likes->where('like', 1)->count() }}</span>
                                                            </button>
                                                        </form>

                                                        <!-- Dislike Button -->
                                                        <form method="POST"
                                                            action="{{ route('review.like', $review->id) }}"
                                                            style="display: inline;">
                                                            @csrf
                                                            <button type="submit" name="like" value="0"
                                                                class="dislike-btn">
                                                                <i class="icofont-thumbs-down"></i>
                                                                <span>{{ $review->likes->where('like', 0)->count() }}</span>
                                                            </button>
                                                        </form>
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Pagination links -->
                                    <div class="mt-4">
                                        {{ $reviews->links() }}
                                    </div>
                                </div>

                                <div class="bg-white rounded shadow-sm p-4 mb-5 rating-review-select-page">
                                    @guest
                                        <p><b>For Add Resturant Review. You need to login first <a
                                                    href="{{ route('login') }}"> Login Here </a> </b></p>
                                    @else
                                        <style>
                                            .star-rating label {
                                                display: inline-flex;
                                                margin-right: 5px;
                                                cursor: pointer;
                                            }

                                            .star-rating input[type="radio"] {
                                                display: none;
                                            }

                                            .star-rating input[type="radio"]:checked+.star-icon {
                                                color: #dd646e;
                                            }
                                        </style>


                                        <div class="rating-section">
                                            <h5 class="mb-4">Leave Comment</h5>
                                            <p class="mb-2">Rate the Place</p>
                                            <!-- الرسالة التي تعرض عدد النجوم -->
                                            <p id="rating-value" class="text-muted">Hover over the stars to rate</p>

                                            <form method="post" action="{{ route('store.review') }}">
                                                @csrf
                                                <input type="hidden" name="client_id" value="{{ $client->id }}">

                                                <!-- النجوم -->
                                                <div class="mb-4">
                                                    <span class="star-rating">
                                                        <label for="rating-1">
                                                            <input type="radio" name="rating" id="rating-1"
                                                                value="1" hidden>
                                                            <i class="icofont-ui-rating icofont-2x star-icon"></i>
                                                        </label>
                                                        <label for="rating-2">
                                                            <input type="radio" name="rating" id="rating-2"
                                                                value="2" hidden>
                                                            <i class="icofont-ui-rating icofont-2x star-icon"></i>
                                                        </label>
                                                        <label for="rating-3">
                                                            <input type="radio" name="rating" id="rating-3"
                                                                value="3" hidden>
                                                            <i class="icofont-ui-rating icofont-2x star-icon"></i>
                                                        </label>
                                                        <label for="rating-4">
                                                            <input type="radio" name="rating" id="rating-4"
                                                                value="4" hidden>
                                                            <i class="icofont-ui-rating icofont-2x star-icon"></i>
                                                        </label>
                                                        <label for="rating-5">
                                                            <input type="radio" name="rating" id="rating-5"
                                                                value="5" hidden>
                                                            <i class="icofont-ui-rating icofont-2x star-icon"></i>
                                                        </label>
                                                    </span>
                                                </div>

                                                <!-- النص الخاص بالتعليق -->
                                                <div class="form-group">
                                                    <label>Your Comment</label>
                                                    <textarea class="form-control" name="comment" id="comment"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-sm" type="submit">Submit
                                                        Comment</button>
                                                </div>
                                            </form>
                                        </div>


                                    @endguest
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @php
                    use Carbon\Carbon;
                    if (Auth::check()) {
                        $coupon = App\Models\Coupon::where('client_id', $client->id)
                            ->where('validity', '>=', Carbon::now()->format('Y-m-d'))
                            ->latest()
                            ->first();
                    }
                @endphp

                @if (Auth::check())

                    <div class="col-md-4">
                        <div class="pb-2">
                            <div
                                class="bg-white rounded shadow-sm text-white mb-4 p-4 clearfix restaurant-detailed-earn-pts card-icon-overlap">
                                <img class="img-fluid float-left mr-3"
                                    src="{{ asset('frontend/img/earn-score-icon.png') }}">
                                <h6 class="pt-0 text-primary mb-1 font-weight-bold">OFFER</h6>

                                {{-- <pre>{{ print_r(Session::get('coupon'), true) }}</pre> --}}

                                @if ($coupon == null)
                                    <p class="mb-0">No Coupon is Available</p>
                                @else
                                    <p class="mb-0">{{ $coupon->discount }}% off on orders above $99 | Use coupon <span
                                            class="text-danger font-weight-bold">{{ $coupon->coupon_name }}</span></p>
                                @endif

                                <div class="icon-overlap">
                                    <i class="icofont-sale-discount"></i>
                                </div>
                            </div>
                        </div>

                        <div class="generator-bg rounded shadow-sm mb-4 p-4 osahan-cart-item">
                            <h5 class="mb-1 text-white">Your Order</h5>
                            <p class="mb-4 text-white">{{ $cart->products()->count() }} ITEMS</p>
                            <div class="bg-white rounded shadow-sm mb-2">

                                @foreach ($cart->products as $product)
                                    <div class="gold-members p-2 border-bottom">
                                        @php
                                            $total_items_price = $product->pivot->total_price;
                                            if ($cart->coupon && $cart->coupon->client_id == $product->client_id) {
                                                $discount = $cart->coupon->discount / 100;
                                                $total_items_price -= $total_items_price * $discount;
                                            }
                                        @endphp
                                        <p class="text-gray mb-0 float-right ml-1">
                                            ${{ $total_items_price }}</p>
                                        <span class="count-number float-right">
                                            <button class="btn btn-outline-secondary  btn-sm left dec"
                                                data-id="{{ $product->pivot->product_id }}"> <i
                                                    class="icofont-minus"></i>
                                            </button>

                                            <input class="count-number-input" type="text"
                                                value="{{ $product->pivot->quantity }}" readonly="">

                                            <button class="btn btn-outline-secondary btn-sm right inc"
                                                data-id="{{ $product->pivot->product_id }}"> <i class="icofont-plus"></i>
                                            </button>

                                            <button class="btn btn-outline-danger btn-sm right remove"
                                                data-id="{{ $product->pivot->product_id }}"> <i
                                                    class="icofont-trash"></i>
                                            </button>
                                        </span>
                                        <div class="media">
                                            <div class="mr-2"><img src="{{ asset($product->image) }}" width="25px">
                                            </div>

                                            @if ($cart->coupon)
                                                @if ($cart->coupon->client_id == $product->client_id)
                                                    <div class="media-body">
                                                        <p class="mt-1 mb-0 text-black d-inline-block">
                                                            {{ $product->name }}
                                                        </p>
                                                        <img src="{{ asset('frontend/img/earn-score-icon.png') }}"
                                                            alt="Product Icon"
                                                            style="width: 13px; height: 13px; margin-right: 8px;">
                                                    </div>
                                                @else
                                                    <div class="media-body">
                                                        <p class="mt-1 mb-0 text-black">{{ $product->name }}</p>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="media-body">
                                                    <p class="mt-1 mb-0 text-black">{{ $product->name }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            {{-- ========================================================================================================= --}}

                            @if ($cart->coupon)
                                <div class="mb-2 bg-white rounded p-2 clearfix">
                                    <p class="mb-1">Total Items <span
                                            class="float-right text-dark">{{ $cart->products()->count() }}</span></p>

                                    @php
                                        $count_applied = $cart
                                            ->products()
                                            ->where('client_id', $cart->coupon->client_id)
                                            ->count();
                                    @endphp
                                    @if ($count_applied == 1)
                                        <p class="mb-1">Coupon applied for <span
                                                class="float-right text-dark">{{ $count_applied }} Item</span>
                                        </p>
                                    @elseif ($count_applied > 1)
                                        <p class="mb-1">Coupon applied for <span
                                                class="float-right text-dark">{{ $count_applied }} Items</span>
                                        </p>
                                    @endif


                                    <p class="mb-1">Coupon Name<span
                                            class="float-right text-dark">{{ $cart->coupon->coupon_name }} (
                                            {{ $cart->coupon->discount }} %) </span>
                                        <a type="submit" onclick="couponRemove()"><i
                                                class="icofont-ui-delete float-right" style="color: red;"></i></a>
                                    </p>

                                    <p class="mb-1 text-success">Total Coupon Discount
                                        <span class="float-right text-success">
                                            ${{ $cart->total_price - $cart->net_total_price }}
                                        </span>
                                    </p>
                                    <hr />
                                    <h6 class="font-weight-bold mb-0">TO PAY <span class="float-right">
                                            ${{ $cart->net_total_price }}
                                        </span></h6>
                                </div>
                            @else
                                <div class="mb-2 bg-white rounded p-2 clearfix">
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="text" class="form-control" placeholder="Enter promo code"
                                            id="coupon_name">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit" id="button-addon2"
                                                onclick="applyCoupon()"><i class="icofont-sale-discount"></i>
                                                APPLY</button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- ========================================================================================================= --}}


                            <div class="mb-2 bg-white rounded p-2 clearfix">
                                <img class="img-fluid float-left" src="{{ asset('frontend/img/wallet-icon.png') }}">
                                <h6 class="font-weight-bold text-right mb-2">Subtotal : <span class="text-danger">
                                        @if ($cart->coupon)
                                            ${{ $cart->net_total_price }}
                                        @else
                                            ${{ $cart->total_price }}
                                        @endif
                                    </span></h6>
                                <p class="seven-color mb-1 text-right">Extra charges may apply</p>

                            </div>

                            <a href="{{ route('checkout') }}" class="btn btn-success btn-block btn-lg">Checkout <i
                                    class="icofont-long-arrow-right"></i></a>
                        </div>

                        <div class="text-center pt-2 mb-4">

                        </div>
                        <div class="text-center pt-2">

                        </div>
                    </div>

                @endif

            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stars = document.querySelectorAll('.star-icon');
            const ratingValue = document.getElementById('rating-value');
            let selectedRating = 0; // لحفظ التصنيف المختار

            stars.forEach((star, index) => {
                // عند تمرير الماوس
                star.addEventListener('mouseover', () => {
                    if (selectedRating === 0) { // تغيير اللون فقط إذا لم يكن هناك تصنيف محفوظ
                        updateStars(index);
                        ratingValue.textContent =
                            `You selected ${index + 1} star${index > 0 ? 's' : ''}`;
                    }
                });

                // عند إزالة الماوس
                star.addEventListener('mouseout', () => {
                    if (selectedRating === 0) { // إعادة تعيين النجوم إذا لم يكن هناك تصنيف محفوظ
                        resetStars();
                        ratingValue.textContent = 'Hover over the stars to rate';
                    }
                });

                // عند النقر
                star.addEventListener('click', () => {
                    selectedRating = index + 1; // حفظ التصنيف المختار
                    updateStars(index);
                    ratingValue.textContent =
                        `You rated ${selectedRating} star${selectedRating > 1 ? 's' : ''}`;
                    document.querySelector(`input[name="rating"][value="${selectedRating}"]`)
                        .checked = true;
                });
            });

            // وظيفة لتحديث لون النجوم
            function updateStars(index) {
                stars.forEach((s, i) => {
                    s.style.color = i <= index ? '#dd646e' : '#ccc';
                });
            }

            // وظيفة لإعادة تعيين النجوم
            function resetStars() {
                stars.forEach(s => {
                    s.style.color = '#ccc';
                });
            }
        });
    </script>
    <script>
        document.querySelectorAll('.like-button').forEach(button => {
            button.addEventListener('click', function() {
                // إضافة أو إزالة الكلاس 'active' عند النقر
                this.classList.toggle('active');
            });
        });
    </script>

@endsection
