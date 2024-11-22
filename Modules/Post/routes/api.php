<?php

use  \Illuminate\Support\Facades\Route;

Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->group(function () {

        Route::apiResource('post', \Modules\Post\Http\Controllers\Api\PostApiController::class);

    });



