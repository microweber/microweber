<?php

namespace Modules\Payment\Repositories;

use Illuminate\Support\Manager;
use Modules\Payment\Models\PaymentProvider;

class PaymentMethodManager extends Manager
{
    public function getDefaultDriver()
    {
        $selected = app()->user_manager->session_get('payment_provider');
        if ($selected) {
            return $selected;
        }
    }

    public function driverExists($driver)
    {
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
        $existingPaymentProvidersNames = [];
        $existingPaymentProviders = PaymentProvider::where('is_active', 1)->get();
        if ($existingPaymentProviders) {
            foreach ($existingPaymentProviders as $existingPaymentProvider) {
                $item = $existingPaymentProvider->toArray();

                if (!isset($item['provider'])) {
                    continue;
                }
                if (!$this->driverExists($item['provider'])) {
                    continue;
                }


                $item['gw_file'] = $item['provider'];
                $existingPaymentProvidersNames[] = $item;
            }
        }

        return $existingPaymentProvidersNames;

    }

    public function getProvider($provider): array
    {
        $existingPaymentProvider = PaymentProvider::where('provider', $provider)
            ->where('is_active', 1)->first();
        if ($existingPaymentProvider) {
            $item = $existingPaymentProvider->toArray();
            if (!$this->driverExists($item['provider'])) {
                return [];
            }
            $item['gw_file'] = $item['provider'];
            return $item;
        }
        return [];
    }

    public function render($provider)
    {
        if (!$provider) {
            return;
        }
        if (!$this->driverExists($provider)) {
            return;
        }
        $driver = $this->driver($provider);
        if ($driver) {
            return $driver->render();
        }
    }
}
