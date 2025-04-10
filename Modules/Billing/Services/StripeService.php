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

class StripeService
{
    /**
     * @var StripeClient
     */
    protected $stripe;
    public $paymentProivderId = 0;

    public function __construct()
    {

        $cashier_billing_payment_provider_id = get_option('cashier_billing_payment_provider_id', 'payments');
        if ($cashier_billing_payment_provider_id) {
            $this->paymentProivderId = $cashier_billing_payment_provider_id;
            $provider = PaymentProvider::where('is_active', 1)
                ->where('provider', 'stripe')
                ->where('id', $cashier_billing_payment_provider_id)
                ->first();

            $cashier_stripe_publishable_api_key = '';
            $cashier_stripe_api_key = '';
            $cashier_stripe_webhook_secret = 'false';
            if ($provider->settings and isset($provider->settings['publishable_key'])) {
                $cashier_stripe_publishable_api_key = $provider->settings['publishable_key'];
            }

            if ($provider->settings and isset($provider->settings['secret_key'])) {
                $cashier_stripe_api_key = $provider->settings['secret_key'];
            }
            if ($provider->settings and isset($provider->settings['webhook_secret'])) {
                $cashier_stripe_webhook_secret = $provider->settings['webhook_secret'];
            }


            //  $cashier_stripe_api_key = get_option('cashier_stripe_api_key', 'payments');
            //   $cashier_stripe_publishable_api_key = get_option('cashier_stripe_publishable_api_key', 'payments');
            //$cashier_stripe_webhook_secret = get_option('cashier_stripe_webhook_secret', 'payments');
            $cashier_currency = get_option('cashier_currency', 'payments');
            $cashier_currency_locale = get_option('cashier_currency_locale', 'payments');

            if ($cashier_currency) {
                Config::set('cashier.currency', $cashier_currency);
            }
            if ($cashier_currency_locale) {
                Config::set('cashier.currency_locale', $cashier_currency_locale);
            }

            if ($cashier_stripe_api_key) {
                Config::set('cashier.secret', $cashier_stripe_api_key);
                Config::set('cashier.key', $cashier_stripe_publishable_api_key);
                Config::set('cashier.webhook.secret', $cashier_stripe_webhook_secret);
            }
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
                $user =  User::where('email', $email)->first();
                if(!$user){
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

                if($user and $customer->user_id != $user->id){
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
