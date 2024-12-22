<?php

use Illuminate\Support\Facades\Route;
use Modules\Lens\Http\Controllers\BrandLensController;
use Modules\Lens\Http\Controllers\FeatureController;
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
    Route::get('brand_lenses/get-dropdown', [BrandLensController::class, 'getDropdown'])->name('brands.getDropdown');
    Route::get('brand_lenses/show-with-features', [BrandLensController::class, 'showWithFeatures'])->name('brands.getDropdown');
    Route::resource('brand_lenses', BrandLensController::class);

    ##################################################################
    ##                          feature routs                        ##
    ##################################################################
    Route::get('features/get-dropdown', [FeatureController::class, 'getDropdown'])->name('brands.getDropdown');
    Route::resource('features', FeatureController::class);



});
