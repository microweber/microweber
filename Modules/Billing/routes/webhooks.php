<?php


Route::post(
    'billing/stripe/webhook',
    '\Modules\Billing\Http\Controllers\WebhookController@handleWebhook'
)->name('billing.webhook.stripe');
