<?php

namespace MicroweberPackages\Payment\Providers\Paypal;

use Illuminate\Support\ServiceProvider;

class PaypalServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->resolving(\MicroweberPackages\Payment\PaymentManager::class, function (\MicroweberPackages\Payment\PaymentManager $manager) {
            $manager->extend('shop/payments/gateways/paypal', function () {
                return new PaypalPaymentProvider();
            });
        });
    }
}
{

}
