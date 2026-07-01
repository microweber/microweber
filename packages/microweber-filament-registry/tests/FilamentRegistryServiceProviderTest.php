<?php

namespace MicroweberPackages\FilamentRegistry\Tests;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;

class FilamentRegistryServiceProviderTest extends TestCase
{
    public function test_service_provider_registers_singleton(): void
    {
        $instance1 = $this->app->make(FilamentRegistryManager::class);
        $instance2 = $this->app->make(FilamentRegistryManager::class);

        $this->assertInstanceOf(FilamentRegistryManager::class, $instance1);
        $this->assertSame($instance1, $instance2);
    }

    public function test_facade_accessor_resolves(): void
    {
        $facade = FilamentRegistry::getFacadeRoot();
        $this->assertInstanceOf(FilamentRegistryManager::class, $facade);
    }

    public function test_facade_and_container_same_instance(): void
    {
        $container = $this->app->make(FilamentRegistryManager::class);
        $facade = FilamentRegistry::getFacadeRoot();
        $this->assertSame($container, $facade);
    }

    public function test_provider_is_loaded(): void
    {
        $providers = $this->app->getLoadedProviders();
        $this->assertArrayHasKey(
            \MicroweberPackages\FilamentRegistry\FilamentRegistryServiceProvider::class,
            $providers
        );
    }
}