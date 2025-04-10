<?php

use Illuminate\Support\Facades\Route;
use Modules\Billing\Http\Controllers\BillingCheckoutController;
use Modules\Billing\Http\Controllers\SubscribeToPlanController;
use Modules\Billing\Http\Controllers\CustomerProfileController;

Route::get('/checkout/billing-portal', [BillingCheckoutController::class, 'billingPortal'])->name('billing.portal');

Route::get('/checkout/subscription-success', [BillingCheckoutController::class, 'subscriptionSuccess'])->name('billing.subscription.success');

Route::get('/checkout/subscription-cancel', [BillingCheckoutController::class, 'subscriptionCancel'])->name('billing.subscription.cancel');

Route::get('/checkout/purchase-success', [BillingCheckoutController::class, 'purchaseSuccess'])->name('billing.purchase.success');

Route::get('/checkout/purchase-cancel', [BillingCheckoutController::class, 'purchaseCancel'])->name('billing.purchase.cancel');

Route::post('/billing/subscribe-to-plan', [SubscribeToPlanController::class, 'subscribeToPlan'])->name('billing.subscribe-to-plan');

Route::post('/billing/save-customer-profile', [CustomerProfileController::class, 'saveCustomerProfile'])->name('billing.save-customer-profile');
