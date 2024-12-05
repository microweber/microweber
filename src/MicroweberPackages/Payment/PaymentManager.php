<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Payment;


use Illuminate\Support\Manager;
use MicroweberPackages\Payment\Providers\AbstractPaymentProvider;
use MicroweberPackages\Payment\Providers\NoPaymentProvider;


/**
 * @deprecated  use PaymentMethodManager
 */
class PaymentManager extends Manager
{

    public $paymentModules = [];

    /**
     * Get default driver instance.
     *
     * @return AbstractPaymentProvider
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
        $selected = app()->user_manager->session_get('payment_provider');


        if (!$selected) {
            $mods = $this->getPaymentModules();
            if ($mods and isset($mods[0]) and isset($mods[0]['gw_file'])) {
                $selected = $mods[0]['gw_file'];
            }
        }


        if ($selected) {
            if (!isset($this->drivers[$selected])) {
                $this->drivers[$selected] = $this->createDriver($selected);
            }
            return $this->drivers[$selected];

        } else {
            return new NoPaymentProvider();
        }
    }

    public function createDefaultDriver()
    {
        return $this->getDefaultDriver();
    }


    /**
     * Get a driver instance.
     *
     * @param string|null $driver
     * @return AbstractPaymentProvider
     *
     * @throws \InvalidArgumentException
     */
    public function driver($driver = 'default')
    {
        return parent::driver($driver);
    }

    public function getPaymentProviderModule($driver = 'default')
    {
        $modules = $this->getPaymentModules();
        if ($modules and isset($modules[$driver])) {
            return $modules[$driver];
        }
    }

    public function hasPaymentProvider($driver = 'default')
    {

        $module = $this->getPaymentProviderModule($driver);
        if ($module) {
            return true;
        }

        return false;
    }

    /**
     * @deprecated
     */
    public function getPaymentModules($only_enabled = true)
    {
        return app()->payment_method_manager->getProviders();


    }
    /**
     * @deprecated
     */
    public function setDefaultDriver($driver)
    {
        app()->user_manager->session_set('payment_provider', $driver);
        return true;
    }


}
