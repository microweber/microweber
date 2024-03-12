<?php
namespace MicroweberPackages\Shipping\tests;

use MicroweberPackages\Core\tests\TestCase;

class ShippingManagerTest extends TestCase
{
    public function testGetShippingModules()
    {

        $shippingModules = app()->shipping_manager->getShippingModules(false);
        $this->assertNotEmpty($shippingModules);

        foreach ($shippingModules as $shippingModule) {
            $this->assertArrayHasKey('id', $shippingModule);
            $this->assertArrayHasKey('name', $shippingModule);
            $this->assertArrayHasKey('description', $shippingModule);
            $this->assertArrayHasKey('icon', $shippingModule);
            $this->assertArrayHasKey('module', $shippingModule);
            $this->assertArrayHasKey('type', $shippingModule);
            $this->assertArrayHasKey('gw_file', $shippingModule);
        }

    }
}
