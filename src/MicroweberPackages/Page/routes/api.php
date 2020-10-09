<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('admin.')
    ->prefix('api')
    ->namespace('\MicroweberPackages\Page\Http\Controllers\Api')
    ->group(function () {

        Route::resource('page', 'PageApiController');

    });