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

use Modules\Customer\Http\Controllers\CustomerTypeController;
use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\CustomerBalanceAdjustmentController;
use Modules\Customer\Http\Controllers\CustomerController;
use Modules\Customer\Http\Controllers\PrescriptionController;


Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone'],'prefix' => 'dashboard','as'=>'admin.'], function () {
    Route::get('customers/get-referral-row', [CustomerController::class , 'getReferralRow'])->name('customers.referral.row');
    Route::get('customers/get-referred-by-details-html', [CustomerController::class , 'getReferredByDetailsHtml'])->name('customers.referral.details.html');
    Route::get('customers/get-dropdown', [CustomerController::class , 'getDropdown'])->name('customers.dropdown');
    Route::get('customers/get-details-by-transaction-type/{customer_id}/{type}', [CustomerController::class , 'getDetailsByTransactionType'])->name('customers.details.by.transaction-type');
    Route::get('customers/get-customer-balance/{customer_id}', [CustomerController::class , 'getCustomerBalance'])->name('customers.balance');
    Route::post('customers/pay-customer-due/{customer_id}', [CustomerController::class , 'postPayContactDue'])->name('customers.pay.due');
    Route::get('customers/pay-customer-due/edit/{id}', [CustomerController::class , 'paymentDuetEdit'])->name('customers.pay.due.edit');
    Route::Put('customers/pay-customer-due/update/{id}', [CustomerController::class , 'UpdatePayContactDue']);
    Route::delete('customers/pay-customer-due/delete/{id}', [CustomerController::class , 'destroyPayContactDue']);
    Route::get('customers/pay-customer-due/{customer_id}', [CustomerController::class , 'getPayContactDue']);
    Route::get('customers/get-important-date-row', [CustomerController::class , 'getImportantDateRow']);
    Route::post('customers/update-address/{customer_id}', [CustomerController::class , 'updateAddress']);
    Route::get('customers/prescriptions/{customer_id}', [CustomerController::class , 'getPrescriptions'])->name('customers.prescriptions');
    Route::get('customers/prescriptions-show/{prescription_id}', [CustomerController::class , 'getPrescriptionShow'])->name('customers.getPrescriptionShow');
    Route::resource('customers', CustomerController::class);


    Route::get('prescriptions/get-dropdown', [PrescriptionController::class , 'getDropdown']);
    Route::resource('prescriptions', PrescriptionController::class);

    Route::get('customer-type/get-dropdown', [CustomerTypeController::class , 'getDropdown']);
    Route::get('customer-type/get-products-discount-row', [CustomerTypeController::class , 'getProductDiscountRow']);
    Route::get('customer-type/get-products-point-row', [CustomerTypeController::class , 'getProductPointRow']);
    Route::resource('customer-type', CustomerTypeController::class);


    Route::resource('customer-balance-adjustment', CustomerBalanceAdjustmentController::class);

});
