<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests\Feature;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use MicroweberPackages\Package\PackageManagerException;
use MicroweberPackages\Package\Tests\Fixtures\ExampleModulePackageServiceProvider;
use MicroweberPackages\Package\Tests\Fixtures\ExamplePackageServiceProvider;
use MicroweberPackages\Package\Tests\Fixtures\MissingModuleTypeServiceProvider;
use MicroweberPackages\Package\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MicroweberPackageServiceProviderTest extends TestCase
{
    #[Test]
    public function base_provider_is_abstract_and_extends_spatie(): void
    {
        $ref = new ReflectionClass(MicroweberPackageServiceProvider::class);
        $this->assertTrue($ref->isAbstract());
        $this->assertTrue($ref->isSubclassOf(PackageServiceProvider::class));
    }

    #[Test]
    public function example_provider_is_loaded_via_microweber_loader(): void
    {
        $provider = $this->app->getProvider(ExamplePackageServiceProvider::class);
        $this->assertInstanceOf(ExamplePackageServiceProvider::class, $provider);
        $this->assertInstanceOf(MicroweberPackageServiceProvider::class, $provider);
        $this->assertTrue($provider->usesMicroweberPackageLoader());
        $this->assertSame('microweber-example-package', $provider->getPackage()->name);
    }

    #[Test]
    public function package_registered_hook_binds_services(): void
    {
        $this->assertTrue($this->app->bound('microweber.example-package.flag'));
        $this->assertTrue((bool) $this->app->make('microweber.example-package.flag'));
    }

    #[Test]
    public function module_type_can_be_configured(): void
    {
        $this->app->register(ExampleModulePackageServiceProvider::class);
        $provider = $this->app->getProvider(ExampleModulePackageServiceProvider::class);
        $this->assertInstanceOf(ExampleModulePackageServiceProvider::class, $provider);
        $module = $provider->getModulePackage();
        $this->assertNotNull($module);
        $this->assertSame('example-module', $module->type);
    }

    #[Test]
    public function missing_module_type_throws_when_required(): void
    {
        $this->expectException(PackageManagerException::class);
        $this->expectExceptionMessage('type');
        $this->app->register(MissingModuleTypeServiceProvider::class);
    }
}
