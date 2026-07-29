<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader\Tests\Feature;

use MicroweberPackages\ClassLoader\ClassLoader;
use MicroweberPackages\ClassLoader\ClassLoaderServiceProvider;
use MicroweberPackages\ClassLoader\PathNormalizer;
use MicroweberPackages\ClassLoader\Tests\TestCase;

/**
 * Validates the package API surface that a standalone Laravel app would use.
 */
class StandaloneAppValidationTest extends TestCase
{
    public function test_package_classes_are_loadable(): void
    {
        $this->assertTrue(class_exists(ClassLoader::class));
        $this->assertTrue(class_exists(PathNormalizer::class));
        $this->assertTrue(class_exists(ClassLoaderServiceProvider::class));
    }

    public function test_old_static_class_loader_is_gone(): void
    {
        // Source was deleted from src/; may still be in composer classmap cache as false
        $this->assertFalse(
            class_exists(\MicroweberPackages\Utils\System\ClassLoader::class, false)
        );
    }

    public function test_old_spl_class_loader_is_gone(): void
    {
        $this->assertFalse(
            class_exists(\MicroweberPackages\LaravelModules\Helpers\SplClassLoader::class, false)
        );
    }

    public function test_service_usable_without_cms(): void
    {
        $loader = new ClassLoader();
        $loader->addDirectories([sys_get_temp_dir()]);
        $loader->addNamespace('Standalone\\Test', sys_get_temp_dir());
        $loader->register();

        $this->assertTrue($loader->isRegistered());
        $this->assertIsArray($loader->getStatistics());
        $this->assertTrue($loader->selfTest()['ok']);

        $loader->reset();
    }

    public function test_full_api_surface_for_external_apps(): void
    {
        $service = app(ClassLoader::class);

        $this->assertIsBool($service->isRegistered());
        $this->assertIsArray($service->getDirectories());
        $this->assertIsArray($service->getNamespaces());
        $this->assertIsArray($service->getStatistics());
        $this->assertIsArray($service->selfTest());
        $this->assertNull($service->resolve('No\\Such\\Class' . uniqid()));
    }

    public function test_facade_works(): void
    {
        if (!class_exists(\MicroweberPackages\ClassLoader\Facades\ClassLoaderFacade::class)) {
            $this->markTestSkipped('Facade not loaded');
        }

        $stats = \MicroweberPackages\ClassLoader\Facades\ClassLoaderFacade::getStatistics();
        $this->assertIsArray($stats);
    }

    public function test_no_static_state_on_public_api(): void
    {
        $ref = new \ReflectionClass(ClassLoader::class);
        foreach ($ref->getProperties() as $prop) {
            $this->assertFalse(
                $prop->isStatic(),
                'ClassLoader must not have static properties (found: ' . $prop->getName() . ')'
            );
        }
    }
}
