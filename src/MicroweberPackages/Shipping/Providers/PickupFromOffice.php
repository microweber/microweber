<?php

namespace MicroweberPackages\Shipping\Providers;


class PickupFromOffice implements ShippingProviderInterface
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
        return [];
    }


}