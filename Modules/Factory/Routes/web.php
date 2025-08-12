<?php

use Modules\Factory\Http\Controllers\FactoryController;
use Modules\Factory\Http\Controllers\RequestLensController;


Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone'],'prefix' => 'dashboard','as'=>'admin.'], function () {
    Route::resource('factories', FactoryController::class);
    Route::get('factories/lenses/index', [RequestLensController::class, 'index'])->name('factories.lenses.index');
    Route::post('factories/lenses/store', [RequestLensController::class, 'store'])->name('factories.lenses.store');
    Route::get('factories/lenses/create', [RequestLensController::class, 'create'])->name('factories.lenses.create');
    Route::post('factories/lenses/save-qr', [RequestLensController::class, 'saveQr'])->name('factories.lenses.save-qr');
});

