<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartApiController;

Route::group(['middleware' => ['web']], function () {
    Route::post('api/update_cart', [CartApiController::class, 'updateCart'])->name('update_cart');
    Route::post('api/remove_cart_item', [CartApiController::class, 'removeCartItem'])->name('remove_cart_item');
    Route::post('api/update_cart_item_qty', [CartApiController::class, 'updateCartItemQty'])->name('update_cart_item_qty');
    Route::post('api/cart_sum', [CartApiController::class, 'sumCart'])->name('cart_sum');
    Route::post('api/empty_cart', [CartApiController::class, 'emptyCart'])->name('empty_cart');

    /*
     * AI-204 (cycle-163 2026-05-10): /cart standalone surface.
     *
     * Microweber doesn't ship a `<module type="shop/cart">` renderer
     * — only `cart_add` (Add-to-Cart button widget) is registered.
     * Modules/Cart/Microweber/CartModule.php was never built (the
     * commented-out reference in CartServiceProvider.php:81 points to
     * a class that doesn't exist on disk).
     *
     * The fully-functional cart-edit Livewire component
     * `Modules\Checkout\Livewire\CartItems` already exists at the
     * registered alias `modules.checkout.livewire.cart-items`. Its
     * view (`livewire/cart-items.blade.php`) renders:
     *   - line items with images + titles + price
     *   - qty input wired to `updateQuantity()` action
     *   - remove button wired to `removeItem()` action
     *   - cart totals
     *   - empty state with "Continue Shopping" CTA
     *
     * Page.content body strings don't process @livewire directives,
     * so the cycle-161 /cart Page that embedded `<module type="shop/
     * cart">` rendered an empty container. Fix: a real route that
     * returns a blade view with the @livewire directive baked in.
     * View extends the active template's master layout so the public
     * header + footer wrap the cart correctly.
     */
    Route::get('cart', [\Modules\Cart\Http\Controllers\CartPageController::class, 'show'])->name('cart');
});

