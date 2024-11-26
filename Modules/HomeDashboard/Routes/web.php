<?php
use \Modules\HomeDashboard\Http\Controllers\HomeDashboardController;
use Illuminate\Support\Facades\Route;
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
Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone'],'prefix' => 'dashboard'], function () {
    Route::get('/home', [HomeDashboardController::class, 'index'])->name('admin.home');
});
