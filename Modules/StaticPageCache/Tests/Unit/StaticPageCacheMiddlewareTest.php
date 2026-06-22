<?php

declare(strict_types=1);

namespace Modules\StaticPageCache\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Modules\StaticPageCache\Http\Middleware\StaticPageCacheMiddleware;
use Modules\StaticPageCache\Services\StaticPageCacheService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StaticPageCacheMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'static-page-cache.enabled' => true,
            'static-page-cache.ttl' => 3600,
            'static-page-cache.cache_for_logged_in' => false,
            'static-page-cache.excluded_patterns' => [],
            'cache.default' => 'array',
        ]);

        try {
            Cache::tags(['static-page-cache'])->flush();
        } catch (\Exception $e) {
            // ignore
        }
    }

    #[Test]
    public function it_passes_through_when_disabled(): void
    {
        config(['static-page-cache.enabled' => false]);
        $service = new StaticPageCacheService();
        $middleware = new StaticPageCacheMiddleware($service);

        $request = Request::create('/test', 'GET');
        $response = $middleware->handle($request, function () {
            return new Response('fresh content');
        });

        $this->assertEquals('fresh content', $response->getContent());
        $this->assertFalse($response->headers->has('X-Static-Page-Cache'));
    }

    #[Test]
    public function it_caches_successful_responses(): void
    {
        $service = new StaticPageCacheService();
        $middleware = new StaticPageCacheMiddleware($service);

        $request = Request::create('/test', 'GET');
        $response = $middleware->handle($request, function () {
            return new Response('<html>Page Content</html>');
        });

        $this->assertEquals('<html>Page Content</html>', $response->getContent());

        // Content should now be cached
        $cached = $service->get();
        $this->assertNotNull($cached);
    }

    #[Test]
    public function it_serves_cached_response(): void
    {
        $service = new StaticPageCacheService();

        // Pre-populate cache
        $service->store('<html>Cached Page</html>');

        $middleware = new StaticPageCacheMiddleware($service);
        $request = Request::create('/', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('should not reach here');
        });

        $this->assertStringContainsString('Cached Page', $response->getContent());
        $this->assertEquals('HIT', $response->headers->get('X-Static-Page-Cache'));
    }
}