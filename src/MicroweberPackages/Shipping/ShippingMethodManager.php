<?php

namespace MicroweberPackages\Shipping;

use Illuminate\Support\Manager;

class ShippingMethodManager extends Manager
{
    public function getDefaultDriver()
    {
        $selected = app()->user_manager->session_get('shipping_provider');
        if ($selected) {
            return $selected;
        }
    }

    public function getProviders()
    {
        return array_keys($this->customCreators);
    }

}
