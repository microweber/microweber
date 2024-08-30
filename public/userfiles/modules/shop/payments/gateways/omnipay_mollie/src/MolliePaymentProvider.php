<?php

namespace MicroweberPackages\Payment\Providers\Mollie;

use MicroweberPackages\Payment\Providers\AbstractPaymentProvider;
use MicroweberPackages\Payment\Traits\LegacyPaymentProviderHelperTrait;

class MolliePaymentProvider extends AbstractPaymentProvider
{
    public $module = 'shop/payments/gateways/omnipay_mollie';
    public $name = 'Mollie';

    use LegacyPaymentProviderHelperTrait;
}
