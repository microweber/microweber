<?php

namespace MicroweberPackages\Payment\Providers\Mollie;

use Illuminate\Support\ServiceProvider;

class MollieServiceProvider extends ServiceProvider
{

    public function register()
    {
        app()->resolving(\MicroweberPackages\Payment\PaymentManager::class, function (\MicroweberPackages\Payment\PaymentManager $manager) {
            $manager->extend('shop/payments/gateways/omnipay_mollie', function () {
                return new MolliePaymentProvider();
            });
        });
    }
}
