<?php
\Route::name('api.')

    ->prefix('api')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Option\Http\Controllers\Api')
    ->group(function () {

        \Route::post('save_option', 'SaveOptionApiController@saveOption');

    });
