<?php

namespace Modules\Billing\Providers;

use Illuminate\Support\Facades\Config;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\CashierServiceProvider;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Billing\Models\SubscriptionItem;
use Modules\Payment\Models\PaymentProvider;

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
            __DIR__ . '/../config/cashier.php', 'cashier'
        );


    }
}
