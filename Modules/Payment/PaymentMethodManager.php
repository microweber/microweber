<?php

namespace Modules\Payment;

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

    public function getProviders()
    {
        return array_keys($this->customCreators);
    }

}
