<?php

declare(strict_types=1);

namespace MicroweberPackages\Cache\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MicroweberPackages\Cache\Services\PageCacheService;

/**
 * Page Cache Middleware
 * 
 * Middleware that intercepts HTTP requests and serves cached responses
 * when available, or caches the response for future requests.
 * 
 * @package MicroweberPackages\Cache\Http\Middleware
 */
class PageCacheMiddleware
{
    /**
     * The page cache service instance.
     */
    protected PageCacheService $cacheService;

    /**
     * Create a new middleware instance.
     */
    public function __construct(PageCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if we should serve from cache
        if (!$this->shouldCache($request)) {
            return $next($request);
        }

        // Try to get cached response
        $cached = $this->cacheService->get();
        
        if ($cached !== null && isset($cached['content'])) {
            return $this->buildCachedResponse($cached);
        }

        // Process the request
        $response = $next($request);

        // Cache the response if applicable
        if ($this->shouldStoreResponse($response)) {
            $this->cacheResponse($response);
        }

        return $response;
    }

    /**
     * Determine if the request should be cached.
     */
    protected function shouldCache(Request $request): bool
    {
        // Only cache GET requests
        if (!$request->isMethod('GET')) {
            return false;
        }

        // Check if page caching is enabled
        if (!$this->cacheService->isEnabled()) {
            return false;
        }

        // Check exclusions
        if ($this->cacheService->shouldExclude()) {
            return false;
        }

        // Don't cache if admin or logged in (unless configured)
        if (auth()->check() && !config('cache.page.cache_for_logged_in', false)) {
            return false;
        }

        // Don't cache in preview/edit mode
        if ($request->has('preview_template') || $request->has('editmode')) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the response should be stored in cache.
     */
    protected function shouldStoreResponse($response): bool
    {
        // Only cache successful responses
        if (!$response instanceof Response) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        // Don't cache if no-cache header is set
        if ($response->headers->has('Cache-Control') && 
            str_contains($response->headers->get('Cache-Control'), 'no-cache')) {
            return false;
        }

        return true;
    }

    /**
     * Cache the response.
     */
    protected function cacheResponse(Response $response): void
    {
        $content = $response->getContent();
        
        if ($content === false || empty($content)) {
            return;
        }

        // Get TTL from config or use default
        $ttl = config('cache.page.ttl', 3600);

        $this->cacheService->store($content, $ttl);
    }

    /**
     * Build a response from cached data.
     */
    protected function buildCachedResponse(array $cached): Response
    {
        $response = new Response($cached['content']);

        // Set cache headers
        $response->header('X-Page-Cache', 'HIT');
        
        if (isset($cached['headers'])) {
            foreach ($cached['headers'] as $key => $value) {
                $response->header($key, $value);
            }
        }

        // Set standard cache headers
        $ttl = config('cache.page.ttl', 3600);
        $response->header('Cache-Control', 'public, max-age=' . $ttl);
        
        if (isset($cached['created_at'])) {
            $response->header('Last-Modified', $cached['created_at']);
        }

        return $response;
    }
}
