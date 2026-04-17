<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\Api\CartApiController;
use Modules\Checkout\Http\Controllers\Api\CheckoutApiController;
use Modules\Product\Http\Controllers\Api\ProductPublicApiController;

/*
|--------------------------------------------------------------------------
| E-commerce API Routes
|--------------------------------------------------------------------------
|
| These routes provide a RESTful API for e-commerce functionality including
| products, cart, and checkout operations.
|
*/

// Ensure we're in the API route group context
Route::middleware([])->group(function () {

    // Public Product API routes (read-only, no authentication required)
    Route::name('api.products.')
        ->prefix('api/products')
        ->group(function () {
            Route::get('/', [ProductPublicApiController::class, 'index'])->name('index');
            Route::get('/featured', [ProductPublicApiController::class, 'featured'])->name('featured');
            Route::get('/category/{category}', [ProductPublicApiController::class, 'byCategory'])->name('category');
            Route::get('/slug/{slug}', [ProductPublicApiController::class, 'bySlug'])->name('slug');
            Route::get('/{id}', [ProductPublicApiController::class, 'show'])->name('show');
        });

    // Public Cart API routes (no authentication required - session-based)
    Route::name('api.cart.')
        ->prefix('api/cart')
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

    // Public Checkout API routes (no authentication required)
    Route::name('api.checkout.')
        ->prefix('api/checkout')
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

    // Protected E-commerce API routes (requires authentication)
    Route::name('api.ecommerce.')
        ->prefix('api/ecommerce')
        ->middleware(['auth:api', 'throttle:api'])
        ->group(function () {
            // Order history for authenticated user
            Route::get('/orders', function () {
                $orders = \Modules\Order\Models\Order::where('customer_id', auth()->id())
                    ->orWhere('email', auth()->user()->email)
                    ->orderBy('created_at', 'desc')
                    ->paginate(30);

                return response()->json([
                    'success' => true,
                    'data' => $orders,
                ]);
            })->name('orders.index');

            Route::get('/orders/{order}', function ($orderId) {
                $order = \Modules\Order\Models\Order::where('id', $orderId)
                    ->where(function ($query) {
                        $query->where('customer_id', auth()->id())
                            ->orWhere('email', auth()->user()->email);
                    })
                    ->first();

                if (!$order) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Order not found',
                    ], 404);
                }

                return response()->json([
                    'success' => true,
                    'data' => $order,
                ]);
            })->name('orders.show');
        });
});
