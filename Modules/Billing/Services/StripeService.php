<?php

namespace Modules\Billing\Services;

use Laravel\Cashier\Cashier;
use Stripe\StripeClient;
use Exception;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanFeature;

class StripeService
{
    /**
     * @var StripeClient
     */
    protected $stripe;

    public function __construct()
    {
        $this->stripe = Cashier::stripe();
    }

    /**
     * Fetch all Stripe products.
     *
     * @param array $params Optional parameters for the API call
     * @return \Stripe\Collection
     */
    public function getProducts(array $params = [])
    {
        return $this->stripe->products->all($params);
    }

    /**
     * Fetch all Stripe prices.
     *
     * @param array $params Optional parameters for the API call
     * @return \Stripe\Collection
     */
    public function getPrices(array $params = [])
    {
        return $this->stripe->prices->all($params);
    }

    /**
     * Fetch all Stripe invoices.
     *
     * @param array $params Optional parameters for the API call
     * @return \Stripe\Collection
     */
    public function getInvoices(array $params = [])
    {
        return $this->stripe->invoices->all($params);
    }

    /**
     * Test if the Stripe API connection is working.
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $account = $this->stripe->accounts->retrieve();
            return isset($account->id);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Sync Stripe products and prices to local SubscriptionPlan models.
     *
     * @return int Number of products synced
     */
    public function syncProducts(): int
    {
        $products = $this->getProducts(['limit' => 100]);
        $prices = $this->getPrices(['limit' => 100]);

        $priceMap = [];
        foreach ($prices->data as $price) {
            if (!isset($priceMap[$price->product])) {
                $priceMap[$price->product] = [];
            }
            $priceMap[$price->product][] = $price;
        }

        $count = 0;

        foreach ($products->data as $product) {
            $plan = SubscriptionPlan::updateOrCreate(
                ['sku' => $product->id],
                [
                    'name' => $product->name,
                    'description' => $product->description ?? '',
                    'remote_provider' => 'stripe',
                    'remote_provider_price_id' => null,
                    'price' => null,
                    'billing_interval' => null,
                ]
            );

            // Attach prices info
            if (isset($priceMap[$product->id])) {
                $priceObj = $priceMap[$product->id][0]; // Take the first price
                $plan->remote_provider_price_id = $priceObj->id;
                $plan->price = $priceObj->unit_amount ? ($priceObj->unit_amount / 100) : null;
                $plan->billing_interval = $priceObj->recurring->interval ?? null;
                $plan->save();
            }

            // Sync features from product metadata or description
            $plan->features()->delete();

            if (!empty($product->metadata)) {
                foreach ($product->metadata as $key => $value) {
                    SubscriptionPlanFeature::create([
                        'subscription_plan_id' => $plan->id,
                        'key' => $key,
                        'value' => $value,
                    ]);
                }
            }

            if (!empty($product->description)) {
                SubscriptionPlanFeature::create([
                    'subscription_plan_id' => $plan->id,
                    'key' => 'description',
                    'value' => $product->description,
                ]);
            }

            $count++;
        }

        return $count;
    }
}
