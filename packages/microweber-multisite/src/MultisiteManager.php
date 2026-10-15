<?php

declare(strict_types=1);

namespace MicroweberPackages\Multisite;

/**
 * Resolves the current site from the request host (or APP_ENV in CLI) and
 * applies per-site isolation for cache, sessions and media/userfiles.
 *
 * A "site" is a domain that has a {config}/{domain}/microweber.php marker
 * file — the exact same contract Laravel's environment detector uses in
 * {@see \MicroweberPackages\App\Providers\AppServiceProvider}. When such a
 * domain is detected the app boots with that domain's own database (via
 * .env.{domain}), cache store, session store and userfiles folder, so a
 * single checkout of the codebase can serve N independent sites.
 *
 * The static helpers ({@see detectSiteFromHost()}, {@see bootstrapConstants()})
 * carry no Laravel dependencies on purpose: they run at composer-autoload time
 * — before the framework boots — to define MW_USERFILES_FOLDER_NAME ahead of
 * Microweber's own bootstrap.php. The instance methods are the Laravel-aware
 * half and drive config() overrides from a service provider.
 *
 * The package works standalone in a clean Laravel app: nothing here requires
 * Microweber core. The MW_USERFILES_FOLDER_NAME define is simply inert when
 * Microweber is absent.
 */
class MultisiteManager
{
    protected ?string $resolvedSite = null;

    protected bool $resolved = false;

    /** Runtime config (merged from config/multisite.php when booted in Laravel). */
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config + self::defaultConfig();
    }

    /**
     * Baseline config so the manager is usable before/without a Laravel
     * container (e.g. from the autoload-time bootstrap).
     */
    public static function defaultConfig(): array
    {
        return [
            'enabled' => true,
            'config_path' => null,
            'isolate' => [
                'cache' => true,
                'session' => true,
                'userfiles' => true,
                'public_disk' => false,
            ],
            'paths' => [
                'userfiles_base' => 'userfiles',
                'cache_base' => 'framework/cache',
                'sessions_base' => 'framework/sessions',
                'sites_segment' => 'sites',
            ],
        ];
    }

    // -----------------------------------------------------------------
    // Pure detection (safe to call at autoload time — no Laravel deps)
    // -----------------------------------------------------------------

    /**
     * Resolve a site slug from a raw host header, or null when the host does
     * not map to a configured site. Strips `www.` and any `:port`, lowercases,
     * then requires {configPath}/{host}/microweber.php to exist.
     */
    public static function detectSiteFromHost(?string $host, string $configPath): ?string
    {
        $host = self::normalizeHost($host);
        if ($host === null) {
            return null;
        }

        $dir = rtrim($configPath, '/\\') . DIRECTORY_SEPARATOR . $host;
        if (is_dir($dir) && is_file($dir . DIRECTORY_SEPARATOR . 'microweber.php')) {
            return $host;
        }

        return null;
    }

    /**
     * Normalize a host: drop port, drop a leading `www.`, lowercase, trim.
     * Returns null for empty/invalid input. Rejects path traversal so a
     * crafted Host header can never escape the config directory.
     */
    public static function normalizeHost(?string $host): ?string
    {
        if (!$host) {
            return null;
        }

        if (str_contains($host, ':')) {
            $host = explode(':', $host, 2)[0];
        }

        $host = strtolower(trim(str_ireplace('www.', '', $host)));

        if ($host === '' || str_contains($host, '/') || str_contains($host, '\\') || str_contains($host, '..')) {
            return null;
        }

        return $host;
    }

    /**
     * The host for the current request, or null in CLI. Falls back to
     * APP_ENV so `APP_ENV=domain-a.com php artisan …` scopes the CLI too.
     */
    public static function currentHost(): ?string
    {
        if (!empty($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }
        if (!empty($_SERVER['SERVER_NAME'])) {
            return $_SERVER['SERVER_NAME'];
        }

        $env = getenv('APP_ENV') ?: ($_ENV['APP_ENV'] ?? null);

        return $env ?: null;
    }

    /**
     * Walk up from this file to find the Laravel application root (the nearest
     * ancestor directory that contains config/app.php). Works both as an
     * in-repo path package and when installed under vendor/. Returns null if
     * the root can't be located.
     */
    public static function discoverBasePath(): ?string
    {
        $dir = __DIR__;
        for ($i = 0; $i < 10; $i++) {
            if (is_file($dir . '/config/app.php') || is_file($dir . '/artisan')) {
                return $dir;
            }
            $parent = dirname($dir);
            if ($parent === $dir) {
                break;
            }
            $dir = $parent;
        }

        return null;
    }

    /**
     * Autoload-time entrypoint: resolve the current site from the environment
     * and define MW_USERFILES_FOLDER_NAME = "userfiles/{site}" before Microweber's
     * bootstrap.php (which guards the define with `if (!defined(...))`) runs.
     *
     * No-op when: not a web/scoped CLI request, no matching site, or the
     * constant was already defined. Never throws — a failure here must not
     * break autoloading.
     */
    public static function bootstrapConstants(): ?string
    {
        if (defined('MW_USERFILES_FOLDER_NAME')) {
            return null;
        }

        try {
            $base = self::discoverBasePath();
            if ($base === null) {
                return null;
            }

            $site = self::detectSiteFromHost(self::currentHost(), $base . DIRECTORY_SEPARATOR . 'config');
            if ($site === null) {
                return null;
            }

            // For WEB requests, export APP_ENV=<domain> now — this file runs as a
            // composer autoload-file, i.e. before the framework's
            // LoadEnvironmentVariables bootstrapper. That bootstrapper decides
            // which env file to load from APP_ENV, so this is what makes Laravel
            // load .env.<domain> and thus the per-site database / credentials.
            // Laravel only switches when .env.<domain> actually exists (else it
            // falls back to .env), so a domain without a per-site env is safe.
            // CLI is left alone — its env file is already resolved from the
            // APP_ENV the operator passed (e.g. `APP_ENV=domain-a.com php artisan`).
            if ((!empty($_SERVER['HTTP_HOST']) || !empty($_SERVER['SERVER_NAME']))
                && getenv('APP_ENV') !== $site) {
                putenv('APP_ENV=' . $site);
                $_ENV['APP_ENV'] = $site;
                $_SERVER['APP_ENV'] = $site;
            }

            $defaults = self::defaultConfig();
            if (!($defaults['isolate']['userfiles'] ?? true)) {
                return $site;
            }

            define('MW_USERFILES_FOLDER_NAME', $defaults['paths']['userfiles_base'] . '/' . $site);

            // Expose the early decision so the service provider can reuse it
            // without re-reading $_SERVER (and stay consistent with autoload time).
            $_SERVER['MW_MULTISITE_SITE'] = $site;

            return $site;
        } catch (\Throwable $e) {
            return null;
        }
    }

    // -----------------------------------------------------------------
    // Laravel-aware instance API
    // -----------------------------------------------------------------

    public function configBasePath(): string
    {
        $configured = $this->config['config_path'] ?? null;
        if ($configured) {
            return $configured;
        }
        if (function_exists('config_path')) {
            return config_path();
        }

        return (self::discoverBasePath() ?? getcwd()) . DIRECTORY_SEPARATOR . 'config';
    }

    /** The resolved site slug for this request, or null for the default (single) site. */
    public function current(): ?string
    {
        if ($this->resolved) {
            return $this->resolvedSite;
        }
        $this->resolved = true;

        if (!($this->config['enabled'] ?? true)) {
            return $this->resolvedSite = null;
        }

        // Reuse the autoload-time decision when present, else resolve now.
        if (!empty($_SERVER['MW_MULTISITE_SITE'])) {
            return $this->resolvedSite = $_SERVER['MW_MULTISITE_SITE'];
        }

        return $this->resolvedSite = self::detectSiteFromHost(self::currentHost(), $this->configBasePath());
    }

    public function isActive(): bool
    {
        return $this->current() !== null;
    }

    /** Force a specific site (used by CLI commands / tests). */
    public function setSite(?string $site): self
    {
        $this->resolved = true;
        $this->resolvedSite = $site;

        return $this;
    }

    /** All configured sites (directories under config/ holding a microweber.php marker). */
    public function sites(): array
    {
        $base = $this->configBasePath();
        $out = [];
        foreach (glob($base . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR) ?: [] as $dir) {
            if (is_file($dir . DIRECTORY_SEPARATOR . 'microweber.php')) {
                $out[] = basename($dir);
            }
        }
        sort($out);

        return $out;
    }

    // ---- per-site path builders (null site => shared default paths) ----

    protected function sitesSegment(?string $site): string
    {
        $site = $site ?? $this->current();
        if (!$site) {
            return '';
        }

        return ($this->config['paths']['sites_segment'] ?? 'sites') . DIRECTORY_SEPARATOR . $site;
    }

    /** Folder (relative to storage/app/public) that holds this site's userfiles/media. */
    public function userfilesFolder(?string $site = null): string
    {
        $site = $site ?? $this->current();
        $base = $this->config['paths']['userfiles_base'] ?? 'userfiles';

        return $site ? $base . '/' . $site : $base;
    }

    public function cachePath(?string $site = null): string
    {
        return $this->storagePath(($this->config['paths']['cache_base'] ?? 'framework/cache'), $site);
    }

    public function sessionPath(?string $site = null): string
    {
        return $this->storagePath(($this->config['paths']['sessions_base'] ?? 'framework/sessions'), $site);
    }

    protected function storagePath(string $relBase, ?string $site): string
    {
        $segment = $this->sitesSegment($site);
        $rel = $relBase . ($segment ? DIRECTORY_SEPARATOR . $segment : '');

        if (function_exists('storage_path')) {
            return storage_path($rel);
        }

        return (self::discoverBasePath() ?? getcwd()) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $rel;
    }

    public function cachePrefix(?string $site = null): string
    {
        $site = $site ?? $this->current();
        if (!$site) {
            return function_exists('config') ? (string) config('cache.prefix') : '';
        }

        return 'mw_' . self::slug($site) . '_cache';
    }

    /**
     * A filesystem/identifier-safe slug for a domain: every run of
     * non-alphanumerics collapses to a single underscore (domain-a.com →
     * domain_a_com). Consistent across cache prefix, session cookie and the
     * install command's env keys.
     */
    public static function slug(string $site): string
    {
        return trim(preg_replace('/[^a-z0-9]+/', '_', strtolower($site)), '_');
    }

    public function sessionCookie(?string $site = null): string
    {
        $site = $site ?? $this->current();
        if (!$site) {
            return function_exists('config') ? (string) config('session.cookie') : 'laravel_session';
        }

        return 'mw_' . self::slug($site) . '_session';
    }

    /**
     * Apply per-site isolation to the live Laravel config. Safe to call every
     * request; a no-op when no site is active. Each subsystem is gated by the
     * `isolate.*` config so a clean-Laravel PoC can opt into exactly what it needs.
     */
    public function applyIsolation(?string $site = null): void
    {
        $site = $site ?? $this->current();
        if (!$site || !function_exists('config')) {
            return;
        }

        $isolate = $this->config['isolate'] ?? [];

        if ($isolate['cache'] ?? true) {
            $cacheData = $this->cachePath($site) . DIRECTORY_SEPARATOR . 'data';
            config([
                'cache.prefix' => $this->cachePrefix($site),
                'cache.stores.file.path' => $cacheData,
                'cache.stores.file.lock_path' => $cacheData,
            ]);
        }

        if ($isolate['session'] ?? true) {
            config([
                'session.cookie' => $this->sessionCookie($site),
                'session.files' => $this->sessionPath($site),
            ]);
        }

        // Clean-Laravel media isolation: repoint the "public" disk at a per-site
        // subfolder. In Microweber this is handled instead by the
        // MW_USERFILES_FOLDER_NAME constant (set at autoload time), so leave the
        // shared public disk untouched there.
        if (($isolate['public_disk'] ?? false) && !defined('MW_USERFILES_FOLDER_NAME')) {
            $root = (function_exists('storage_path') ? storage_path('app/public') : 'storage/app/public')
                . DIRECTORY_SEPARATOR . $this->userfilesFolder($site);
            config(['filesystems.disks.public.root' => $root]);
        }

        $this->ensureDirectories($site);
    }

    /** Create this site's cache/session directories if missing (best effort). */
    public function ensureDirectories(?string $site = null): void
    {
        $site = $site ?? $this->current();
        if (!$site) {
            return;
        }

        $dirs = [];
        $isolate = $this->config['isolate'] ?? [];
        if ($isolate['cache'] ?? true) {
            $dirs[] = $this->cachePath($site) . DIRECTORY_SEPARATOR . 'data';
        }
        if ($isolate['session'] ?? true) {
            $dirs[] = $this->sessionPath($site);
        }

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
        }
    }
}
