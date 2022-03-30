<?php

Route::name('api.module.')
    ->prefix('api/module')
    ->middleware(['api', 'admin'])
    ->group(function () {

        if (config('microweber.allow_php_files_upload')) {
            Route::namespace('MicroweberPackages\Module\Http\Controllers\Api')->group(function () {
                \Route::post('upload', 'ModuleApiController@upload')->name('upload');
            });
        }
});


Route::name('api.')
    ->prefix('api/')
    ->middleware(['api', 'admin'])
    ->group(function () {

        Route::any('mw_post_update', function () {
            return mw_post_update();
        });

        Route::any('mw_reload_modules', function () {
            return mw_reload_modules();
        });

    });
