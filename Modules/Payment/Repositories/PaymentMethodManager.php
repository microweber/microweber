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
            $item['gw_file'] = $item['provider'];
            return $item;
        }
        return [];
    }

}
