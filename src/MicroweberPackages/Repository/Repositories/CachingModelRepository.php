<?php

namespace MicroweberPackages\Repository\Repositories;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * CachingModelRepository — thin cache wrapper base class.
 *
 * Repositories extending this class delegate all business logic
 * to Eloquent model static methods and only add a caching layer.
 *
 * Usage:
 *   class CategoryRepository extends CachingModelRepository
 *   {
 *       protected string $modelClass = Category::class;
 *       protected array $cacheTags = ['repositories', 'categories'];
 *
 *       public function tree(int $parentId = 0): array
 *       {
 *           return $this->cached(__FUNCTION__, func_get_args(), function () use ($parentId) {
 *               return Category::queryTree($parentId);
 *           });
 *       }
 *   }
 */
abstract class CachingModelRepository
{
    /**
     * The Eloquent model class this repository wraps.
     */
    protected string $modelClass;

    /**
     * Cache tags used for tagging and flushing.
     * If empty, defaults to ['repositories', <table_name>].
     */
    protected array $cacheTags = [];

    /**
     * Default cache TTL in seconds.
     */
    protected int $cacheTtl = 60000;

    /**
     * In-memory request-scoped cache to avoid redundant cache hits.
     */
    protected static array $memoryCache = [];

    /**
     * Whether cache is currently disabled (e.g. after a write).
     */
    protected static bool $cacheDisabled = false;

    public function __construct()
    {
        $this->registerModelObservers();
    }

    /**
     * Get a new model instance.
     */
    public function getModel(): Model
    {
        return new $this->modelClass;
    }

    /**
     * Get the resolved cache tags for this repository.
     */
    public function getCacheTags(): array
    {
        if (!empty($this->cacheTags)) {
            return $this->cacheTags;
        }

        return ['repositories', $this->getModel()->getTable()];
    }

    /**
     * Execute a query through the cache layer.
     *
     * This is the primary method repositories should use. It handles:
     * - Skipping cache when disabled
     * - In-memory deduplication within the same request
     * - Tagged cache storage via Laravel's cache manager
     *
     * @param string  $key      Cache key identifier (typically __FUNCTION__)
     * @param array   $args     Arguments used to build unique cache key
     * @param Closure $callback The query to execute on cache miss
     * @param int|null $ttl     Optional TTL override in seconds
     * @return mixed
     */
    public function cached(string $key, array $args, Closure $callback, ?int $ttl = null)
    {
        if ($this->isCacheSkipped()) {
            return $callback();
        }

        $tags = $this->getCacheTags();
        $cacheKey = $this->buildCacheKey($key, $args, $tags);
        $memoryKey = implode('-', $tags) . $cacheKey;

        if (isset(static::$memoryCache[$memoryKey])) {
            return static::$memoryCache[$memoryKey];
        }

        $result = app('cache')->tags($tags)->remember(
            $cacheKey,
            $ttl ?? $this->cacheTtl,
            $callback
        );

        static::$memoryCache[$memoryKey] = $result;

        return $result;
    }

    /**
     * Flush all cached data for this repository.
     */
    public function clearCache(): void
    {
        static::$memoryCache = [];
        static::$cacheDisabled = true;

        if (config('repositories.cache_enabled', false)) {
            app('cache')->tags($this->getCacheTags())->flush();
        }
    }

    /**
     * Re-enable the cache after it was disabled by clearCache().
     */
    public static function enableCache(): void
    {
        static::$cacheDisabled = false;
    }

    /**
     * Register Eloquent event listeners that auto-invalidate the cache.
     */
    protected function registerModelObservers(): void
    {
        $repo = $this;

        $this->modelClass::saved(function () use ($repo) {
            $repo->clearCache();
        });

        $this->modelClass::deleted(function () use ($repo) {
            $repo->clearCache();
        });
    }

    /**
     * Determine if cache should be skipped.
     */
    protected function isCacheSkipped(): bool
    {
        if (static::$cacheDisabled) {
            return true;
        }

        if (Config::get('microweber.disable_model_cache')) {
            return true;
        }

        if (config('repositories.cache_enabled', false) === false) {
            return true;
        }

        if (app('request')->has(config('repositories.cache_skip_param', 'skipCache'))) {
            return true;
        }

        return false;
    }

    /**
     * Build a unique cache key from the method name and arguments.
     */
    protected function buildCacheKey(string $method, array $args, array $tags): string
    {
        $hashArgs = [];
        foreach ($args as $k => $a) {
            if ($a instanceof Closure) {
                $hashArgs[$k] = spl_object_id($a);
            } elseif ($a instanceof Model) {
                $hashArgs[$k] = get_class($a) . '|' . $a->getKey();
            } else {
                $hashArgs[$k] = $a;
            }
        }

        return sprintf(
            '%s-%s--%s-%s-%s',
            app()->getLocale(),
            implode('-', $tags),
            $method,
            crc32(json_encode($hashArgs)),
            intval(is_https())
        );
    }
}
