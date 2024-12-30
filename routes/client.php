<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Client\ClientChatController;
use App\Http\Controllers\Client\ClientManageOrderController;
use App\Http\Controllers\Client\CouponController;
use App\Http\Controllers\Client\ResturantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientEmailVerificationNotificationController;
use App\Http\Controllers\ClientEmailVerificationPromptController;
use App\Http\Controllers\ClientVerifyEmailController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;


// ==========================================================================================================================================
// All Client Routes
Route::controller(ClientController::class)->prefix('client/')->name('client.')->group(function () {
    Route::middleware(['client-auth', 'clientVerifed', 'Active-Client'])->group(function () {
        Route::get('dashboard', 'clientDashboard')->withoutMiddleware('Active-Client')->name('dashboard');
        // ======================================
        Route::get('profile', 'clientProfile')->name('profile');
        Route::post('profile-store', 'clientProfileStore')->name('profile-store');
        // ======================================
        Route::get('change-password', 'clientChangePassword')->name('change-password');
        Route::post('password-update', 'clientPasswordUpdate')->name('password-update');
        // ======================================
        Route::get('logout', 'clientLogout')->name('logout')->withoutMiddleware('clientVerifed');
        // ======================================


    });

    Route::middleware('client-guest')->group(function () {
        Route::get('login', 'clientLogin')->name('login');
        Route::post('login_submit', 'clientLoginSubmit')->name('login_submit');
        // ======================================
        Route::get('register', 'clientRegister')->name('register');
        Route::post('register', 'clientRegisterStore')->name('register-store');
        // ======================================
        Route::get('forgot-password', 'clientForgetPassword')->name('forget_password');
        Route::post('password-submit', 'clientPasswordSubmit')->name('password_submit');
        Route::get('reset-password/{token}/{email}', 'clientResetPassword')->name('reset_password');
        Route::post('reset-password-submit', 'clientResetPasswordSubmit')->name('reset_password_submit');
        // ======================================


    });
});

// Client verify email
Route::middleware(['client-auth', 'Active-Client'])->group(function () {
    Route::get('client/verify-email', ClientEmailVerificationPromptController::class)
        ->name('client.verification.notice');

    Route::post('client/email/verification-notification', [ClientEmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('client.verification.send');

    Route::get('client/verify-email/{id}/{hash}', ClientVerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('client.verification.verify');
    // ========================================================================================

}); // End client verification email routes

Route::controller(ResturantController::class)->middleware(['client-auth', 'Active-Client'])->prefix('client/menus')->name('client.menus.')->group(function () {
    Route::get('/', 'allMenus')->name('all');
    Route::get('add', 'addMenu')->name('add_menu');
    Route::post('store', 'storeMenu')->name('store_menu');
    Route::get('edit/{menu}', 'editMenu')->name('edit_menu');
    Route::post('update/{menu}', 'updateMenu')->name('update_menu');
    Route::get('destroy/{menu}', 'destroyMenu')->name('destroy_menu');
}); // End Client Menu routes

Route::controller(ResturantController::class)->middleware(['client-auth', 'Active-Client'])->prefix('client/products')->name('client.products.')->group(function () {
    Route::get('/', 'allProducts')->name('all');
    Route::get('add', 'addProduct')->name('add');
    Route::post('store', 'storeProduct')->name('store');
    Route::get('edit/{product}', 'editProduct')->name('edit');
    Route::post('update/{product}', 'updateProduct')->name('update');
    Route::get('destroy/{product}', 'destroyProduct')->name('destroy');
    Route::get('changeStatus', 'changeStatus');
}); // End Client Product routes

Route::controller(ResturantController::class)->middleware(['client-auth', 'Active-Client'])->prefix('client/gallery')->name('client.gallery.')->group(function () {
    Route::get('/', 'allGallery')->name('all');
    Route::get('add', 'addGallery')->name('add');
    Route::post('store', 'storeGallery')->name('store');
    Route::get('edit/{gallery}', 'editGallery')->name('edit');
    Route::post('update/{gallery}', 'updateGallery')->name('update');
    Route::get('destroy/{gallery}', 'destroyGallery')->name('destroy');
}); // End Client Gallery routes

Route::controller(CouponController::class)->middleware(['client-auth', 'Active-Client'])->prefix('client/coupons')->name('client.coupons.')->group(function () {
    Route::get('/', 'allCoupons')->name('all');
    Route::get('add', 'addCoupon')->name('add');
    Route::post('store', 'storeCoupon')->name('store');
    Route::get('edit/{coupon}', 'editCoupon')->name('edit');
    Route::post('update/{coupon}', 'updateCoupon')->name('update');
    Route::get('destroy/{coupon}', 'destroyCoupon')->name('destroy');
}); // End Client Coupon routes

Route::get('client/inActive', function () {
    return view('client.errors.404');
})->middleware('client-auth')->name('client.error.inactive');


Route::controller(ClientManageOrderController::class)->group(function () {
    Route::get('/all/client/orders', 'allClientOrders')->name('all.client.orders');
    Route::get('/client/order/details/{order}', 'clientOrderDetails')->name('client.order.details');
    Route::get('/pening_to_confirm/{order}', 'pendingToConfirm')->name('pening_to_confirm');
    Route::get('/confirm_to_processing/{order}', 'confirmToProcessing')->name('confirm_to_processing');
    Route::get('/processing_to_deliverd/{order}', 'processingToDiliverd')->name('processing_to_deliverd');
});

Route::controller(ReportController::class)->group(function () {
    Route::get('/client/all/reports', 'clientAllReports')->name('client.all.reports');
    Route::post('/client/search/bydate', 'clientSearchByDate')->name('client.search.bydate');
    Route::post('/client/search/bymonth', 'clientSearchByMonth')->name('client.search.bymonth');
    Route::post('/client/search/byyear', 'clientSearchByYear')->name('client.search.byyear');
});

Route::controller(ReviewController::class)->group(function () {
    Route::get('/client/all/reviews', 'clientAllReviews')->name('client.all.reviews');
});

Route::post('/client/mark-notification-as-read/{notification}', [OrderController::class, 'markAsReadForClient']);

Route::controller(PaymentController::class)->middleware('client-auth')->group(function() {
    Route::get('resturant/payments', 'allPaymentsForClient')->name('client.view.payments');
    Route::get('resturant/payment/{payment}', 'paymentDetailsForClient')->name('client.show.payment.details');
});

