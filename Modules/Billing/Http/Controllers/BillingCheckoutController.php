<?php

namespace Modules\Billing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Order\Models\Order;

class BillingCheckoutController
{
    public function billingPortal(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $customer = SubscriptionCustomer::where('user_id', $user->id)->first();
        if (!$customer) {
            $customer = new SubscriptionCustomer();
            $customer->user_id = $user->id;
            $customer->email = $user->email;
            $customer->save();
        }

        if ($customer->subscriptions()->where('stripe_status', 'active')->count() == 0) {
            return redirect(url()->previous() . '?status=no-subscription');
        }

        return $customer->redirectToBillingPortal(route('filament.billing'));
    }

    public function subscriptionSuccess(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $customer = SubscriptionCustomer::firstOrCreate(['user_id' => $user->id]);

        $latestInvoice = $request->get('latest_invoice') ?? false;
        $sessionId = $request->get('session_id') ?? false;
        $checkoutSessionData = [];
        $latestInvoiceData = [];
        $transactionData = ['currency' => 'USD'];

        if ($sessionId) {
            $checkoutSessionData = $customer->stripe()->checkout->sessions->retrieve($sessionId);
            if ($checkoutSessionData && isset($checkoutSessionData['amount_total']) && $checkoutSessionData['amount_total']) {
                $transactionData['value'] = intval($checkoutSessionData['amount_total']) / 100;
                $transactionData['transaction_id'] = $checkoutSessionData['id'];
                if (isset($checkoutSessionData['currency'])) {
                    $transactionData['currency'] = strtoupper($checkoutSessionData['currency']);
                }
            }
        }

        if ($checkoutSessionData->payment_status == 'paid') {
            if ($latestInvoice) {
                $latestInvoiceData = $customer->stripe()->invoices->retrieve($latestInvoice);
                if ($latestInvoiceData && isset($latestInvoiceData['total']) && $latestInvoiceData['total']) {
                    $transactionData['value'] = intval($latestInvoiceData['total']) / 100;
                    $transactionData['transaction_id'] = $latestInvoiceData['id'];
                    if (isset($latestInvoiceData['currency'])) {
                        $transactionData['currency'] = $latestInvoiceData['currency'];
                    }
                }
            }

            if ($checkoutSessionData->mode == 'subscription') {
                if (isset($checkoutSessionData->metadata->subscription_plan_id)) {
                    $plan = \Modules\Billing\Models\SubscriptionPlan::find($checkoutSessionData->metadata->subscription_plan_id);
                    $stripePlanPriceId = $plan ? $plan->remote_provider_price_id : '';

                    Subscription::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'stripe_id' => $checkoutSessionData->customer,
                            'customer_id' => $customer->id,
                            'subscription_plan_id' => $checkoutSessionData->metadata->subscription_plan_id,
                        ],
                        [
                            'stripe_status' => 'active',
                            'stripe_price' => $stripePlanPriceId,
                            'starts_at' => now(),
                        ]
                    );
                }
            }

            if (isset($checkoutSessionData->metadata->internal_order_id)) {
                $order = Order::find($checkoutSessionData->metadata->internal_order_id);
                if ($order) {
                    $utmData = [];
                    foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'] as $utmKey) {
                        if (isset($checkoutSessionData->metadata->{$utmKey})) {
                            $utmData[$utmKey] = $checkoutSessionData->metadata->{$utmKey};
                        }
                    }
                    $order->is_paid = 1;
                    $order->order_completed = 1;
                    $order->transaction_id = $transactionData['transaction_id'] ?? $checkoutSessionData->id;
                    $order->save();

                    event(new \Modules\Order\Events\OrderWasPaid($order, $utmData));
                }
            }

            $redirectOnSuccess = get_option('cashier_success_url', 'payments');
            if ($redirectOnSuccess) {
                return redirect($redirectOnSuccess)->with('transactionData', $transactionData);
            }
        }

        return redirect(site_url('project/plans'));
    }

    public function subscriptionCancel(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $customer = SubscriptionCustomer::firstOrCreate(['user_id' => $user->id]);

        $sessionId = $request->get('session_id') ?? false;
        $checkoutSessionData = [];
        if ($sessionId) {
            $checkoutSessionData = $customer->stripe()->checkout->sessions->retrieve($sessionId);
        }

        if (isset($checkoutSessionData->metadata->internal_order_id)) {
            $order = Order::find($checkoutSessionData->metadata->internal_order_id);
            if ($order) {
                $utmData = [];
                foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'] as $utmKey) {
                    if (isset($checkoutSessionData->metadata->{$utmKey})) {
                        $utmData[$utmKey] = $checkoutSessionData->metadata->{$utmKey};
                    }
                }
                event(new \Modules\Order\Events\OrderWasCanceled($order, $utmData));
            }
        }

        $redirectOnCancel = get_option('cashier_cancel_url', 'payments');
        if ($redirectOnCancel) {
            return redirect($redirectOnCancel);
        }

        return view('modules.billing::cancel', [
            'backButtonUrl' => session_get('billing_subscription_referer'),
        ]);
    }

    public function purchaseSuccess(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $customer = SubscriptionCustomer::firstOrCreate(['user_id' => $user->id]);

        $latestInvoice = $request->get('latest_invoice') ?? false;
        $sessionId = $request->get('session_id') ?? false;
        $checkoutSessionData = [];
        $latestInvoiceData = [];
        $transactionData = ['currency' => 'USD'];

        if ($sessionId) {
            $checkoutSessionData = $customer->stripe()->checkout->sessions->retrieve($sessionId);
            if ($checkoutSessionData && isset($checkoutSessionData['amount_total']) && $checkoutSessionData['amount_total']) {
                $transactionData['value'] = intval($checkoutSessionData['amount_total']) / 100;
                $transactionData['transaction_id'] = $checkoutSessionData['id'];
                if (isset($checkoutSessionData['currency'])) {
                    $transactionData['currency'] = $checkoutSessionData['currency'];
                }
            }
        }

        if ($latestInvoice) {
            $latestInvoiceData = $customer->stripe()->invoices->retrieve($latestInvoice);
            if ($latestInvoiceData && isset($latestInvoiceData['total']) && $latestInvoiceData['total']) {
                $transactionData['value'] = intval($latestInvoiceData['total']) / 100;
                $transactionData['transaction_id'] = $latestInvoiceData['id'];
                if (isset($latestInvoiceData['currency'])) {
                    $transactionData['currency'] = $latestInvoiceData['currency'];
                }
            }
        }

        if (isset($checkoutSessionData->metadata->internal_order_id)) {
            $order = Order::find($checkoutSessionData->metadata->internal_order_id);
            if ($order) {
                $utmData = [];
                foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'] as $utmKey) {
                    if (isset($checkoutSessionData->metadata->{$utmKey})) {
                        $utmData[$utmKey] = $checkoutSessionData->metadata->{$utmKey};
                    }
                }
                $order->is_paid = 1;
                $order->order_completed = 1;
                $order->transaction_id = $checkoutSessionData->id;
                $order->save();

                event(new \Modules\Order\Events\OrderWasPaid($order, $utmData));
            }
        }

        $redirectOnSuccess = get_option('cashier_success_url', 'payments');
        if ($redirectOnSuccess) {
            return redirect($redirectOnSuccess)->with('transactionData', $transactionData);
        }

        return view('modules.billing::purchase-finish', [
            'backButtonUrl' => session_get('billing_purchase_referer'),
            'checkoutSessionData' => $checkoutSessionData,
            'latestInvoiceData' => $latestInvoiceData,
            'transactionData' => $transactionData,
        ]);
    }

    public function purchaseCancel(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $customer = SubscriptionCustomer::firstOrCreate(['user_id' => $user->id]);

        $sessionId = $request->get('session_id') ?? false;
        $checkoutSessionData = [];
        if ($sessionId) {
            $checkoutSessionData = $customer->stripe()->checkout->sessions->retrieve($sessionId);
        }

        if (isset($checkoutSessionData->metadata->internal_order_id)) {
            $order = Order::find($checkoutSessionData->metadata->internal_order_id);
            if ($order) {
                $utmData = [];
                foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'] as $utmKey) {
                    if (isset($checkoutSessionData->metadata->{$utmKey})) {
                        $utmData[$utmKey] = $checkoutSessionData->metadata->{$utmKey};
                    }
                }
                event(new \Modules\Order\Events\OrderWasCanceled($order, $utmData));
            }
        }

        $redirectOnCancel = get_option('cashier_cancel_url', 'payments');
        if ($redirectOnCancel) {
            return redirect($redirectOnCancel);
        }

        return view('modules.billing::purchase-cancel', [
            'backButtonUrl' => session_get('billing_purchase_referer'),
        ]);
    }
}
