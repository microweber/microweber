<?php

namespace Modules\Shipping\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Modules\Shipping\Services\ShippingMethodManager;
use Modules\Shipping\Drivers\FlatRate;
use Modules\Shipping\Drivers\PickupFromAddress;
use Modules\Shipping\Drivers\ShippingToCountry;
use Modules\Shipping\Models\ShippingProvider;
use Tests\TestCase;

class ShippingMethodManagerTest extends TestCase
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
        $this->manager->extend('shipping_to_country', function() {
            return new ShippingToCountry();
        });
    }

    #[Test]
    public function testManagerInitialization()
    {
        $this->assertInstanceOf(ShippingMethodManager::class, $this->manager);
    }

    #[Test]
    public function testDriverRegistration()
    {
        // Test registration of each driver type
        $this->assertTrue($this->manager->driverExists('flat_rate'));
        $this->assertTrue($this->manager->driverExists('pickup_from_address'));
        $this->assertTrue($this->manager->driverExists('shipping_to_country'));
    }

    #[Test]
    public function testCalculateShippingCostWithProvider()
    {
        $provider = ShippingProvider::create([
            'name' => 'Test Provider',
            'provider' => 'flat_rate', 
            'is_active' => true,
            'settings' => ['shipping_cost' => 15]
        ]);
        
        $cost = $this->manager->getShippingCost($provider->id, []);
        $this->assertEquals(15, $cost);
    }
}