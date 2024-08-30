<?php

namespace MicroweberPackages\Payment\Providers\Przelewy24;

use MicroweberPackages\Payment\Providers\AbstractPaymentProvider;
use MicroweberPackages\Payment\Traits\LegacyPaymentProviderHelperTrait;

class Przelewy24PaymentProvider extends AbstractPaymentProvider
{
    public $module = 'shop/payments/gateways/omnipay_przelewy24';
    public $name = 'Przelewy24';

    use LegacyPaymentProviderHelperTrait;
}
