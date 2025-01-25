<?php

use Illuminate\Support\Facades\Route;
use Modules\Sale\Http\Controllers\SellController;
use Modules\Sale\Http\Controllers\SellPosController;

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



    ##########################################
    ##              POS routes              ##
    ##########################################
    Route::get('pos/update-status-to-cancel/{id}', [SellPosController::class , 'updateStatusToCancel'])->name('pos.updateStatusToCancel');
    Route::get('pos/get-non-identifiable-item-row', [SellPosController::class , 'getNonIdentifiableItemRow'])->name('pos.getNonIdentifiableItemRow'); ;
    Route::get('pos/get-products', [SellPosController::class , 'getProducts'])->name('pos.getProducts');
    Route::get('pos/add-products-row', [SellPosController::class , 'addProductRow'])->name('pos.addProductRow');
    Route::get('sale/addEditProductRow', [SellPosController::class , 'addEditProductRow'])->name('sale.addEditProductRow');
    Route::get('pos/add-discounts', [SellPosController::class , 'addDiscounts'])->name('pos.addDiscounts');
    Route::get('pos/get-products-discount', [SellPosController::class , 'getProductDiscount'])->name('pos.getProductDiscount');
    Route::get('pos/get-products-items-by-filter', [SellPosController::class , 'getProductItemsByFilter'])->name('pos.getProductItemsByFilter');
    Route::get('pos/get-lens-transactions', [SellPosController::class , 'getLensTransactions'])->name('pos.getLensTransactions');
    Route::get('pos/get-recent-transactions', [SellPosController::class , 'getRecentTransactions'])->name('pos.getRecentTransactions');
    Route::get('pos/get-customer-details/{customer_id}', [SellPosController::class , 'getCustomerDetails'])->name('pos.getCustomerDetails');
    Route::get('pos/get-customer-balance/{customer_id}', [SellPosController::class , 'getCustomerBalance'])->name('pos.getCustomerBalance');
    Route::get('pos/get-payment-row', [SellPosController::class , 'getPaymentRow'])->name('pos.getPaymentRow');
    Route::get('pos/get-sale-promotion-details-if-valid', [SellPosController::class , 'getSalePromotionDetailsIfValid'])->name('pos.getSalePromotionDetailsIfValid');
    Route::get('pos/get-transaction-details/{transaction_id}', [SellPosController::class , 'getTransactionDetails'])->name('pos.getTransactionDetails');
    Route::post('pos/update-transaction-status-cancel/{transaction_id}', [SellPosController::class , 'updateTransactionStatusCancel'])->name('pos.updateTransactionStatusCancel');
    Route::post('pos/change-selling-price/{product_id}', [SellPosController::class , 'changeSellingPrice'])->name('pos.changeSellingPrice');
    Route::post('pos/save-lens-data', [SellPosController::class , 'SaveLens'])->name('pos.SaveLens');

    Route::resource('pos', SellPosController::class);




    Route::post('sale/save-import', 'SellController@saveImport');
    Route::get('sale/get-import', 'SellController@getImport');
    Route::get('sale/print/{id}', 'SellController@print');
    Route::get('sale/get-total-details', 'SellController@getTotalDetails')->name('sale.getTotalDetails');
    Route::resource('sale', SellController::class);


});
