<?php

namespace Modules\Shipping\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\CreateShippingProvider;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\EditShippingProvider;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders;
use Modules\Shipping\Models\ShippingProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FlatRateFilamentResourceTest extends TestCase
{

    #[Test]

    public function testFlatRateShippingProviderLifecycle()
    {
        ShippingProvider::truncate();
        // Create Flat Rate Shipping Provider
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

        // Edit Flat Rate Shipping Provider
        $provider = ShippingProvider::where('name', 'Flat Rate Provider')->first();
        Livewire::test(EditShippingProvider::class, ['record' => $provider->id])
            ->fillForm([
                'name' => 'Updated Flat Rate Provider',
                'provider' => 'flat_rate',
                'is_active' => false,
                'settings' => [
                    'shipping_cost' => 20,
                    'shipping_instructions' => 'Handle with extra care',
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('shipping_providers', [
            'name' => 'Updated Flat Rate Provider',
            'provider' => 'flat_rate',
            'is_active' => false,
        ]);

        // List Flat Rate Shipping Providers
        Livewire::test(ListShippingProviders::class)
            ->assertSee('Updated Flat Rate Provider')
            ->assertSee('flat_rate')
            ->assertSee('false');

        // Delete Flat Rate Shipping Provider
        Livewire::test(ListShippingProviders::class)
            ->callTableAction('delete', $provider)
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseMissing('shipping_providers', [
            'name' => 'Updated Flat Rate Provider',
            'provider' => 'flat_rate',
            'is_active' => false,
        ]);
    }
}
