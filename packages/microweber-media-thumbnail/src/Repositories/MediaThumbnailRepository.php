<?php

namespace MicroweberPackages\MediaThumbnail\Repositories;

use Illuminate\Support\Facades\Cache;
use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail;

/**
 * Thin caching repository for MediaThumbnail lookups.
 *
 * Standalone — works with any Laravel cache driver.
 */
class MediaThumbnailRepository
{
    protected string $cachePrefix = 'media_tn:';

    /** @var int Cache TTL in seconds (1 hour). */
    protected int $cacheTtl = 3600;

    /**
     * Look up a cached thumbnail row by its filename (cache key).
     *
     * @return array<string, mixed>|null
     */
    public function findByFilename(string $filename): ?array
    {
        return Cache::remember(
            $this->cachePrefix . $filename,
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
        Cache::forget($this->cachePrefix . $filename);

        return $model;
    }

    /**
     * Remove thumbnail cache entries by filename.
     */
    public function removeByFilename(string $filename): int
    {
        Cache::forget($this->cachePrefix . $filename);

        return MediaThumbnail::removeByFilename($filename);
    }

    /**
     * Flush all thumbnail cache entries older than the given date.
     */
    public function pruneOlderThan(\DateTimeInterface $before): int
    {
        /** @var int $count */
        $count = MediaThumbnail::where('created_at', '<', $before)->delete();

        return $count;
    }
}