<?php
use Illuminate\Support\Facades\Route;
use Modules\Hr\Http\Controllers\AdminController;
use Modules\Hr\Http\Controllers\JobController;
use Modules\Hr\Http\Controllers\EmployeeController;
use Modules\Hr\Http\Controllers\LeaveTypeController;
use Modules\Hr\Http\Controllers\LeaveController;
//use AttendanceController

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
    Route::post('admins/check-password/{id}', [AdminController::class, 'checkPassword'])->name('check-password');
//    Route::post('admins/check-admin-password/{id}}', [AdminController::class, 'checkAdminPassword'])->name('check-passwordord');

    Route::group(['prefix' => 'hr','as'=>'hr.'], function () {
        Route::resource('jobs', JobController::class);
        Route::get('get-same-jobs-employee-details/{id}', [EmployeeController::class ,'getSameJobEmployeeDetails'])->name('employees.getSameJobEmployeeDetails');
        Route::get('get-balance-leave-details/{id}', [EmployeeController::class ,'getBalanceLeaveDetails'])->name('employees.getBalanceLeaveDetails');
        Route::get('get-employee-details-by-id/{id}', [EmployeeController::class ,'getDetails'])->name('employees.getDetails');
        Route::get('send-login-details/{employee_id}', [EmployeeController::class ,'sendLoginDetails'])->name('employees.sendLoginDetails');
        Route::get('toggle-active/{employee_id}', [EmployeeController::class ,'toggleActive'])->name('employees.toggleActive');
        Route::get('Employees/get-dropdown', [EmployeeController::class ,'getDropdown'])->name('employees.getDropdown');
        Route::get('print/employee-barcode/{id}',[EmployeeController::class ,'printEmployeeBarcode'])->name('employees.print_employee_barcode');
        Route::resource('employees', EmployeeController::class);
        Route::resource('leave-type', LeaveTypeController::class);

        Route::get('leave/get-leave-details/{employee_id}', [LeaveController::class ,'getLeaveDetails'])->name('getLeaveDetails');
        Route::resource('leave', LeaveController::class);
        Route::get('attendance/get-attendance-row/{row_index}', [AttendanceController::class ,'getAttendanceRow'])->name('getAttendanceRow');
        Route::resource('attendances', AttendanceController::class);
        Route::get('wages-and-compensations/change-status-to-paid/{id}', [WagesAndCompensationController::class ,'changeStatusToPaid'])->name('changeStatusToPaid');
        Route::get('wages-and-compensations/calculate-salary-and-commission/{employee_id}/{payment_type}'
            , [WagesAndCompensationController::class ,'calculateSalaryAndCommission'])->name('calculateSalaryAndCommission');
        Route::resource('wages-and-compensations', WagesAndCompensationController::class);
        Route::get('forfeit-leaves/get-leave-type-balance-for-employee/{employee_id}/{leave_type_id}',
            [ForfeitLeaveController::class ,'getLeaveTypeBalanceForEmployee'])->name('getLeaveTypeBalanceForEmployee');
        Route::resource('forfeit-leaves', ForfeitLeaveController::class);
    });


});
