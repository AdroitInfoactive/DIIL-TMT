<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CollectionTaxController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\TermsAndCondition;
use App\Http\Controllers\Admin\TermsAndConditionController;
use App\Http\Controllers\Admin\VendorController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // admin/dashboard and name admin.dashboard

    /** Profile Routes **/
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

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

    /** Setting Routes */
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/general-setting', [SettingController::class, 'UpdateGeneralSetting'])->name('general-setting.update');
    Route::put('/pusher-setting', [SettingController::class, 'UpdatePusherSetting'])->name('pusher-setting.update');
    Route::put('/mail-setting', [SettingController::class, 'UpdateMailSetting'])->name('mail-setting.update');
    Route::put('/logo-setting', [SettingController::class, 'UpdateLogoSetting'])->name('logo-setting.update');
    Route::put('/appearance-setting', [SettingController::class, 'UpdateAppearanceSetting'])->name('appearance-setting.update');
    Route::put('/seo-setting', [SettingController::class, 'UpdateSeoSetting'])->name('seo-setting.update');

});
