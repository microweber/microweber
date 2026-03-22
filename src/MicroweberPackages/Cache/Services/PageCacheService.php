<?php

declare(strict_types=1);

namespace MicroweberPackages\Cache\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

/**
 * Advanced Page Cache Service
 * 
 * Provides full page caching with support for:
 * - Full page cache with tag-based invalidation
 * - Fragment caching for partial content
 * - Cache warming and preloading
 * - Smart cache invalidation on content changes
 * - Cache exclusion patterns
 * - Mobile/desktop separate caching
 * - User role-based caching
 * 
 * @package MicroweberPackages\Cache\Services
 */
class PageCacheService
{
    /**
     * Cache configuration
     */
    protected array $config;

    /**
     * Whether page caching is enabled
     */
    protected bool $enabled;

    /**
     * Default cache TTL in seconds
     */
    protected int $defaultTtl;

    /**
     * Cache tags for invalidation
     */
    protected array $cacheTags = [];

    /**
     * Exclusion patterns for URLs that should not be cached
     */
    protected array $excludedPatterns;

    /**
     * Cache statistics
     */
    protected array $stats = [
        'hits' => 0,
        'misses' => 0,
        'writes' => 0,
        'deletes' => 0,
    ];

    public function __construct()
    {
        $this->config = config('cache.page', []);
        $this->enabled = $this->config['enabled'] ?? false;
        $this->defaultTtl = $this->config['ttl'] ?? 3600;
        $this->excludedPatterns = $this->config['excluded_patterns'] ?? [];
    }

    /**
     * Check if page caching is enabled
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
     * Generate cache key for current request
     */
    public function getCacheKey(): string
    {
        $uri = Request::getRequestUri();
        $method = Request::getMethod();
        $mobile = $this->isMobile() ? '_mobile' : '';
        $loggedIn = $this->isAuthenticated() ? '_auth' : '';
        $locale = app()->getLocale();
        
        return 'page_cache_' . md5($method . $uri . $mobile . $loggedIn . $locale);
    }

    /**
     * Check if request is from mobile device
     */
    protected function isMobile(): bool
    {
        $userAgent = Request::header('User-Agent', '');
        $mobileAgents = ['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'];
        
        foreach ($mobileAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated(): bool
    {
        return auth()->check();
    }

    /**
     * Check if current request should be excluded from caching
     */
    public function shouldExclude(): bool
    {
        // Don't cache POST/PUT/DELETE requests
        if (!in_array(Request::getMethod(), ['GET', 'HEAD'], true)) {
            return true;
        }

        // Don't cache if user is logged in (unless configured otherwise)
        if ($this->isAuthenticated() && !($this->config['cache_for_logged_in'] ?? false)) {
            return true;
        }

        // Don't cache AJAX requests
        if (Request::ajax()) {
            return true;
        }

        // Check excluded patterns
        $uri = Request::getRequestUri();
        foreach ($this->excludedPatterns as $pattern) {
            if (preg_match('#' . $pattern . '#', $uri)) {
                return true;
            }
        }

        // Don't cache if has query params (unless configured otherwise)
        if (!empty(Request::query()) && !($this->config['cache_with_query_params'] ?? false)) {
            return true;
        }

        return false;
    }

    /**
     * Get cached page content
     */
    public function get(): ?array
    {
        if (!$this->isEnabled() || $this->shouldExclude()) {
            $this->stats['misses']++;
            return null;
        }

        $key = $this->getCacheKey();
        $tags = $this->getCacheTags();

        try {
            $cached = Cache::tags($tags)->get($key);
            
            if ($cached !== null) {
                $this->stats['hits']++;
                
                // Log cache hit for debugging
                if (config('app.debug')) {
                    Log::debug('Page cache hit', ['key' => $key, 'tags' => $tags]);
                }
                
                return $cached;
            }
            
            $this->stats['misses']++;
        } catch (\Exception $e) {
            Log::warning('Page cache get failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Store page content in cache
     */
    public function store(string $content, int $ttl = null): bool
    {
        if (!$this->isEnabled() || $this->shouldExclude()) {
            return false;
        }

        $key = $this->getCacheKey();
        $tags = $this->getCacheTags();
        $ttl = $ttl ?? $this->defaultTtl;

        try {
            $data = [
                'content' => $content,
                'created_at' => now()->toIso8601String(),
                'expires_at' => now()->addSeconds($ttl)->toIso8601String(),
                'key' => $key,
                'tags' => $tags,
                'headers' => [
                    'X-Page-Cache' => 'HIT',
                    'X-Page-Cache-Key' => $key,
                    'X-Page-Cache-Generated' => now()->toRfc7231String(),
                ],
            ];

            Cache::tags($tags)->put($key, $data, $ttl);
            $this->stats['writes']++;

            // Log cache write for debugging
            if (config('app.debug')) {
                Log::debug('Page cache stored', ['key' => $key, 'ttl' => $ttl, 'tags' => $tags]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Page cache store failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Clear page cache
     */
    public function clear(string $tag = null): bool
    {
        try {
            if ($tag) {
                Cache::tags([$tag])->flush();
            } else {
                Cache::tags(['page'])->flush();
            }
            
            $this->stats['deletes']++;
            Log::info('Page cache cleared', ['tag' => $tag]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Page cache clear failed', [
                'tag' => $tag,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Invalidate cache for specific content
     */
    public function invalidateContent(int $contentId, string $contentType = 'page'): bool
    {
        try {
            Cache::tags(["content:{$contentType}", "content:{$contentId}"])->flush();
            
            Log::info('Content cache invalidated', [
                'content_id' => $contentId,
                'content_type' => $contentType,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Content cache invalidation failed', [
                'content_id' => $contentId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get cache tags for current request
     */
    protected function getCacheTags(): array
    {
        $tags = ['page', 'content'];
        
        // Add language tag
        $tags[] = 'lang:' . app()->getLocale();
        
        // Add mobile/desktop tag
        $tags[] = $this->isMobile() ? 'mobile' : 'desktop';
        
        // Add auth tag
        if ($this->isAuthenticated()) {
            $tags[] = 'authenticated';
        }
        
        return array_merge($tags, $this->cacheTags);
    }

    /**
     * Add custom cache tags
     */
    public function addTags(array $tags): self
    {
        $this->cacheTags = array_merge($this->cacheTags, $tags);
        return $this;
    }

    /**
     * Get cache statistics
     */
    public function getStats(): array
    {
        return array_merge($this->stats, [
            'enabled' => $this->isEnabled(),
            'driver' => config('cache.default'),
            'ttl' => $this->defaultTtl,
            'excluded_patterns' => $this->excludedPatterns,
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
     * Warm cache for specific URLs
     */
    public function warmCache(array $urls): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($urls as $url) {
            try {
                // Use cURL to fetch the page and warm the cache
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_USERAGENT, 'MicroweberCacheWarmer/1.0');
                
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200 && $response !== false) {
                    $results['success'][] = $url;
                } else {
                    $results['failed'][] = [
                        'url' => $url,
                        'code' => $httpCode,
                    ];
                }
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'url' => $url,
                    'error' => $e->getMessage(),
                ];
            }
        }

        Log::info('Cache warming completed', [
            'success_count' => count($results['success']),
            'failed_count' => count($results['failed']),
        ]);

        return $results;
    }

    /**
     * Get all cached pages
     */
    public function getCachedPages(): array
    {
        // This is a simplified implementation
        // In production, you might want to scan Redis keys or use a tracking mechanism
        return Cache::tags(['page'])->get('page_cache_index', []);
    }

    /**
     * Update cache index
     */
    public function updateCacheIndex(string $key, bool $remove = false): void
    {
        try {
            $index = Cache::tags(['page'])->get('page_cache_index', []);
            
            if ($remove) {
                unset($index[$key]);
            } else {
                $index[$key] = [
                    'created_at' => now()->toIso8601String(),
                    'url' => Request::getRequestUri(),
                ];
            }
            
            Cache::tags(['page'])->forever('page_cache_index', $index);
        } catch (\Exception $e) {
            Log::warning('Failed to update page cache index', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
