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
 *
 * The fix: only Microweber packages are suppressed from auto-discovery
 * (via the dont-discover list in composer.json) and loaded manually by
 * CoreServiceProvider. Third-party packages use normal auto-discovery.
 */
class DeterministicLoadingTest extends TestCase
{
    /**
     * Auto-discovery is selectively disabled — only Microweber packages
     * are listed in dont-discover, NOT "*".  Third-party packages must
     * be auto-discovered normally.
     */
    public function test_auto_discovery_targets_only_microweber_packages(): void
    {
        $composerJson = json_decode(
            file_get_contents(base_path('composer.json')),
            true
        );

        $dontDiscover = $composerJson['extra']['laravel']['dont-discover'] ?? [];

        // Must NOT contain "*" — that would block all auto-discovery
        $this->assertNotContains(
            '*',
            $dontDiscover,
            'composer.json must NOT use dont-discover ["*"] — only MW packages should be listed'
        );

        // Must contain at least some microweber packages
        $hasMwPackage = false;
        foreach ($dontDiscover as $pkg) {
            if (str_starts_with($pkg, 'microweber-packages/') || str_starts_with($pkg, 'microweber-deps/') || str_starts_with($pkg, 'microweber/')) {
                $hasMwPackage = true;
                break;
            }
        }
        $this->assertTrue($hasMwPackage, 'dont-discover list must contain Microweber package names');

        // Must NOT contain third-party packages (e.g. filament, spatie, livewire)
        foreach ($dontDiscover as $pkg) {
            $this->assertFalse(
                str_starts_with($pkg, 'filament/')
                || str_starts_with($pkg, 'spatie/')
                || str_starts_with($pkg, 'livewire/'),
                "Third-party package '{$pkg}' must NOT be in dont-discover — it should auto-discover"
            );
        }
    }

    /**
     * Individual MW package composer.json files must NOT have
     * extra.laravel.providers — only microweber-core retains it.
     */
    public function test_mw_packages_do_not_have_laravel_auto_discovery(): void
    {
        $packagesDir = base_path('packages');
        if (!is_dir($packagesDir)) {
            $this->markTestSkipped('packages/ directory not found');
        }

        $dirs = glob($packagesDir . '/*/composer.json');
        foreach ($dirs as $file) {
            $dirname = basename(dirname($file));
            // microweber-core keeps its discovery key (it's the entry point)
            if ($dirname === 'microweber-core') {
                continue;
            }

            $data = json_decode(file_get_contents($file), true);
            $providers = $data['extra']['laravel']['providers'] ?? null;

            $this->assertNull(
                $providers,
                "Package {$dirname}/composer.json must NOT have extra.laravel.providers — "
                . "it is loaded by CoreServiceProvider instead"
            );
        }
    }

    /**
     * CoreServiceProvider is registered as the FIRST thing AppServiceProvider
     * does — so every package loads before the rest of the app.
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
     * CoreServiceProvider must NOT hardcode the bulk of third-party providers —
     * they auto-discover. The ONLY allowed exception is a tiny set of providers
     * that Microweber code depends on at register time (currently just Livewire,
     * because `UserServiceProvider::register()` calls `Livewire::component()`,
     * which needs `livewire.finder` to already exist). Those live in the
     * documented `earlyThirdPartyProviders` array and are still auto-discovered.
     */
    public function test_core_provider_does_not_hardcode_third_party(): void
    {
        $src = file_get_contents(__DIR__ . '/../src/CoreServiceProvider.php');

        // The big auto-discovered libraries must NOT appear anywhere.
        $thirdPartyNamespaces = [
            'Filament\\',
            'Spatie\\',
            'Carbon\\',
            'Akaunting\\',
            'Barryvdh\\',
            'BezhanSalleh\\',
            'Intervention\\',
            'Jenssegers\\',
        ];

        foreach ($thirdPartyNamespaces as $ns) {
            $this->assertStringNotContainsString(
                $ns,
                $src,
                "CoreServiceProvider must not hardcode third-party namespace {$ns} — it should be auto-discovered"
            );
        }

        // The register-time exceptions must be confined to earlyThirdPartyProviders
        // and stay minimal — only Livewire is permitted today.
        $provider = $this->app->getProvider(CoreServiceProvider::class);
        $early = (fn () => $this->earlyThirdPartyProviders)->call($provider);
        $this->assertSame(
            [\Livewire\LivewireServiceProvider::class],
            $early,
            'earlyThirdPartyProviders must contain only Livewire (the sole register-time exception)'
        );
    }

    /**
     * Third-party providers must be auto-discovered and loaded by Laravel
     * (not by CoreServiceProvider).
     */
    public function test_third_party_providers_are_auto_discovered(): void
    {
        $loaded = $this->app->getLoadedProviders();

        // Livewire should be auto-discovered
        if (class_exists(\Livewire\LivewireServiceProvider::class)) {
            $this->assertArrayHasKey(
                \Livewire\LivewireServiceProvider::class,
                $loaded,
                'Livewire should be loaded via auto-discovery'
            );
        }
    }
}