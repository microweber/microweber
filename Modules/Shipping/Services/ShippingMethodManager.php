<?php

namespace Modules\Shipping\Services;

use Illuminate\Support\Manager;
use Modules\Shipping\Drivers\AbstractShippingMethod;
use Modules\Shipping\Models\ShippingProvider;

class ShippingMethodManager extends Manager
{
    public function getDefaultDriver()
    {
        $selected = app()->user_manager->session_get('shipping_provider_id');
        if ($selected) {
            $provider = $this->getProviderById($selected);
            if ($provider) {
                return $provider['provider'];
            }
        }
    }

    public function driverExists($driver)
    {
        if (!$driver) {
            return false;
        }
        if ($driver and isset($this->customCreators[$driver])) {
            return true;
        }
        return false;
    }

    public function getDrivers()
    {
        return array_keys($this->customCreators);
    }

    public function getProviders(): array
    {
        $existingShippingProvidersNames = [];
        $existingShippingProviders = ShippingProvider::where('is_active', 1)
            ->orderBy('position', 'asc')
            ->get();
        if ($existingShippingProviders) {
            foreach ($existingShippingProviders as $existingShippingProvider) {
                $item = $existingShippingProvider->toArray();

                if (!isset($item['provider'])) {
                    continue;
                }
                if (!$this->driverExists($item['provider'])) {
                    continue;
                }
                $existingShippingProvidersNames[] = $item;
            }
        }

        return $existingShippingProvidersNames;
    }

    public function getProviderById($providerId): ShippingProvider|null
    {
        $existingShippingProvider = ShippingProvider::where('id', $providerId)
            ->where('is_active', 1)->first();
        if ($existingShippingProvider) {
            $item = $existingShippingProvider->toArray();
            if (!$this->driverExists($item['provider'])) {
                return null;
            }
            return $existingShippingProvider;
        }
        return null;
    }

    public function hasProviders(): bool
    {
        $existingShippingProviders = ShippingProvider::where('is_active', 1)->count();
        if ($existingShippingProviders) {
            return true;
        }
        return false;
    }

    public function getForm($providerId): array|null
    {
        $providerModel = $this->getProviderById($providerId);

        if (!$providerModel) {
            return null;
        }
        $providerName = $providerModel['provider'] ?? null;
        if (!$this->driverExists($providerName)) {
            return null;
        }

        $driver = $this->driver($providerName);
        $driver->setModel($providerModel);

        return $driver->getForm();
    }

    public function getShippingCost($providerId, $data): array|null
    {
        $providerModel = $this->getProviderById($providerId);

        if (!$providerModel) {
            return null;
        }
        $providerName = $providerModel['provider'] ?? null;
        if (!$this->driverExists($providerName)) {
            return null;
        }
        /* @var AbstractShippingMethod $driver */
        $driver = $this->driver($providerName);
        $driver->setModel($providerModel);
        if ($driver) {
            return $driver->getShippingCost($data);
        }
    }
}
