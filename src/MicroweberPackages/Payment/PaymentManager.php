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
 * @mixin AbstractPaymentProvider
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
     * @param  string|null $driver
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
        if($modules and isset($modules[$driver])){
            return $modules[$driver];
        }
    }

    public function hasPaymentProvider($driver = 'default')
    {

         $module = $this->getPaymentProviderModule($driver);
         if($module){
             return true;
         }

         return false;
     }

    public function getPaymentModules($only_enabled = true)
    {


        $payment_gateways = app()->module_repository->getModulesByType('payment_gateway');
        if (!empty($payment_gateways)) {
            $gw = array();
            foreach ($payment_gateways as $item) {
                if (!isset($item['gw_file']) and isset($item['module'])) {
                    $item['gw_file'] = $item['module'];
                }
                if (!isset($item['module_base']) and isset($item['module'])) {
                    $item['module_base'] = $item['module'];
                }
                $gw[$item['gw_file']] = $item;

            }

            $this->paymentModules = array_merge($this->paymentModules, $gw);
        } else {
          //  $this->paymentModules = array_merge($this->paymentModules,$payment_gateways);
        }


        if ($only_enabled) {
            if (!empty($this->paymentModules)) {
                foreach ($this->paymentModules as $key => $item) {
                    if (isset($item['gw_file']) and isset($item['gw_file'])) {
                        $isEnabled = false;

                        try {
                            $isEnabled = $this->driver($item['gw_file'])->isEnabled();
                        } catch (\InvalidArgumentException $e) {
                            $isEnabled = false;
                        }

                        if (!$isEnabled) {
                            unset($this->paymentModules[$key]);
                        }

                    }
                }
            }
        }


        return $this->paymentModules;
    }

    public function setDefaultDriver($driver)
    {
        app()->user_manager->session_set('payment_provider', $driver);
        return true;
    }


}
