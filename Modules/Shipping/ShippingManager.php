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

namespace Modules\Shipping;


use Illuminate\Support\Manager;
use Modules\Shipping\Providers\AbstractShippingDriver;
use Modules\Shipping\Providers\NoShippingDriver;
use Modules\Shipping\Models\ShippingProvider;


/**
 * @deprecated This class is deprecated.
 */
class ShippingManager extends Manager
{

    public $shippingModules = [];

    /**
     * Get default driver instance.
     * @deprecated
     * @return AbstractShippingDriver
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
        $selected = app()->user_manager->session_get('shipping_provider_id');

        if ($selected) {
            $provider = ShippingProvider::find($selected);
            if ($provider && $provider->provider) {
                if (!isset($this->drivers[$provider->provider])) {
                    $this->drivers[$provider->provider] = $this->createDriver($provider->provider);
                }
                return $this->drivers[$provider->provider];
            }
        }

        if (!$selected) {
            $mods = $this->getShippingModules();
            if ($mods and isset($mods[0]) and isset($mods[0]['gw_file'])) {
                $provider = ShippingProvider::where('provider', $mods[0]['gw_file'])->first();
                if ($provider) {
                    $selected = $provider->id;
                    app()->user_manager->session_set('shipping_provider_id', $selected);
                }
            }
        }

        return new NoShippingDriver();
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

    /**
     * @deprecated
     */
    public function getShippingModules($only_enabled = true)
    {
        $shipping_gateways = app()->module_repository->getModulesByType('shipping_gateway');

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
                    if (isset($item['gw_file'])) {
                        $isEnabled = false;
                        try {
                            $provider = ShippingProvider::where('provider', $item['gw_file'])->where('is_active', 1)->first();
                            $isEnabled = $provider ? true : false;
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
        $provider = ShippingProvider::where('provider', $driver)->first();
        if ($provider) {
            app()->user_manager->session_set('shipping_provider_id', $provider->id);
            return true;
        }
        return false;
    }
}
