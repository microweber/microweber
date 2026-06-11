<?php

use Modules\Payment\Http\Middleware\VerifyWebhookSignature;

\Illuminate\Support\Facades\Route::post(
    'billing/stripe/webhook',
    \Modules\Billing\Http\Controllers\WebhookController::class.'@handleWebhook'
)->name('billing.webhook.stripe')
    ->middleware(VerifyWebhookSignature::class . ':billing_stripe')
    ->withoutMiddleware(['web', 'auth', 'csrf']);
