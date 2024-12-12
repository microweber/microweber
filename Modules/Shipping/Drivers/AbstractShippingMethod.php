<?php

namespace Modules\Shipping\Drivers;

use Modules\Shipping\Models\ShippingProvider;

abstract class AbstractShippingMethod
{

    public string $provider = 'abstract_shipping_method';
    public ShippingProvider $model; // must be set by the driver with setModel

    public function getModel(): ShippingProvider|null
    {
        return $this->model ?? null;
    }

    public function setModel(ShippingProvider $model)
    {
        return $this->model = $model;
    }

    public function title(): string
    {
        return '';
    }


    public function getShippingCost($data = []): float|int
    {



        return 0;
    }

    public function getForm(): array
    {
        return [];
    }

    public function getSettingsForm(): array
    {
        return [];
    }


}
