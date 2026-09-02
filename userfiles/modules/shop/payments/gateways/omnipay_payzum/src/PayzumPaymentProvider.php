<?php

namespace MicroweberPackages\Payment\Providers\Payzum;

use MicroweberPackages\Payment\Providers\AbstractPaymentProvider;
use MicroweberPackages\Payment\Traits\LegacyPaymentProviderHelperTrait;

class PayzumPaymentProvider extends AbstractPaymentProvider
{
    public $module = 'shop/payments/gateways/omnipay_payzum';
    public $name = 'Payzum';

    use LegacyPaymentProviderHelperTrait;
}
