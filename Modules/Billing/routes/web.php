<?php


use Illuminate\Http\Request;


Route::get('/checkout/billing-portal', function (Request $request) {

    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    $findSubscriptionCustomer = \Modules\Billing\Models\Stripe\SubscriptionCustomer::where('user_id', $user->id)->first();
    if (!$findSubscriptionCustomer) {
        $findSubscriptionCustomer = new \Modules\Billing\Models\Stripe\SubscriptionCustomer();
        $findSubscriptionCustomer->user_id = $user->id;
        $findSubscriptionCustomer->email = $user->email;
        $findSubscriptionCustomer->save();
    }

    if ($findSubscriptionCustomer->subscriptions()->count() == 0) {
        return redirect(url()->previous() . '?status=no-subscription');
    }

    return $findSubscriptionCustomer->redirectToBillingPortal();

})->name('billing.portal');


Route::get('/checkout/subscription-success', function (Request $request) {


    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    $subscriptionCustomer = \Modules\Billing\Models\Stripe\SubscriptionCustomer::firstOrCreate([
        'user_id' => $user->id,
    ]);

    $latestInvoice = $request->get('latest_invoice') ?? false;
    $sessionId = $request->get('session_id') ?? false;
    $checkoutSessionData = [];
    $latestInvoiceData = [];
    $transactionData = [];
    $transactionData['currency'] = 'USD';
    if ($sessionId) {
        $checkoutSessionData = $subscriptionCustomer->stripe()->checkout->sessions->retrieve($sessionId);
        if ($checkoutSessionData and isset($checkoutSessionData['amount_total']) and $checkoutSessionData['amount_total']) {
            $transactionData['value'] = intval($checkoutSessionData['amount_total']) / 100;
            $transactionData['transaction_id'] = $checkoutSessionData['id'];
            if (isset($checkoutSessionData['currency'])) {
                $transactionData['currency'] = $checkoutSessionData['currency'];
            }
        }

    }
    if ($latestInvoice) {
        $latestInvoiceData = $subscriptionCustomer->stripe()->invoices->retrieve($latestInvoice);
        if ($latestInvoiceData and isset($latestInvoiceData['total']) and $latestInvoiceData['total']) {
            $transactionData['value'] = intval($latestInvoiceData['total']) / 100;
            $transactionData['transaction_id'] = $latestInvoiceData['id'];
            if (isset($latestInvoiceData['currency'])) {
                $transactionData['currency'] = $latestInvoiceData['currency'];
            }

        }
    }

    if (isset($checkoutSessionData->metadata->internal_order_id)) {
        $findOrder = \MicroweberPackages\Order\Models\Order::where('id', $checkoutSessionData->metadata->internal_order_id)->first();
        if ($findOrder) {
            $utmData = [];
            if (isset($checkoutSessionData->metadata->utm_source)) {
                $utmData['utm_source'] = $checkoutSessionData->metadata->utm_source;
            }
            if (isset($checkoutSessionData->metadata->utm_medium)) {
                $utmData['utm_medium'] = $checkoutSessionData->metadata->utm_medium;
            }
            if (isset($checkoutSessionData->metadata->utm_campaign)) {
                $utmData['utm_campaign'] = $checkoutSessionData->metadata->utm_campaign;
            }
            if (isset($checkoutSessionData->metadata->utm_content)) {
                $utmData['utm_content'] = $checkoutSessionData->metadata->utm_content;
            }
            if (isset($checkoutSessionData->metadata->utm_term)) {
                $utmData['utm_term'] = $checkoutSessionData->metadata->utm_term;
            }

            $findOrder->is_paid = 1;
            $findOrder->order_completed = 1;
            $findOrder->transaction_id = $checkoutSessionData->id;
            $findOrder->save();

            event(new \MicroweberPackages\Order\Events\OrderWasPaid($findOrder, $utmData));
        }
    }

    $redirectOnSuccess = get_option('cashier_success_url', 'payments');
    if ($redirectOnSuccess) {
        return redirect($redirectOnSuccess)->with('transactionData', $transactionData);
    }

    $backButtonUrl = session_get('billing_subscription_referer');

   return redirect(site_url('project/plans'));
//
//    return view('billing::finish', [
//        'backButtonUrl' => $backButtonUrl,
//        'checkoutSessionData' => $checkoutSessionData,
//        'latestInvoiceData' => $latestInvoiceData,
//        'transactionData' => $transactionData,
//    ]);


})->name('billing.subscription.success');

Route::get('/checkout/subscription-cancel', function (Request $request) {

    $backButtonUrl = session_get('billing_subscription_referer');

    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    $subscriptionCustomer = \Modules\Billing\Models\Stripe\SubscriptionCustomer::firstOrCreate([
        'user_id' => $user->id,
    ]);

    $sessionId = $request->get('session_id') ?? false;
    $checkoutSessionData = [];
    if ($sessionId) {
        $checkoutSessionData = $subscriptionCustomer->stripe()->checkout->sessions->retrieve($sessionId);
    }

    if (isset($checkoutSessionData->metadata->internal_order_id)) {
        $findOrder = \MicroweberPackages\Order\Models\Order::where('id', $checkoutSessionData->metadata->internal_order_id)->first();
        if ($findOrder) {
            $utmData = [];
            if (isset($checkoutSessionData->metadata->utm_source)) {
                $utmData['utm_source'] = $checkoutSessionData->metadata->utm_source;
            }
            if (isset($checkoutSessionData->metadata->utm_medium)) {
                $utmData['utm_medium'] = $checkoutSessionData->metadata->utm_medium;
            }
            if (isset($checkoutSessionData->metadata->utm_campaign)) {
                $utmData['utm_campaign'] = $checkoutSessionData->metadata->utm_campaign;
            }
            if (isset($checkoutSessionData->metadata->utm_content)) {
                $utmData['utm_content'] = $checkoutSessionData->metadata->utm_content;
            }
            if (isset($checkoutSessionData->metadata->utm_term)) {
                $utmData['utm_term'] = $checkoutSessionData->metadata->utm_term;
            }

            event(new \MicroweberPackages\Order\Events\OrderWasCanceled($findOrder, $utmData));
        }
    }

    $redirect_on_cancel = get_option('cashier_cancel_url', 'payments');
    if ($redirect_on_cancel) {
        return redirect($redirect_on_cancel);
    }

    if (empty($backButtonUrl)) {
        return redirect(site_url('projects/plans'));
    }

    return view('billing::cancel', [
        'backButtonUrl' => $backButtonUrl,

    ]);

})->name('billing.subscription.cancel');


Route::get('/checkout/purchase-success', function (Request $request) {


    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    $subscriptionCustomer = \Modules\Billing\Models\Stripe\SubscriptionCustomer::firstOrCreate([
        'user_id' => $user->id,
    ]);

    $latestInvoice = $request->get('latest_invoice') ?? false;
    $sessionId = $request->get('session_id') ?? false;
    $checkoutSessionData = [];
    $latestInvoiceData = [];
    $transactionData = [];
    $transactionData['currency'] = 'USD';
    if ($sessionId) {
        $checkoutSessionData = $subscriptionCustomer->stripe()->checkout->sessions->retrieve($sessionId);
        if ($checkoutSessionData and isset($checkoutSessionData['amount_total']) and $checkoutSessionData['amount_total']) {
            $transactionData['value'] = intval($checkoutSessionData['amount_total']) / 100;
            $transactionData['transaction_id'] = $checkoutSessionData['id'];
            if (isset($checkoutSessionData['currency'])) {
                $transactionData['currency'] = $checkoutSessionData['currency'];
            }
        }

    }
    if ($latestInvoice) {
        $latestInvoiceData = $subscriptionCustomer->stripe()->invoices->retrieve($latestInvoice);
        if ($latestInvoiceData and isset($latestInvoiceData['total']) and $latestInvoiceData['total']) {
            $transactionData['value'] = intval($latestInvoiceData['total']) / 100;
            $transactionData['transaction_id'] = $latestInvoiceData['id'];
            if (isset($latestInvoiceData['currency'])) {
                $transactionData['currency'] = $latestInvoiceData['currency'];
            }

        }
    }

    if (isset($checkoutSessionData->metadata->internal_order_id)) {
        $findOrder = \MicroweberPackages\Order\Models\Order::where('id', $checkoutSessionData->metadata->internal_order_id)->first();
        if ($findOrder) {
            $utmData = [];
            if (isset($checkoutSessionData->metadata->utm_source)) {
                $utmData['utm_source'] = $checkoutSessionData->metadata->utm_source;
            }
            if (isset($checkoutSessionData->metadata->utm_medium)) {
                $utmData['utm_medium'] = $checkoutSessionData->metadata->utm_medium;
            }
            if (isset($checkoutSessionData->metadata->utm_campaign)) {
                $utmData['utm_campaign'] = $checkoutSessionData->metadata->utm_campaign;
            }
            if (isset($checkoutSessionData->metadata->utm_content)) {
                $utmData['utm_content'] = $checkoutSessionData->metadata->utm_content;
            }
            if (isset($checkoutSessionData->metadata->utm_term)) {
                $utmData['utm_term'] = $checkoutSessionData->metadata->utm_term;
            }

            $findOrder->is_paid = 1;
            $findOrder->order_completed = 1;
            $findOrder->transaction_id = $checkoutSessionData->id;
            $findOrder->save();

            event(new \MicroweberPackages\Order\Events\OrderWasPaid($findOrder, $utmData));
        }
    }

    $redirectOnSuccess = get_option('cashier_success_url', 'payments');
    if ($redirectOnSuccess) {
        return redirect($redirectOnSuccess)->with('transactionData', $transactionData);
    }

    $backButtonUrl = session_get('billing_purchase_referer');

    return view('billing::purchase-finish', [
        'backButtonUrl' => $backButtonUrl,
        'checkoutSessionData' => $checkoutSessionData,
        'latestInvoiceData' => $latestInvoiceData,
        'transactionData' => $transactionData,
    ]);


})->name('billing.purchase.success');

Route::get('/checkout/purchase-cancel', function (Request $request) {

    $backButtonUrl = session_get('billing_purchase_referer');
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    $subscriptionCustomer = \Modules\Billing\Models\Stripe\SubscriptionCustomer::firstOrCreate([
        'user_id' => $user->id,
    ]);

    $sessionId = $request->get('session_id') ?? false;
    $checkoutSessionData = [];
    if ($sessionId) {
        $checkoutSessionData = $subscriptionCustomer->stripe()->checkout->sessions->retrieve($sessionId);
    }

    if (isset($checkoutSessionData->metadata->internal_order_id)) {
        $findOrder = \MicroweberPackages\Order\Models\Order::where('id', $checkoutSessionData->metadata->internal_order_id)->first();
        if ($findOrder) {
            $utmData = [];
            if (isset($checkoutSessionData->metadata->utm_source)) {
                $utmData['utm_source'] = $checkoutSessionData->metadata->utm_source;
            }
            if (isset($checkoutSessionData->metadata->utm_medium)) {
                $utmData['utm_medium'] = $checkoutSessionData->metadata->utm_medium;
            }
            if (isset($checkoutSessionData->metadata->utm_campaign)) {
                $utmData['utm_campaign'] = $checkoutSessionData->metadata->utm_campaign;
            }
            if (isset($checkoutSessionData->metadata->utm_content)) {
                $utmData['utm_content'] = $checkoutSessionData->metadata->utm_content;
            }
            if (isset($checkoutSessionData->metadata->utm_term)) {
                $utmData['utm_term'] = $checkoutSessionData->metadata->utm_term;
            }

            event(new \MicroweberPackages\Order\Events\OrderWasCanceled($findOrder, $utmData));
        }
    }

    $redirect_on_cancel = get_option('cashier_cancel_url', 'payments');
    if ($redirect_on_cancel) {
        return redirect($redirect_on_cancel);
    }

    if (empty($backButtonUrl)) {
        return redirect(site_url('projects/plans'));
    }

    return view('billing::purchase-cancel', [
        'backButtonUrl' => $backButtonUrl,

    ]);

})->name('billing.purchase.cancel');


Route::post('/billing/subscribe-to-plan',
    [
        \Modules\Billing\Http\Controllers\SubscribeToPlanController::class, 'subscribeToPlan'
    ])->name('billing.subscribe-to-plan');


Route::post('/billing/save-customer-profile',
    [
        \Modules\Billing\Http\Controllers\CustomerProfileController::class, 'saveCustomerProfile'
    ]
)->name('billing.save-customer-profile');


