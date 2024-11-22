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
     ->group(function () {

        Route::apiResource('page', \Modules\Page\Http\Controllers\Api\PageApiController::class);

    });
