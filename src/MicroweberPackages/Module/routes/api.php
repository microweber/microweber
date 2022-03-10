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
