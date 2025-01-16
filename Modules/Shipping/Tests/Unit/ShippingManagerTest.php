<?php
namespace Modules\Shipping\Tests\Unit;

use Livewire\Livewire;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\CreateShippingProvider;
use Modules\Shipping\Models\ShippingProvider;
use Tests\TestCase;
class ShippingManagerTest extends TestCase
{
    public function testGetShippingModules()
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

        $shippingDrivers = app()->shipping_method_manager->getProviders();
        $this->assertNotEmpty($shippingDrivers);

        //flat_rate
        $flatRate = app()->shipping_method_manager->driver('flat_rate');
        $this->assertNotEmpty($flatRate);
        $this->assertNotEmpty($flatRate->title());


//        $shippingModules = app()->shipping_manager->getShippingModules(false);
//        $this->assertNotEmpty($shippingModules);
//
//        foreach ($shippingModules as $shippingModule) {
//            $this->assertArrayHasKey('id', $shippingModule);
//            $this->assertArrayHasKey('name', $shippingModule);
//            $this->assertArrayHasKey('description', $shippingModule);
//            $this->assertArrayHasKey('icon', $shippingModule);
//            $this->assertArrayHasKey('module', $shippingModule);
//            $this->assertArrayHasKey('type', $shippingModule);
//            $this->assertArrayHasKey('gw_file', $shippingModule);
//        }

    }
}
