<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader\Tests\Feature;

use MicroweberPackages\ClassLoader\ClassLoaderService;
use MicroweberPackages\ClassLoader\ClassLoaderServiceProvider;
use MicroweberPackages\ClassLoader\Facades\ClassLoader;
use MicroweberPackages\ClassLoader\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_provider_class_exists(): void
    {
        $this->assertTrue(class_exists(ClassLoaderServiceProvider::class));
    }

    public function test_class_loader_is_bound_as_singleton(): void
    {
        $a = app(ClassLoaderService::class);
        $b = app(ClassLoaderService::class);
        $this->assertSame($a, $b);
        $this->assertInstanceOf(ClassLoaderService::class, $a);
    }

    public function test_facade_resolves_the_singleton(): void
    {
        $this->assertSame(app(ClassLoaderService::class), ClassLoader::getFacadeRoot());
    }

    public function test_config_is_merged(): void
    {
        $this->assertNotNull(config('class-loader'));
        $this->assertIsBool(config('class-loader.enabled'));
        $this->assertIsArray(config('class-loader.directories'));
        $this->assertIsArray(config('class-loader.namespaces'));
    }

    public function test_loader_is_registered_when_enabled(): void
    {
        if (!(bool) config('class-loader.enabled', true)) {
            $this->markTestSkipped('class-loader disabled');
        }
        $this->assertTrue(app(ClassLoaderService::class)->isRegistered());
    }
}
