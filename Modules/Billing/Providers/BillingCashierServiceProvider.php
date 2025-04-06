<?php

namespace Modules\Billing\Providers;

use Illuminate\Support\Facades\Config;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\CashierServiceProvider;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Billing\Models\SubscriptionItem;

class BillingCashierServiceProvider extends CashierServiceProvider
{
    public function register()
    {
      //  Cashier::ignoreMigrations();
        Cashier::ignoreRoutes();

        Cashier::useSubscriptionModel(Subscription::class);
        Cashier::useSubscriptionItemModel(SubscriptionItem::class);
        Cashier::useCustomerModel(SubscriptionCustomer::class);

        parent::register();
    }

    /**
     * Setup the configuration for Cashier.
     *
     * @return void
     */
    protected function configure()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/cashier.php', 'cashier'
        );

        if (mw_is_installed()) {
            $cashier_stripe_api_key = get_option('cashier_stripe_api_key', 'payments');
            $cashier_stripe_publishable_api_key = get_option('cashier_stripe_publishable_api_key', 'payments');
            $cashier_stripe_webhook_secret = get_option('cashier_stripe_webhook_secret', 'payments');
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
    }
}
