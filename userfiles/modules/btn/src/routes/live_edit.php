<?php







\Route::prefix('api/live_edit')
    ->middleware(['api', 'admin','module_settings'])
    ->group(function () {
        Route::get('live_edit.modules.settings.btn'
            , MicroweberPackages\Modules\Btn\Http\Controllers\BtnLiveEditSettingsController::class.'@index')
            ->name('live_edit.modules.settings.btn');

    });



