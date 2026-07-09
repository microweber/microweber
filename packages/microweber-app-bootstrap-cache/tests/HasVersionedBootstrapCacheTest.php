<?php

namespace MicroweberPackages\AppBootstrapCache\Tests;

use Illuminate\Foundation\Application;
use MicroweberPackages\AppBootstrapCache\Tests\Fixtures\TestApplication;
use MicroweberPackages\AppBootstrapCache\Tests\Fixtures\VersionedTestApplication;

class HasVersionedBootstrapCacheTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempDir = sys_get_temp_dir() . '/mw_bootstrap_cache_test_' . uniqid();
        mkdir($this->tempDir);
        mkdir($this->tempDir . '/bootstrap');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up temp directory
        $this->removeDir($this->tempDir);
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->removeDir($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    // ---------------------------------------------------------------
    // Version prefix tests
    // ---------------------------------------------------------------

    public function test_version_prefix_without_app_version(): void
    {
        $app = new TestApplication($this->tempDir);
        $prefix = $app->getBootstrapCacheVersionPrefix();

        // Should contain the Laravel version slugified
        $this->assertMatchesRegularExpression('/^[a-z0-9_]+$/', $prefix);
        $this->assertNotEmpty($prefix);
    }

    public function test_version_prefix_with_app_version_constant(): void
    {
        $app = new VersionedTestApplication($this->tempDir);
        $prefix = $app->getBootstrapCacheVersionPrefix();

        // Should contain both Laravel version and app version
        // Str::slug converts "4.0-dev17" → "40_dev17"
        $this->assertStringContainsString('40_dev17', $prefix);
    }

    public function test_get_app_version_returns_null_when_no_constant(): void
    {
        $app = new TestApplication($this->tempDir);
        $this->assertNull($app->getAppVersion());
    }

    public function test_get_app_version_returns_constant_value(): void
    {
        $app = new VersionedTestApplication($this->tempDir);
        $this->assertSame('4.0-dev17', $app->getAppVersion());
    }

    public function test_get_laravel_version_matches_framework(): void
    {
        $app = new TestApplication($this->tempDir);
        $this->assertSame(Application::VERSION, $app->getLaravelVersion());
    }

    // ---------------------------------------------------------------
    // Cache path tests
    // ---------------------------------------------------------------

    public function test_cached_services_path_is_versioned(): void
    {
        $app = new TestApplication($this->tempDir);
        $path = $app->getCachedServicesPath();

        $this->assertStringContainsString('cache_', $path);
        $this->assertStringEndsWith('_services.php', $path);
        $this->assertStringContainsString($this->tempDir, $path);
    }

    public function test_cached_packages_path_is_versioned(): void
    {
        $app = new TestApplication($this->tempDir);
        $path = $app->getCachedPackagesPath();

        $this->assertStringContainsString('cache_', $path);
        $this->assertStringEndsWith('_packages.php', $path);
    }

    public function test_cached_config_path_is_versioned(): void
    {
        $app = new TestApplication($this->tempDir);
        $path = $app->getCachedConfigPath();

        $this->assertStringContainsString('cache_', $path);
        $this->assertStringEndsWith('_config.php', $path);
    }

    public function test_cached_routes_path_is_versioned(): void
    {
        $app = new TestApplication($this->tempDir);
        $path = $app->getCachedRoutesPath();

        $this->assertStringContainsString('cache_', $path);
        $this->assertStringEndsWith('_routes.php', $path);
    }

    public function test_cached_events_path_is_versioned(): void
    {
        $app = new TestApplication($this->tempDir);
        $path = $app->getCachedEventsPath();

        $this->assertStringContainsString('cache_', $path);
        $this->assertStringEndsWith('_events.php', $path);
    }

    public function test_cached_paths_use_bootstrap_directory(): void
    {
        $app = new TestApplication($this->tempDir);

        $paths = [
            $app->getCachedServicesPath(),
            $app->getCachedPackagesPath(),
            $app->getCachedConfigPath(),
            $app->getCachedRoutesPath(),
            $app->getCachedEventsPath(),
        ];

        foreach ($paths as $path) {
            $this->assertStringContainsString('bootstrap' . DIRECTORY_SEPARATOR . 'cache', $path);
        }
    }

    public function test_versioned_app_produces_different_paths_than_unversioned(): void
    {
        $unversioned = new TestApplication($this->tempDir);
        $versioned = new VersionedTestApplication($this->tempDir);

        $this->assertNotSame(
            $unversioned->getCachedServicesPath(),
            $versioned->getCachedServicesPath()
        );
    }

    public function test_all_cache_paths_share_same_version_prefix(): void
    {
        $app = new VersionedTestApplication($this->tempDir);
        $prefix = $app->getBootstrapCacheVersionPrefix();

        $this->assertStringContainsString($prefix, $app->getCachedServicesPath());
        $this->assertStringContainsString($prefix, $app->getCachedPackagesPath());
        $this->assertStringContainsString($prefix, $app->getCachedConfigPath());
        $this->assertStringContainsString($prefix, $app->getCachedRoutesPath());
        $this->assertStringContainsString($prefix, $app->getCachedEventsPath());
    }

    // ---------------------------------------------------------------
    // Each cache path uses its own distinct file name
    // ---------------------------------------------------------------

    public function test_all_cache_paths_are_distinct(): void
    {
        $app = new TestApplication($this->tempDir);

        $paths = [
            $app->getCachedServicesPath(),
            $app->getCachedPackagesPath(),
            $app->getCachedConfigPath(),
            $app->getCachedRoutesPath(),
            $app->getCachedEventsPath(),
        ];

        $this->assertSame(count($paths), count(array_unique($paths)));
    }

    // ---------------------------------------------------------------
    // ensureBootstrapCacheDirectoryExists
    // ---------------------------------------------------------------

    public function test_ensure_bootstrap_cache_directory_creates_cache_dir(): void
    {
        // Start fresh without the cache dir
        $freshDir = sys_get_temp_dir() . '/mw_bootstrap_ensure_test_' . uniqid();
        mkdir($freshDir);
        mkdir($freshDir . '/bootstrap');

        $app = new TestApplication($freshDir);
        $cacheDir = $freshDir . '/bootstrap/cache';

        $this->assertDirectoryDoesNotExist($cacheDir);

        $app->ensureBootstrapCacheDirectoryExists();

        $this->assertDirectoryExists($cacheDir);

        // Clean up
        $this->removeDir($freshDir);
    }

    public function test_ensure_bootstrap_cache_directory_is_idempotent(): void
    {
        $app = new TestApplication($this->tempDir);
        $cacheDir = $this->tempDir . '/bootstrap/cache';

        mkdir($cacheDir);
        $this->assertDirectoryExists($cacheDir);

        // Calling again should not throw
        $app->ensureBootstrapCacheDirectoryExists();

        $this->assertDirectoryExists($cacheDir);
    }

    // ---------------------------------------------------------------
    // Build methods
    // ---------------------------------------------------------------

    public function test_build_versioned_cache_file_name(): void
    {
        $app = new TestApplication($this->tempDir);
        $fileName = $app->buildVersionedCacheFileName('services');

        $this->assertStringStartsWith('cache_', $fileName);
        $this->assertStringEndsWith('_services.php', $fileName);
    }

    public function test_build_versioned_cache_path(): void
    {
        $app = new TestApplication($this->tempDir);
        $path = $app->buildVersionedCachePath('config');

        $this->assertStringStartsWith('cache/', $path);
        $this->assertStringEndsWith('_config.php', $path);
    }

    // ---------------------------------------------------------------
    // Config path uses correct env key (regression test)
    // ---------------------------------------------------------------

    public function test_config_path_uses_app_config_cache_env_key(): void
    {
        // Set APP_CONFIG_CACHE to a custom path and verify it's used
        $customPath = '/tmp/custom_config_cache.php';
        putenv('APP_CONFIG_CACHE=' . $customPath);

        try {
            $app = new TestApplication($this->tempDir);
            $this->assertSame($customPath, $app->getCachedConfigPath());
        } finally {
            putenv('APP_CONFIG_CACHE');
        }
    }

    public function test_services_path_uses_app_services_cache_env_key(): void
    {
        $customPath = '/tmp/custom_services_cache.php';
        putenv('APP_SERVICES_CACHE=' . $customPath);

        try {
            $app = new TestApplication($this->tempDir);
            $this->assertSame($customPath, $app->getCachedServicesPath());
        } finally {
            putenv('APP_SERVICES_CACHE');
        }
    }

    public function test_packages_path_uses_app_packages_cache_env_key(): void
    {
        $customPath = '/tmp/custom_packages_cache.php';
        putenv('APP_PACKAGES_CACHE=' . $customPath);

        try {
            $app = new TestApplication($this->tempDir);
            $this->assertSame($customPath, $app->getCachedPackagesPath());
        } finally {
            putenv('APP_PACKAGES_CACHE');
        }
    }

    public function test_routes_path_uses_app_routes_cache_env_key(): void
    {
        $customPath = '/tmp/custom_routes_cache.php';
        putenv('APP_ROUTES_CACHE=' . $customPath);

        try {
            $app = new TestApplication($this->tempDir);
            $this->assertSame($customPath, $app->getCachedRoutesPath());
        } finally {
            putenv('APP_ROUTES_CACHE');
        }
    }

    public function test_events_path_uses_app_events_cache_env_key(): void
    {
        $customPath = '/tmp/custom_events_cache.php';
        putenv('APP_EVENTS_CACHE=' . $customPath);

        try {
            $app = new TestApplication($this->tempDir);
            $this->assertSame($customPath, $app->getCachedEventsPath());
        } finally {
            putenv('APP_EVENTS_CACHE');
        }
    }

    // ---------------------------------------------------------------
    // registerDefaultFacadeAliases — makes bare facades resolve under
    // a cached config (no config/app.php 'aliases' key needed)
    // ---------------------------------------------------------------

    public function test_register_default_facade_aliases_seeds_the_alias_loader(): void
    {
        $app = new TestApplication($this->tempDir);
        $app->registerDefaultFacadeAliases();

        $aliases = \Illuminate\Foundation\AliasLoader::getInstance()->getAliases();

        // A representative sample of the default facades MW relies on.
        $this->assertArrayHasKey('Route', $aliases);
        $this->assertSame(\Illuminate\Support\Facades\Route::class, $aliases['Route']);
        $this->assertArrayHasKey('DB', $aliases);
        $this->assertArrayHasKey('Schema', $aliases);
    }

    public function test_user_supplied_aliases_coexist_with_and_override_the_defaults(): void
    {
        $app = new TestApplication($this->tempDir);
        $app->registerDefaultFacadeAliases();

        // Simulate what Laravel's RegisterFacades does later with a user's
        // config('app.aliases'): getInstance() MERGES on top (config wins on
        // key collision), so a custom alias is ADDED and an override REPLACES.
        \Illuminate\Foundation\AliasLoader::getInstance([
            'MwUserAlias' => \Illuminate\Support\Collection::class, // add
            'Route'       => \Illuminate\Support\Collection::class, // override
        ])->register();

        $aliases = \Illuminate\Foundation\AliasLoader::getInstance()->getAliases();

        // User addition present…
        $this->assertArrayHasKey('MwUserAlias', $aliases);
        $this->assertSame(\Illuminate\Support\Collection::class, $aliases['MwUserAlias']);
        // …user override wins over our default…
        $this->assertSame(\Illuminate\Support\Collection::class, $aliases['Route']);
        // …and untouched defaults survive.
        $this->assertArrayHasKey('DB', $aliases);
        $this->assertSame(\Illuminate\Support\Facades\DB::class, $aliases['DB']);
    }
}