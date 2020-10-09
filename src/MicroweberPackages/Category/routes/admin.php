<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('api.')
    ->prefix('api')
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Api')
    ->group(function () {

        Route::apiResource('category', 'CategoryApiController');

    });