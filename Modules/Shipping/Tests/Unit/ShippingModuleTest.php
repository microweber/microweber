<?php

namespace Modules\Shipping\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Modules\Shipping\Models\ShippingProvider;
use Modules\Shipping\Services\ShippingMethodManager;
use Modules\Shipping\Drivers\FlatRate;
use Modules\Shipping\Drivers\PickupFromAddress;
use Tests\TestCase;

class ShippingModuleTest extends TestCase
{
    protected $manager;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->manager = app(ShippingMethodManager::class);
        $this->manager->extend('flat_rate', function() {
            return new FlatRate();
        });
        $this->manager->extend('pickup_from_address', function() {
            return new PickupFromAddress();
        });
    }

    #[Test]
    public function testShippingProviderCreation()
    {
        $provider = ShippingProvider::create([
            'name' => 'Test Provider',
            'provider' => 'flat_rate',
            'is_active' => true,
            'settings' => ['cost' => 10]
        ]);

        $this->assertDatabaseHas('shipping_providers', [
            'name' => 'Test Provider',
            'provider' => 'flat_rate'
        ]);
    }

    #[Test]
    public function testShippingManagerRegistration()
    {
        $this->assertInstanceOf(ShippingMethodManager::class, $this->manager);
    }

    #[Test]
    public function testFlatRateDriver()
    {
        $driver = $this->manager->driver('flat_rate');
        $this->assertInstanceOf(FlatRate::class, $driver);
        $this->assertEquals('Flat Rate', $driver->title());
    }

    #[Test]
    public function testPickupFromAddressDriver()
    {
        $driver = $this->manager->driver('pickup_from_address');
        $this->assertInstanceOf(PickupFromAddress::class, $driver);
        $this->assertEquals('Pickup From Address', $driver->title());
    }
}