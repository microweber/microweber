<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\StripeWebhookController;
use Modules\Payment\Http\Controllers\PayPalWebhookController;
use Modules\Payment\Http\Middleware\VerifyWebhookSignature;

/*
|--------------------------------------------------------------------------
| Payment Module Webhook Routes
|--------------------------------------------------------------------------
|
| These routes handle incoming webhooks from payment providers.
| They are stateless and do not use session or CSRF protection.
|
| Security layers (applied in order):
| 1. VerifyWebhookSignature middleware — validates signed URL nonce/HMAC
| 2. Controller-level provider signature verification (Stripe-Signature
|    header, PayPal verification API)
| 3. Event nonce/idempotency check — prevents replay attacks
|
*/

Route::post('payment/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('payment.stripe.webhook')
    ->middleware(VerifyWebhookSignature::class . ':stripe')
    ->withoutMiddleware(['web', 'auth', 'csrf']);

Route::post('payment/paypal/webhook', [PayPalWebhookController::class, 'handleWebhook'])
    ->name('payment.paypal.webhook')
    ->middleware(VerifyWebhookSignature::class . ':paypal')
    ->withoutMiddleware(['web', 'auth', 'csrf']);
