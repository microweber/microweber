<?php

namespace Modules\Profile\Filament\Pages\Concerns;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Modules\Address\Models\Address;
use Modules\Country\Models\Country;
use Modules\Customer\Models\Customer;

/**
 * Shared address form + current-customer resolution for the Create/Edit address
 * pages (extracted so the two pages stay in sync and the form is defined once).
 * Uses Country options rather than ->relationship() so it works on a plain
 * Filament Page (no backing record).
 */
trait HasAddressForm
{
    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    protected function addressFormComponents(): array
    {
        return [
            TextInput::make('name')
                ->label(__('Address Label (e.g., Home, Office)'))
                ->required()
                ->maxLength(255)
                ->placeholder(__('e.g., Home, Office, Parents House')),

            Select::make('type')
                ->label(__('Address Type'))
                ->required()
                ->native(false)
                ->options([
                    Address::SHIPPING_TYPE => __('Shipping'),
                    Address::BILLING_TYPE => __('Billing'),
                    Address::OTHER_TYPE => __('Other'),
                ])
                ->default(Address::SHIPPING_TYPE),

            TextInput::make('address_street_1')
                ->label(__('Street Address'))
                ->required()
                ->maxLength(255)
                ->placeholder(__('123 Main Street')),

            TextInput::make('address_street_2')
                ->label(__('Apartment, Suite, etc. (Optional)'))
                ->maxLength(255)
                ->placeholder(__('Apt 4B, Suite 100')),

            TextInput::make('city')
                ->label(__('City'))
                ->required()
                ->maxLength(255),

            TextInput::make('state')
                ->label(__('State / Province'))
                ->maxLength(255),

            TextInput::make('zip')
                ->label(__('Postal Code / ZIP'))
                ->required()
                ->maxLength(20),

            Select::make('country_id')
                ->label(__('Country'))
                ->required()
                ->searchable()
                ->preload()
                ->native(false)
                ->options(fn (): array => Country::query()->orderBy('name')->pluck('name', 'id')->all())
                ->default(fn () => Country::query()->where('name', 'United States')->value('id')),

            TextInput::make('phone')
                ->label(__('Phone Number (Optional)'))
                ->tel()
                ->maxLength(50),
        ];
    }

    /**
     * The Customer record for the signed-in user (by user_id, then email).
     */
    protected function resolveCurrentCustomer(): ?Customer
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        return Customer::where('user_id', $user->id)->first()
            ?? Customer::where('email', $user->email)->first();
    }

    /**
     * Load an address only if it belongs to the signed-in user's customer
     * record — prevents editing someone else's address by guessing the id.
     */
    protected function resolveOwnedAddress(?int $id): ?Address
    {
        $customer = $this->resolveCurrentCustomer();

        if (! $customer || ! $id) {
            return null;
        }

        return Address::query()
            ->where('id', $id)
            ->where('rel_type', 'customer')
            ->where('rel_id', $customer->id)
            ->first();
    }
}
