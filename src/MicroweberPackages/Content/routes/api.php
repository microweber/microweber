<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('api.')
    ->prefix('api')
    ->middleware(['api'])
    ->namespace('\MicroweberPackages\Content\Http\Controllers\Api')
    ->group(function () {

        Route::apiResource('content', 'ContentApiController');

    });