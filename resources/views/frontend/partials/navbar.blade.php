<nav class="navbar navbar-expand-lg navbar-dark osahan-nav">
    <div class="container">
        <a class="navbar-brand" href="{{ Route('index') }}"><img alt="logo"
                src="{{ asset('frontend') }}/img/favicon.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ Route('index') }}">Home <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Restaurants
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                        <a class="dropdown-item" href="{{ route('list.restaurant') }}">Listing</a>
                    </div>
                </li>

                @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{ Route('dashboard') }}" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img alt="Generic placeholder image"
                                src="{{ !empty($user->photo) ? asset("upload/user_images/$user->photo") : asset('upload/no_image.jpg') }}"
                                class="nav-osahan-pic rounded-pill"> My Account
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                            <a class="dropdown-item" href="{{ Route('dashboard') }}"><i></i>
                                Dashboard</a>
                            <a class="dropdown-item" href="{{ Route('user.order.list') }}"><i></i>
                                Orders</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i></i> Logout
                            </a>

                        </div>
                    </li>
                @else
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ Route('login') }}"> Login <span
                                    class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                @endif

                @php
                    if (Auth::check()) {
                        $nav_cart = App\Models\Cart::where('user_id', Auth::id()) // البحث عن السلة باستخدام user_id
                            ->with(['products', 'coupon', 'products.resturant']) // تحميل العلاقات 'products' و 'coupon' دفعة واحدة
                            ->first(); // الحصول على السلة الأولى المطابقة للشروط

                        $clients = $nav_cart->products
                            ->pluck('resturant') // استخراج قائمة المطاعم
                            ->unique() // إزالة التكرارات
                            ->values(); // إعادة فهرسة المصفوفة
                    }
                @endphp

                @if (Auth::check())
                    <li class="nav-item dropdown dropdown-cart">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-shopping-basket"></i> Cart
                            <span class="badge badge-success">{{ $nav_cart->products()->count() }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-cart-top p-0 dropdown-menu-right shadow-sm border-0">

                            @foreach ($clients as $client)
                                <div class="dropdown-cart-top-header p-4">
                                    <img class="img-fluid mr-3" alt="osahan"
                                        src="{{ asset('upload/client_images/' . $client->photo) }}">
                                    <h6 class="mb-0">{{ $client->name }}</h6>
                                    <p class="text-secondary mb-0">{{ $client->address }}</p>
                                </div>
                            @endforeach


                            <div class="dropdown-cart-top-body border-top p-4">
                                @if ($nav_cart)
                                    @foreach ($nav_cart->products as $product)
                                        <p class="mb-2"><i
                                                class="icofont-ui-press text-danger food-item"></i>{{ $product->name }}
                                            x
                                            {{ $product->pivot->quantity }} <span
                                                class="float-right text-secondary">${{ $product->pivot->total_price }}</span>
                                        </p>
                                    @endforeach
                                @endif

                            </div>
                            <div class="dropdown-cart-top-footer border-top p-4">
                                <p class="mb-0 font-weight-bold text-secondary">Sub Total <span
                                        class="float-right text-dark">
                                        @if ($nav_cart->coupon)
                                            ${{ $nav_cart->net_total_price }}
                                        @else
                                            ${{ $nav_cart->total_price }}
                                        @endif
                                    </span></p>

                            </div>
                            <div class="dropdown-cart-top-footer border-top p-2">
                                <a class="btn btn-success btn-block btn-lg" href="{{ route('checkout') }}">
                                    Checkout</a>
                            </div>
                        </div>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>
