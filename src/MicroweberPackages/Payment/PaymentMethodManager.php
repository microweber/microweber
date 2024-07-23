<?php

namespace MicroweberPackages\Payment;

use Illuminate\Support\Manager;

class PaymentMethodManager extends Manager
{
    public function getDefaultDriver()
    {
        $selected = app()->user_manager->session_get('payment_provider');
        if ($selected) {
            return $selected;
        }
    }

    public function getDrivers()
    {
        return array_merge($this->customCreators, $this->drivers);
    }

}
