<?php

Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Post\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('post', 'PostController',['except' => ['show']]);
    });
