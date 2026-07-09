<?php

namespace MicroweberPackages\AppBootstrapCache;

use Illuminate\Support\Str;

/**
 * Standalone helper for computing versioned bootstrap cache paths.
 *
 * Use this when you do not have a custom Application subclass and need to
 * compute versioned cache file names outside the Application context.
 */
class VersionedBootstrapCacheHelper
{
    private string $laravelVersion;
    private ?string $appVersion;

    public function __construct(string $laravelVersion, ?string $appVersion = null)
    {
        $this->laravelVersion = $laravelVersion;
        $this->appVersion = $appVersion;
    }

    /**
     * Get the version prefix slug.
     *
     * @return string
     */
    public function getVersionPrefix(): string
    {
        $parts = $this->laravelVersion;

        if ($this->appVersion !== null && $this->appVersion !== '') {
            $parts .= '_' . $this->appVersion;
        }

        return Str::slug($parts, '_');
    }

    /**
     * Get a versioned cache file name.
     *
     * @param string $name  Logical cache name, e.g. "services", "config"
     * @return string
     */
    public function getCacheFileName(string $name): string
    {
        return 'cache_' . $this->getVersionPrefix() . '_' . $name . '.php';
    }

    /**
     * Get a versioned bootstrap-relative cache path.
     *
     * @param string $name  Logical cache name, e.g. "services", "config"
     * @return string
     */
    public function getCachePath(string $name): string
    {
        return 'cache/' . $this->getCacheFileName($name);
    }

    /**
     * @return string
     */
    public function getLaravelVersion(): string
    {
        return $this->laravelVersion;
    }

    /**
     * @return string|null
     */
    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }
}