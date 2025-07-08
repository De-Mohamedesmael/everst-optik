<?php

use Modules\Factory\Http\Controllers\FactoryController;
use Modules\Factory\Http\Controllers\RequestLensController;


Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone'],'prefix' => 'dashboard','as'=>'admin.'], function () {
    Route::resource('factories', FactoryController::class);
    Route::resource('factories_lenses', RequestLensController::class);
});
