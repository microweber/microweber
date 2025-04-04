<?php

namespace Modules\Shipping\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Modules\Shipping\Models\ShippingProvider;
use Tests\TestCase;

class ShippingProviderTest extends TestCase
{
    #[Test]
    public function testCanCreateShippingProvider()
    {
        $provider = ShippingProvider::create([
            'name' => 'Test Provider',
            'provider' => 'flat_rate',
            'is_active' => true,
            'settings' => ['cost' => 15]
        ]);

        $this->assertEquals('Test Provider', $provider->name);
        $this->assertEquals('flat_rate', $provider->provider);
        $this->assertTrue($provider->is_active);
        $this->assertEquals(['cost' => 15], $provider->settings);
    }

    #[Test]
    public function testProviderSettingsAreCastToArray()
    {
        $provider = ShippingProvider::create([
            'name' => 'Test Cast',
            'provider' => 'pickup_from_address',
            'settings' => ['address' => '123 Main St']
        ]);

        $this->assertIsArray($provider->settings);
        $this->assertEquals('123 Main St', $provider->settings['address']);
    }

    #[Test]
    public function testActiveProviders()
    {
        // Clear existing providers
        ShippingProvider::truncate();

        ShippingProvider::create([
            'name' => 'Active Provider',
            'provider' => 'flat_rate',
            'is_active' => true
        ]);

        ShippingProvider::create([
            'name' => 'Inactive Provider',
            'provider' => 'pickup_from_address',
            'is_active' => false
        ]);

        $activeProviders = ShippingProvider::where('is_active', true)->get();
        $this->assertCount(1, $activeProviders);
        $this->assertEquals('Active Provider', $activeProviders->first()->name);
    }
}