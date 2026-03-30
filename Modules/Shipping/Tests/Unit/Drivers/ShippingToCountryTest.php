<?php

namespace Modules\Shipping\Tests\Unit\Drivers;

use PHPUnit\Framework\Attributes\Test;
use Modules\Shipping\Drivers\ShippingToCountry;
use Tests\TestCase;

class ShippingToCountryTest extends TestCase
{
    #[Test]
    public function it_driverinitialization(): void {
        $driver = new ShippingToCountry();
        $this->assertEquals('Shipping to Country', $driver->title());
    }

    #[Test]
    public function it_defaultcountryrates(): void {
        $driver = new ShippingToCountry();
        $this->assertEmpty($driver->settings['country_rates'] ?? []);
    }

    #[Test]
    public function it_settingcountryrates(): void {
        $driver = new ShippingToCountry();
        $driver->settings = [
            'country_rates' => [
                'US' => 15,
                'CA' => 20
            ]
        ];

        $this->assertEquals(15, $driver->settings['country_rates']['US']);
        $this->assertEquals(20, $driver->settings['country_rates']['CA']);
    }

    #[Test]
    public function it_countryratesconfiguration(): void {
        $driver = new ShippingToCountry();
        $driver->settings = [
            'country_rates' => [
                'US' => 10,
                'UK' => 15
            ]
        ];

        $this->assertEquals(10, $driver->settings['country_rates']['US']);
        $this->assertEquals(15, $driver->settings['country_rates']['UK']);
    }
}
