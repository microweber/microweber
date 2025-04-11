<?php

namespace Modules\Billing\Http\Controllers;

use Modules\Billing\Services\SubscriptionManager;

class SubscribeToPlanController
{
    public function subscribeToPlan(\Illuminate\Http\Request $request)
    {
        $subscriptionManager = new SubscriptionManager();

        $sku = $request->get('sku');
        $referer = $request->headers->get('referer');

        $swap = $subscriptionManager->subscribeToPlan($sku, $referer);
        if (isset($swap) and is_object($swap) and method_exists($swap, 'toArray')) {
            $data = $swap->toArray();
            if (isset($data['url']) and $data['url']) {
                return redirect($data['url']);
            }
        }

        return redirect(route('billing.subscription.success') . '?latest_invoice=' . $swap->latest_invoice);
    }

    public function swapSubscription($subscriptionCustomer, $plan, $newPlan)
    {
        $subscriptionManager = new SubscriptionManager();

        $swap = $subscriptionManager->swapSubscription($subscriptionCustomer, $plan, $newPlan);

        return redirect(route('billing.subscription.success') . '?latest_invoice=' . $swap->latest_invoice);
    }

    public function newSubscription($subscriptionCustomer, $plan)
    {
        $subscriptionManager = new SubscriptionManager();

        return $subscriptionManager->newSubscription($subscriptionCustomer, $plan);
    }
}
