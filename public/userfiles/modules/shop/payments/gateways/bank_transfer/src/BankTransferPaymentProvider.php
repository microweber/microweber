<?php

namespace MicroweberPackages\Payment\Providers\BankTransfer;

use MicroweberPackages\Payment\Providers\AbstractPaymentProvider;
use MicroweberPackages\Payment\Traits\LegacyPaymentProviderHelperTrait;

class BankTransferPaymentProvider extends AbstractPaymentProvider
{
    use LegacyPaymentProviderHelperTrait;
    public $module = 'shop/payments/gateways/bank_transfer';
    public $name = 'Bank Transfer';


}
