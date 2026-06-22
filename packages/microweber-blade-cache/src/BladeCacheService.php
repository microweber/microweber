<?php

declare(strict_types=1);

namespace MicroweberPackages\BladeCache;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

class BladeCacheService
{
    protected bool $enabled;

    protected int $defaultTtl;

    protected ?string $store;

    public function __construct()
    {
        $this->enabled = (bool) config('blade-cache.enabled', true);
        $this->defaultTtl = (int) config('blade-cache.ttl', 3600);
        $this->store = config('blade-cache.store');
    }

    /**
     * Check if blade caching is enabled.
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Get the default TTL.
     */
    public function getDefaultTtl(): int
    {
        return $this->defaultTtl;
    }

    /**
     * Resolve the cache repository (with tag support).
     */
    protected function cacheStore(): Repository
    {
        return Cache::store($this->store);
    }

    /**
     * Get cached content for a key + tags combination.
     *
     * @return string|null The cached HTML or null on miss.
     */
    public function get(string $key, array $tags = []): ?string
    {
        if (! $this->enabled) {
            return null;
        }

        try {
            $tags = $this->normalizeTags($tags);

            return $this->cacheStore()->tags($tags)->get($key);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Store HTML content.
     */
    public function put(string $key, string $content, array $tags = [], ?int $ttl = null): bool
    {
        if (! $this->enabled) {
            return false;
        }

        try {
            $tags = $this->normalizeTags($tags);
            $ttl = $ttl ?? $this->defaultTtl;

            return $this->cacheStore()->tags($tags)->put($key, $content, $ttl);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Flush all entries that carry the given tags.
     */
    public function flush(array $tags = []): bool
    {
        try {
            $tags = $this->normalizeTags($tags);

            return $this->cacheStore()->tags($tags)->flush();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Forget a single key within tags.
     */
    public function forget(string $key, array $tags = []): bool
    {
        try {
            $tags = $this->normalizeTags($tags);

            return $this->cacheStore()->tags($tags)->forget($key);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Ensure tags always include the base "blade-cache" tag.
     */
    protected function normalizeTags(array $tags): array
    {
        if (! in_array('blade-cache', $tags, true)) {
            array_unshift($tags, 'blade-cache');
        }

        return $tags;
    }
}