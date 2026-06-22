<?php

declare(strict_types=1);

namespace Modules\StaticPageCache\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class StaticPageCacheService
{
    protected bool $enabled;

    protected int $defaultTtl;

    protected array $excludedPatterns;

    protected bool $cacheForLoggedIn;

    public function __construct()
    {
        $this->enabled = $this->resolveEnabled();
        $this->defaultTtl = (int) config('static-page-cache.ttl', 3600);
        $this->excludedPatterns = config('static-page-cache.excluded_patterns', []);
        $this->cacheForLoggedIn = (bool) config('static-page-cache.cache_for_logged_in', false);
    }

    /**
     * Resolve whether caching is enabled.
     * The admin option takes precedence, then config.
     */
    protected function resolveEnabled(): bool
    {
        // Check admin panel setting first
        if (function_exists('get_option')) {
            $option = get_option('enable_full_page_cache', 'website');
            if ($option === 'y' || $option === '1' || $option === true) {
                return true;
            }
            if ($option === 'n' || $option === '0' || $option === false) {
                return false;
            }
        }

        return (bool) config('static-page-cache.enabled', false);
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Generate a unique cache key for the current request.
     */
    public function getCacheKey(?string $uri = null): string
    {
        $uri = $uri ?? Request::getRequestUri();
        $locale = app()->getLocale();
        $mobile = $this->isMobile() ? '_mobile' : '';

        return 'static_page_' . md5($uri . $locale . $mobile);
    }

    /**
     * Determine if the current request should be excluded from caching.
     */
    public function shouldExclude(): bool
    {
        if (! in_array(Request::getMethod(), ['GET', 'HEAD'], true)) {
            return true;
        }

        if (auth()->check() && ! $this->cacheForLoggedIn) {
            return true;
        }

        if (Request::ajax()) {
            return true;
        }

        if (Request::has('preview_template') || Request::has('editmode')) {
            return true;
        }

        $uri = Request::getRequestUri();
        foreach ($this->excludedPatterns as $pattern) {
            if (preg_match('#' . $pattern . '#', $uri)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get cached page content.
     *
     * @return array|null ['content' => string, 'headers' => array]
     */
    public function get(): ?array
    {
        if (! $this->enabled || $this->shouldExclude()) {
            return null;
        }

        try {
            $key = $this->getCacheKey();

            return Cache::tags(['static-page-cache'])->get($key);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Store page content.
     */
    public function store(string $content, ?int $ttl = null): bool
    {
        if (! $this->enabled || $this->shouldExclude()) {
            return false;
        }

        try {
            $key = $this->getCacheKey();
            $ttl = $ttl ?? $this->defaultTtl;

            $data = [
                'content' => $content,
                'created_at' => now()->toIso8601String(),
                'headers' => [
                    'X-Static-Page-Cache' => 'HIT',
                    'X-Cache-Generated' => now()->toRfc7231String(),
                ],
            ];

            Cache::tags(['static-page-cache'])->put($key, $data, $ttl);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clear all static page cache.
     */
    public function clear(): bool
    {
        try {
            Cache::tags(['static-page-cache'])->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Simple mobile detection.
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
}