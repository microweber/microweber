<?php

namespace MicroweberPackages\Shipping\Http\Controllers;


use Illuminate\Http\Request;


class Api
{
    public function setShippingProviderToSession(Request $request)
    {
        $provider = $request->input('provider');
        if ($provider) {
            try {
                $has = app()->shipping_manager->driver($provider)->title();

            } catch (\InvalidArgumentException $e) {
                $provider = null;

            }
        }
        if ($provider) {
            app()->user_manager->session_set('shipping_provider', $provider);
        } else {
            //we didnt find the provider so we set NoShipping
            app()->user_manager->session_set('shipping_provider', false);
        }

    }

}