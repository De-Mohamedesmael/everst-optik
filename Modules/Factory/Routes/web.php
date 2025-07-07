<?php

use Modules\Factory\Http\Controllers\FactoryController;


Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone'],'prefix' => 'dashboard','as'=>'admin.'], function () {
    Route::resource('factories', FactoryController::class);

    Route::get('factories/lenses/index', [FactoryController::class, 'getFactoriesLenses'])->name('factories.lenses.index');
    Route::get('factories/lenses/create', [FactoryController::class, 'createLenses'])->name('factories.lenses.create');
    Route::post('factories/lenses/send', [FactoryController::class, 'sendLenses'])->name('factories.lenses.send');
});