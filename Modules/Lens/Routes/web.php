<?php

use Illuminate\Support\Facades\Route;
use Modules\Lens\Http\Controllers\BrandLensController;
use Modules\Lens\Http\Controllers\FeatureController;
use Modules\Lens\Http\Controllers\LensController;

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



    ##################################################################
    ##                          Lens routs                       ##
    ##################################################################
    Route::get('lenses/get-raw-discount', [LensController::class,'getRawDiscount'])->name('lenses.getRawDiscount');
    Route::get('lenses/get-lenses', [LensController::class,'getLenses'])->name('lenses.getLenss');
    Route::get('lenses/get-purchase-history/{id}', [LensController::class,'getPurchaseHistory'])->name('lenses.getPurchaseHistory');
    Route::post('lenses/save-import', [LensController::class,'saveImport'])->name('lenses.saveImport');
    Route::get('lenses/import', [LensController::class,'getImport'])->name('lenses.getImport');
    Route::get('lenses/check-sku/{sku}', [LensController::class,'checkSku'])->name('lenses.checkSku');
    Route::get('lenses/check-name', [LensController::class,'checkName'])->name('lenses.checkName');
    Route::get('lenses-stocks', [LensController::class,'getLensStocks'])->name('lenses.getLensStocks');
    Route::get('lenses/delete-lenses-image/{id}', [LensController::class,'deleteLensImage'])->name('lenses.deleteLensImage');
    Route::post('lenses/multiDeleteRow', [LensController::class,'multiDeleteRow'])->name('lenses.multiDeleteRow');
    Route::post('/update-column-visibility', [LensController::class,'updateColumnVisibility'])->name('lenses.updateColumnVisibility');
    Route::get('lenses/get-dropdown-filter-lenses', [LensController::class,'getDropdownFilterLenses'])->name('lenses.getDropdownFilterLenses');
    Route::get('lenses/get-price-lenses', [LensController::class,'getPriceLenses'])->name('lenses.getPriceLenses');
    Route::resource('lenses', LensController::class);


});
