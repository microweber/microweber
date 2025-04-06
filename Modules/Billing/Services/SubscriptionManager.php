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
     public function getSubscriptionCustomer()
    {
        $user = auth()->user();

        if (!$user) {
            return ['error'=>'User not found'];
        }

        $subscriptionCustomer = SubscriptionCustomer::firstOrCreate([
            'user_id' => $user->id,
        ]);

        if($subscriptionCustomer->stripe_id){
            $stripe = $subscriptionCustomer->stripe();
            try {
                $stripeCustomer = $stripe->customers->retrieve($subscriptionCustomer->stripe_id);
            }  catch (\Stripe\Exception\InvalidRequestException $e) {
                $subscriptionCustomer->stripe_id = null;
                $subscriptionCustomer->save();
            }
        }

        return $subscriptionCustomer;
    }

    public function subscribeToPlan($sku, $referer = null)
    {
        if (!$sku) {
            return ['error'=>'SKU not found'];
        }

        if ($referer) {
            session_set('billing_subscription_referer', $referer);
        }

        $subscriptionCustomer = $this->getSubscriptionCustomer($referer);

        $plan = getSubscriptionPlanBySKU($sku);
        if (!$plan) {
            return ['error' => 'Plan not found'];
        }

        $swapFromPlan = false;
        if (isset($plan['subscription_plan_group_id']) and $plan['subscription_plan_group_id'] != 0) {
            $allPlansFromGroup = SubscriptionPlan::where('subscription_plan_group_id', $plan['subscription_plan_group_id'])->get();
            if ($allPlansFromGroup) {
                foreach ($allPlansFromGroup as $planFromGroup) {
                    $isOnPlan = checkUserIsSubscribedToPlanBySKU(user_id(), $planFromGroup['sku']);
                    if ($isOnPlan) {
                        $planSubDetails = getUserSubscriptionPlanBySKU($planFromGroup['sku']);
                        $subId = $planSubDetails['stripe_id'];
                        if ($subId) {
                            $stripe = $subscriptionCustomer->stripe();
                            $stripeSubscription = $stripe->subscriptions->retrieve($subId);

                            if ($stripeSubscription->status == 'active') {
                                $swapFromPlan = $planFromGroup;
                            } else {
                                return ['error' => 'Cannot swap from inactive plan'];
                            }
                        }
                    }
                }
            }
        }

        if ($swapFromPlan) {
            return $this->swapSubscription($subscriptionCustomer, $swapFromPlan, $plan);
        } else {
            return $this->newSubscription($subscriptionCustomer, $plan);
        }
    }

    public function swapSubscription($subscriptionCustomer, $plan, $newPlan)
    {
        $swapFromPlan = $plan;

        $planSubDetails = getUserSubscriptionPlanBySKU($swapFromPlan['sku']);

        $subId = $planSubDetails['stripe_id'];

        $stripe = $subscriptionCustomer->stripe();
        $stripeSubscription = $stripe->subscriptions->retrieve($subId);

        $updateSub = $stripe->subscriptions->update(
            $subId,
            [
                'cancel_at_period_end' => false,
                'proration_behavior' => 'create_prorations',
                'items' => [
                    [
                        'id' => $stripeSubscription->items->data[0]->id,
                        'price' => $newPlan['remote_provider_price_id'],
                    ],
                ],
            ]
        );

        if ($updateSub) {
            $customerDetails = [];
            $customerDetails['email'] = $subscriptionCustomer->email();

            mw()->cart_manager->delete_cart([
                'session_id' => session_id()
            ]);
            mw()->cart_manager->update_cart([
                'for'=>'subscription_plans',
                'for_id'=>$plan['id'],
                'qty'=>1,
                'title'=>$plan['name'],
                'price'=> 10,
            ]);

            $cartTotal = cart_total();
            $cartDiscount = cart_get_discount();
            $currencyCode = get_currency_code();

            $newOrder = new Order();
            $newOrder->created_by = $subscriptionCustomer->user_id;
            $newOrder->first_name = $subscriptionCustomer->first_name;
            $newOrder->last_name = $subscriptionCustomer->last_name;
            $newOrder->payment_gw = 'stripe';
            $newOrder->email = $customerDetails['email'];
            $newOrder->currency = $currencyCode;
            $newOrder->amount = $cartTotal;
            $newOrder->rel_type = 'subscription_plans';
            $newOrder->rel_id = $plan['id'];
            $newOrder->is_paid = 1;
            $newOrder->order_completed = 1;
            $newOrder->transaction_id = time().rand(1111,99999);
            $newOrder->save();

            $getCart = get_cart();
            if (!empty($getCart)) {
                foreach ($getCart as $cartItem) {
                    $findCartItem = Cart::where('id', $cartItem['id'])->first();
                    if ($findCartItem) {
                        $findCartItem->order_completed = 1;
                        $findCartItem->order_id = $newOrder->id;
                        $findCartItem->save();
                    }
                }
            }

            event(new  OrderWasPaid($newOrder, []));
        }

        return $updateSub;
    }

    public function newSubscription($subscriptionCustomer, $plan)
    {
        $customerDetails = [];
        $customerDetails['email'] = $subscriptionCustomer->email();

        mw()->cart_manager->delete_cart([
            'session_id' => session_id()
        ]);
        mw()->cart_manager->update_cart([
            'for'=>'subscription_plans',
            'for_id'=>$plan['id'],
            'qty'=>1,
            'title'=>$plan['name'],
            'price'=> 10,
        ]);

        $cartTotal = cart_total();
        $cartDiscount = cart_get_discount();
        $currencyCode = get_currency_code();

        $newOrder = new Order();
        $newOrder->created_by = $subscriptionCustomer->user_id;
        $newOrder->first_name = $subscriptionCustomer->first_name;
        $newOrder->last_name = $subscriptionCustomer->last_name;
        $newOrder->payment_gw = 'stripe';
        $newOrder->email = $customerDetails['email'];
        $newOrder->is_paid = 0;
        $newOrder->order_completed = 0;
        $newOrder->currency = $currencyCode;
        $newOrder->amount = $cartTotal;
        $newOrder->rel_type = 'subscription_plans';
        $newOrder->rel_id = $plan['id'];
        $newOrder->save();

        $getCart = get_cart();
        if (!empty($getCart)) {
            foreach ($getCart as $cartItem) {
                $findCartItem = Cart::where('id', $cartItem['id'])->first();
                if ($findCartItem) {
                    $findCartItem->order_completed = 1;
                    $findCartItem->order_id = $newOrder->id;
                    $findCartItem->save();
                }
            }
        }

        $metaData = [
            'subscription_plan_id' => $plan['id'],
            'internal_order_id' => $newOrder->id,
        ];

        $utmDetails = UserAttribute::getUtmDetails($subscriptionCustomer->user_id);
        if (!empty($utmDetails)) {
            $metaData = array_merge($metaData, $utmDetails);
        }

        $checkoutData = [
            'success_url' => route('billing.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('billing.subscription.cancel') . '?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => $metaData,
        ];

        if(!empty($plan['auto_apply_coupon_code'])) {
            $checkoutData['discounts'][]['coupon'] = $plan['auto_apply_coupon_code'];
        } else {
            $checkoutData['allow_promotion_codes'] = true;
        }

        $response = $subscriptionCustomer
            ->newSubscription('default', $plan['remote_provider_price_id'])
            ->checkout($checkoutData, $customerDetails);

        event(new BeginCheckoutEvent([
            'cart'=>$getCart,
            'total'=>$cartTotal,
            'discount'=>$cartDiscount,
            'currency'=>$currencyCode
        ]));

        return $response;
    }

    public function newPurchase($subscriptionCustomer, $plan)
    {
        $customerDetails = [];
        $customerDetails['email'] = $subscriptionCustomer->email();

        mw()->cart_manager->delete_cart([
            'session_id' => session_id()
        ]);
        mw()->cart_manager->update_cart([
            'for'=>'subscription_plans',
            'for_id'=>$plan['id'],
            'qty'=>1,
            'title'=>$plan['name'],
            'price'=> 10,
        ]);
        $cartTotal = cart_total();
        $cartDiscount = cart_get_discount();
        $currencyCode = get_currency_code();

        $newOrder = new Order();
        $newOrder->created_by = $subscriptionCustomer->user_id;
        $newOrder->first_name = $subscriptionCustomer->first_name;
        $newOrder->last_name = $subscriptionCustomer->last_name;
        $newOrder->payment_gw = 'stripe';
        $newOrder->email = $customerDetails['email'];
        $newOrder->is_paid = 0;
        $newOrder->order_completed = 0;
        $newOrder->currency = $currencyCode;
        $newOrder->amount = $cartTotal;
        $newOrder->rel_type = 'subscription_plans';
        $newOrder->rel_id = $plan['id'];
        $newOrder->save();

        $getCart = get_cart();
        if (!empty($getCart)) {
            foreach ($getCart as $cartItem) {
                $findCartItem = Cart::where('id', $cartItem['id'])->first();
                if ($findCartItem) {
                    $findCartItem->order_completed = 1;
                    $findCartItem->order_id = $newOrder->id;
                    $findCartItem->save();
                }
            }
        }

        $metaData = [
            'subscription_plan_id' => $plan['id'],
            'internal_order_id' => $newOrder->id,
        ];

        $utmDetails = UserAttribute::getUtmDetails($subscriptionCustomer->user_id);
        if (!empty($utmDetails)) {
            $metaData = array_merge($metaData, $utmDetails);
        }

        $checkoutData = [
            'success_url' => route('billing.purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('billing.purchase.cancel') . '?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => $metaData,
        ];

        if(!empty($plan['auto_apply_coupon_code'])) {
            $checkoutData['discounts'][]['coupon'] = $plan['auto_apply_coupon_code'];
        } else {
            $checkoutData['allow_promotion_codes'] = true;
        }

        $quantity = 1;

        $response = $subscriptionCustomer->checkout([$plan['remote_provider_price_id'] => $quantity], $checkoutData);

        event(new BeginCheckoutEvent([
            'cart'=>$getCart,
            'total'=>$cartTotal,
            'discount'=>$cartDiscount,
            'currency'=>$currencyCode,
        ]));

        return $response;
    }
}
