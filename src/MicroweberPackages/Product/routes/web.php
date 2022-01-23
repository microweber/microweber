<?php
// admin

Route::name('admin.')
    ->prefix('admin')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('product', 'ProductController');
    });

// front end
Route::name('api.product.')->prefix('api/product')
    ->namespace('\MicroweberPackages\Product\Http\Controllers')
    ->group(function () {
        Route::get('quick-view', 'ProductQuickViewController@view')->name('quick-view');
    });

