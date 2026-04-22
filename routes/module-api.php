<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\Api\CartApiController;
use Modules\Category\Http\Controllers\Api\CategoriesApiController;
use Modules\Checkout\Http\Controllers\Api\CheckoutApiController;
use Modules\Comments\Http\Controllers\Api\CommentsApiController;
use Modules\ContactForm\Http\Controllers\Api\FormsApiController;
use Modules\Content\Http\Controllers\Api\ContentApiController;
use Modules\Coupons\Http\Controllers\Api\CouponsApiController;
use Modules\Invoice\Http\Controllers\Api\InvoicesApiController;
use Modules\Media\Http\Controllers\Api\MediaApiController;
use Modules\Menu\Http\Controllers\Api\MenusApiController;
use Modules\Order\Http\Controllers\Api\OrdersApiController;
use Modules\Page\Http\Controllers\Api\PageApiController;
use Modules\Post\Http\Controllers\Api\PostApiController;
use Modules\Product\Http\Controllers\Api\ProductsApiController;
use Modules\Shipping\Http\Controllers\Api\ShippingApiController;
use Modules\Tag\Http\Controllers\Api\TagApiController;
use Modules\Tax\Http\Controllers\Api\TaxApiController;

/*
|--------------------------------------------------------------------------
| Headless Module API Routes
|--------------------------------------------------------------------------
|
| Unified `/api/module/{module}/*` namespace authenticated via Passport
| personal-access tokens issued from /admin/api-applications.
|
| Reads are public (rate-limited by IP), writes require a Passport token
| belonging to a user with is_admin = 1. Each controller performs its own
| admin check against $request->user() so the same controller can be
| reused across authenticated and unauthenticated surfaces.
|
*/

$modules = [
    'content' => [ContentApiController::class, 'content'],
    'pages' => [PageApiController::class, 'page'],
    'posts' => [PostApiController::class, 'post'],
    'tags' => [TagApiController::class, 'tag'],
    'comments' => [CommentsApiController::class, 'comment'],
    'menus' => [MenusApiController::class, 'menu'],
    'media' => [MediaApiController::class, 'media'],
    // `forms` and `contact-form` are aliases onto the same Form resource
    // so clients can use either legacy (`contact-form`) or plural (`forms`).
    'forms' => [FormsApiController::class, 'form'],
    'contact-form' => [FormsApiController::class, 'form'],
    'products' => [ProductsApiController::class, 'product'],
    'categories' => [CategoriesApiController::class, 'category'],
    'orders' => [OrdersApiController::class, 'order'],
    'coupons' => [CouponsApiController::class, 'coupon'],
    'shipping' => [ShippingApiController::class, 'shipping'],
    'tax' => [TaxApiController::class, 'tax'],
    'invoices' => [InvoicesApiController::class, 'invoice'],
];

foreach ($modules as $slug => [$controller, $binding]) {
    Route::prefix("api/module/{$slug}")
        ->middleware(['api', 'throttle:public'])
        ->name("api.module.{$slug}.")
        ->group(function () use ($controller, $binding) {
            Route::get('/', [$controller, 'index'])->name('index');
            Route::get('/{' . $binding . '}', [$controller, 'show'])->name('show');
        });

    Route::prefix("api/module/{$slug}")
        ->middleware(['api', 'auth:api', 'throttle:api'])
        ->name("api.module.{$slug}.")
        ->group(function () use ($controller, $binding) {
            Route::post('/', [$controller, 'store'])->name('store');
            Route::put('/{' . $binding . '}', [$controller, 'update'])->name('update');
            Route::patch('/{' . $binding . '}', [$controller, 'update'])->name('update.partial');
            Route::delete('/{' . $binding . '}', [$controller, 'destroy'])->name('destroy');
        });
}

/*
| Cart and Checkout expose action verbs (empty, totals, apply-coupon,
| validate, shipping-methods, …) that don't fit the standard REST shape,
| so they register here instead of going through the $modules loop.
| Both are session-backed and intentionally public — no admin guard.
*/

Route::prefix('api/module/cart')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.cart.')
    ->group(function () {
        Route::get('/', [CartApiController::class, 'index'])->name('index');
        Route::post('/', [CartApiController::class, 'store'])->name('store');
        Route::get('/totals', [CartApiController::class, 'totals'])->name('totals');
        Route::delete('/empty', [CartApiController::class, 'empty'])->name('empty');
        Route::post('/coupon', [CartApiController::class, 'applyCoupon'])->name('coupon.apply');
        Route::delete('/coupon', [CartApiController::class, 'removeCoupon'])->name('coupon.remove');
        Route::put('/{id}', [CartApiController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartApiController::class, 'destroy'])->name('destroy');
    });

Route::prefix('api/module/checkout')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.checkout.')
    ->group(function () {
        Route::get('/', [CheckoutApiController::class, 'index'])->name('index');
        Route::post('/', [CheckoutApiController::class, 'store'])->name('store');
        Route::put('/', [CheckoutApiController::class, 'update'])->name('update');
        Route::post('/validate', [CheckoutApiController::class, 'validate'])->name('validate');
        Route::get('/shipping-methods', [CheckoutApiController::class, 'shippingMethods'])->name('shipping.methods');
        Route::get('/payment-methods', [CheckoutApiController::class, 'paymentMethods'])->name('payment.methods');
        Route::post('/calculate-shipping', [CheckoutApiController::class, 'calculateShipping'])->name('shipping.calculate');
        Route::get('/order/{orderReferenceId}', [CheckoutApiController::class, 'orderStatus'])->name('order.status');
    });
