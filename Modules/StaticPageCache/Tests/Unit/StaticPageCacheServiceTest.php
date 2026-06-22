<?php

declare(strict_types=1);

namespace Modules\StaticPageCache\Tests\Unit;

use Illuminate\Support\Facades\Cache;
use Modules\StaticPageCache\Services\StaticPageCacheService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StaticPageCacheServiceTest extends TestCase
{
    protected StaticPageCacheService $service;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'static-page-cache.enabled' => true,
            'static-page-cache.ttl' => 3600,
            'static-page-cache.cache_for_logged_in' => false,
            'static-page-cache.excluded_patterns' => ['^/admin', '^/api'],
            'cache.default' => 'array',
        ]);

        $this->service = new StaticPageCacheService();

        try {
            Cache::tags(['static-page-cache'])->flush();
        } catch (\Exception $e) {
            // ignore
        }
    }

    #[Test]
    public function it_can_check_if_enabled(): void
    {
        $this->assertTrue($this->service->isEnabled());
    }

    #[Test]
    public function it_generates_cache_keys(): void
    {
        $key = $this->service->getCacheKey('/about');
        $this->assertStringStartsWith('static_page_', $key);
        $this->assertNotEmpty($key);
    }

    #[Test]
    public function it_generates_different_keys_for_different_uris(): void
    {
        $key1 = $this->service->getCacheKey('/about');
        $key2 = $this->service->getCacheKey('/contact');

        $this->assertNotEquals($key1, $key2);
    }

    #[Test]
    public function it_excludes_post_requests(): void
    {
        request()->setMethod('POST');
        $this->assertTrue($this->service->shouldExclude());
        request()->setMethod('GET');
    }

    #[Test]
    public function it_excludes_ajax_requests(): void
    {
        request()->headers->set('X-Requested-With', 'XMLHttpRequest');
        $this->assertTrue($this->service->shouldExclude());
        request()->headers->remove('X-Requested-With');
    }

    #[Test]
    public function it_can_store_and_retrieve_content(): void
    {
        $content = '<html><body>Test</body></html>';

        $stored = $this->service->store($content);
        $this->assertTrue($stored);

        $cached = $this->service->get();
        $this->assertNotNull($cached);
        $this->assertEquals($content, $cached['content']);
    }

    #[Test]
    public function it_returns_null_on_cache_miss(): void
    {
        $cached = $this->service->get();
        $this->assertNull($cached);
    }

    #[Test]
    public function it_can_clear_cache(): void
    {
        $this->service->store('<html>Test</html>');
        $this->assertNotNull($this->service->get());

        $cleared = $this->service->clear();
        $this->assertTrue($cleared);

        $this->assertNull($this->service->get());
    }

    #[Test]
    public function it_returns_false_when_disabled(): void
    {
        config(['static-page-cache.enabled' => false]);
        $service = new StaticPageCacheService();

        $this->assertFalse($service->store('content'));
        $this->assertNull($service->get());
    }
}