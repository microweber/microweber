<?php

namespace Modules\Shop\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Shipping\Models\ShippingProvider;

class ShopManagerTest extends TestCase
{

    public function testGetShippingModules()
    {
        $check_module = 'flat_rate';
        if (is_module('shipping')) {


            $check = ShippingProvider::where('provider', $check_module)->first();
            if (!$check) {
                $shippingProvider = new ShippingProvider();
                $shippingProvider->name = 'Flat Rate';
                $shippingProvider->is_active = 1;
                $shippingProvider->provider = $check_module;
                $shippingProvider->save();
            }


            $shippingDrivers = app()->shipping_method_manager->getProviders();
            $this->assertNotEmpty($shippingDrivers);

            //flat_rate
            $flatRate = app()->shipping_method_manager->driver('flat_rate');
            $this->assertNotEmpty($flatRate);
            $this->assertNotEmpty($flatRate->title());

            $shipping_options = app()->shipping_method_manager->getDrivers();
            $this->assertNotEmpty($shipping_options);
            $found = false;
            foreach ($shipping_options as $shipping_option) {
                if ($shipping_option == $check_module) {
                    $found = true;
                }
            }
            $this->assertTrue($found);
        }
    }

}
