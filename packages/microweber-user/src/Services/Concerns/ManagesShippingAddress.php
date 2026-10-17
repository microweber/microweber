<?php

namespace MicroweberPackages\User\Services\Concerns;

use Illuminate\Support\Facades\Auth;
use Modules\Customer\Models\Customer;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ManagesShippingAddress
{
    public function get_shipping_address()
    {
        $shipping_address_from_profile = [];
        if ($this->is_logged()) {
            $findCustomer = \Modules\Customer\Models\Customer::where('user_id', Auth::id())->first();
            if ($findCustomer) {
                $findAddressShipping = \Modules\Address\Models\Address::where('type', 'shipping')

                    ->where('rel_id', $findCustomer->id)
                    ->where('rel_type', Customer::class)

                    ->first();
                if ($findAddressShipping) {
                    $country_from_shipping_addr = $findAddressShipping->country()->first();
                    foreach ($findAddressShipping->toArray() as $addressKey => $addressValue) {
                        $shipping_address_from_profile[$addressKey] = $addressValue;
                    }
                    if ($country_from_shipping_addr and isset($country_from_shipping_addr->name)) {
                        $shipping_address_from_profile['country'] = $country_from_shipping_addr->name;
                    }

                    if ($findAddressShipping and isset($findAddressShipping->address_street_1)) {
                        $shipping_address_from_profile['address'] = $findAddressShipping->address_street_1;
                    }

                    if (!isset($shipping_address_from_profile['address']) and $findAddressShipping and isset($findAddressShipping->address_street_2)) {
                        $shipping_address_from_profile['address'] = $findAddressShipping->address_street_2;
                    }

            return $shipping_address_from_profile;
        }
    }

}
}

    public function save_shipping_address(array $data): bool
    {
        if (!$this->is_logged()) {
            return false;
        }

        $userId = $this->id();
        if (!$userId) {
            return false;
        }

        // Find or create customer record
        $customer = \Modules\Customer\Models\Customer::firstOrCreate(
            ['user_id' => $userId],
            [
                'first_name' => $data['first_name'] ?? get_user()['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? get_user()['last_name'] ?? '',
                'email' => $data['email'] ?? get_user()['email'] ?? '',
            ]
        );

        // Find or create shipping address
        $addressData = [
            'rel_type' => \Modules\Customer\Models\Customer::class,
            'rel_id' => $customer->id,
            'type' => 'shipping',
        ];

        $shippingAddress = \Modules\Address\Models\Address::updateOrCreate(
            $addressData,
            [
                'address_street_1' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'zip' => $data['zip'] ?? $data['postal_code'] ?? null,
                'country' => $data['country'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]
        );

        return (bool) $shippingAddress;
    }
}
