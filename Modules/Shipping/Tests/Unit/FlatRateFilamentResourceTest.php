<?php

namespace Modules\Shipping\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\CreateShippingProvider;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\EditShippingProvider;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders;
use Modules\Shipping\Models\ShippingProvider;
use Tests\TestCase;

class FlatRateFilamentResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFlatRateShippingProvider()
    {
        Livewire::test(CreateShippingProvider::class)
            ->fillForm([
                'name' => 'Flat Rate Provider',
                'provider' => 'flat_rate',
                'is_active' => true,
                'settings' => [
                    'shipping_cost' => 10,
                    'shipping_instructions' => 'Handle with care',
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('shipping_providers', [
            'name' => 'Flat Rate Provider',
            'provider' => 'flat_rate',
            'is_active' => true,
        ]);

        //delete the record
        $provider = ShippingProvider::where('name', 'Flat Rate Provider')->first();
        $provider->delete();
        //check if the record is deleted
        $this->assertDatabaseMissing('shipping_providers', [
            'name' => 'Flat Rate Provider',
            'provider' => 'flat_rate',
            'is_active' => true,
        ]);
    }

    public function testEditFlatRateShippingProvider()
    {
        $provider = ShippingProvider::create([
            'name' => 'Flat Rate Provider',
            'provider' => 'flat_rate',
            'is_active' => true,
            'settings' => [
                'shipping_cost' => 10,
                'shipping_instructions' => 'Handle with care',
            ],
        ]);
        $this->assertTrue(ShippingProvider::where('name', 'Flat Rate Provider')->exists());


        Livewire::test(EditShippingProvider::class, ['record' => $provider->id])
            ->fillForm([
                'name' => 'Updated Flat Rate Provider',

            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('shipping_providers', [
            'name' => 'Updated Flat Rate Provider',
            'provider' => 'flat_rate',
            'is_active' => false,
        ]);
    }

    public function testListFlatRateShippingProviders()
    {
        ShippingProvider::create([
            'name' => 'Flat Rate Provider',
            'provider' => 'flat_rate',
            'is_active' => true,
            'settings' => [
                'shipping_cost' => 10,
                'shipping_instructions' => 'Handle with care',
            ],
        ]);

        Livewire::test(ListShippingProviders::class)
            ->assertSee('Flat Rate Provider')
            ->assertSee('flat_rate')
            ->assertSee('true');
    }
}
