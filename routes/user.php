<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Client\ResturantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientEmailVerificationNotificationController;
use App\Http\Controllers\ClientEmailVerificationPromptController;
use App\Http\Controllers\ClientVerifyEmailController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\FilterController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\UserManageOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Route;



// ==========================================================================================================================================
// All Users Routes

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/dashboard', [UserController::class, 'dashboard'])->middleware('auth')->name('dashboard');


Route::controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('index');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('resturant/details/{client}', 'resturantDetails')->name('resturant.details');
    Route::post('/add-wish-list/{id}', 'addWishList');
    Route::get('/all/wishlist', 'allWishlist')->name('all.wishlist');
    Route::get('/remove/wishlist/{id}', 'removeWishlist')->name('remove.wishlist');
});

Route::controller(CartController::class)->middleware('auth')->group(function () {
    Route::get('/add_to_cart/{product}', 'addToCart')->name('add_to_cart');
    Route::post('/cart/update-quantity', 'updateQuantity')->name('cart.updateQuantity');
    Route::post('/cart/remove', 'removeFromCart')->name('cart.remove');
    Route::post('/apply-coupon', 'applyCoupon');
    Route::get('/remove-coupon', 'couponRemove');
    Route::get('/checkout', 'shopCheckout')->name('checkout');
});

// Route::view('/user/404', 'frontend.errors.404')->name('user.error.404');

Route::controller(OrderController::class)->group(function () {
    Route::post('/cash_order', 'CashOrder')->name('cash_order');
    Route::post('/stripe_order', 'stripeOrder')->name('stripe_order');
});

Route::controller(UserManageOrderController::class)->group(function () {
    Route::get('/user/order/list', 'userOrderList')->name('user.order.list');
    Route::get('/user/order/details/{id}', 'userOrderDetails')->name('user.order.details');
    Route::get('/user/invoice/download/{id}', 'userInvoiceDownload')->name('user.invoice.download');
});

Route::controller(ReviewController::class)->group(function () {
    Route::post('/store/review', 'StoreReview')->name('store.review');
});

Route::post('/review/{review}/like', [ReviewController::class, 'storeFeedback'])->name('review.like');

Route::controller(FilterController::class)->group(function () {
    Route::get('/list/restaurant', 'listRestaurant')->name('list.restaurant');
    Route::get('/filter/products', 'filterProducts')->name('filter.products');
});


require __DIR__ . '/auth.php';
