<?php

namespace MicroweberPackages\Core\Tests;

use Tests\TestCase;
use MicroweberPackages\Core\CoreServiceProvider;

/**
 * Tests that validate the deterministic loading behaviour that prevents
 * the race condition under php-fpm / php-cgi.
 *
 * The original error was:
 *   "Call to undefined function normalize_path()"
 *   in src/MicroweberPackages/App/functions/paths.php (line 52)
 *
 * This happened because Laravel's auto-discovery cache
 * (bootstrap/cache/packages.php) could be written/read concurrently,
 * causing the `files` autoload for microweber-filesystem/src/helpers.php
 * to not execute before paths.php needed normalize_path().
 */
class DeterministicLoadingTest extends TestCase
{
    /**
     * Auto-discovery is disabled — the packages.php cache should either
     * be empty or not contain our internal packages (they are loaded
     * explicitly by CoreServiceProvider).
     */
    public function test_auto_discovery_is_disabled_in_composer_json(): void
    {
        $composerJson = json_decode(
            file_get_contents(base_path('composer.json')),
            true
        );

        $dontDiscover = $composerJson['extra']['laravel']['dont-discover'] ?? [];

        $this->assertContains(
            '*',
            $dontDiscover,
            'composer.json extra.laravel.dont-discover must contain "*" to disable all auto-discovery'
        );
    }

    /**
     * CoreServiceProvider is registered as the FIRST thing AppServiceProvider
     * does — so every package loads before the rest of the app. It lives in the
     * app's master provider (not directly in bootstrap/providers.php), matching
     * how the former Core\Providers\CoreServiceProvider was wired.
     */
    public function test_core_service_provider_registered_first_by_app_provider(): void
    {
        // bootstrap/providers.php holds the single master provider.
        $providers = require base_path('bootstrap/providers.php');
        $this->assertContains(
            \MicroweberPackages\App\Providers\AppServiceProvider::class,
            $providers,
            'AppServiceProvider must be listed in bootstrap/providers.php'
        );

        // AppServiceProvider::register() must register CoreServiceProvider before
        // any other provider (its first $this->app->register(...) call).
        $src = file_get_contents(base_path('src/MicroweberPackages/App/Providers/AppServiceProvider.php'));
        $registerBody = substr($src, strpos($src, 'function register'));

        $corePos = strpos($registerBody, 'register(CoreServiceProvider::class)');
        $this->assertNotFalse($corePos, 'AppServiceProvider::register() must register CoreServiceProvider');

        $firstRegisterPos = strpos($registerBody, '$this->app->register(');
        $this->assertSame(
            $corePos - strlen('$this->app->'),
            $firstRegisterPos,
            'CoreServiceProvider must be the FIRST $this->app->register(...) call in AppServiceProvider::register()'
        );

        // And it must actually be loaded.
        $this->assertArrayHasKey(
            CoreServiceProvider::class,
            $this->app->getLoadedProviders(),
            'CoreServiceProvider must be loaded'
        );
    }

    /**
     * Helper functions provided by the filesystem package are available
     * before any other package code runs.  This test would fail if we
     * had the old race condition.
     */
    public function test_normalize_path_available_during_boot(): void
    {
        $this->assertTrue(function_exists('normalize_path'));

        // The function should return a string and normalize separators
        $path = normalize_path('/test/path/to/file', true);
        $this->assertIsString($path);
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * The reduce_double_slashes helper must also be available.
     */
    public function test_reduce_double_slashes_available(): void
    {
        $this->assertTrue(function_exists('reduce_double_slashes'));
    }

    /**
     * All critical helper files have been loaded.
     */
    public function test_critical_helper_functions_are_loaded(): void
    {
        // These are the functions that were failing due to the race condition
        $criticalFunctions = [
            'normalize_path',
            'reduce_double_slashes',
        ];

        foreach ($criticalFunctions as $func) {
            $this->assertTrue(
                function_exists($func),
                "Critical function {$func}() must be available after boot"
            );
        }
    }

    /**
     * Providers are registered exactly once (no double-registration).
     */
    public function test_no_duplicate_provider_registration(): void
    {
        $loaded = $this->app->getLoadedProviders();

        // Each provider class should appear exactly once
        // (getLoadedProviders returns class => true)
        foreach ($loaded as $class => $registered) {
            $this->assertTrue(
                $registered,
                "Provider {$class} should be marked as registered"
            );
        }
    }

    /**
     * The package:discover artisan command is NOT in the post-autoload-dump
     * scripts (we don't want auto-discovery running at all).
     */
    public function test_package_discover_not_in_composer_scripts(): void
    {
        $composerJson = json_decode(
            file_get_contents(base_path('composer.json')),
            true
        );

        $postAutoloadDump = $composerJson['scripts']['post-autoload-dump'] ?? [];

        foreach ($postAutoloadDump as $script) {
            $this->assertStringNotContainsString(
                'package:discover',
                $script,
                'package:discover should NOT be in post-autoload-dump scripts'
            );
        }
    }
}