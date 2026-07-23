<?php

namespace MicroweberPackages\MediaThumbnail\Repositories;

use Illuminate\Support\Facades\Cache;
use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail;

/**
 * Thin caching repository for MediaThumbnail lookups.
 *
 * Uses tagged cache (tags: media, media_thumbnails) when the driver
 * supports it, and falls back to a prefixed Cache::remember otherwise.
 */
class MediaThumbnailRepository
{
    protected string $cachePrefix = 'media_tn:';

    /** @var int Cache TTL in seconds (1 hour). */
    protected int $cacheTtl = 3600;

    /** @var list<string> Cache tags matching the model's $cacheTagsToClear */
    protected array $cacheTags = ['media', 'media_thumbnails'];

    /**
     * Look up a cached thumbnail row by its filename (cache key).
     *
     * @return array<string, mixed>|null
     */
    public function findByFilename(string $filename): ?array
    {
        $key = $this->cachePrefix . $filename;

        return $this->cacheStore()->remember(
            $key,
            $this->cacheTtl,
            fn () => MediaThumbnail::findByFilename($filename)
        );
    }

    /**
     * Look up a cached thumbnail row by its UUID.
     */
    public function findByUuid(string $uuid): ?MediaThumbnail
    {
        return MediaThumbnail::where('uuid', $uuid)->first();
    }

    /**
     * Store a new thumbnail cache entry.
     *
     * @param array<string, mixed> $imageOptions
     */
    public function store(string $filename, array $imageOptions): MediaThumbnail
    {
        $model = new MediaThumbnail();
        $model->filename = $filename;
        $model->image_options = $imageOptions;
        $model->save();

        // Bust the read cache
        $this->forgetCacheKey($filename);

        return $model;
    }

    /**
     * Remove thumbnail cache entries by filename.
     */
    public function removeByFilename(string $filename): int
    {
        $this->forgetCacheKey($filename);

        return MediaThumbnail::removeByFilename($filename);
    }

    /**
     * Flush all thumbnail cache entries older than the given date.
     */
    public function pruneOlderThan(\DateTimeInterface $before): int
    {
        /** @var int $count */
        $count = MediaThumbnail::where('created_at', '<', $before)->delete();

        // Flush the tag group so stale rows are not served
        $this->flushCacheTags();

        return $count;
    }

    /**
     * Get a cache store with tags when the driver supports it.
     *
     * @return \Illuminate\Contracts\Cache\Repository|\Illuminate\Cache\Repository
     */
    protected function cacheStore()
    {
        $store = Cache::getStore();

        if (method_exists($store, 'tags')) {
            try {
                return Cache::tags($this->cacheTags);
            } catch (\BadMethodCallException $e) {
                // Driver does not actually support tagging
            }
        }

        return Cache::store();
    }

    /**
     * Forget a single cache key (tag-aware).
     */
    protected function forgetCacheKey(string $filename): void
    {
        $key = $this->cachePrefix . $filename;

        $store = Cache::getStore();
        if (method_exists($store, 'tags')) {
            try {
                Cache::tags($this->cacheTags)->forget($key);
                return;
            } catch (\BadMethodCallException $e) {
                // fall through
            }
        }

        Cache::forget($key);
    }

    /**
     * Flush all entries for the cache tags.
     */
    protected function flushCacheTags(): void
    {
        $store = Cache::getStore();
        if (method_exists($store, 'tags')) {
            try {
                Cache::tags($this->cacheTags)->flush();
                return;
            } catch (\BadMethodCallException $e) {
                // fall through
            }
        }
    }
}