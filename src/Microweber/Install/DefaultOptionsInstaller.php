<?php

namespace Microweber\Install;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


use \Option;
use \Shipping;

class DefaultOptionsInstaller
{

    public function run()
    {

        $this->setDefault();
        $this->setCommentsEnabled();
        $this->setShippingEnabled();
        $this->setPaymentsEnabled();
        return true;
    }

    public function setDefault()
    {

        $existing = DB::table('options')->where('option_key', 'website_title')
            ->where('option_group', 'website')->first();
        if ($existing != false) {
            $option = Option::find($existing->id);
        } else {
            $option = new Option;
        }
        $option->option_key = 'website_title';
        $option->option_group = 'website';
        $option->option_value = 'Microweber';
        $option->is_system = 1;
        $option->save();


    }

    public function setCommentsEnabled()
    {
        $existing = DB::table('options')->where('option_key', 'enable_comments')
            ->where('option_group', 'comments')->first();
        if ($existing != false) {
            $option = Option::find($existing->id);
        } else {
            $option = new Option;
        }
        $option->option_key = 'enable_comments';
        $option->option_group = 'comments';
        $option->option_value = 'y';
        $option->save();
    }

    public function setShippingEnabled()
    {
        $existing = DB::table('options')->where('option_key', 'shipping_gw_shop/shipping/gateways/country')
            ->where('option_group', 'shipping')->first();
        if ($existing != false) {
            $option = Option::find($existing->id);
        } else {
            $option = new Option;
        }
        $option->option_key = 'shipping_gw_shop/shipping/gateways/country';
        $option->option_group = 'shipping';
        $option->option_value = 'y';
        $option->save();


        $existing = DB::table('cart_shipping')->where('shipping_country', 'Worldwide')->first();
        if ($existing != false) {
            $shipping = Shipping::find($existing->id);
        } else {
            $shipping = new Shipping;
        }
        $shipping->shipping_country = 'Worldwide';
        $shipping->shipping_type = 'fixed';
        $shipping->shipping_cost = 0;
        $shipping->is_active = 1;
        $shipping->save();

    }

    public function setPaymentsEnabled()
    {
        $existing = DB::table('options')->where('option_key', 'payment_gw_shop/payments/gateways/paypal')
            ->where('option_group', 'payments')->first();
        if ($existing != false) {
            $option = Option::find($existing->id);
        } else {
            $option = new Option;
        }
        $option->option_key = 'payment_gw_shop/payments/gateways/paypal';
        $option->option_group = 'payments';
        $option->option_value = 1;
        $option->save();


        $existing = DB::table('options')->where('option_key', 'currency')
            ->where('option_group', 'payments')->first();
        if ($existing != false) {
            $option = Option::find($existing->id);
        } else {
            $option = new Option;
        }
        $option->option_key = 'currency';
        $option->option_group = 'payments';
        $option->option_value = "USD";
        $option->save();


    }


}