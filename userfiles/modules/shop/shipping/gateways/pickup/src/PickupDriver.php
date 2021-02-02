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

