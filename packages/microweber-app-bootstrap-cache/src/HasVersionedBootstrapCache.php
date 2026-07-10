<?php

namespace MicroweberPackages\AppBootstrapCache;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Str;

/**
 * Trait HasVersionedBootstrapCache
 *
 * Overrides the default Laravel bootstrap cache path methods so that each
 * combination of Laravel version + application version gets its own set of
 * cached files. This prevents stale cache from being loaded after a framework
 * or application upgrade.
 *
 * Usage: `use HasVersionedBootstrapCache;` in a class that extends
 * `Illuminate\Foundation\Application`.
 *
 * Optionally set `APP_VERSION` constant or override `getAppVersion()` to
 * include a custom application version in the cache prefix.
 */
trait HasVersionedBootstrapCache
{
    /**
     * Build a version prefix slug from the Laravel framework version
     * and an optional application version.
     *
     * @return string
     */
    public function getBootstrapCacheVersionPrefix(): string
    {
        $parts = $this->getLaravelVersion();

        $appVersion = $this->getAppVersion();
        if ($appVersion !== null && $appVersion !== '') {
            $parts .= '_' . $appVersion;
        }

        return Str::slug($parts, '_');
    }

    /**
     * Return the Laravel framework version string.
     *
     * @return string
     */
    public function getLaravelVersion(): string
    {
        return $this->version();
    }

    /**
     * Return the application-level version, if any.
     *
     * Override this method or define an `APP_VERSION` constant on the
     * consuming class to include a custom version in the cache prefix.
     *
     * @return string|null
     */
    public function getAppVersion(): ?string
    {
        if (defined('static::APP_VERSION')) {
            return static::APP_VERSION;
        }

        return null;
    }

    /**
     * Build a versioned cache filename.
     *
     * @param string $name  Logical cache name, e.g. "services", "config"
     * @return string       Filename like "cache_11_54_0_myapp_1_0_services.php"
     */
    public function buildVersionedCacheFileName(string $name): string
    {
        return 'cache_' . $this->getBootstrapCacheVersionPrefix() . '_' . $name . '.php';
    }

    /**
     * Build a versioned bootstrap-relative cache path.
     *
     * @param string $name  Logical cache name, e.g. "services", "config"
     * @return string       Path like "cache/cache_11_54_0_services.php"
     */
    public function buildVersionedCachePath(string $name): string
    {
        return 'cache/' . $this->buildVersionedCacheFileName($name);
    }

    // ---------------------------------------------------------------
    // Override the Laravel Application cache path methods
    // ---------------------------------------------------------------

    /**
     * Get the path to the cached services.php file.
     *
     * @return string
     */
    public function getCachedServicesPath(): string
    {
        return $this->normalizeCachePath(
            'APP_SERVICES_CACHE',
            $this->buildVersionedCachePath('services')
        );
    }

    /**
     * Get the path to the cached packages.php file.
     *
     * @return string
     */
    public function getCachedPackagesPath(): string
    {
        return $this->normalizeCachePath(
            'APP_PACKAGES_CACHE',
            $this->buildVersionedCachePath('packages')
        );
    }

    /**
     * Get the path to the cached config.php file.
     *
     * @return string
     */
    public function getCachedConfigPath(): string
    {
        return $this->normalizeCachePath(
            'APP_CONFIG_CACHE',
            $this->buildVersionedCachePath('config')
        );
    }

    /**
     * Get the path to the cached routes file.
     *
     * @return string
     */
    public function getCachedRoutesPath(): string
    {
        return $this->normalizeCachePath(
            'APP_ROUTES_CACHE',
            $this->buildVersionedCachePath('routes')
        );
    }

    /**
     * Get the path to the cached events file.
     *
     * @return string
     */
    public function getCachedEventsPath(): string
    {
        return $this->normalizeCachePath(
            'APP_EVENTS_CACHE',
            $this->buildVersionedCachePath('events')
        );
    }

    /**
     * Ensure the bootstrap/cache directory exists.
     *
     * NOTE: this resolves the path via $this->basePath(), so it is only correct
     * AFTER the application's base path has been set (i.e. after
     * Application::__construct()/setBasePath()). Do NOT call it from a subclass
     * constructor *before* parent::__construct() — at that point basePath() is
     * empty and it would target "/bootstrap". If you need it that early, create
     * the directory from your own known base path instead.
     *
     * @return void
     */
    public function ensureBootstrapCacheDirectoryExists(): void
    {
        $bootstrapDir = $this->basePath('bootstrap');
        $cacheDir = $bootstrapDir . DIRECTORY_SEPARATOR . 'cache';

        if (is_dir($bootstrapDir) && !is_dir($cacheDir) && !is_link($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
    }

    /**
     * Register Laravel's default facade class aliases (Route, DB, Schema, …)
     * on the global AliasLoader.
     *
     * Laravel 11's RegisterFacades bootstrapper only registers aliases from
     * config('app.aliases') + package aliases — the framework ships no default
     * aliases. An app that uses bare-aliased facades (e.g. `Route::` in route
     * files, `DB::` in providers) therefore needs them registered somewhere.
     *
     * Calling this from the Application constructor seeds the AliasLoader
     * singleton before the kernel bootstraps; RegisterFacades later merges its
     * own aliases on top (AliasLoader::getInstance() merges), so the defaults
     * survive. This makes bare facades resolve for BOTH normal and
     * config-cached boots — without needing an 'aliases' key in config/app.php,
     * which is fragile (a cached config that omits it white-screens with
     * "Class Route not found" during provider registration).
     *
     * @return void
     */
    public function registerDefaultFacadeAliases(): void
    {
        AliasLoader::getInstance(Facade::defaultAliases()->toArray())->register();
    }
}