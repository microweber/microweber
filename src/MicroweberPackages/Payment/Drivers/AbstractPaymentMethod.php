<?php

namespace MicroweberPackages\Payment\Drivers;
abstract class AbstractPaymentMethod
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
