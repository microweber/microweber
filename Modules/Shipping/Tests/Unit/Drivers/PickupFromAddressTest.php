<?php

namespace Modules\Shipping\Tests\Unit\Drivers;

use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use Modules\Shipping\Drivers\PickupFromAddress;
use Tests\TestCase;

class PickupFromAddressTest extends TestCase
{
    #[Test]

    public function testPickupInitialization()
    {
        $pickup = new PickupFromAddress();
        $this->assertEquals('Pickup From Address', $pickup->title());
    }

    #[Test]
    public function testDefaultAddress()
    {
        $pickup = new PickupFromAddress();
        $this->assertEmpty($pickup->settings['address'] ?? '');
    }

    #[Test]
    public function testCustomAddress()
    {
        $pickup = new PickupFromAddress();
        $pickup->settings = ['address' => '123 Main St, City'];
        $this->assertEquals('123 Main St, City', $pickup->settings['address']);
    }

    #[Test]
    public function testSettingsHandling()
    {
        $pickup = new PickupFromAddress();
        $pickup->settings = [
            'address' => '456 Commerce Ave',
            'pickup_hours' => '9am-5pm'
        ];

        $this->assertEquals('456 Commerce Ave', $pickup->settings['address']);
    }

    #[Test]
    public function testCalculateCostIsAlwaysZero()
    {
        $pickup = new PickupFromAddress();
        $this->assertEquals(0, $pickup->getShippingCost());

        $pickup->settings = ['address' => '123 Main St'];
        $this->assertEquals(0, $pickup->getShippingCost());
    }
}
