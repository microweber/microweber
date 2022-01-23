<?php

namespace MicroweberPackages\Shop\Shipping\Gateways\Pickup;


use MicroweberPackages\Shipping\Providers\ShippingDriverInterface;


class PickupDriver implements ShippingDriverInterface
{
    public $module = 'shop/shipping/gateways/pickup';


    public function enable()
    {
        $saveOption = [];
        $saveOption['option_key'] = 'shipping_gw_' . $this->module;
        $saveOption['option_value'] = 'y';
        $saveOption['option_group'] = 'shipping';

        return save_option($saveOption);
    }

    public function disable()
    {
        $saveOption = [];
        $saveOption['option_key'] = 'shipping_gw_' . $this->module;
        $saveOption['option_value'] = 'y';
        $saveOption['option_group'] = 'shipping';

        return save_option($saveOption);
    }


    public function isEnabled()
    {
        $module = 'shop/shipping/gateways/pickup';
        $status = get_option('shipping_gw_' . $this->module, 'shipping') === 'y' ? true : false;

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
        return ['valid' => true];
    }

    public function process()
    {
        return [];
    }


}

