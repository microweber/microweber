<?php

namespace MicroweberPackages\Payment\Providers\Stripe;

use MicroweberPackages\Payment\Providers\AbstractPaymentProvider;
use MicroweberPackages\Payment\Traits\LegacyPaymentProviderHelperTrait;

class StripePaymentProvider extends AbstractPaymentProvider
{
    public $module = 'shop/payments/gateways/omnipay_stripe';
    public $name = 'Stripe';

    use LegacyPaymentProviderHelperTrait;
}
