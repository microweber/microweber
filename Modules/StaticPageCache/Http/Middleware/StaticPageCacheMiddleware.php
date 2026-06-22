<?php

declare(strict_types=1);

namespace Modules\StaticPageCache\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\StaticPageCache\Services\StaticPageCacheService;

class StaticPageCacheMiddleware
{
    protected StaticPageCacheService $cacheService;

    public function __construct(StaticPageCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function handle(Request $request, Closure $next)
    {
        if (! $this->cacheService->isEnabled()) {
            return $next($request);
        }

        if ($this->cacheService->shouldExclude()) {
            return $next($request);
        }

        // Try cached response
        $cached = $this->cacheService->get();
        if ($cached !== null && isset($cached['content'])) {
            return $this->buildCachedResponse($cached);
        }

        $response = $next($request);

        // Only cache successful HTML responses
        if ($response instanceof Response
            && $response->getStatusCode() === 200
            && $response->getContent() !== false
            && ! empty($response->getContent())
        ) {
            $this->cacheService->store($response->getContent());
        }

        return $response;
    }

    protected function buildCachedResponse(array $cached): Response
    {
        $response = new Response($cached['content']);

        if (isset($cached['headers'])) {
            foreach ($cached['headers'] as $key => $value) {
                $response->header($key, $value);
            }
        }

        $ttl = config('static-page-cache.ttl', 3600);
        $response->header('Cache-Control', 'public, max-age=' . $ttl);

        return $response;
    }
}