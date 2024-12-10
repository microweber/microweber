<?php

namespace Modules\Payment\Drivers;

use Modules\Payment\Models\PaymentProvider;

abstract class AbstractPaymentMethod
{
    public string $provider = '';
    public PaymentProvider $model; // must be set by the driver with setModel

    public function title(): string
    {
        return 'PaymentMethod';
    }


    public function getModel(): PaymentProvider|null
    {
        return $this->model ?? null;
    }

    public function setModel(PaymentProvider $model)
    {
        return $this->model = $model;
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

    public function verifyPayment(array $data): array
    {
        return [];
    }


}
