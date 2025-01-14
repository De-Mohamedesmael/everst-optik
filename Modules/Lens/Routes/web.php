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
    Route::get('lenses/get-lenses', [LensController::class,'getLenss'])->name('lenses.getLenss');
    Route::get('lenses/get-purchase-history/{id}', [LensController::class,'getPurchaseHistory'])->name('lenses.getPurchaseHistory');
    Route::post('lenses/save-import', [LensController::class,'saveImport'])->name('lenses.saveImport');
    Route::get('lenses/import', [LensController::class,'getImport'])->name('lenses.getImport');
    Route::get('lenses/check-sku/{sku}', [LensController::class,'checkSku'])->name('lenses.checkSku');
    Route::get('lenses/check-name', [LensController::class,'checkName'])->name('lenses.checkName');
    Route::get('lenses-stocks', [LensController::class,'getLensStocks'])->name('lenses.getLensStocks');
    Route::get('lenses/delete-lenses-image/{id}', [LensController::class,'deleteLensImage'])->name('lenses.deleteLensImage');
    Route::get('lenses/remove_damage/{id}', [LensController::class,'get_remove_damage'])->name('lenses.remove_damage');
    Route::get('lenses/create/lens_id={id}/convolutions', [LensController::class,'addConvolution'])->name("lenses.addConvolution");
    Route::get('lenses/create/lens_id={id}/getDamageLens', [LensController::class,'getDamageLens'])->name("lenses.getDamageLens");
    Route::post('lenses/convolutions/storeStockRemoved', [LensController::class,'storeStockRemoved'])->name("lenses.storeStockRemoved");
    Route::post('lenses/convolutions/storeStockDamaged', [LensController::class,'storeStockDamaged'])->name("lenses.storeStockDamaged");
    Route::get('lenses/toggle-appearance-pos/{id}', [LensController::class,'toggleAppearancePos'])->name('lenses.toggleAppearancePos');
    Route::post('lenses/multiDeleteRow', [LensController::class,'multiDeleteRow'])->name('lenses.multiDeleteRow');
    Route::post('/update-column-visibility', [LensController::class,'updateColumnVisibility'])->name('lenses.updateColumnVisibility');
    Route::get('lenses/get-dropdown-filter-lenses', [LensController::class,'getDropdownFilterLenses'])->name('lenses.getDropdownFilterLenses');
    Route::resource('lenses', LensController::class);


});
