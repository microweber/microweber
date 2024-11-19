<?php
// admin
use  \Illuminate\Support\Facades\Route;


// front end
Route::name('api.product.')->prefix('api/product')
    ->group(function () {
        Route::get('quick-view',
            Modules\Product\Http\Controllers\ProductQuickViewController::class . '@view')
            ->name('quick-view');
    });

