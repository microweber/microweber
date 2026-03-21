<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\StripeWebhookController;
use Modules\Payment\Http\Controllers\PayPalWebhookController;

/*
|--------------------------------------------------------------------------
| Payment Module Webhook Routes
|--------------------------------------------------------------------------
|
| These routes handle incoming webhooks from payment providers.
| They are stateless and do not use session or CSRF protection.
|
*/

Route::post('payment/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('payment.stripe.webhook')
    ->withoutMiddleware(['web', 'auth', 'csrf']);

Route::post('payment/paypal/webhook', [PayPalWebhookController::class, 'handleWebhook'])
    ->name('payment.paypal.webhook')
    ->withoutMiddleware(['web', 'auth', 'csrf']);
