<?php

namespace MicroweberPackages\Payment\Providers\Stripe;

use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->resolving(\MicroweberPackages\Payment\PaymentManager::class, function (\MicroweberPackages\Payment\PaymentManager $manager) {
            $manager->extend('shop/payments/gateways/omnipay_stripe', function () {
                return new StripePaymentProvider();
            });
        });
    }
}
