<?php
// admin

Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Admin')
    ->group(function () {
        Route::get('shop/dashboard', 'ProductController@dashboard')->name('product.dashboard');
        Route::resource('shop/product', 'ProductController',['except' => ['show']]);
    });

// front end
Route::name('api.product.')->prefix('api/product')
    ->namespace('\MicroweberPackages\Product\Http\Controllers')
    ->group(function () {
        Route::get('quick-view', 'ProductQuickViewController@view')->name('quick-view');
    });

