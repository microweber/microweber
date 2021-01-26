<?php

namespace MicroweberPackages\Shipping\Drivers;


class PickupFromOffice implements ShippingDriverInterface
{

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
        return true;
    }


}