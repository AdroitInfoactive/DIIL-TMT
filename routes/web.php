<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ChargesController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CollectionTaxController;
use App\Http\Controllers\Admin\InvoiceEntityController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\TermsAndConditionController;
use App\Http\Controllers\Admin\VendorController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    // Route::get('admin/login', [AdminAuthController::class, 'index'])->name('admin.login');
    // Route::get('/', [AdminAuthController::class, 'index'])->name('admin.auth.login');
    /* Route::get('/forget-password', [AdminAuthController::class, 'forgetPassword'])->name('admin.forget-password');
    Route::get('/reset-password', [AdminAuthController::class, 'resetPassword'])->name('admin.reset-password'); */
});

require __DIR__ . '/auth.php';

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    /** Profile Routes **/
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('user/update/{id}', [ProfileController::class, 'updateUserProfile'])->name('user.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    Route::get('user', [ProfileController::class, 'users'])->name('user');
    Route::get('user/edit/{id}', [ProfileController::class,'edit'])->name('user.edit');
    Route::delete('user/delete/{id}', [ProfileController::class,'delete'])->name('user.delete');
    Route::get('user/create', [ProfileController::class,'createUser'])->name('user.create');
    Route::post('save', [ProfileController::class,'save'])->name('user.save');


    /** Collection Category Routes */
    Route::resource('tax', TaxController::class);
    /** Collection Tax Routes */
    Route::get('collection-tax/{tax}', [CollectionTaxController::class, 'index'])->name('collection-tax.show-index');
    Route::post('collection-tax/update-products', [CollectionTaxController::class, 'updateCollectionList'])->name('collection-tax.update-product');
    Route::resource('collection-tax', CollectionTaxController::class);
    /** Product Routes */
    Route::resource('product', ProductController::class);
    /** Client Routes */
    Route::resource('client', ClientController::class);
    /* Size Routes */
    Route::resource('size', SizeController::class);
    /* Brand Routes */
    Route::resource('brand', VendorController::class);
    /* Terms and conditions Routes */
    Route::resource('terms-and-conditions', TermsAndConditionController::class);
    /* Inoice Entity Routes */
    Route::resource('invoice-entity', InvoiceEntityController::class);
    /* Charges Routes */
    Route::resource('charges', ChargesController::class);

    /*order Routes */
    Route::get('order/get-taxes/{id}', [OrderController::class, 'getTaxes'])->name('order.get-taxes');
    Route::post('order/add-product', [OrderController::class, 'addProducts'])->name('order.add-product');
    Route::post('order/update-product/{quoteProdId}', [OrderController::class, 'addProducts'])->name('order.update-product');
    Route::delete('order/remove-product{id}', [OrderController::class, 'removeProducts'])->name('order.remove-product');
    Route::get('order/get-products/{id}', [OrderController::class, 'getProducts'])->name('order.get-products');
    Route::get('order/get-client-details/{id}', [OrderController::class, 'getClientDetails'])->name('order.get-client-details');
    Route::post('order/add-terms', [OrderController::class, 'addTerms'])->name('order.add-terms');
    Route::post('order/add-charges', [OrderController::class, 'addCharges'])->name('order.add-charges');
    Route::get('order/delete/{id}', [OrderController::class, 'deleteOrder'])->name('order.delete');
    Route::get('order/print/{id}', [OrderController::class, 'printOrder'])->name('order.print');
    Route::get('order/revise/{id}', [OrderController::class, 'reviseOrder'])->name('order.revise');
    Route::post('order/status-update', [OrderController::class, 'statusUpdate'])->name('order.status-update');

    Route::get('order/accepted', [OrderController::class, 'accepted'])->name('order.accepted');
    Route::get('order/pending', [OrderController::class, 'pending'])->name('order.pending');
    Route::get('order/rejected', [OrderController::class, 'rejected'])->name('order.rejected');
    Route::get('order/deleted', [OrderController::class, 'deleted'])->name('order.deleted');


    Route::resource('order', OrderController::class);


    /** Setting Routes */
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/general-setting', [SettingController::class, 'UpdateGeneralSetting'])->name('general-setting.update');
    Route::put('/pusher-setting', [SettingController::class, 'UpdatePusherSetting'])->name('pusher-setting.update');
    Route::put('/mail-setting', [SettingController::class, 'UpdateMailSetting'])->name('mail-setting.update');
    Route::put('/logo-setting', [SettingController::class, 'UpdateLogoSetting'])->name('logo-setting.update');
    Route::put('/appearance-setting', [SettingController::class, 'UpdateAppearanceSetting'])->name('appearance-setting.update');
    Route::put('/seo-setting', [SettingController::class, 'UpdateSeoSetting'])->name('seo-setting.update');
});
