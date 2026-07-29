<?php

namespace MicroweberPackages\Core;

use Illuminate\Support\ServiceProvider;

/**
 * CoreServiceProvider — deterministic, ordered loading of Microweber
 * sub-packages only.
 *
 * Why this exists:
 *   Laravel's package auto-discovery reads `vendor/composer/installed.json`,
 *   builds `bootstrap/cache/packages.php` once and caches it.  Under php-fpm /
 *   php-cgi multiple concurrent requests can race while that cache file is
 *   being written (truncated read, partial write, opcache serving a stale
 *   version), which causes "Call to undefined function normalize_path()" and
 *   similar errors because the `files` autoload for
 *   `microweber-filesystem/src/helpers.php` has not been executed yet.
 *
 *   This provider registers every *internal* Microweber package provider in a
 *   fixed, deterministic order, while third-party packages are left to
 *   Laravel's normal auto-discovery mechanism.  The `dont-discover` list in
 *   the root `composer.json` names only Microweber packages (not "*"), so
 *   every third-party library (Filament, Livewire, Spatie, etc.) is discovered
 *   automatically — no need to hardcode them here.
 *
 *   In the Microweber monorepo it is registered as the very first statement of
 *   `AppServiceProvider::register()` (the app's single master provider, and the
 *   only entry in `bootstrap/providers.php`), so every MW package loads before
 *   the rest of the application registers.
 *
 * Usage in a standalone Laravel app:
 *   1. `composer require microweber-packages/core`
 *   2. The CoreServiceProvider auto-discovers via its own `extra.laravel.providers`
 *      key, or register it manually as the first entry of `bootstrap/providers.php`.
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Third-party providers that Microweber code depends on at REGISTER time,
     * so they must be registered before the rest of the app boots.
     *
     * These stay auto-discovered too (they are NOT in the root `dont-discover`
     * list), but auto-discovery registers them only *after* AppServiceProvider —
     * too late for MW providers that call e.g. `Livewire::component()` inside
     * their own `register()` (which needs `livewire.finder` to already exist).
     * Registering them here (idempotently — Laravel de-dupes) fixes the order
     * without hardcoding the ~55 other third-party providers.
     */
    protected array $earlyThirdPartyProviders = [
        \Livewire\LivewireServiceProvider::class,
    ];

    /**
     * Internal Microweber package providers, in dependency order.
     */
    protected array $packageProviders = [
        // ── Layer 0: Foundation helpers (class loader, filesystem, format) ──
        \MicroweberPackages\ClassLoader\ClassLoaderServiceProvider::class,
        \MicroweberPackages\Filesystem\FilesystemServiceProvider::class,
        \MicroweberPackages\Format\FormatServiceProvider::class,
        \MicroweberPackages\Security\SecurityServiceProvider::class,
        \MicroweberPackages\Http\HttpServiceProvider::class,

        // ── Layer 1: Config, caching, env ──
        \MicroweberPackages\TaggableFileCache\TaggableFileCacheServiceProvider::class,
        \MicroweberPackages\EnvWriter\EnvWriterServiceProvider::class,
        \MicroweberPackages\BladeCache\BladeCacheServiceProvider::class,

        // ── Layer 2: Database, repositories, search ──
        \MicroweberPackages\Database\DatabaseManagerServiceProvider::class,
        \MicroweberPackages\Repository\Providers\RepositoryServiceProvider::class,
        \MicroweberPackages\Searchable\SearchableServiceProvider::class,
        \MicroweberPackages\Url\Providers\UrlServiceProvider::class,

        // ── Layer 3: Database migrations / install / export ──
        \MicroweberPackages\DbMigrator\DbMigratorServiceProvider::class,
        \MicroweberPackages\DbInstaller\DbInstallerServiceProvider::class,
        \MicroweberPackages\DbExport\DbExportServiceProvider::class,

        // ── Layer 4: Event system, translation ──
        \MicroweberPackages\Event\EventManagerServiceProvider::class,
        \MicroweberPackages\Translation\Providers\TranslationServiceProvider::class,

        // ── Layer 5: Media / thumbnailer ──
        \MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider::class,

        // ── Layer 6: File uploader ──
        \MicroweberPackages\FileUploader\FileUploaderServiceProvider::class,

        // ── Layer 7: Frontend assets ──
        \MicroweberPackages\FrontendAssets\MicroweberFrontendAssetsServiceProvider::class,
        \MicroweberPackages\FrontendAssetsLibs\MicroweberFrontendAssetsLibsServiceProvider::class,

        // ── Layer 8: CSS framework (publishes assets under the `public` tag) ──
        \MicroweberPackages\CssFrameworkBootstrap\Providers\CssFrameworkServiceProvider::class,

        // ── Layer 9: PhpQuery ──
        \MicroweberPackages\PhpQuery\Providers\PhpQueryServiceProvider::class,

        // ── Layer 10: Filament integration ──
        \MicroweberPackages\FilamentRegistry\FilamentRegistryServiceProvider::class,
        \MicroweberPackages\FilamentModalTeleport\ModalTeleportServiceProvider::class,

        // ── Layer 11: Config (Microweber extended config) ──
        \MicroweberPackages\Config\ConfigServiceProvider::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Third-party providers our own providers depend on at register time
        // (e.g. Livewire) must come first — auto-discovery would load them too
        // late. De-duped by Laravel, so auto-discovery re-registering is a no-op.
        foreach ($this->earlyThirdPartyProviders as $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }

        // Register Microweber internal packages in strict dependency order.
        // The other ~55 third-party packages are auto-discovered by Laravel —
        // they are NOT listed here.  Only our own packages are suppressed from
        // auto-discovery (via the dont-discover list in root composer.json) and
        // loaded manually.
        foreach ($this->packageProviders as $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    /**
     * Boot services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->environment('testing')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/tests.php');
        }
    }

    /**
     * Get the list of internal Microweber package providers.
     *
     * Useful for testing / introspection.
     */
    public function getPackageProviders(): array
    {
        return $this->packageProviders;
    }
}