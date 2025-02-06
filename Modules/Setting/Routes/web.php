<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\ColorController;
use Modules\Setting\Http\Controllers\DesignController;
use Modules\Setting\Http\Controllers\ExchangeRateController;
use Modules\Setting\Http\Controllers\FocusController;
use Modules\Setting\Http\Controllers\IndexLensController;
use Modules\Setting\Http\Controllers\SettingController;
use Modules\Setting\Http\Controllers\SizeController;
use Modules\Setting\Http\Controllers\MoneySafeController;
use Modules\Setting\Http\Controllers\SpecialAdditionController;
use Modules\Setting\Http\Controllers\StoreController;
use Modules\Setting\Http\Controllers\StorePosController;
use Modules\Setting\Http\Controllers\TaxController;
use Modules\Setting\Http\Controllers\SpecialBaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone'],'prefix' => 'dashboard','as'=>'admin.'], function () {



    Route::get('stores/get-dropdown', [StoreController::class,'getDropdown'])->name('stores.getDropdown');
    Route::post('stores/fetch_branch_stores',[StoreController::class,'fetch_branch_stores'])->name('stores.fetch_branch_stores');
    Route::resource('store',StoreController::class);


    Route::get('store-pos/get-pos-details-by-store/{store_id}', [StorePosController::class,'getPosDetailsByStore']);
    Route::resource('store-pos', StorePosController::class);



    Route::get('exchange-rate/get-currency-dropdown', [ExchangeRateController::class,'getExchangeRateCurrencyDropdown']);
    Route::get('exchange-rate/get-exchange-rate-by-currency', [ExchangeRateController::class,'getExchangeRateByCurrency']);
    Route::resource('exchange-rate', ExchangeRateController::class);



    Route::get('tax/get-dropdown-html-by-store', [TaxController::class,'getDropdownHtmlByStore']);
    Route::get('tax/get-dropdown', [TaxController::class,'getDropdown']);
    Route::get('tax/get-details/{tax_id}', [TaxController::class,'getDetails']);
    Route::resource('tax', TaxController::class);


    Route::get('colors/get-dropdown', [ColorController::class,'getDropdown'])->name('colors.dropdown');
    Route::resource('colors', ColorController::class);
    Route::get('sizes/get-dropdown', [SizeController::class,'getDropdown'])->name('sizes.dropdown');
    Route::resource('sizes', SizeController::class);
//Özel Baz
    Route::get('special_base/get-dropdown', [SpecialBaseController::class,'getDropdown'])->name('special_base.dropdown');
    Route::resource('special_bases', SpecialBaseController::class);


    Route::get('special_addition/get-dropdown', [SpecialAdditionController::class,'getDropdown'])->name('special_addition.dropdown');
    Route::resource('special_additions', SpecialAdditionController::class);


    //money safe
    Route::post('money_safe/post-add-money-to-safe', [MoneySafeController::class,'postAddMoneyToSafe'])->name('money_safe.post-add-money-to-safe');
    Route::get('money_safe/get-add-money-to-safe/{id}', [MoneySafeController::class,'getAddMoneyToSafe'])->name('money_safe.get-add-money-to-safe');
    Route::post('money_safe/post-take-money-to-safe', [MoneySafeController::class,'postTakeMoneyFromSafe'])->name('money_safe.post-take-money-to-safe');
    Route::get('money_safe/get-take-money-to-safe/{id}', [MoneySafeController::class,'getTakeMoneyFromSafe'])->name('money_safe.get-take-money-to-safe');
    Route::get('money_safe/watch-money-to-safe-transaction/{id}', [MoneySafeController::class,'getMoneySafeTransactions'])->name('money_safe.watch-money-to-safe-transaction');
    Route::resource('money_safe', MoneySafeController::class);

    Route::get('index_lenses/get-dropdown', [IndexLensController::class,'getDropdown'])->name('index_lens.getDropdown');
    Route::resource('index_lenses',IndexLensController::class);


    Route::get('designs/get-dropdown', [DesignController::class,'getDropdown'])->name('design.getDropdown');
    Route::resource('designs',DesignController::class);

    Route::get('foci/get-dropdown', [FocusController::class,'getDropdown'])->name('foci.getDropdown');
    Route::resource('foci',FocusController::class);


    Route::post('settings/update-general-setting', [SettingController::class , 'updateGeneralSetting'])->name('settings.updateGeneralSetting');
    Route::get('settings/get-general-setting', [SettingController::class , 'getGeneralSetting'])->name('settings.getGeneralSetting');
    Route::post('settings/remove-image/{type}', [SettingController::class , 'removeImage'])->name('settings.removeImage');

});
