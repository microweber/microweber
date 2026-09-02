<?php

namespace MicroweberPackages\Payment\Providers\Payzum;

use Illuminate\Support\ServiceProvider;

class PayzumServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->resolving(\MicroweberPackages\Payment\PaymentManager::class, function (\MicroweberPackages\Payment\PaymentManager $manager) {
            $manager->extend('shop/payments/gateways/omnipay_payzum', function () {
                return new PayzumPaymentProvider();
            });
        });
    }
}
