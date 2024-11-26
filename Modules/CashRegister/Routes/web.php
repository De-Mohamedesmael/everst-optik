<?php

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
use Illuminate\Support\Facades\Route;
use Modules\CashRegister\Http\Controllers\CashRegisterController;

Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone'],'prefix' => 'dashboard','as'=>'admin.'], function () {

    Route::get('cash-register/get-available-cash-register/{admin_id}', [CashRegisterController::class,'getAvailableCashRegister'])->name('getAvailableCashRegister');
    Route::resource('cash-register', CashRegisterController::class);

});
