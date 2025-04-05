<?php


use Illuminate\Http\Request;
use Modules\Billing\Models\Stripe\SubscriptionCustomer;



Route::post(
    'billing/stripe/webhook',
    '\Modules\Billing\Http\Controllers\WebhookController@handleWebhook'
)->name('billing.webhook.stripe');
