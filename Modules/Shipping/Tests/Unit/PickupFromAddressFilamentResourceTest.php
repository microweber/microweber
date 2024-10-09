<?php

namespace Modules\Shipping\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\CreateShippingProvider;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\EditShippingProvider;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders;
use Modules\Shipping\Models\ShippingProvider;
use Tests\TestCase;

class PickupFromAddressFilamentResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testPickupFromAddressShippingProviderLifecycle()
    {
        // Create Pickup From Address Shipping Provider
        Livewire::test(CreateShippingProvider::class)
            ->fillForm([
                'name' => 'Pickup From Address Provider',
                'provider' => 'pickup_from_address',
                'is_active' => true,
                'settings' => [
                    'shipping_instructions' => 'Pickup at the front desk',
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('shipping_providers', [
            'name' => 'Pickup From Address Provider',
            'provider' => 'pickup_from_address',
            'is_active' => true,
        ]);

        // Edit Pickup From Address Shipping Provider
        $provider = ShippingProvider::where('name', 'Pickup From Address Provider')->first();
        Livewire::test(EditShippingProvider::class, ['record' => $provider->id])
            ->fillForm([
                'name' => 'Updated Pickup From Address Provider',
                'provider' => 'pickup_from_address',
                'is_active' => false,
                'settings' => [
                    'shipping_instructions' => 'Pickup at the back door',
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('shipping_providers', [
            'name' => 'Updated Pickup From Address Provider',
            'provider' => 'pickup_from_address',
            'is_active' => false,
        ]);

        // List Pickup From Address Shipping Providers
        Livewire::test(ListShippingProviders::class)
            ->assertSee('Updated Pickup From Address Provider')
            ->assertSee('pickup_from_address')
            ->assertSee('false');

        // Delete Pickup From Address Shipping Provider
        Livewire::test(ListShippingProviders::class)
            ->callTableAction('delete', $provider)
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseMissing('shipping_providers', [
            'name' => 'Updated Pickup From Address Provider',
            'provider' => 'pickup_from_address',
            'is_active' => false,
        ]);
    }
}
