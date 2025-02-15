<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\BarcodeController;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\CategoryController;
use Modules\Product\Http\Controllers\BrandController;
use Modules\Product\Http\Controllers\LensesController;
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
    ##                          category routs                      ##
    ##################################################################

    Route::get('categories/get-dropdown', [CategoryController::class ,'getDropdown'])->name('categories.getDropdown');
    Route::resource('categories', CategoryController::class);



    ##################################################################
    ##                          brands routs                      ##
    ##################################################################
    Route::get('brands/get-dropdown', [BrandController::class, 'getDropdown'])->name('brands.getDropdown');
    Route::resource('brands', BrandController::class);



    ##################################################################
    ##                          Product routs                       ##
    ##################################################################
    Route::get('products/get-raw-discount', [ProductController::class,'getRawDiscount'])->name('products.getRawDiscount');
    Route::get('products/get-products', [ProductController::class,'getProducts'])->name('products.getProducts');
    Route::get('products/get-purchase-history/{id}', [ProductController::class,'getPurchaseHistory'])->name('products.getPurchaseHistory');
    Route::post('products/save-import', [ProductController::class,'saveImport'])->name('products.saveImport');
    Route::get('products/import', [ProductController::class,'getImport'])->name('products.getImport');
    Route::get('products/check-sku/{sku}', [ProductController::class,'checkSku'])->name('products.checkSku');
    Route::get('products/check-name', [ProductController::class,'checkName'])->name('products.checkName');
    Route::get('products-stocks', [ProductController::class,'getProductStocks'])->name('products.getProductStocks');
    Route::get('products/delete-products-image/{id}', [ProductController::class,'deleteProductImage'])->name('products.deleteProductImage');
    Route::get('products/remove_damage/{id}', [ProductController::class,'get_remove_damage'])->name('products.remove_damage');
    Route::get('products/create/product_id={id}/convolutions', [ProductController::class,'addConvolution'])->name("products.addConvolution");
    Route::get('products/create/product_id={id}/getDamageProduct', [ProductController::class,'getDamageProduct'])->name("products.getDamageProduct");
    Route::post('products/convolutions/storeStockRemoved', [ProductController::class,'storeStockRemoved'])->name("products.storeStockRemoved");
    Route::post('products/convolutions/storeStockDamaged', [ProductController::class,'storeStockDamaged'])->name("products.storeStockDamaged");
    Route::get('products/toggle-appearance-pos/{id}', [ProductController::class,'toggleAppearancePos'])->name('products.toggleAppearancePos');
    Route::post('products/multiDeleteRow', [ProductController::class,'multiDeleteRow'])->name('products.multiDeleteRow');
    Route::post('/update-column-visibility', [ProductController::class,'updateColumnVisibility'])->name('products.updateColumnVisibility');
    Route::resource('products', ProductController::class);


    Route::get('barcode/add-products-row', [BarcodeController::class,'addProductRow'])->name('barcode.addProductRow');
    Route::get('barcode/print-barcode', [BarcodeController::class,'printBarcode'])->name('barcode.printBarcode');
    Route::get('barcode/create', [BarcodeController::class,'create'])->name('barcode.create');
    Route::post('barcode/store', [BarcodeController::class,'store'])->name('barcode.store');


});
