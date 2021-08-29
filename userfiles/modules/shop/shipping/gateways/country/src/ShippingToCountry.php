<?php


namespace MicroweberPackages\Shop\Shipping\Gateways\Country;



use MicroweberPackages\Shipping\Providers\ShippingDriverInterface;
use shop\shipping\gateways\country\shipping_to_country;

class ShippingToCountry implements ShippingDriverInterface
{

    public $module = 'shop/shipping/gateways/country';


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
        $module = 'shop/shipping/gateways/country';
        $status = get_option('shipping_gw_' . $module, 'shipping') === 'y' ? true: false;
        return $status;
    }


    public function title()
    {
        return 'Shipping to address';
    }

    public function instructions()
    {
        return _e('Your package will be delivered to address.', true);
    }

    public function cost()
    {
        return (new shipping_to_country())->get_cost();
    }

    public function quickSetup()
    {
        return '<module type="shop/shipping/gateways/country" class="no-settings" template="select_bootstrap4" />';
    }

    public function getCountries()
    {
        return (new shipping_to_country())->get_available_countries();
    }

//    public function cost($params=[])
//    {
//        $rates = [];
//        $rates[] = ['name' => 'Delivery to address', 'cost' => 0];
//        $rates[] = ['name' => 'Delivery to address 123', 'cost' => 100];
//        $rates[] = ['name' => 'Delivery to address 1234', 'cost' => 200];
//        return $rates;
//    }
    public function getData()
    {
        return [];
    }

    public function validate($data = [])
    {
        $rules['state'] = 'required';
        $rules['country'] = 'required';
        $rules['address'] = 'required';
        $rules['city'] = 'required';
        $rules['zip'] = 'required';
        $rules['state'] = 'required';

        if (get_option('require_state', 'shipping') != 1) {
            unset($rules['state']);
        }

        if (get_option('require_country', 'shipping') != 1) {
            unset($rules['country']);
        }

        if (get_option('require_address', 'shipping') != 1) {
            unset($rules['address']);
        }

        if (get_option('require_city', 'shipping') != 1) {
            unset($rules['city']);
        }

        if (get_option('require_zip', 'shipping') != 1) {
            unset($rules['zip']);
        }

        if (get_option('require_state', 'shipping') != 1) {
            unset($rules['state']);
        }

        if (empty($rules)) {
            return ['valid'=>true];
        }

        $validator = \Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            return ['valid'=>false,'errors'=>$errors];
        }

        return ['valid'=>true];
    }

    public function process()
    {
        return [];
    }

}
