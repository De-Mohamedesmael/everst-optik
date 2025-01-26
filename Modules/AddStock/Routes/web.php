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
    Route::get('add-stock/add-products-row', [AddStockController::class,'addProductRow'])->name('add-stock.addProductRow');
    Route::get('add-stock/add-multiple-products-row', [AddStockController::class,'addMultipleProductRow'])->name('add-stock.addMultipleProductRow');
    Route::get('add-stock/add-products-different-batch-row', [AddStockController::class,'addProductBatchRow'])->name('add-stock.addProductBatchRow');
    Route::get('add-stock/add-products-batch-row', [AddStockController::class,'addProductBatchRow'])->name('add-stock.addProductBatchRow');
    Route::get('add-stock/get-products', [AddStockController::class,'getProducts'])->name('add-stock.getProducts');

    Route::get('add-stock/get-purchase-order-details/{id}', [AddStockController::class,'getPurchaseOrderDetails'])->name('getPurchaseOrderDetails');
    Route::post('add-stock/save-import', [AddStockController::class,'saveImport'])->name('add-stock.saveImport');
    Route::get('add-stock/get-import', [AddStockController::class,'getImport'])->name('add-stock.getImport');
    Route::resource('add-stock', AddStockController::class);



});
