<?php

namespace MicroweberPackages\Payment\Providers\PayOnDelivery;

use MicroweberPackages\Payment\Providers\AbstractPaymentProvider;
use MicroweberPackages\Payment\Traits\LegacyPaymentProviderHelperTrait;

class PayOnDeliveryPaymentProvider extends AbstractPaymentProvider
{
    public $module = 'shop/payments/gateways/pay_on_delivery';
    public $name = 'Pay On Delivery';

    use LegacyPaymentProviderHelperTrait;
}
