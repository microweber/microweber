<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests\Standalone;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use MicroweberPackages\Package\ModulePackage;
use MicroweberPackages\Package\PackageManagerException;
use MicroweberPackages\Package\Tests\Fixtures\ExamplePackageServiceProvider;
use MicroweberPackages\Package\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Validates that microweber-packages/package can be used in a standalone
 * Laravel application without any CMS services.
 */
class StandaloneLaravelUsageTest extends TestCase
{
    #[Test]
    public function package_classes_are_autoloadable_without_cms(): void
    {
        $this->assertTrue(class_exists(MicroweberPackageServiceProvider::class));
        $this->assertTrue(class_exists(ModulePackage::class));
        $this->assertTrue(class_exists(PackageManagerException::class));
        $this->assertTrue(class_exists(Package::class));
        $this->assertTrue(class_exists(PackageServiceProvider::class));
    }

    #[Test]
    public function concrete_provider_registers_in_laravel_container(): void
    {
        $provider = $this->app->getProvider(ExamplePackageServiceProvider::class);
        $this->assertInstanceOf(MicroweberPackageServiceProvider::class, $provider);
        $this->assertSame('microweber-example-package', $provider->getPackage()->name);
    }

    #[Test]
    public function module_package_works_without_filament_or_module_admin(): void
    {
        // Must not throw even when optional facades are absent
        $module = new ModulePackage('standalone');
        $module
            ->hasFilamentPage('Does\\Not\\Exist')
            ->hasFilamentResource('Does\\Not\\Exist')
            ->hasLiveEditSettings('Does\\Not\\Exist')
            ->hasViewComponent('Does\\Not\\Exist');

        $this->assertSame('standalone', $module->type);
    }

    #[Test]
    public function base_provider_cannot_be_instantiated_directly(): void
    {
        $ref = new ReflectionClass(MicroweberPackageServiceProvider::class);
        $this->assertTrue($ref->isAbstract());
        $this->assertFalse($ref->isInstantiable());
    }
}
