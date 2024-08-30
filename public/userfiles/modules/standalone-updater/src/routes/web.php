<?php

/*Route::prefix(ADMIN_PREFIX)->middleware(['admin'])->group(function () {

    Route::get('standalone-update-now', function() {

    })->name('module.standalone-updater.update');
});*/

Route::name('standalone-updater.')
    ->prefix(ADMIN_PREFIX . '/standalone-updater')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\StandaloneUpdater\Http\Controllers')
    ->group(function () {

        Route::get('about-new-version', 'StandaloneUpdaterController@aboutNewVersion')->name('about-new-version');

    });

Route::name('api.standalone-updater.')
    ->prefix('api/standalone-updater')
    ->middleware(['api', 'admin'])
    ->namespace('MicroweberPackages\Modules\StandaloneUpdater\Http\Controllers')
    ->group(function () {

        Route::get('delete-temp', 'StandaloneUpdaterController@deleteTemp')->name('delete-temp');
        Route::post('update-now', 'StandaloneUpdaterController@updateNow')->name('update-now');

        Route::get('remove-dashboard-notice', function () {
            return save_option( 'last_update_check_time',\Carbon\Carbon::parse('+24 hours'),'standalone-updater');
        })->name('remove-dashboard-notice');


    });
