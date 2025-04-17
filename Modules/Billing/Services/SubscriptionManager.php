<?php

namespace Modules\Billing\Services;

use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Cart\Models\Cart;
use Modules\Checkout\Events\BeginCheckoutEvent;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Models\Order;
use Modules\SiteStats\Models\UserAttribute;

class SubscriptionManager
{
    private function handleCart($plan)
    {
        mw()->cart_manager->delete_cart([
            'session_id' => session_id()
        ]);

        mw()->cart_manager->update_cart([
            'rel_type' => morph_name(SubscriptionPlan::class),
            'rel_id' => $plan['id'],
            'qty' => 1,
            'title' => $plan['name'],
            'price' => $plan['price'],
        ]);

        return get_cart();
    }

    private function createOrder(SubscriptionCustomer $subscriptionCustomer, $plan, $isPaid = false)
    {
        $cartTotal = cart_total();
        $currencyCode = get_currency_code();


        //   Stripe
        $service = app()->make(\Modules\Billing\Services\StripeService::class);
        /**
         * @var StripeService $service
         */
        $payment_provider_id = $service->getPaymentProivderId();
        $payment_provider = $service->getPaymentProivderType();

        $order = new Order();
        $order->created_by = $subscriptionCustomer->user_id;
        $order->customer_id = $subscriptionCustomer->id;
        $order->first_name = $subscriptionCustomer->first_name;
        $order->last_name = $subscriptionCustomer->last_name;
        $order->payment_provider = $payment_provider;
        $order->payment_provider_id = $payment_provider_id;
        $order->email = $subscriptionCustomer->getEmail();
        $order->currency = $currencyCode;
        $order->amount = $cartTotal;
        $order->rel_type = morph_name(SubscriptionPlan::class);
        $order->rel_id = $plan['id'];
        $order->is_paid = $isPaid;
        $order->order_completed = $isPaid;
        $order->transaction_id = $isPaid ? 'billing-order-' . time() . rand(1111, 99999) : null;
        $order->save();

        $this->attachCartItemsToOrder($order);

        return $order;
    }

    private function attachCartItemsToOrder($order)
    {
        $cart = get_cart();
        if (!empty($cart)) {
            foreach ($cart as $cartItem) {
                Cart::where('id', $cartItem['id'])->update([
                    'order_completed' => 1,
                    'order_id' => $order->id
                ]);
            }
        }
    }

    private function prepareCheckoutData($plan, $order, $subscriptionCustomer, $type = 'subscription')
    {
        $metaData = [
            'subscription_plan_id' => $plan['id'],
            'internal_order_id' => $order->id,
        ];

        $utmDetails = UserAttribute::getUtmDetails($subscriptionCustomer->user_id);
        if (!empty($utmDetails)) {
            $metaData = array_merge($metaData, $utmDetails);
        }

        $checkoutData = [
            'success_url' => route("billing.{$type}.success") . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route("billing.{$type}.cancel") . '?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => $metaData,
        ];

        if (!empty($plan['auto_apply_coupon_code'])) {
            $checkoutData['discounts'][]['coupon'] = $plan['auto_apply_coupon_code'];
        } else {
            $checkoutData['allow_promotion_codes'] = true;
        }

        return $checkoutData;
    }

    private function triggerCheckoutEvent()
    {
        event(new BeginCheckoutEvent([
            'cart' => get_cart(),
            'total' => cart_total(),
            'discount' => cart_get_discount(),
            'currency' => get_currency_code()
        ]));
    }

    public function getSubscriptionCustomer()
    {
        $user = auth()->user();

        if (!$user) {
            return ['error' => 'User not found'];
        }

        $subscriptionCustomer = SubscriptionCustomer::firstOrCreate([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
        if (!$subscriptionCustomer->email) {
            $subscriptionCustomer->email = $subscriptionCustomer->getEmail();
        }
        if ($subscriptionCustomer->stripe_id) {
            $stripe = $subscriptionCustomer->stripe();
            try {
                $stripe->customers->retrieve($subscriptionCustomer->stripe_id);
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $subscriptionCustomer->update(['stripe_id' => null]);
            }
        }

        return $subscriptionCustomer;
    }

    public function subscribeToPlan($sku, $referer = null)
    {
        if (!$sku) {
            return ['error' => 'SKU not found'];
        }

        if ($referer) {
            session_set('billing_subscription_referer', $referer);
        }

        $subscriptionCustomer = $this->getSubscriptionCustomer($referer);
        $plan = getSubscriptionPlanBySKU($sku);

        if (!$plan) {
            return ['error' => 'Plan not found'];
        }


        $isRecurring = !empty($plan['billing_interval']);

        if (!$isRecurring) {
            return $this->newPurchase($subscriptionCustomer, $plan);
        }

        $swapFromPlan = $this->findSwapablePlan($plan, $subscriptionCustomer);

        return $swapFromPlan
            ? $this->swapSubscription($subscriptionCustomer, $swapFromPlan, $plan)
            : $this->newSubscription($subscriptionCustomer, $plan);
    }

    private function findSwapablePlan($plan, $subscriptionCustomer)
    {
        if (!isset($plan['subscription_plan_group_id']) || $plan['subscription_plan_group_id'] == 0) {
            return false;
        }

        $groupPlans = SubscriptionPlan::where('subscription_plan_group_id', $plan['subscription_plan_group_id'])->get();

        foreach ($groupPlans as $groupPlan) {
            if (checkUserIsSubscribedToPlanBySKU(user_id(), $groupPlan['sku'])) {
                $planSubDetails = getUserSubscriptionPlanBySKU($groupPlan['sku']);

                if (!empty($planSubDetails['stripe_id'])) {
                    $stripe = $subscriptionCustomer->stripe();
                    $subscription = $stripe->subscriptions->retrieve($planSubDetails['stripe_id']);

                    if ($subscription->status == 'active') {
                        return $groupPlan;
                    }
                }
            }
        }

        return false;
    }

    public function swapSubscription(SubscriptionCustomer $subscriptionCustomer, $plan, $newPlan)
    {
        $planSubDetails = getUserSubscriptionPlanBySKU($plan['sku']);
        $stripe = $subscriptionCustomer->stripe();
        $subscription = $stripe->subscriptions->retrieve($planSubDetails['stripe_id']);

        $updateSub = $stripe->subscriptions->update(
            $planSubDetails['stripe_id'],
            [
                'cancel_at_period_end' => false,
                'proration_behavior' => 'create_prorations',
                'items' => [
                    [
                        'id' => $subscription->items->data[0]->id,
                        'price' => $newPlan['remote_provider_price_id'],
                    ],
                ],
            ]
        );

        if ($updateSub) {
            $cart = $this->handleCart($plan);
            $order = $this->createOrder($subscriptionCustomer, $plan, true);
            event(new OrderWasPaid($order, []));
        }

        return $updateSub;
    }

    public function newSubscription(SubscriptionCustomer $subscriptionCustomer, $plan): \Laravel\Cashier\Checkout
    {
        $cart = $this->handleCart($plan);
        $order = $this->createOrder($subscriptionCustomer, $plan);
        $checkoutData = $this->prepareCheckoutData($plan, $order, $subscriptionCustomer);

        $response = $subscriptionCustomer
            ->newSubscription('default', $plan['remote_provider_price_id'])
            ->checkout($checkoutData, ['email' => $subscriptionCustomer->email()]);

        $this->triggerCheckoutEvent();

        return $response;
    }

    public function newPurchase(SubscriptionCustomer $subscriptionCustomer, $plan): \Laravel\Cashier\Checkout
    {
        $cart = $this->handleCart($plan);
        $order = $this->createOrder($subscriptionCustomer, $plan);
        $checkoutData = $this->prepareCheckoutData($plan, $order, $subscriptionCustomer, 'purchase');

        $response = $subscriptionCustomer->checkout(
            [$plan['remote_provider_price_id'] => 1],
            $checkoutData
        );

        $this->triggerCheckoutEvent();

        return $response;
    }
}
