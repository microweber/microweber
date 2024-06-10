<?php

Route::name('admin.')
    ->prefix(mw_admin_prefix_url_legacy())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Post\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('post', 'PostController',['except' => ['show']]);
    });
