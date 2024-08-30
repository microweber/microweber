<?php

namespace MicroweberPackages\Payment\Providers\BankTransfer;

use Illuminate\Support\ServiceProvider;

class BankTransferServiceProvider extends ServiceProvider

{
    public function register()
    {
        app()->resolving(\MicroweberPackages\Payment\PaymentManager::class, function (\MicroweberPackages\Payment\PaymentManager $manager) {
            $manager->extend('shop/payments/gateways/bank_transfer', function () {
                return new BankTransferPaymentProvider();
            });
        });
    }

}
