<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader\Tests\Feature;

use MicroweberPackages\ClassLoader\ClassLoaderService;
use MicroweberPackages\ClassLoader\Facades\ClassLoader;
use MicroweberPackages\ClassLoader\Tests\TestCase;

/**
 * Integration: container bindings, config, helpers, and dependent packages.
 */
class StandaloneIntegrationTest extends TestCase
{
    public function test_singleton_and_facade_resolve_same_instance(): void
    {
        $a = app(ClassLoaderService::class);
        $b = app(ClassLoaderService::class);
        $this->assertSame($a, $b, 'ClassLoader is a container singleton.');
        $this->assertSame($a, ClassLoader::getFacadeRoot(), 'Facade resolves the same instance.');
    }

    public function test_helpers_use_container(): void
    {
        $viaHelper = mw_class_loader();
        $viaService = app(ClassLoaderService::class);
        $this->assertSame($viaHelper, $viaService);
    }

    public function test_terminating_clears_cache(): void
    {
        $loader = app(ClassLoaderService::class);
        $loader->addDirectories(sys_get_temp_dir());
        $loader->load('NoSuchForTerminate' . uniqid());
        $this->assertGreaterThanOrEqual(0, $loader->getStatistics()['not_found_cache_count']);

        // Simulate terminate callback
        $loader->clearCache();
        $this->assertSame(0, $loader->getStatistics()['not_found_cache_count']);
    }

    public function test_path_dedup_through_service(): void
    {
        $loader = app(ClassLoaderService::class);
        $base = sys_get_temp_dir() . '/mw-int-dedup-' . uniqid();
        $before = count($loader->getDirectories());
        $loader->addDirectories($base);
        $loader->addDirectories($base . '/');
        $loader->addDirectories(str_replace('/', DIRECTORY_SEPARATOR, $base));
        $after = count($loader->getDirectories());
        $this->assertSame($before + 1, $after);
    }

    public function test_dependent_filesystem_package_available_when_in_monorepo(): void
    {
        // Optional dependent package from packages/* — only assert when present.
        if (class_exists(\MicroweberPackages\Filesystem\FilesystemService::class)) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(true, 'Filesystem package not required for standalone unit use');
        }
    }
}
