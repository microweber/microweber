<?php

namespace Modules\Billing\Http\Livewire\Admin;

use Livewire\Component;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanGroup;

class SubscriptionPlans extends Component
{
    public $listeners = ['refreshSubscriptionPlans' => '$refresh'];

    public $deleteConfirm = false;

    public function render()
    {
        $subscriptionPlans = SubscriptionPlan::all();
        return view('billing::admin.livewire.subscription-plans', compact('subscriptionPlans'));
    }

    public function syncPricesFromStripe()
    {

        $cashier_stripe_api_key = get_option('cashier_stripe_api_key', 'payments');

        $stripe = new \Stripe\StripeClient($cashier_stripe_api_key);
        $getPlans = $stripe->plans->all();
        if (isset($getPlans->data)) {

            $findHostingPlanGroup = SubscriptionPlanGroup::where('sku', 'hosting')->first();
            if (!$findHostingPlanGroup) {
                $findHostingPlanGroup = new SubscriptionPlanGroup();
                $findHostingPlanGroup->sku = 'hosting';
                $findHostingPlanGroup->name = 'Hosting';
                $findHostingPlanGroup->save();
            }

            foreach ($getPlans->data as $stripePlan) {

                $findSubscriptionPlan = SubscriptionPlan::where('remote_provider', 'stripe')
                    ->where('remote_provider_price_id', $stripePlan->id)
                    ->first();
                if (!$findSubscriptionPlan) {
                    $findSubscriptionPlan = new SubscriptionPlan();
                    $findSubscriptionPlan->remote_provider = 'stripe';
                    $findSubscriptionPlan->remote_provider_price_id = $stripePlan->id;
                    $findSubscriptionPlan->name = "Plan " . $stripePlan->amount / 100;
                    $findSubscriptionPlan->sku = "stripe_" . $stripePlan->id;
                    $findSubscriptionPlan->group_id = $findHostingPlanGroup->id;
                }

                $findSubscriptionPlan->price = currency_format(($stripePlan->amount / 100), $stripePlan->currency);
                if ($stripePlan->interval == 'month') {
                    $findSubscriptionPlan->billing_interval = 'monthly';
                } else {
                    $findSubscriptionPlan->billing_interval = 'annually';
                }
                $findSubscriptionPlan->save();

            }
        }

    }

    public function delete($id)
    {
        $this->deleteConfirm = $id;
    }

    public function deleteExecute($id)
    {
        $subscriptionPlan = SubscriptionPlan::find($id);
        $subscriptionPlan->delete();

        $this->emit('refreshSubscriptionPlans');
    }

}
