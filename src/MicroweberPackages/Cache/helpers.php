<?php

/**
 * Microweber Advanced Cache Helper Functions
 * 
 * These functions provide convenient access to the page and fragment
 * caching systems.
 * 
 * @package MicroweberPackages\Cache
 */

use MicroweberPackages\Cache\Services\FragmentCacheService;
use MicroweberPackages\Cache\Services\PageCacheService;

if (!function_exists('page_cache')) {
    /**
     * Get the Page Cache service instance
     * 
     * @return PageCacheService
     */
    function page_cache(): PageCacheService
    {
        return app(PageCacheService::class);
    }
}

if (!function_exists('fragment_cache')) {
    /**
     * Get the Fragment Cache service instance
     * 
     * @return FragmentCacheService
     */
    function fragment_cache(): FragmentCacheService
    {
        return app(FragmentCacheService::class);
    }
}

if (!function_exists('cache_fragment')) {
    /**
     * Cache a fragment of content
     * 
     * @param string $key Cache key
     * @param array $tags Cache tags
     * @param int|null $ttl Cache TTL in seconds
     * @param callable $callback Function to generate content
     * @return string
     */
    function cache_fragment(string $key, array $tags = [], ?int $ttl = null, callable $callback = null): string
    {
        if ($callback === null) {
            // Allow syntax: cache_fragment($key, $callback, $ttl)
            if (is_callable($tags)) {
                $callback = $tags;
                $tags = [];
            }
        }
        
        return fragment_cache()->remember($key, $tags, $ttl, $callback);
    }
}

if (!function_exists('cache_menu')) {
    /**
     * Cache a menu fragment
     * 
     * @param string $menuId Menu identifier
     * @param callable $callback Function to render menu
     * @param int|null $ttl Cache TTL in seconds
     * @return string
     */
    function cache_menu(string $menuId, callable $callback, ?int $ttl = null): string
    {
        return fragment_cache()->menu($menuId, $callback, $ttl);
    }
}

if (!function_exists('cache_module')) {
    /**
     * Cache a module fragment
     * 
     * @param string $moduleId Module identifier
     * @param callable $callback Function to render module
     * @param int|null $ttl Cache TTL in seconds
     * @return string
     */
    function cache_module(string $moduleId, callable $callback, ?int $ttl = null): string
    {
        return fragment_cache()->module($moduleId, $callback, $ttl);
    }
}

if (!function_exists('cache_category_tree')) {
    /**
     * Cache a category tree fragment
     * 
     * @param int|null $parentId Parent category ID
     * @param callable $callback Function to render categories
     * @param int|null $ttl Cache TTL in seconds
     * @return string
     */
    function cache_category_tree(?int $parentId, callable $callback, ?int $ttl = null): string
    {
        return fragment_cache()->categoryTree($parentId, $callback, $ttl);
    }
}

if (!function_exists('cache_product_list')) {
    /**
     * Cache a product list fragment
     * 
     * @param string $listId List identifier
     * @param array $params Query parameters
     * @param callable $callback Function to render products
     * @param int|null $ttl Cache TTL in seconds
     * @return string
     */
    function cache_product_list(string $listId, array $params, callable $callback, ?int $ttl = null): string
    {
        return fragment_cache()->productList($listId, $params, $callback, $ttl);
    }
}

if (!function_exists('clear_page_cache')) {
    /**
     * Clear the page cache
     * 
     * @param string|null $tag Specific tag to clear (optional)
     * @return bool
     */
    function clear_page_cache(?string $tag = null): bool
    {
        return page_cache()->clear($tag);
    }
}

if (!function_exists('clear_fragment_cache')) {
    /**
     * Clear the fragment cache
     * 
     * @param array|string|null $tags Tags to clear (optional)
     * @return bool
     */
    function clear_fragment_cache($tags = null): bool
    {
        if ($tags === null) {
            return fragment_cache()->clearAll();
        }
        
        return fragment_cache()->clear($tags);
    }
}

if (!function_exists('invalidate_content_cache')) {
    /**
     * Invalidate cache for specific content
     * 
     * @param int $contentId Content ID
     * @param string $contentType Content type (e.g., 'page', 'post')
     * @return bool
     */
    function invalidate_content_cache(int $contentId, string $contentType = 'page'): bool
    {
        return page_cache()->invalidateContent($contentId, $contentType);
    }
}

if (!function_exists('get_cache_stats')) {
    /**
     * Get cache statistics
     * 
     * @param string $type 'page', 'fragment', or 'all'
     * @return array
     */
    function get_cache_stats(string $type = 'all'): array
    {
        $stats = [];
        
        if (in_array($type, ['page', 'all'], true)) {
            $stats['page'] = page_cache()->getStats();
        }
        
        if (in_array($type, ['fragment', 'all'], true)) {
            $stats['fragment'] = fragment_cache()->getStats();
        }
        
        return $stats;
    }
}

if (!function_exists('is_page_cache_enabled')) {
    /**
     * Check if page caching is enabled
     * 
     * @return bool
     */
    function is_page_cache_enabled(): bool
    {
        return page_cache()->isEnabled();
    }
}

if (!function_exists('is_fragment_cache_enabled')) {
    /**
     * Check if fragment caching is enabled
     * 
     * @return bool
     */
    function is_fragment_cache_enabled(): bool
    {
        return fragment_cache()->isEnabled();
    }
}

if (!function_exists('warm_page_cache')) {
    /**
     * Warm the page cache with specified URLs
     * 
     * @param array $urls URLs to warm
     * @return array Results with 'success' and 'failed' arrays
     */
    function warm_page_cache(array $urls): array
    {
        return page_cache()->warmCache($urls);
    }
}
