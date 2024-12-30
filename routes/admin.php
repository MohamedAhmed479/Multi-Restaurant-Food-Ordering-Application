<?php

use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ManageAdminController;
use App\Http\Controllers\Admin\ManageOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Client\ResturantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientEmailVerificationNotificationController;
use App\Http\Controllers\ClientEmailVerificationPromptController;
use App\Http\Controllers\ClientVerifyEmailController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;



// ==========================================================================================================================================
// All Admin Routes
Route::controller(AdminController::class)->prefix('admin/')->name('admin.')->group(function () {

    Route::middleware('admin-auth')->group(function () {
        Route::get('logout', 'adminLogout')->name('logout');
        Route::get('dashboard', 'adminDashboard')->name('dashboard');
        Route::get('profile', 'adminProfile')->name('profile');
        Route::post('profile-store', 'adminProfileStore')->name('profile-store');
        Route::get('change-password', 'adminChangePassword')->name('change-password');
        Route::post('password-update', 'adminPasswordUpdate')->name('password-update');
        Route::get('lock-screen', 'adminLockScreen')->name('lock-screen');
    });

    Route::middleware('admin-guest')->group(function () {
        Route::get('login', 'adminLogin')->name('login');
        Route::post('login_submit', 'adminLoginSubmit')->name('login_submit');
        Route::get('forgot-password', 'adminForgetPassword')->name('forget_password');
        Route::post('password-submit', 'adminPasswordSubmit')->name('password_submit');
        Route::get('reset-password/{token}/{email}', 'adminResetPassword')->name('reset_password');
        Route::post('reset-password-submit', 'adminResetPasswordSubmit')->name('reset_password_submit');
        
    });
});

Route::controller(CategoryController::class)->middleware('admin-auth')->prefix('admin/categories/')->name('admin.')->group(function () {
    Route::get('/', 'allCategories')->name('categories')->middleware(['permission:Category.All']);
    Route::get('add', 'addCategory')->name('add_category')->middleware(['permission:Category.Add']);
    Route::post('store', 'storeCategory')->name('store_category')->middleware(['permission:Category.Add']);
    Route::get('edit/{category}', 'editCategory')->name('edit_category')->middleware(['permission:Category.Edit']);
    Route::post('update/{category}', 'updateCategory')->name('update_category')->middleware(['permission:Category.Edit']);
    Route::get('destroy/{category}', 'destroyCategory')->name('destroy_category')->middleware(['permission:Category.Delete']);
}); // End Admin Categories

Route::controller(CityController::class)->middleware('admin-auth')->prefix('admin/cities/')->name('admin.')->group(function () {
    Route::get('/', 'allCities')->name('cities')->middleware(['permission:City.All']);
    Route::get('add', 'addCity')->name('add_city')->middleware(['permission:City.Add']);
    Route::post('store', 'storeCities')->name('store_city');
    Route::get('/edit/{id}', 'EditCity')->middleware(['permission:City.Edit']);
    Route::post('/update', 'updateCity')->name('update_city');
    Route::get('destroy/{id}', 'destroyCity')->name('destroy_city')->middleware(['permission:City.Delete']);
}); // End Admin Categories

Route::controller(ManageController::class)->prefix('admin/products/')->name('admin.products.')->group(function () {
    Route::get('/', 'allProducts')->name('all')->middleware(['permission:Product.All']);
    Route::get('select-resturant', 'selectResturant')->name('select-resturant')->middleware(['permission:Product.Add']);
    Route::get('add', 'addProduct')->name('add')->middleware(['permission:Product.Add']);
    Route::post('store/{client}', 'storeProduct')->name('store');
    Route::get('edit/{product}', 'editProduct')->name('edit')->middleware(['permission:Product.Edit']);
    Route::post('update/{product}', 'updateProduct')->name('update');
    Route::get('destroy/{product}', 'destroyProduct')->name('destroy')->middleware(['permission:Product.Delete']);
    Route::get('changeStatus', 'changeStatus')->middleware(['permission:Product.Activation']);
}); // End Admin Resturants Products

Route::controller(ManageController::class)->prefix('admin/clients/')->name('admin.clients.')->group(function () {
    Route::get('/pending/restaurant', 'pendingRestaurant')->name('pending.restaurant')->middleware(['permission:Pending.Restaurant']);
    Route::get('/clientchangeStatus', 'clientChangeStatus')->middleware(['permission:Restaurant.Activation']);
    Route::get('/approve/restaurant', 'approveRestaurant')->name('approve.restaurant')->middleware(['permission:Approve.Restaurant']);
});

Route::controller(ManageController::class)->prefix('admin/banners/')->name('admin.banners.')->group(function () {
    Route::get('/', 'allBanners')->name('all')->middleware(['permission:Banner.All']);
    Route::get('add', 'addBanner')->name('add')->middleware(['permission:Banner.Add']);
    Route::post('store', 'storeBanner')->name('store');
    Route::get('edit/{banner}', 'editBanner')->name('edit')->middleware(['permission:Banner.Edit']);
    Route::post('update', 'updateBanner')->name('update');
    Route::get('destroy/{banner}', 'destroyBanner')->name('destroy')->middleware(['permission:Banner.Delete']);
}); // End Admin Resturants Products

Route::controller(ManageOrderController::class)->middleware(['permission:Order.All.Permissions'])->prefix('admin/orders/')->name('admin.orders.')->group(function () {
    Route::get('/pending', 'pendingOrder')->name('pending');
    Route::get('/confirm', 'confirmOrder')->name('confirm');
    Route::get('/processing', 'processingOrder')->name('processing');
    Route::get('/deliverd', 'deliverdOrder')->name('deliverd');
    Route::get('/details/{order}', 'adminOrderDetails')->name('details');
    Route::get('/pening_to_confirm/{order}', 'pendingToConfirm')->name('pening_to_confirm');
    Route::get('/confirm_to_processing/{order}', 'confirmToProcessing')->name('confirm_to_processing');
    Route::get('/processing_to_deliverd/{order}', 'processingToDiliverd')->name('processing_to_deliverd');
}); // End Admin Resturants Products

Route::controller(ReportController::class)->middleware(['permission:Reports.All.Permissions'])->group(function () {
    Route::get('/admin/all/reports', 'aminAllReports')->name('admin.all.reports');
    Route::post('/admin/search/bydate', 'aminSearchByDate')->name('admin.search.bydate');
    Route::post('/admin/search/bymonth', 'adminSearchByMonth')->name('admin.search.bymonth');
    Route::post('/admin/search/byyear', 'adminSearchByYear')->name('admin.search.byyear');
});

Route::controller(ReviewController::class)->group(function () {
    Route::get('/admin/pending/review', 'adminPendingReview')->name('admin.pending.review')->middleware(['permission:Pending.Reviews']);
    Route::get('/admin/approve/review', 'adminApproveReview')->name('admin.approve.review')->middleware(['permission:Approve.Reviews']);
    Route::get('/reviewchangeStatus', 'ReviewChangeStatus')->middleware(['permission:Reviews.Activation']);
});

Route::controller(RoleController::class)->middleware(['admin-auth', 'permission:Roles&permissions.All'])->group(function () {
    // All Permissions Routes
    Route::get('/admin/permissions', 'allPermission')->name('admin.permission.all');
    Route::get('/add/permission', 'addPermission')->name('add.permission');
    Route::post('/store/permission', 'storePermission')->name('permission.store');
    Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission');
    Route::post('/update/permission', 'UpdatePermission')->name('permission.update');
    Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission');
    Route::get('/import/permission', 'importPermission')->name('import.permission');
    Route::get('/export', 'export')->name('export.permission');
    Route::post('/import', 'import')->name('import');
    // ======================================================================================= End Permissions Routes
    // All Roles Routes
    Route::get('/all/roles', 'allRoles')->name('all.roles');
    Route::get('/add/roles', 'addRoles')->name('add.roles');
    Route::post('/store/roles', 'storeRoles')->name('roles.store');
    Route::get('/edit/roles/{id}', 'editRoles')->name('edit.roles');
    Route::post('/update/roles', 'updateRoles')->name('roles.update');
    Route::get('/delete/roles/{id}', 'deleteRoles')->name('delete.roles');
    // ======================================================================================= End Roles Routes
    // All RolesPermission Routes
    Route::get('/add/roles/permission', 'addRolesPermission')->name('add.roles.permission');
    Route::post('/role/permission/store', 'rolePermissionStore')->name('role.permission.store');
    Route::get('/all/roles/permission', 'allRolesPermission')->name('all.roles.permission');
    Route::get('/admin/edit/roles/{id}', 'adminEditRoles')->name('admin.edit.roles');
    Route::post('/admin/roles/update/{id}', 'adminRolesUpdate')->name('admin.roles.update');
    Route::get('/admin/delete/roles/{id}', 'adminDeleteRoles')->name('admin.delete.roles');
    // ======================================================================================= End RolesPermission Routes

});

Route::controller(ManageAdminController::class)->group(function () {
    // All Admins Crud Routes
    Route::get('/all/admin', 'allAdmin')->name('all.admin')->middleware(['permission:Admins.All']);
    Route::get('/add/admin', 'addAdmin')->name('add.admin')->middleware(['permission:Admins.Add']);
    Route::post('/admin/store', 'adminStore')->name('admin.store');
    Route::get('/edit/admin/{id}', 'editadmin')->name('edit.admin')->middleware(['permission:Admins.Edit']);
    Route::post('/admin/update/{id}', 'adminUpdate')->name('admin.update');
    Route::get('/delete/admin/{id}', 'deleteAdmin')->name('delete.admin')->middleware(['permission:Admins.Delete']);
});

Route::post('/admin/mark-notification-as-read/{notification}', [OrderController::class, 'markAsReadForAdmin']);


Route::controller(PaymentController::class)->middleware(['admin-auth', 'permission:Payments'])->group(function () {
    Route::get('admin/payments', 'allPayments')->name('admin.view.payments');
    Route::get('admin/payment/{payment}', 'paymentDetails')->name('admin.show.payment.details');
});