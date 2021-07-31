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

namespace MicroweberPackages\Shipping;


use Illuminate\Support\Manager;
use MicroweberPackages\Shipping\Providers\AbstractShippingDriver;
use MicroweberPackages\Shipping\Providers\NoShippingDriver;


/**
 * ShippingManager module api.
 */
// ------------------------------------------------------------------------


/**
 * @mixin AbstractShippingDriver
 */
class ShippingManager extends Manager
{

    public $shippingModules = [];
    // public $defaultDriver = 'NoShippingDriver';

    /**
     * Get default driver instance.
     *
     * @return AbstractShippingDriver
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
        $selected = app()->user_manager->session_get('shipping_provider');


        if (!$selected) {
            $mods = $this->getShippingModules();
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
            return new NoShippingDriver();
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
     * @return AbstractShippingDriver
     *
     * @throws \InvalidArgumentException
     */
    public function driver($driver = 'default')
    {
        return parent::driver($driver);
    }


    public function getShippingModules($only_enabled = true)
    {

        $shipping_modules_path = modules_path() . 'shop/shipping/gateways';


     //   $shipping_gateways = get_modules('is_installed=1&type=shipping_gateway');
      //  $shipping_gateways = get_modules('is_installed=1&type=shipping_gateway');
        $shipping_gateways = app()->module_repository->getModulesByType('shipping_gateway');

        if ($shipping_gateways == false) {
          //  $shipping_gateways = scan_for_modules("cache_group=modules/global&dir_name={$shipping_modules_path}");

        }

        if (!empty($shipping_gateways)) {
            $gw = array();
            foreach ($shipping_gateways as $item) {
                if (!isset($item['gw_file']) and isset($item['module'])) {
                    $item['gw_file'] = $item['module'];
                }
                if (!isset($item['module_base']) and isset($item['module'])) {
                    $item['module_base'] = $item['module'];
                }
                $gw[] = $item;

            }

            $this->shippingModules = $gw;
        } else {
            $this->shippingModules = $shipping_gateways;
        }


        if ($only_enabled) {
            if (!empty($this->shippingModules)) {
                foreach ($this->shippingModules as $key => $item) {
                    if (isset($item['gw_file']) and isset($item['gw_file'])) {
                        $isEnabled = false;
                        try {
                            $isEnabled = $this->driver($item['gw_file'])->isEnabled();
                        } catch (\InvalidArgumentException $e) {
                            $isEnabled = false;
                        }

                        if (!$isEnabled) {
                            unset($this->shippingModules[$key]);
                        }

                    }


                }
            }
        }


        return $this->shippingModules;
    }

    public function setDefaultDriver($driver)
    {
        app()->user_manager->session_set('shipping_provider', $driver);
        return true;
    }

//    public function with($driver = null)
//    {
//        return $this->driver($driver);
//    }

}
