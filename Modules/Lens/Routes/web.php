<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\ColorController;
use Modules\Setting\Http\Controllers\SettingController;
use Modules\Setting\Http\Controllers\SizeController;
use Modules\Setting\Http\Controllers\MoneySafeController;
use Modules\Setting\Http\Controllers\StoreController;
use Modules\Setting\Http\Controllers\StorePosController;
use Modules\Setting\Http\Controllers\TaxController;

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

    ##################################################################
    ##                          brands routs                        ##
    ##################################################################
    Route::get('brands/get-dropdown', [BrandController::class, 'getDropdown'])->name('brands.getDropdown');
    Route::resource('brands', BrandController::class);



    Route::resource('products', ProductController::class);

});
