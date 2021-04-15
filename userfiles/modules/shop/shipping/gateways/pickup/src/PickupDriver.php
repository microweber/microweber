<?php

namespace MicroweberPackages\Shop\Shipping\Gateways\Pickup;


use MicroweberPackages\Shipping\Providers\ShippingDriverInterface;


class PickupDriver implements ShippingDriverInterface
{

    public function isEnabled()
    {
        $module = 'shop/shipping/gateways/pickup';
        $status = get_option('shipping_gw_' . $module, 'shipping') === 'y' ? true: false;

        return $status;
    }

    public function title()
    {
        return 'Pickup from address';
    }

    public function quickSetup()
    {
        return '';
    }

    public function instructions()
    {
        return get_option('shipping_pickup_instructions', 'shipping');
    }

    public function cost()
    {
        return 0;
    }

    public function validate($data = [])
    {
       return ['valid'=>true];
    }

    public function process()
    {
        return [];
    }


}

