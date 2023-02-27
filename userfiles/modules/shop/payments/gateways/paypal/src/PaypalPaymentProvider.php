<?php

namespace MicroweberPackages\Payment\Providers\Paypal;

use MicroweberPackages\Payment\Providers\AbstractPaymentProvider;
use MicroweberPackages\Payment\Traits\LegacyPaymentProviderHelperTrait;

class PaypalPaymentProvider extends AbstractPaymentProvider
{
    public $module = 'shop/payments/gateways/paypal';
    public $name = 'Paypal';

    use LegacyPaymentProviderHelperTrait;

}
