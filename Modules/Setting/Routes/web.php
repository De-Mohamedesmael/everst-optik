<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\ColorController;
use Modules\Setting\Http\Controllers\SizeController;

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

    Route::get('colors/get-dropdown', [ColorController::class,'getDropdown'])->name('colors.dropdown');
    Route::resource('colors', ColorController::class);
    Route::get('sizes/get-dropdown', [SizeController::class,'getDropdown'])->name('sizes.dropdown');
    Route::resource('sizes', SizeController::class);
});
