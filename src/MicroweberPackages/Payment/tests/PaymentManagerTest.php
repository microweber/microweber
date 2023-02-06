<?php

namespace MicroweberPackages\Payment\tests;

use MicroweberPackages\Core\tests\TestCase;

class PaymentManagerTest extends TestCase
{
    public function testGetPaymentModules()
    {

        $paymentModulesWithDisabled = app()->payment_manager->getPaymentModules(false);
        $this->assertNotEmpty($paymentModulesWithDisabled);
        foreach ($paymentModulesWithDisabled as $paymentModule) {
            $enable = app()->payment_manager->driver($paymentModule['gw_file'])->enable();
            $this->assertTrue($enable);
        }

        $paymentModules = app()->payment_manager->getPaymentModules();

        $this->assertNotEmpty($paymentModules);
        foreach ($paymentModules as $paymentModule) {
            $this->assertArrayHasKey('id', $paymentModule);
            $this->assertArrayHasKey('name', $paymentModule);
            $this->assertArrayHasKey('description', $paymentModule);
            $this->assertArrayHasKey('icon', $paymentModule);
            $this->assertArrayHasKey('module', $paymentModule);
            $this->assertArrayHasKey('type', $paymentModule);
            $this->assertArrayHasKey('gw_file', $paymentModule);
            $name = app()->payment_manager->driver($paymentModule['gw_file'])->name();
            $this->assertIsString($name);
        }

        foreach ($paymentModules as $paymentModule) {
            app()->payment_manager->driver($paymentModule['gw_file'])->disable();
        }

        $paymentModules = app()->payment_manager->getPaymentModules();
        $this->assertEmpty($paymentModules);

    }
}
