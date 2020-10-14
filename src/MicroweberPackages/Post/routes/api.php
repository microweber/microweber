<?php

Route::name('api.')
    ->prefix('api')
    ->namespace('\MicroweberPackages\Post\Http\Controllers\Api')
    ->group(function () {
    Route::apiResource('post', 'PostApiController');
});