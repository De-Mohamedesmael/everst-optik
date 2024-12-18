<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\ColorController;
use Modules\Setting\Http\Controllers\SizeController;
use Modules\Setting\Http\Controllers\MoneySafeController;
use Modules\Setting\Http\Controllers\StoreController;
use Modules\Setting\Http\Controllers\StorePosController;

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


    Route::resource('store-pos', StorePosController::class);




    Route::get('colors/get-dropdown', [ColorController::class,'getDropdown'])->name('colors.dropdown');
    Route::resource('colors', ColorController::class);
    Route::get('sizes/get-dropdown', [SizeController::class,'getDropdown'])->name('sizes.dropdown');
    Route::resource('sizes', SizeController::class);




    //money safe
    Route::post('money_safe/post-add-money-to-safe', [MoneySafeController::class,'postAddMoneyToSafe'])->name('money_safe.post-add-money-to-safe');
    Route::get('money_safe/get-add-money-to-safe/{id}', [MoneySafeController::class,'getAddMoneyToSafe'])->name('money_safe.get-add-money-to-safe');
    Route::post('money_safe/post-take-money-to-safe', [MoneySafeController::class,'postTakeMoneyFromSafe'])->name('money_safe.post-take-money-to-safe');
    Route::get('money_safe/get-take-money-to-safe/{id}', [MoneySafeController::class,'getTakeMoneyFromSafe'])->name('money_safe.get-take-money-to-safe');
    Route::get('money_safe/watch-money-to-safe-transaction/{id}', [MoneySafeController::class,'getMoneySafeTransactions'])->name('money_safe.watch-money-to-safe-transaction');
    Route::resource('money_safe', MoneySafeController::class);

});
