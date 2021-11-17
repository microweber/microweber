<?php

Route::name('admin.')
    ->prefix('admin')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Post\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('post', 'PostController');
    });
