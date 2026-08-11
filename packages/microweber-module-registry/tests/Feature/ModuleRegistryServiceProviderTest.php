<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests\Feature;

use MicroweberPackages\ModuleRegistry\Facades\Microweber;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use MicroweberPackages\ModuleRegistry\ModuleRegistryManager;
use MicroweberPackages\ModuleRegistry\ModuleRegistryServiceProvider;
use MicroweberPackages\ModuleRegistry\Tests\Fixtures\ExampleModule;
use MicroweberPackages\ModuleRegistry\Tests\TestCase;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;

class ModuleRegistryServiceProviderTest extends TestCase
{
    #[Test]
    public function provider_extends_microweber_package_loader(): void
    {
        $ref = new ReflectionClass(ModuleRegistryServiceProvider::class);
        $this->assertTrue($ref->isSubclassOf(MicroweberPackageServiceProvider::class));

        $provider = $this->app->getProvider(ModuleRegistryServiceProvider::class);
        $this->assertInstanceOf(ModuleRegistryServiceProvider::class, $provider);
        $this->assertSame('microweber-packages/module-registry', $provider->getPackage()->name);
    }

    #[Test]
    public function microweber_facade_alias_works(): void
    {
        Microweber::module(ExampleModule::class);
        $this->assertTrue(Microweber::hasModule('example'));
        $this->assertTrue(ModuleRegistry::hasModule('example'));
    }

    #[Test]
    public function views_are_loaded_under_module_registry_and_microweber_namespaces(): void
    {
        $this->assertTrue(view()->exists('module-registry::livewire.no-settings'));
        $this->assertTrue(view()->exists('microweber::livewire.no-settings'));
    }

    #[Test]
    public function provides_lists_bindings(): void
    {
        $provider = new ModuleRegistryServiceProvider($this->app);
        $this->assertContains(ModuleRegistryManager::class, $provider->provides());
        $this->assertContains('microweber', $provider->provides());
    }
}
