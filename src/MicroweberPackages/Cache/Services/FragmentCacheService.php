<?php

declare(strict_types=1);

namespace MicroweberPackages\Cache\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Fragment Cache Service
 * 
 * Provides fragment/partials caching for specific sections of a page.
 * Useful for caching expensive-to-render components like:
 * - Navigation menus
 * - Product listings
 * - Category trees
 * - Widget outputs
 * - Module content
 * 
 * @package MicroweberPackages\Cache\Services
 */
class FragmentCacheService
{
    /**
     * Default cache TTL in seconds
     */
    protected int $defaultTtl;

    /**
     * Whether fragment caching is enabled
     */
    protected bool $enabled;

    /**
     * Cache statistics
     */
    protected array $stats = [
        'hits' => 0,
        'misses' => 0,
        'writes' => 0,
        'deletes' => 0,
    ];

    /**
     * Currently active cache keys for this request
     */
    protected array $activeKeys = [];

    public function __construct()
    {
        $this->enabled = config('cache.fragment.enabled', true);
        $this->defaultTtl = config('cache.fragment.ttl', 3600);
    }

    /**
     * Check if fragment caching is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled && $this->isCacheDriverSupported();
    }

    /**
     * Check if current cache driver supports tagging
     */
    protected function isCacheDriverSupported(): bool
    {
        $driver = config('cache.default');
        return in_array($driver, ['redis', 'memcached', 'array'], true);
    }

    /**
     * Get cached fragment content
     * 
     * @param string $key Cache key
     * @param array $tags Cache tags for invalidation
     * @param int|null $ttl Time to live in seconds
     * @return array|null ['content' => string, 'metadata' => array] or null if not cached
     */
    public function get(string $key, array $tags = [], ?int $ttl = null): ?array
    {
        if (!$this->isEnabled()) {
            $this->stats['misses']++;
            return null;
        }

        $tags = $this->normalizeTags($tags);

        try {
            $cached = Cache::tags($tags)->get($key);
            
            if ($cached !== null && isset($cached['content'])) {
                $this->stats['hits']++;
                $this->activeKeys[$key] = true;
                
                if (config('app.debug')) {
                    Log::debug('Fragment cache hit', ['key' => $key, 'tags' => $tags]);
                }
                
                return $cached;
            }
            
            $this->stats['misses']++;
        } catch (\Exception $e) {
            Log::warning('Fragment cache get failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Store fragment content in cache
     * 
     * @param string $key Cache key
     * @param string $content Content to cache
     * @param array $tags Cache tags for invalidation
     * @param int|null $ttl Time to live in seconds
     * @param array $metadata Additional metadata to store
     * @return bool
     */
    public function store(
        string $key,
        string $content,
        array $tags = [],
        ?int $ttl = null,
        array $metadata = []
    ): bool {
        if (!$this->isEnabled()) {
            return false;
        }

        $tags = $this->normalizeTags($tags);
        $ttl = $ttl ?? $this->defaultTtl;

        try {
            $data = [
                'content' => $content,
                'created_at' => now()->toIso8601String(),
                'expires_at' => now()->addSeconds($ttl)->toIso8601String(),
                'key' => $key,
                'tags' => $tags,
                'metadata' => $metadata,
            ];

            Cache::tags($tags)->put($key, $data, $ttl);
            $this->stats['writes']++;
            $this->activeKeys[$key] = true;

            if (config('app.debug')) {
                Log::debug('Fragment cache stored', ['key' => $key, 'ttl' => $ttl, 'tags' => $tags]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Fragment cache store failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Remember fragment - get from cache or compute and store
     * 
     * @param string $key Cache key
     * @param array $tags Cache tags for invalidation
     * @param int|null $ttl Time to live in seconds
     * @param callable $callback Function to compute content if not cached
     * @return string The cached or computed content
     */
    public function remember(string $key, array $tags, ?int $ttl, callable $callback): string
    {
        $cached = $this->get($key, $tags, $ttl);
        
        if ($cached !== null) {
            return $cached['content'];
        }

        $content = $callback();
        
        if (is_string($content)) {
            $this->store($key, $content, $tags, $ttl);
        }

        return $content;
    }

    /**
     * Delete cached fragment
     * 
     * @param string $key Cache key
     * @param array $tags Cache tags
     * @return bool
     */
    public function delete(string $key, array $tags = []): bool
    {
        try {
            $tags = $this->normalizeTags($tags);
            Cache::tags($tags)->forget($key);
            $this->stats['deletes']++;
            unset($this->activeKeys[$key]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Fragment cache delete failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Clear all fragments by tags
     * 
     * @param array|string $tags Tags to flush
     * @return bool
     */
    public function clear($tags): bool
    {
        try {
            $tags = $this->normalizeTags($tags);
            Cache::tags($tags)->flush();
            
            $this->stats['deletes']++;
            
            Log::info('Fragment cache cleared', ['tags' => $tags]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Fragment cache clear failed', [
                'tags' => $tags,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Clear all fragment caches
     * 
     * @return bool
     */
    public function clearAll(): bool
    {
        return $this->clear(['fragment']);
    }

    /**
     * Invalidate fragments by content type
     * 
     * @param string $type Content type (e.g., 'menu', 'product', 'category')
     * @param int|null $id Specific content ID
     * @return bool
     */
    public function invalidateByType(string $type, ?int $id = null): bool
    {
        try {
            $tags = ['fragment', "type:{$type}"];
            
            if ($id !== null) {
                $tags[] = "{$type}:{$id}";
            }
            
            $this->clear($tags);
            
            Log::info('Fragment cache invalidated by type', [
                'type' => $type,
                'id' => $id,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Fragment cache invalidation by type failed', [
                'type' => $type,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get cache key for a fragment
     * 
     * @param string $type Fragment type (e.g., 'menu', 'module')
     * @param string|int $identifier Unique identifier
     * @param array $params Additional parameters that affect the content
     * @return string
     */
    public function getCacheKey(string $type, $identifier, array $params = []): string
    {
        $key = "fragment_{$type}_{$identifier}";
        
        if (!empty($params)) {
            $key .= '_' . md5(serialize($params));
        }
        
        // Add locale
        $key .= '_' . app()->getLocale();
        
        return $key;
    }

    /**
     * Generate tags for a fragment
     * 
     * @param string $type Fragment type
     * @param string|int|null $identifier Unique identifier
     * @param array $additionalTags Additional tags
     * @return array
     */
    public function getTags(string $type, $identifier = null, array $additionalTags = []): array
    {
        $tags = ['fragment', "type:{$type}"];
        
        if ($identifier !== null) {
            $tags[] = "{$type}:{$identifier}";
        }
        
        // Add locale tag
        $tags[] = 'lang:' . app()->getLocale();
        
        return array_merge($tags, $additionalTags);
    }

    /**
     * Check if a fragment is cached
     * 
     * @param string $key Cache key
     * @param array $tags Cache tags
     * @return bool
     */
    public function has(string $key, array $tags = []): bool
    {
        return $this->get($key, $tags) !== null;
    }

    /**
     * Touch a cached fragment to extend its expiration
     * 
     * @param string $key Cache key
     * @param array $tags Cache tags
     * @param int|null $ttl New TTL (null = use default)
     * @return bool
     */
    public function touch(string $key, array $tags = [], ?int $ttl = null): bool
    {
        $cached = $this->get($key, $tags);
        
        if ($cached === null) {
            return false;
        }
        
        return $this->store(
            $key,
            $cached['content'],
            $tags,
            $ttl,
            $cached['metadata'] ?? []
        );
    }

    /**
     * Get cache statistics
     * 
     * @return array
     */
    public function getStats(): array
    {
        return array_merge($this->stats, [
            'enabled' => $this->isEnabled(),
            'driver' => config('cache.default'),
            'ttl' => $this->defaultTtl,
            'active_keys' => count($this->activeKeys),
        ]);
    }

    /**
     * Reset cache statistics
     */
    public function resetStats(): void
    {
        $this->stats = [
            'hits' => 0,
            'misses' => 0,
            'writes' => 0,
            'deletes' => 0,
        ];
    }

    /**
     * Get active cache keys
     * 
     * @return array
     */
    public function getActiveKeys(): array
    {
        return array_keys($this->activeKeys);
    }

    /**
     * Clear active keys tracking
     */
    public function clearActiveKeys(): void
    {
        $this->activeKeys = [];
    }

    /**
     * Normalize tags to array
     * 
     * @param array|string $tags
     * @return array
     */
    protected function normalizeTags($tags): array
    {
        if (is_string($tags)) {
            return [$tags];
        }
        
        return array_merge(['fragment'], $tags);
    }

    /**
     * Cache a menu fragment
     * 
     * @param string $menuId Menu identifier
     * @param callable $callback Function to render menu
     * @param int|null $ttl Cache TTL
     * @return string
     */
    public function menu(string $menuId, callable $callback, ?int $ttl = null): string
    {
        $key = $this->getCacheKey('menu', $menuId);
        $tags = $this->getTags('menu', $menuId);
        
        return $this->remember($key, $tags, $ttl, $callback);
    }

    /**
     * Cache a module fragment
     * 
     * @param string $moduleId Module identifier
     * @param callable $callback Function to render module
     * @param int|null $ttl Cache TTL
     * @return string
     */
    public function module(string $moduleId, callable $callback, ?int $ttl = null): string
    {
        $key = $this->getCacheKey('module', $moduleId);
        $tags = $this->getTags('module', $moduleId);
        
        return $this->remember($key, $tags, $ttl, $callback);
    }

    /**
     * Cache a category tree fragment
     * 
     * @param int|null $parentId Parent category ID
     * @param callable $callback Function to render categories
     * @param int|null $ttl Cache TTL
     * @return string
     */
    public function categoryTree(?int $parentId, callable $callback, ?int $ttl = null): string
    {
        $key = $this->getCacheKey('category_tree', $parentId ?? 'root');
        $tags = $this->getTags('category', $parentId);
        
        return $this->remember($key, $tags, $ttl, $callback);
    }

    /**
     * Cache a product list fragment
     * 
     * @param string $listId List identifier
     * @param array $params Query parameters
     * @param callable $callback Function to render products
     * @param int|null $ttl Cache TTL
     * @return string
     */
    public function productList(string $listId, array $params, callable $callback, ?int $ttl = null): string
    {
        $key = $this->getCacheKey('product_list', $listId, $params);
        $tags = $this->getTags('product', null, ['product_list']);
        
        return $this->remember($key, $tags, $ttl, $callback);
    }
}
