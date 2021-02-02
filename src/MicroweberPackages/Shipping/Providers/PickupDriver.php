<?php

namespace MicroweberPackages\Shipping\Providers;


class PickupDriver implements ShippingDriverInterface
{

    public function isEnabled()
    {
        return false;
    }

    public function title()
    {
        return 'Pickup from office';
    }

    public function cost()
    {
        return 0;
    }

    public function process()
    {
        return [];
    }


}