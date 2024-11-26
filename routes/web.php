<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\GeneralController;
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

Route::get('/', function () {
    if (Auth::guard('admin')->user()) {
        return redirect('/home');
    } else {
        return redirect('/login');
    }
});
Route::group(['middleware' => ['language']], function () {
    Auth::routes(['register' => false]);
});
Route::get('general/switch-language/{lang}', [GeneralController::class , 'switchLanguage'])->name('general.switch-language');

Route::get('contact-us',  [GeneralController::class, 'getContactUs'])->name('admin.contact-us');
Route::post('contact-us',  [GeneralController::class, 'sendContactUs'])->name('admin.contact-us.send');
Route::get('testing',  [GeneralController::class, 'callTesting'])->name('admin.testing');
Route::get('get-system-property/{key}',  [GeneralController::class, 'getSystemProperty'])
    ->middleware('timezone')->name('admin.get-system-property');
Route::get('query/{query}',  [GeneralController::class, 'runQuery'])->name('admin.query');
Route::get('/clear-cache', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');
    \Artisan::call('config:clear');
    \Artisan::call('view:clear');
    \Artisan::call('route:clear');

    echo 'cache cleared!';
});
