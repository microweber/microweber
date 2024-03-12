<?php

namespace MicroweberPackages\Shop\tests;

use MicroweberPackages\Core\tests\TestCase;

class ShopManagerTest extends TestCase
{

    public function testGetShippingModules()
    {
        $check_module = 'shop/shipping/gateways/country';
        if (is_module($check_module)) {

            app()->shipping_manager->driver('shop/shipping/gateways/country')->enable();


            $shipping_options = app()->shipping_manager->getShippingModules();
            $this->assertNotEmpty($shipping_options);
            $found = false;
            foreach ($shipping_options as $shipping_option) {
                $this->assertArrayHasKey('module_base', $shipping_option);
                $this->assertArrayHasKey('gw_file', $shipping_option);
                $this->assertArrayHasKey('module', $shipping_option);
                if ($shipping_option['module'] == $check_module){
                    $found = true;
                }

            }
            $this->assertTrue($found);


        }


    }

}
