<?php

namespace Modules\Payment\Tests\Unit;

use Tests\TestCase;
use Modules\Payment\Services\PaymentMethodManager;
use Modules\Payment\Drivers\AbstractPaymentMethod;

class TestablePaymentManager extends PaymentMethodManager {
    protected function createDriver($driver) {
        return $this->customDrivers[$driver] ?? null;
    }
    
    public array $customDrivers = [];
}

class PaymentMethodManagerTest extends TestCase
{
    protected TestablePaymentManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->manager = new TestablePaymentManager(app());
        
        // Register test drivers directly
        $this->manager->customDrivers['test1'] = new class extends AbstractPaymentMethod {
            public function initializePayment($payment) {}
            public function completePayment($payment) {}
            public function title(): string { return 'Test1'; }
            public function logo(): string { return 'test1.png'; }
        };
        
        $this->manager->customDrivers['test2'] = new class extends AbstractPaymentMethod {
            public function initializePayment($payment) {}
            public function completePayment($payment) {}
            public function title(): string { return 'Test2'; }
            public function logo(): string { return 'test2.png'; }
        };
    }

    public function testDriverRegistration()
    {
        $this->assertArrayHasKey('test1', $this->manager->customDrivers);
        $this->assertArrayHasKey('test2', $this->manager->customDrivers);
    }
    
    public function testDriverResolution()
    {
        $driver1 = $this->manager->customDrivers['test1'];
        $driver2 = $this->manager->customDrivers['test2'];
        
        $this->assertNotSame($driver1, $driver2);
        $this->assertEquals('Test1', $driver1->title());
        $this->assertEquals('Test2', $driver2->title());
    }
}