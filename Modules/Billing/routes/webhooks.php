<?php


\Illuminate\Support\Facades\Route::post(
    'billing/stripe/webhook',
    \Modules\Billing\Http\Controllers\WebhookController::class.'@handleWebhook'
)->name('billing.webhook.stripe');
