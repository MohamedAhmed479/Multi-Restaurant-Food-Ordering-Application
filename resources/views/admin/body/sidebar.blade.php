<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                {{-- Dashboard --}}
                <li>
                    <a href="{{ Route('admin.dashboard') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>


                <!-- Categories Section -->
                @if (Auth::guard('admin')->user()->can('Category.Menu'))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="grid"></i>
                            <span data-key="t-apps">Category</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if (Auth::guard('admin')->user()->can('Category.All'))
                                <li>
                                    <a href="{{ Route('admin.categories') }}">
                                        <i data-feather="list"></i>
                                        <span data-key="t-calendar">All Categories</span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::guard('admin')->user()->can('Category.Add'))
                                <li>
                                    <a href="{{ Route('admin.add_category') }}">
                                        <i data-feather="plus"></i>
                                        <span data-key="t-chat">Add Category</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Cities Section -->
                @if (Auth::guard('admin')->user()->can('City.Menu'))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="map"></i>
                            <span data-key="t-apps">Cities</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if (Auth::guard('admin')->user()->can('City.All'))
                                <li>
                                    <a href="{{ Route('admin.cities') }}">
                                        <i data-feather="map-pin"></i>
                                        <span data-key="t-calendar">Cities</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Products Section -->
                @if (Auth::guard('admin')->user()->can('Product.Menu'))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="box"></i>
                            <span data-key="t-apps">Manage Products</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if (Auth::guard('admin')->user()->can('Product.All'))
                                <li>
                                    <a href="{{ Route('admin.products.all') }}">
                                        <i data-feather="list"></i>
                                        <span data-key="t-calendar">All Products</span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::guard('admin')->user()->can('Product.Add'))
                                <li>
                                    <a href="{{ route('admin.products.select-resturant') }}">
                                        <i data-feather="plus"></i>
                                        <span data-key="t-calendar">Add Product</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Banners Section -->
                @if (Auth::guard('admin')->user()->can('Banner.Menu'))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="image"></i>
                            <span data-key="t-apps">Manage Banners</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if (Auth::guard('admin')->user()->can('Banner.All'))
                                <li>
                                    <a href="{{ Route('admin.banners.all') }}">
                                        <i data-feather="list"></i>
                                        <span data-key="t-calendar">All Banners</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Orders Section -->
                @if (Auth::guard('admin')->user()->can('Order.All.Permissions'))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="shopping-cart"></i>
                            <span data-key="t-apps">Manage Orders</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ Route('admin.orders.pending') }}">
                                    <i data-feather="clock"></i>
                                    <span data-key="t-calendar">Pending Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ Route('admin.orders.confirm') }}">
                                    <i data-feather="check"></i>
                                    <span data-key="t-calendar">Confirm Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ Route('admin.orders.processing') }}">
                                    <i data-feather="repeat"></i>
                                    <span data-key="t-calendar">Processing Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ Route('admin.orders.deliverd') }}">
                                    <i data-feather="truck"></i>
                                    <span data-key="t-calendar">Delivered Orders</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif


                <!-- Manage Restaurants Section -->
                @if (Auth::guard('admin')->user()->can('Payments'))
                    {{-- Manage Payments --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="credit-card"></i> <!-- استبدل "shopping-cart" بـ "credit-card" -->
                            <span data-key="t-apps">Manage Payments</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('admin.view.payments') }}">
                                    <i class="mdi mdi-wallet-outline"></i>
                                    <!-- استبدال clock-outline بـ wallet-outline -->
                                    <span data-key="t-calendar">All Payments</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif


                <!-- Manage Restaurants Section -->
                @if (Auth::guard('admin')->user()->can('Restaurant.Menu'))
                    {{-- Manage Restaurants Taps --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="grid"></i>
                            <span data-key="t-apps">Manage Restaurants</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if (Auth::guard('admin')->user()->can('Pending.Restaurant'))
                                <li>
                                    <a href="{{ route('admin.clients.pending.restaurant') }}">
                                        <i class="mdi mdi-clock-outline"></i>
                                        <span data-key="t-calendar">Pending Restaurant</span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::guard('admin')->user()->can('Approve.Restaurant'))
                                <li>
                                    <a href="{{ route('admin.clients.approve.restaurant') }}">
                                        <i class="mdi mdi-check-outline"></i>
                                        <span data-key="t-chat">Approve Restaurant</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Manage Admins Section -->
                @if (Auth::guard('admin')->user()->can('Admins.Menu'))
                    {{-- Manage Admins Taps --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="user-check"></i>
                            <span data-key="t-ui-elements">Manage Admins</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if (Auth::guard('admin')->user()->can('Admins.All'))
                                <li>
                                    <a href="{{ route('all.admin') }}">
                                        <i class="mdi mdi-account-multiple-outline"></i>
                                        <span data-key="t-lightbox">All Admins</span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::guard('admin')->user()->can('Admins.Add'))
                                <li>
                                    <a href="{{ route('add.admin') }}">
                                        <i class="mdi mdi-account-plus-outline"></i>
                                        <span data-key="t-range-slider">Add Admin</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Manage Reports Section -->
                @if (Auth::guard('admin')->user()->can('Reports.All.Permissions'))
                    {{-- Manage Admins Taps --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow" aria-expanded="false">
                            <i data-feather="file-text"></i>
                            <span data-key="t-components">Manage Reports</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if (Auth::guard('admin')->user()->can('Admins.All'))
                                <li>
                                    <a href="{{ route('admin.all.reports') }}">
                                        <i class="mdi mdi-file-chart-outline"></i>
                                        <span data-key="t-alerts">All Reports</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Manage Reviews Section -->
                @if (Auth::guard('admin')->user()->can('Review.Menu'))
                    {{-- Manage Reviews Taps --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="star"></i>
                            <span data-key="t-components">Manage Reviews</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if (Auth::guard('admin')->user()->can('Pending.Reviews'))
                                <li>
                                    <a href="{{ route('admin.pending.review') }}">
                                        <i class="fa fa-clock"></i>
                                        <span data-key="t-lightbox">Pending Review</span>
                                    </a>

                                </li>
                            @endif
                            @if (Auth::guard('admin')->user()->can('Approve.Reviews'))
                                <li>
                                    <a href="{{ route('admin.approve.review') }}">
                                        <i class="mdi mdi-comment-check-outline"></i>
                                        <span data-key="t-range-slider">Approve Review</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Role & Permission Section -->
                @if (Auth::guard('admin')->user()->can('Roles&permissions.All'))
                    {{-- Role & Permission Taps --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="key"></i>
                            <span data-key="t-components">Roles & Permissions</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('admin.permission.all') }}">
                                    <i class="mdi mdi-lock-outline"></i>
                                    <span data-key="t-lightbox">All Permissions</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('all.roles') }}">
                                    <i class="mdi mdi-account-key-outline"></i>
                                    <span data-key="t-range-slider">All Roles</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('add.roles.permission') }}">
                                    <i class="fa fa-key"></i>
                                    <span data-key="t-range-slider">Role In Permission</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('all.roles.permission') }}">
                                    <i class="mdi mdi-key-outline"></i>
                                    <span data-key="t-range-slider">All Role In Permission</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
