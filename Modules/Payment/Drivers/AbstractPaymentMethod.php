<?php

namespace Modules\Payment\Drivers;
use Modules\Payment\Models\PaymentProvider;

abstract class AbstractPaymentMethod
{
    public string $provider = '';

    public function title(): string
    {
        return 'PaymentMethod';
    }



    public function getModel() : PaymentProvider | null
    {
        return  PaymentProvider::query()->where('provider', $this->provider)->first();
    }

    public function process($data = [])
    {
        return [];
    }

    public function getSettingsForm(): array
    {
        return [];
    }
    public function getForm(): array
    {
        return [];
    }


}
