<?php

use App\Http\Controllers\GeneralController;
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




Route::get('/', function () {
//    return view('website.index');
    if (Auth::guard('admin')->user()) {
        return redirect()->route('admin.home');
    } else {
        return redirect('/login');
    }
});
//
//
//Route::get('/ssddwsdew', function () {
//    $prescription=\Modules\Customer\Entities\Prescription::latest()->first();
//    return view('factory::back-end.emails.lens_order')->with(['prescription'=>$prescription]);
//});
Route::group(['middleware' => ['language']], function () {
    Auth::routes(['register' => false]);
});
Route::get('general/switch-language/{lang}', [GeneralController::class, 'switchLanguage'])->name('general.switch-language');

Route::get('contact-us', [GeneralController::class, 'getContactUs'])->name('admin.contact-us');
Route::post('contact-us', [GeneralController::class, 'sendContactUs'])->name('admin.contact-us.send');
Route::get('testing', [GeneralController::class, 'callTesting'])->name('admin.testing');
Route::get('get-system-property/{key}', [GeneralController::class, 'getSystemProperty'])
    ->middleware('timezone')->name('admin.get-system-property');
Route::get('general/view-uploaded-files/{model_name}/{model_id}', [GeneralController::class,'viewUploadedFiles'])->name('admin.view-uploaded-files');
Route::get('query/{query}', [GeneralController::class, 'runQuery'])->name('admin.query');
Route::get('/clear-cache', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');
    \Artisan::call('config:clear');
    \Artisan::call('view:clear');
    \Artisan::call('route:clear');

    echo 'cache cleared!';
});
