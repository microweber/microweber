<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartApiController;

Route::group(['middleware' => ['web']], function () {
    Route::post('api/update_cart', [CartApiController::class, 'updateCart'])->name('update_cart');
    Route::post('api/remove_cart_item', [CartApiController::class, 'removeCartItem'])->name('remove_cart_item');
    Route::post('api/update_cart_item_qty', [CartApiController::class, 'updateCartItemQty'])->name('update_cart_item_qty');
    Route::post('api/cart_sum', [CartApiController::class, 'sumCart'])->name('cart_sum');
    Route::post('api/empty_cart', [CartApiController::class, 'emptyCart'])->name('empty_cart');


});

