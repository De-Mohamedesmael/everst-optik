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
use Modules\AddStock\Http\Controllers\AddStockController;

Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone'],'prefix' => 'dashboard','as'=>'admin.'], function () {

    Route::get('add-stocks/get-source-by-type-dropdown/{type}', [AddStockController::class,'getSourceByTypeDropdown'])->name('getSourceByTypeDropdown');

});
