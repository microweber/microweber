<?php

Route::name('admin.')
    ->prefix('api')
    ->namespace('\MicroweberPackages\Post\Http\Controllers\Api')
    ->group(function () {
    Route::apiResource('posts', 'PostsApiController');
});