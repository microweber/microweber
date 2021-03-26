<?php

namespace MicroweberPackages\Shop\tests;

use MicroweberPackages\Core\tests\TestCase;

class ShopManagerTest extends TestCase
{

    public function testGetShippingModules()
    {

        if (is_module('shop/shipping/gateways/country')) {
            $shipping_options = app()->shipping_manager->getShippingModules();

            $this->assertNotEmpty($shipping_options);

            foreach ($shipping_options as $shipping_option){
                $this->assertArrayHasKey('module_base', $shipping_option);
                $this->assertArrayHasKey('gw_file', $shipping_option);

            }

        }


    }

}