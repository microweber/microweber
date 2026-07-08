<?php

namespace MicroweberPackages\Core\Tests;

use Tests\TestCase;

/**
 * Tests that validate individual Microweber packages can work independently
 * when installed in a clean Laravel application.
 *
 * Each package must:
 * 1. Have its laravel.extra.providers removed (no auto-discovery)
 * 2. Be loadable via CoreServiceProvider (deterministic order)
 * 3. Have its service provider class available
 * 4. Not crash when its dependencies are present
 */
class StandalonePackageUsageTest extends TestCase
{
    /**
     * Maps package directory names to their service provider classes.
     */
    protected function getPackageProviderMap(): array
    {
        return [
            'microweber-filesystem' => \MicroweberPackages\Filesystem\FilesystemServiceProvider::class,
            'microweber-format' => \MicroweberPackages\Format\FormatServiceProvider::class,
            'microweber-security' => \MicroweberPackages\Security\SecurityServiceProvider::class,
            'microweber-http' => \MicroweberPackages\Http\HttpServiceProvider::class,
            'microweber-taggable-file-cache' => \MicroweberPackages\TaggableFileCache\TaggableFileCacheServiceProvider::class,
            'microweber-env-writer' => \MicroweberPackages\EnvWriter\EnvWriterServiceProvider::class,
            'microweber-blade-cache' => \MicroweberPackages\BladeCache\BladeCacheServiceProvider::class,
            'microweber-repository' => \MicroweberPackages\Repository\Providers\RepositoryServiceProvider::class,
            'microweber-searchable' => \MicroweberPackages\Searchable\SearchableServiceProvider::class,
            'microweber-url' => \MicroweberPackages\Url\Providers\UrlServiceProvider::class,
            'microweber-thumbnailer' => \MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider::class,
            'microweber-database' => \MicroweberPackages\Database\DatabaseManagerServiceProvider::class,
            'microweber-db-migrator' => \MicroweberPackages\DbMigrator\DbMigratorServiceProvider::class,
            'microweber-db-installer' => \MicroweberPackages\DbInstaller\DbInstallerServiceProvider::class,
            'microweber-event-manager' => \MicroweberPackages\Event\EventManagerServiceProvider::class,
            'microweber-config' => \MicroweberPackages\Config\ConfigServiceProvider::class,
            'microweber-file-uploader' => \MicroweberPackages\FileUploader\FileUploaderServiceProvider::class,
            'microweber-filament-registry' => \MicroweberPackages\FilamentRegistry\FilamentRegistryServiceProvider::class,
            'microweber-filament-modal-teleport' => \MicroweberPackages\FilamentModalTeleport\ModalTeleportServiceProvider::class,
            'microweber-phpquery' => \MicroweberPackages\PhpQuery\Providers\PhpQueryServiceProvider::class,
        ];
    }

    /**
     * All MW package service providers must be loadable.
     */
    public function test_all_package_providers_are_loadable(): void
    {
        foreach ($this->getPackageProviderMap() as $pkg => $providerClass) {
            $this->assertTrue(
                class_exists($providerClass),
                "Service provider for {$pkg} ({$providerClass}) must be autoloadable"
            );
        }
    }

    /**
     * All MW package providers listed in CoreServiceProvider must be registered.
     */
    public function test_all_core_listed_providers_are_registered(): void
    {
        $coreProvider = $this->app->getProvider(\MicroweberPackages\Core\CoreServiceProvider::class);
        $this->assertNotNull($coreProvider);

        $loaded = $this->app->getLoadedProviders();

        foreach ($coreProvider->getPackageProviders() as $providerClass) {
            if (class_exists($providerClass)) {
                $this->assertArrayHasKey(
                    $providerClass,
                    $loaded,
                    "Provider {$providerClass} exists but was not loaded by CoreServiceProvider"
                );
            }
        }
    }

    /**
     * Each package's composer.json must NOT have laravel auto-discovery.
     * (Except microweber-core which is the entry point.)
     */
    public function test_no_package_has_auto_discovery_except_core(): void
    {
        $packagesDir = base_path('packages');
        if (!is_dir($packagesDir)) {
            $this->markTestSkipped('packages/ directory not found');
        }

        $dirs = glob($packagesDir . '/*/composer.json');
        foreach ($dirs as $file) {
            $dirname = basename(dirname($file));
            if ($dirname === 'microweber-core') {
                continue;
            }

            $data = json_decode(file_get_contents($file), true);
            $providers = $data['extra']['laravel']['providers'] ?? null;

            $this->assertNull(
                $providers,
                "Package {$dirname}/composer.json should NOT have extra.laravel.providers"
            );
        }
    }

    /**
     * The microweber-core package MUST keep its auto-discovery key
     * so it works as a standalone installable package.
     */
    public function test_core_package_has_auto_discovery(): void
    {
        $coreComposer = base_path('packages/microweber-core/composer.json');
        if (!file_exists($coreComposer)) {
            $this->markTestSkipped('Core package composer.json not found');
        }

        $data = json_decode(file_get_contents($coreComposer), true);
        $providers = $data['extra']['laravel']['providers'] ?? [];

        $this->assertContains(
            'MicroweberPackages\\Core\\CoreServiceProvider',
            $providers,
            'Core package must retain its auto-discovery provider for standalone use'
        );
    }

    /**
     * The filesystem package helpers must be available after boot.
     * This is the package that provides normalize_path().
     */
    public function test_filesystem_helpers_available(): void
    {
        $this->assertTrue(function_exists('normalize_path'));
        $this->assertTrue(function_exists('reduce_double_slashes'));

        $path = normalize_path('/some/test/path/', true);
        $this->assertIsString($path);
    }

    /**
     * The event manager helpers must be available.
     */
    public function test_event_manager_helpers_available(): void
    {
        $this->assertTrue(function_exists('event_trigger'));
    }

    /**
     * Each package should have a valid composer.json with a name field.
     */
    public function test_all_packages_have_valid_composer_json(): void
    {
        $packagesDir = base_path('packages');
        if (!is_dir($packagesDir)) {
            $this->markTestSkipped('packages/ directory not found');
        }

        $dirs = glob($packagesDir . '/*/composer.json');
        foreach ($dirs as $file) {
            $dirname = basename(dirname($file));
            $data = json_decode(file_get_contents($file), true);

            $this->assertNotNull($data, "Package {$dirname}/composer.json must be valid JSON");
            $this->assertArrayHasKey('name', $data, "Package {$dirname}/composer.json must have a name");
        }
    }

    /**
     * Cross-dependency check: format depends on security, both should load.
     */
    public function test_cross_dependency_format_and_security(): void
    {
        $loaded = $this->app->getLoadedProviders();

        if (class_exists(\MicroweberPackages\Security\SecurityServiceProvider::class)) {
            $this->assertArrayHasKey(
                \MicroweberPackages\Security\SecurityServiceProvider::class,
                $loaded,
                'Security provider must be loaded (format depends on it)'
            );
        }

        if (class_exists(\MicroweberPackages\Format\FormatServiceProvider::class)) {
            $this->assertArrayHasKey(
                \MicroweberPackages\Format\FormatServiceProvider::class,
                $loaded,
                'Format provider must be loaded'
            );
        }
    }

    /**
     * Cross-dependency check: database depends on filesystem.
     */
    public function test_cross_dependency_database_and_filesystem(): void
    {
        $loaded = $this->app->getLoadedProviders();

        // Filesystem must be loaded before database
        $coreProvider = $this->app->getProvider(\MicroweberPackages\Core\CoreServiceProvider::class);
        $list = $coreProvider->getPackageProviders();

        $fsIdx = array_search(\MicroweberPackages\Filesystem\FilesystemServiceProvider::class, $list);
        $dbIdx = array_search(\MicroweberPackages\Database\DatabaseManagerServiceProvider::class, $list);

        if ($fsIdx !== false && $dbIdx !== false) {
            $this->assertLessThan(
                $dbIdx,
                $fsIdx,
                'Filesystem must be loaded before Database in CoreServiceProvider'
            );
        }
    }
}
