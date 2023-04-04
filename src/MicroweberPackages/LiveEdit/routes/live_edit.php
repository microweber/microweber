<?php


\Route::prefix('api/live_edit')
    ->middleware(['api', 'admin','module_settings'])
    ->group(function () {
        Route::get('live_edit.module_settings', \MicroweberPackages\LiveEdit\Http\Controllers\ModuleSettingsController::class.'@index')
            ->name('live_edit.module_settings');

    });
