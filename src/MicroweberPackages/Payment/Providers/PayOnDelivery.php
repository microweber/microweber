<?php

namespace MicroweberPackages\Payment\Providers;

class PayOnDelivery extends \MicroweberPackages\Payment\PaymentMethod
{

    public function title(): string
    {
        return 'Pay on delivery';
    }

    public function process($data = [])
    {
        return [
            'success' => true,
           // 'redirect' => route('checkout.success')
        ];
    }



    public function view(): string
    {
        return 'payment::pay_on_delivery';
    }

}
