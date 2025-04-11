<?php

namespace Modules\Billing\Services;

use Illuminate\Support\Facades\Config;
use Laravel\Cashier\Cashier;
use MicroweberPackages\User\Models\User;
use Modules\Payment\Models\PaymentProvider;
use Stripe\StripeClient;
use Exception;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanFeature;
use Modules\Customer\Models\Customer;
use Modules\Billing\Models\Subscription;

class StripeService
{
    /**
     * @var StripeClient
     */
    protected $stripe;
    public $paymentProivderId = 0;
    public $paymentProivderType = 'stripe';

    public function __construct()
    {
        $cashier_billing_payment_provider_id = get_option('cashier_billing_payment_provider_id', 'payments');
        if ($cashier_billing_payment_provider_id) {
            $this->paymentProivderId = $cashier_billing_payment_provider_id;
        }
        $this->stripe = Cashier::stripe();
    }

    public function getProducts(array $params = [])
    {
        return $this->stripe->products->all($params);
    }

    public function getPrices(array $params = [])
    {
        return $this->stripe->prices->all($params);
    }

    public function getInvoices(array $params = [])
    {
        return $this->stripe->invoices->all($params);
    }

    public function getPaymentProivderId(): int
    {
        return $this->paymentProivderId;
    }

    public function getPaymentProivderType(): string
    {
        return $this->paymentProivderType;
    }

    public function testConnection(): bool
    {
        try {
            $account = $this->stripe->accounts->retrieve();
            return isset($account->id);
        } catch (Exception $e) {
            return false;
        }
    }

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

            if (isset($priceMap[$product->id])) {
                $priceObj = $priceMap[$product->id][0];
                $plan->remote_provider_price_id = $priceObj->id;
                $plan->price = $priceObj->unit_amount ? ($priceObj->unit_amount / 100) : null;
                $plan->billing_interval = $priceObj->recurring->interval ?? null;
                $plan->save();
            }

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

    /**
     * Fetch all customers from Stripe and sync to local Customer model.
     *
     * @return int Number of customers synced
     */
    public function syncCustomers(): int
    {
        $count = 0;
        $starting_after = null;

        do {
            $params = ['limit' => 100];
            if ($starting_after) {
                $params['starting_after'] = $starting_after;
            }

            $stripeCustomers = $this->stripe->customers->all($params);

            foreach ($stripeCustomers->data as $stripeCustomer) {

                $email = $stripeCustomer->email ?? '';

                //find user with email
                $user = User::where('email', $email)->first();
                if (!$user) {
                    //create user
                    $user = new User();
                    $user->email = $email;
                    $user->is_active = 0;
                    $user->is_verified = 0;
                    $user->is_admin = 0;
                    $user->user_information = 'Synced from Stripe Customer';
                    $user->password = bcrypt(str_random(10)); // Set a random password
                    $user->save();
                }

                $customer = Customer::firstOrNew(['stripe_id' => $stripeCustomer->id]);

                if ($user && $customer->user_id != $user->id) {
                    $customer->user_id = $user->id;
                }

                $customer->email = $stripeCustomer->email ?? '';
                $customer->name = $stripeCustomer->name ?? '';
                $customer->first_name = '';
                $customer->last_name = '';
                $customer->stripe_id = $stripeCustomer->id;
                $customer->pm_type = $stripeCustomer->invoice_settings->default_payment_method->type ?? null;
                $customer->pm_last_four = $stripeCustomer->invoice_settings->default_payment_method->card->last4 ?? null;
                $customer->save();

                // sync subscriptions for this customer
                try {
                    $stripeSubscriptions = $this->stripe->subscriptions->all([
                        'customer' => $stripeCustomer->id,
                        'limit' => 100,
                    ]);

                    foreach ($stripeSubscriptions->data as $stripeSubscription) {
                        $planId = null;
                        $stripePriceId = null;
                        $priceAmount = null;
                        $billingInterval = null;

                        if (isset($stripeSubscription->items->data[0])) {
                            $item = $stripeSubscription->items->data[0];
                            $stripePriceId = $item->price->id ?? null;
                            $priceAmount = isset($item->price->unit_amount) ? ($item->price->unit_amount / 100) : null;
                            $billingInterval = $item->price->recurring->interval ?? null;

                            $plan = SubscriptionPlan::where('remote_provider_price_id', $stripePriceId)->first();
                            if ($plan) {
                                $planId = $plan->id;
                            }
                        }

                        Subscription::updateOrCreate(
                            [
                                'stripe_id' => $stripeSubscription->id,
                            ],
                            [
                                'customer_id' => $customer->id,
                                'user_id' => $user->id,
                                'subscription_plan_id' => $planId,
                                'stripe_status' => $stripeSubscription->status,
                                'stripe_price' => $stripePriceId
                            ]
                        );
                    }
                } catch (\Exception $e) {
                    // ignore subscription sync errors for this customer
                }

                $count++;
            }

            if (count($stripeCustomers->data) > 0) {
                $starting_after = end($stripeCustomers->data)->id;
            } else {
                $starting_after = null;
            }
        } while ($stripeCustomers->has_more);

        return $count;
    }
}
