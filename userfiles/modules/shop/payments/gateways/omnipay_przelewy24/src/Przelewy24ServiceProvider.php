<?php

namespace MicroweberPackages\Payment\Providers\Przelewy24;

use Illuminate\Support\ServiceProvider;

class Przelewy24ServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->resolving(\MicroweberPackages\Payment\PaymentManager::class, function (\MicroweberPackages\Payment\PaymentManager $manager) {
            $manager->extend('shop/payments/gateways/omnipay_przelewy24', function () {
                return new Przelewy24PaymentProvider();
            });
        });
    }
}
