<?php

use Modules\Billing\Models\SubscriptionPlanGroup;


if (!function_exists('get_user_active_subscription_plan_by_group_sku')) {
    function get_user_active_subscription_plan_by_group_sku($sku)
    {
        $userPlans = getUserActiveSubscriptionPlanBySKU(user_id(), $sku);
        if ($userPlans) {
            return $userPlans;
        }
    }
}
if (!function_exists('get_subscription_plans')) {
    function get_subscription_plans($filter = [])
    {
        return getSubscriptionPlans($filter);
    }
}

if (!function_exists('user_is_subscribed_to_plan_sku')) {
    function user_is_subscribed_to_plan_sku($userId, $sku)
    {
        return checkUserIsSubscribedToPlanBySKU($userId, $sku);
    }

}

if (!function_exists('user_is_subscribed_to_plan_group_sku')) {
    function user_is_subscribed_to_plan_group_sku($userId, $sku)
    {
        return getUserActiveSubscriptionPlanBySKU($userId, $sku);
    }

}
function checkUserIsSubscribedToPlanBySKU($userId, $sku)
{
    $user = \MicroweberPackages\User\Models\User::where('id', $userId)->first();
    if ($user) {
        $subscriptionCustomer = \Modules\Billing\Models\SubscriptionCustomer::firstOrCreate([
            'user_id' => $user->id,
        ]);
        $sub = $subscriptionCustomer->subscribed($sku);
        if ($sub) {
            return true;
        }
    }
    return false;
}
function getUserActiveSubscriptionPlanBySKU($userId, $sku)
{
    $user = \MicroweberPackages\User\Models\User::where('id', $userId)->first();

    if ($user) {

        $findSubscriptionManual = \Modules\Billing\Models\SubscriptionManual::where('user_id', $user->id)->first();
        if ($findSubscriptionManual) {
            $plan = \Modules\Billing\Models\SubscriptionPlan::where('id', $findSubscriptionManual->subscription_plan_id)->first();
            if ($plan) {
                return $plan->toArray();
            }
        }

        $subscriptionCustomer = \Modules\Billing\Models\SubscriptionCustomer::firstOrNew([
            'user_id' => $user->id,
        ]);

        $group = \Modules\Billing\Models\SubscriptionPlanGroup::where('sku', $sku)->first();

        if ($group) {
            $plans = $group->plans()->get();
            if ($plans) {
                foreach ($plans as $plan) {

                    $is = checkUserIsSubscribedToPlanBySKU($user->id, $plan->sku);
                    if ($is) {
                        return $plan->toArray();
                    }
                }
            }
        }

    }
}

function getUserSubscriptionPlanBySKU($sku)
{
    $user = auth()->user();
    if ($user) {
        $subscriptionCustomer = \Modules\Billing\Models\SubscriptionCustomer::firstOrNew([
            'user_id' => $user->id,
        ]);
        if ($subscriptionCustomer) {
            $plan = \Modules\Billing\Models\SubscriptionPlan::where('sku', $sku)->first();
            $subscriptionCustomer->load('subscriptions');

            $subs = $subscriptionCustomer->subscriptions;

            if ($plan and $subs) {
                foreach ($subs as $sub) {

                    if (!$sub->stripe_price) {
                        continue;
                    }
                    if (!$plan->remote_provider_price_id) {
                        continue;
                    }

                    if (trim($sub->stripe_price) == trim($plan->remote_provider_price_id)) {
                        return $sub->toArray();
                    }
                }
            }


        }
    }

    return [];
}

function getSubscriptionPlanBySKU($sku)
{
    $plan = \Modules\Billing\Models\SubscriptionPlan::where('sku', $sku)->first();
    if (!empty($plan)) {
        return $plan->toArray();
    }
    return [];
}

function getSubscriptionPlans($filter = [])
{
    $planGroup = SubscriptionPlanGroup::where('sku', 'hosting')->first();
    if (!$planGroup) {
        return [];
    }

    $query = \Modules\Billing\Models\SubscriptionPlan::query();
    $query->where('group_id', $planGroup->id);
    $query->orderBy('sort_order', 'asc');
    $plans = $query->get();
    if ($plans->count() > 0) {
        return $plans->toArray();
    }
    return [];
}

function getSubscriptionCustomer()
{
    $user = auth()->user();
    if ($user) {
        $subscriptionCustomer = \Modules\Billing\Models\SubscriptionCustomer::firstOrNew([
            'user_id' => $user->id,
        ]);

        if ($subscriptionCustomer) {
            return $subscriptionCustomer->toArray();
        }
    }

}

function generatePublicCheckoutUrlBySKU($sku)
{
    $plan = \Modules\Billing\Models\SubscriptionPlan::where('sku', $sku)->first();
    if (!$plan) {
        return '';
    }

    $redirectLink = route('user.websites.buy-license', $plan['id']);
    $user = auth()->user();
    if (!$user) {
        return site_url() . 'auth/login?quick_checkout_sku=' . $sku;
    }

    return $redirectLink;

}
