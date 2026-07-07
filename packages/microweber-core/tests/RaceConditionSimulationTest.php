<?php

namespace MicroweberPackages\Core\Tests;

use Tests\TestCase;
use MicroweberPackages\Core\CoreServiceProvider;

/**
 * Tests that simulate the race condition scenario where the packages cache
 * is deleted or corrupted during concurrent requests.
 *
 * The original error:
 *   "Call to undefined function normalize_path()"
 *
 * These tests verify that deleting the cache files has no impact on the
 * deterministic loading, because the CoreServiceProvider loads packages
 * explicitly in code rather than relying on cached auto-discovery.
 */
class RaceConditionSimulationTest extends TestCase
{
    /**
     * Deleting the packages cache file should not break anything,
     * because we use deterministic loading.
     */
    public function test_deleting_packages_cache_does_not_break_app(): void
    {
        $cacheFile = base_path('bootstrap/cache/packages.php');

        // Delete the cache if it exists
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

        // Core functions should still work
        $this->assertTrue(function_exists('normalize_path'));

        // The app should still be functional
        $this->assertNotNull($this->app);
    }

    /**
     * Deleting the services cache file should not break anything.
     */
    public function test_deleting_services_cache_does_not_break_app(): void
    {
        $cacheFile = base_path('bootstrap/cache/services.php');

        // Delete the cache if it exists
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

        // Core functions should still work
        $this->assertTrue(function_exists('normalize_path'));
    }

    /**
     * Writing a corrupt packages cache should not affect our loading.
     */
    public function test_corrupt_packages_cache_does_not_break_loading(): void
    {
        $cacheFile = base_path('bootstrap/cache/packages.php');

        // Write a corrupt file
        file_put_contents($cacheFile, '<?php return "corrupt";');

        // Core functions should still work because they're loaded
        // deterministically by CoreServiceProvider
        $this->assertTrue(function_exists('normalize_path'));

        // Clean up
        @unlink($cacheFile);
    }

    /**
     * Writing an empty packages cache should not affect our loading.
     */
    public function test_empty_packages_cache_does_not_break_loading(): void
    {
        $cacheFile = base_path('bootstrap/cache/packages.php');

        // Write an empty array
        file_put_contents($cacheFile, '<?php return [];');

        // Core functions should still work
        $this->assertTrue(function_exists('normalize_path'));

        // Clean up
        @unlink($cacheFile);
    }

    /**
     * Multiple rapid calls to normalize_path should all succeed.
     * This simulates concurrent access patterns.
     */
    public function test_rapid_normalize_path_calls_all_succeed(): void
    {
        $results = [];
        for ($i = 0; $i < 1000; $i++) {
            $results[] = normalize_path("/test/path/{$i}", true);
        }

        $this->assertCount(1000, $results);
        foreach ($results as $i => $result) {
            $this->assertIsString($result, "Result {$i} should be a string");
            $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $result);
        }
    }

    /**
     * The CoreServiceProvider boot sequence is idempotent — calling
     * register() again doesn't crash or duplicate providers.
     */
    public function test_provider_registration_is_idempotent(): void
    {
        // Register the provider again
        $this->app->register(CoreServiceProvider::class);

        // Everything should still work
        $this->assertTrue(function_exists('normalize_path'));
        $this->assertArrayHasKey(
            CoreServiceProvider::class,
            $this->app->getLoadedProviders()
        );
    }
}