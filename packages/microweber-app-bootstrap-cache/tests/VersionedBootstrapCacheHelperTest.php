<?php

namespace MicroweberPackages\AppBootstrapCache\Tests;

use MicroweberPackages\AppBootstrapCache\VersionedBootstrapCacheHelper;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class VersionedBootstrapCacheHelperTest extends PHPUnitTestCase
{
    public function test_version_prefix_with_laravel_version_only(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0');
        // Str::slug treats dots as separators; "11.54.0" → "11540"
        $this->assertSame('11540', $helper->getVersionPrefix());
    }

    public function test_version_prefix_with_app_version(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0', '4.0-dev17');
        $this->assertSame('11540_40_dev17', $helper->getVersionPrefix());
    }

    public function test_version_prefix_with_null_app_version(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0', null);
        $this->assertSame('11540', $helper->getVersionPrefix());
    }

    public function test_version_prefix_with_empty_app_version(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0', '');
        $this->assertSame('11540', $helper->getVersionPrefix());
    }

    public function test_cache_file_name_for_services(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0', '2.0.0');
        $this->assertSame('cache_11540_200_services.php', $helper->getCacheFileName('services'));
    }

    public function test_cache_file_name_for_config(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0', '2.0.0');
        $this->assertSame('cache_11540_200_config.php', $helper->getCacheFileName('config'));
    }

    public function test_cache_file_name_for_packages(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0', '2.0.0');
        $this->assertSame('cache_11540_200_packages.php', $helper->getCacheFileName('packages'));
    }

    public function test_cache_file_name_for_routes(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0');
        $this->assertSame('cache_11540_routes.php', $helper->getCacheFileName('routes'));
    }

    public function test_cache_file_name_for_events(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0');
        $this->assertSame('cache_11540_events.php', $helper->getCacheFileName('events'));
    }

    public function test_cache_path_includes_cache_directory(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0', '1.0.0');
        $this->assertSame('cache/cache_11540_100_services.php', $helper->getCachePath('services'));
    }

    public function test_different_laravel_versions_produce_different_prefixes(): void
    {
        $helper10 = new VersionedBootstrapCacheHelper('10.48.0', '1.0.0');
        $helper11 = new VersionedBootstrapCacheHelper('11.54.0', '1.0.0');

        $this->assertNotSame($helper10->getVersionPrefix(), $helper11->getVersionPrefix());
    }

    public function test_different_app_versions_produce_different_prefixes(): void
    {
        $helper1 = new VersionedBootstrapCacheHelper('11.54.0', '1.0.0');
        $helper2 = new VersionedBootstrapCacheHelper('11.54.0', '2.0.0');

        $this->assertNotSame($helper1->getVersionPrefix(), $helper2->getVersionPrefix());
    }

    public function test_getter_for_laravel_version(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0', '1.0.0');
        $this->assertSame('11.54.0', $helper->getLaravelVersion());
    }

    public function test_getter_for_app_version(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0', '1.0.0');
        $this->assertSame('1.0.0', $helper->getAppVersion());
    }

    public function test_getter_for_null_app_version(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.54.0');
        $this->assertNull($helper->getAppVersion());
    }

    public function test_version_prefix_with_semver_prerelease(): void
    {
        $helper = new VersionedBootstrapCacheHelper('12.0.0-beta.1', 'v3.0.0-rc.2');
        $prefix = $helper->getVersionPrefix();

        // Slug should contain only lowercase alphanumerics and underscores
        $this->assertMatchesRegularExpression('/^[a-z0-9_]+$/', $prefix);
        $this->assertStringContainsString('12', $prefix);
        $this->assertStringContainsString('3', $prefix);
    }

    public function test_cache_file_name_always_ends_with_php_extension(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.0.0');

        foreach (['services', 'packages', 'config', 'routes', 'events'] as $name) {
            $this->assertStringEndsWith('.php', $helper->getCacheFileName($name));
        }
    }

    public function test_cache_file_name_starts_with_cache_prefix(): void
    {
        $helper = new VersionedBootstrapCacheHelper('11.0.0');

        foreach (['services', 'packages', 'config', 'routes', 'events'] as $name) {
            $this->assertStringStartsWith('cache_', $helper->getCacheFileName($name));
        }
    }
}