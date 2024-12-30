@php
    $client_id_ = Auth::guard('client')->id();
    $client = App\Models\Client::find($client_id_);
    $client_status = $client->status;
@endphp



<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->

            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <!-- Dashboard -->
                <li>
                    <a href="{{ Route('client.dashboard') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                @if ($client_status == 1)
                    <!-- Menus Section -->
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="grid"></i>
                            <span data-key="t-apps">Menu</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ Route('client.menus.all') }}">
                                    <span data-key="t-calendar">All Menus</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ Route('client.menus.add_menu') }}">
                                    <span data-key="t-chat">Add Menu</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Products Section -->
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="box"></i>
                            <span data-key="t-apps">Product</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ Route('client.products.all') }}">
                                    <span data-key="t-calendar">All Products</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ Route('client.products.add') }}">
                                    <span data-key="t-chat">Add Product</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Gallery Section -->
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="image"></i>
                            <span data-key="t-apps">Gallery</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ Route('client.gallery.all') }}">
                                    <span data-key="t-calendar">All Gallery</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ Route('client.gallery.add') }}">
                                    <span data-key="t-chat">Add Gallery</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Coupons Section -->
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="tag"></i>
                            <span data-key="t-apps">Coupons</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ Route('client.coupons.all') }}">
                                    <span data-key="t-calendar">All Coupons</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ Route('client.coupons.add') }}">
                                    <span data-key="t-chat">Add Coupon</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Orders Section -->
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="shopping-cart"></i>
                            <span data-key="t-apps">All Orders</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('all.client.orders') }}">
                                    <span data-key="t-calendar">All Orders</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Payments Section -->
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="credit-card"></i> <!-- استبدل "shopping-cart" بـ "credit-card" -->
                            <span data-key="t-apps">All Payment</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('client.view.payments') }}">
                                    <span data-key="t-calendar">All Payments</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Reports Section -->
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="file-text"></i>
                            <span data-key="t-apps">Manage Reports</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('client.all.reports') }}">
                                    <span data-key="t-calendar">All Reports</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Reviews Section -->
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="star"></i>
                            <span data-key="t-apps">Manage Reviews</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('client.all.reviews') }}">
                                    <span data-key="t-calendar">All Reviews</span>
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
