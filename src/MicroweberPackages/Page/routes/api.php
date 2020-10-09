<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('api.')
    ->prefix('api')
    ->namespace('\MicroweberPackages\Page\Http\Controllers\Api')
    ->group(function () {

        Route::resource('page', 'PageApiController');

    });