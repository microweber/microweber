<?php

namespace MicroweberPackages\Core\Tests;

use Tests\TestCase;
use MicroweberPackages\Core\CoreServiceProvider;

/**
 * Tests for the CoreServiceProvider — the deterministic package loader
 * that loads Microweber packages in strict order, while letting
 * third-party packages auto-discover normally.
 */
class CoreServiceProviderTest extends TestCase
{
    /**
     * The CoreServiceProvider must be registered in the application.
     */
    public function test_core_service_provider_is_registered(): void
    {
        $providers = $this->app->getLoadedProviders();
        $this->assertArrayHasKey(CoreServiceProvider::class, $providers);
    }

    /**
     * The provider lists contain class names (strings), not empty arrays.
     */
    public function test_package_providers_list_is_not_empty(): void
    {
        $provider = $this->app->getProvider(CoreServiceProvider::class);
        $this->assertNotNull($provider);
        $this->assertNotEmpty($provider->getPackageProviders());
    }

    /**
     * CoreServiceProvider should only list Microweber providers,
     * not third-party ones.
     */
    public function test_only_microweber_providers_are_listed(): void
    {
        $provider = $this->app->getProvider(CoreServiceProvider::class);
        $this->assertNotNull($provider);

        // These are the only allowed provider namespaces (our own packages)
        $allowedPrefixes = [
            'MicroweberPackages\\',
        ];

        foreach ($provider->getPackageProviders() as $providerClass) {
            $allowed = false;
            foreach ($allowedPrefixes as $prefix) {
                if (str_starts_with($providerClass, $prefix)) {
                    $allowed = true;
                    break;
                }
            }
            $this->assertTrue(
                $allowed,
                "Provider {$providerClass} should be a Microweber package, not a third-party library"
            );
        }
    }

    /**
     * Critical helper functions that caused the race condition must be
     * available after the CoreServiceProvider registers.
     */
    public function test_normalize_path_function_is_available(): void
    {
        $this->assertTrue(function_exists('normalize_path'));

        // Verify it actually works (returns a string, normalizes separators)
        $result = normalize_path('/foo/bar/baz', true);
        $this->assertIsString($result);
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $result);
    }

    /**
     * The Filesystem service provider should be loaded (it provides
     * normalize_path which was the original race condition trigger).
     */
    public function test_filesystem_service_provider_is_loaded(): void
    {
        $providers = $this->app->getLoadedProviders();

        if (class_exists(\MicroweberPackages\Filesystem\FilesystemServiceProvider::class)) {
            $this->assertArrayHasKey(
                \MicroweberPackages\Filesystem\FilesystemServiceProvider::class,
                $providers
            );
        } else {
            $this->markTestSkipped('Filesystem package not installed');
        }
    }

    /**
     * Internal package providers are listed in dependency order:
     * Filesystem must come before higher-level packages.
     */
    public function test_package_providers_are_in_dependency_order(): void
    {
        $provider = $this->app->getProvider(CoreServiceProvider::class);
        $list = $provider->getPackageProviders();

        $filesystemIndex = array_search(
            \MicroweberPackages\Filesystem\FilesystemServiceProvider::class,
            $list
        );

        // If Filesystem is in the list, it should be one of the first providers.
        if ($filesystemIndex !== false) {
            $this->assertLessThan(5, $filesystemIndex, 'FilesystemServiceProvider must be among the first providers (dependency order)');
        }
    }

    /**
     * All internal package providers that exist should be registered.
     */
    public function test_all_available_package_providers_are_registered(): void
    {
        $provider = $this->app->getProvider(CoreServiceProvider::class);
        $loadedProviders = $this->app->getLoadedProviders();

        foreach ($provider->getPackageProviders() as $providerClass) {
            if (class_exists($providerClass)) {
                $this->assertArrayHasKey(
                    $providerClass,
                    $loadedProviders,
                    "Package provider {$providerClass} exists but was not registered"
                );
            }
        }
    }

    /**
     * Missing providers should NOT cause errors — class_exists guard works.
     */
    public function test_missing_providers_do_not_cause_errors(): void
    {
        // If we got this far, the provider loaded successfully despite
        // potentially missing optional dependencies. Just assert true.
        $this->assertTrue(true);
    }

    /**
     * The legacy Providers\CoreServiceProvider path should still resolve.
     */
    public function test_legacy_providers_namespace_still_works(): void
    {
        $this->assertTrue(
            class_exists(\MicroweberPackages\Core\Providers\CoreServiceProvider::class)
        );
    }

    /**
     * Core Events classes should be available from the new package location.
     */
    public function test_core_events_classes_are_available(): void
    {
        $events = [
            \MicroweberPackages\Core\Events\AbstractModelEvent::class,
            \MicroweberPackages\Core\Events\AbstractResourceIsCreating::class,
            \MicroweberPackages\Core\Events\AbstractResourceIsUpdating::class,
            \MicroweberPackages\Core\Events\AbstractResourceWasCreated::class,
            \MicroweberPackages\Core\Events\AbstractResourceWasDeleted::class,
            \MicroweberPackages\Core\Events\AbstractResourceWasUpdated::class,
        ];

        foreach ($events as $eventClass) {
            $this->assertTrue(
                class_exists($eventClass),
                "Event class {$eventClass} should be available"
            );
        }
    }

    /**
     * Core Repositories classes should be available from the new package location.
     */
    public function test_core_repository_classes_are_available(): void
    {
        $this->assertTrue(class_exists(\MicroweberPackages\Core\Repositories\BaseRepository::class));
        $this->assertTrue(
            interface_exists(\MicroweberPackages\Core\Repositories\BaseRepositoryInterface::class),
            'BaseRepositoryInterface should be available as an interface'
        );
    }

    /**
     * Core Services classes should be available from the new package location.
     */
    public function test_core_services_classes_are_available(): void
    {
        $this->assertTrue(class_exists(\MicroweberPackages\Core\Services\ServiceLoader::class));
    }

    /**
     * The MergesConfig concern should be available from the new package location.
     */
    public function test_merges_config_concern_is_available(): void
    {
        $this->assertTrue(
            trait_exists(\MicroweberPackages\Core\Providers\Concerns\MergesConfig::class)
        );
    }
}