<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/
use  \Illuminate\Support\Facades\Route;

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->namespace('\MicroweberPackages\Marketplace\Http\Controllers\Api')
    ->group(function () {



    });
