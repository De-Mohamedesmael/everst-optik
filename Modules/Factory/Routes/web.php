<?php

use Modules\Factory\Http\Controllers\FactoryController;
use Modules\Factory\Http\Controllers\RequestLensController;


Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone'],'prefix' => 'dashboard','as'=>'admin.'], function () {
    Route::resource('factories', FactoryController::class);
    Route::get('factories/lenses/index', [FactoryController::class, 'getFactoriesLenses'])->name('factories.lenses.index');
    Route::get('factories/lenses/create', [FactoryController::class, 'createLenses'])->name('factories.lenses.create');
    Route::post('factories/lenses/save-qr', [FactoryController::class, 'saveQr'])->name('factories.lenses.save-qr');
    Route::post('factories/lenses/sell_to_uts', [FactoryController::class, 'sellToUts'])->name('factories.lenses.sell_to_uts'); // send to uts
    
});

