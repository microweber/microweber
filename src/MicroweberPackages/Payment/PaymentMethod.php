<?php

namespace MicroweberPackages\Payment;

abstract class PaymentMethod
{

    public function title(): string
    {
        return '';
    }

    public function view(): string
    {
        return '';
    }

    public function process($data = [])
    {
        return [];
    }

    public function getSettingsForm($form): array
    {
        return [];
    }


}
