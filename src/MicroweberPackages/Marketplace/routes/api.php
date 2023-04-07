<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->namespace('\MicroweberPackages\Marketplace\Http\Controllers\Api')
    ->group(function () {



    });
