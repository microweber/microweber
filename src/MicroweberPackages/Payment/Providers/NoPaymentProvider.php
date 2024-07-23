<?php

namespace MicroweberPackages\Payment\Providers;
/**
 * @deprecated  Deprecated
 */
class NoPaymentProvider extends AbstractPaymentProvider
{
    public function name() : string
    {
        return 'No payment provider';
    }

}
