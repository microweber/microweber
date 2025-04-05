<?php

namespace Modules\Customer\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Customer\Providers\CustomerServiceProvider;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\Customer\Services\CustomerManager;

class CustomerServiceProviderTest extends TestCase
{
    public function testServiceRegistration()
    {
        // Test if services are properly registered
        $this->assertTrue(app()->bound(CustomerManager::class));
        $this->assertInstanceOf(CustomerManager::class, app(CustomerManager::class));
        
        $this->assertTrue(app()->bound(CustomerRepository::class));
        $this->assertInstanceOf(CustomerRepository::class, app(CustomerRepository::class));
        
        // Test if provider is loaded
        $providers = app()->getLoadedProviders();
        $this->assertArrayHasKey(CustomerServiceProvider::class, $providers);
    }

    public function testFacades()
    {
        // Test if facades are working
        $this->assertTrue(class_exists('Modules\Customer\Facades\Customer'));
        $this->assertInstanceOf(CustomerManager::class, \Modules\Customer\Facades\Customer::getFacadeRoot());
    }
}