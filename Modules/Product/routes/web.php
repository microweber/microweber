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

// Shareable add-to-cart link: GET /add-to-cart/{id}?qty=N. Runs in the web
// group for the session-backed cart; registered here (before the frontend
// catch-all) so it is not swallowed by the {slug} route.
Route::middleware('web')->group(function () {
    Route::get('add-to-cart/{id}',
        Modules\Product\Http\Controllers\ProductAddToCartController::class . '@add')
        ->where('id', '[0-9]+')
        ->name('product.add-to-cart');
});

