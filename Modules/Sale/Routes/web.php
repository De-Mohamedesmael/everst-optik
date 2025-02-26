<?php

use Illuminate\Support\Facades\Route;
use Modules\AddStock\Http\Controllers\TransactionPaymentController;
use Modules\Sale\Http\Controllers\SellController;
use Modules\Sale\Http\Controllers\SellPosController;
use Modules\Sale\Http\Controllers\SellReturnController;

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



    Route::get('sale/get-prescription-lenses', [SellController::class,'getPrescriptionDetails'])->name('sale.getPrescriptionDetails');
    Route::post('sale/save-import', [SellController::class,'saveImport'])->name('sale.saveImport');
    Route::get('sale/get-import', [SellController::class,'getImport'])->name('sale.getImport');
    Route::get('sale/print/{id}', [SellController::class,'print'])->name('sale.print');
    Route::get('sale/get-total-details', [SellController::class,'getTotalDetails'])->name('sale.getTotalDetails');
    Route::resource('sale', SellController::class);



    Route::post('transaction-payment/pay-customer-due/{customer_id}', [TransactionPaymentController::class,'payCustomerDue'])->name('transactionPayment.payCustomerDue');
    Route::get('transaction-payment/get-customer-due/{customer_id}/{extract_due?}', [TransactionPaymentController::class,'getCustomerDue'])->name('transaction.getCustomerDue');
    Route::get('transaction-payment/add-payment/{id}', [TransactionPaymentController::class,'addPayment'])->name('transaction.addPayment');
//    Route::resource('transaction-payment', TransactionPaymentController::class);

//    Route::get('transaction-payment', [TransactionPaymentController::class, 'index'])->name('transaction-payment.index');
//    Route::get('transaction-payment/create', [TransactionPaymentController::class, 'create'])->name('transaction-payment.create');
    Route::post('transaction-payment', [TransactionPaymentController::class, 'store'])->name('transaction-payment.store');
    Route::get('transaction-payment/{id}', [TransactionPaymentController::class, 'show'])->name('transaction-payment.show');
    Route::get('transaction-payment/{id}/edit', [TransactionPaymentController::class, 'edit'])->name('transaction-payment.edit');
    Route::put('transaction-payment/{id}', [TransactionPaymentController::class, 'update'])->name('transaction-payment.update');
    Route::delete('transaction-payment/{id}', [TransactionPaymentController::class, 'destroy'])->name('transaction-payment.destroy');



    Route::get('sale-return/add/{id}', [SellReturnController::class, 'add'])->name('saleReturn.add');
    Route::get('sale-return/print/{id}', [SellReturnController::class, 'print'])->name('saleReturn.print');
    Route::resource('sale-return', SellReturnController::class);
});
