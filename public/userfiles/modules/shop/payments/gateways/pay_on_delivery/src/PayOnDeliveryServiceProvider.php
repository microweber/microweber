<?php

namespace MicroweberPackages\Payment\Providers\PayOnDelivery;

use Illuminate\Support\ServiceProvider;

class PayOnDeliveryServiceProvider extends  ServiceProvider
{

    public function register()
    {
        app()->resolving(\MicroweberPackages\Payment\PaymentManager::class, function (\MicroweberPackages\Payment\PaymentManager $manager) {
            $manager->extend('shop/payments/gateways/pay_on_delivery', function () {
                return new PayOnDeliveryPaymentProvider();
            });
        });
    }
}
