<?php

namespace Modules\Billing\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
class SubscriptionItem extends \Laravel\Cashier\SubscriptionItem
{
    use FiresModelLifecycleEvents;

    protected $table = 'subscription_items';
}
