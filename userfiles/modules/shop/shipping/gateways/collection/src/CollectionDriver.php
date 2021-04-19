<?php

namespace MicroweberPackages\Shop\Shipping\Gateways\Collection;


use MicroweberPackages\Shipping\Providers\ShippingDriverInterface;


class CollectionDriver implements ShippingDriverInterface
{

    public function isEnabled()
    {
        $module = 'shop/shipping/gateways/collection';
        $status = get_option('shipping_gw_' . $module, 'shipping') === 'y' ? true: false;

        return $status;
    }

    public function title()
    {
        return 'Collection from address';
    }

    public function quickSetup()
    {
        return '';
    }

    public function instructions()
    {
        return get_option('shipping_collection_instructions', 'shipping');
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

