<?php

declare(strict_types=1);

namespace MicroweberPackages\BladeCache\Tests;

use MicroweberPackages\BladeCache\BladeCacheService;
use PHPUnit\Framework\Attributes\Test;

class BladeCacheServiceTest extends TestCase
{
    protected BladeCacheService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->app->make(BladeCacheService::class);
    }

    #[Test]
    public function it_is_enabled_by_default(): void
    {
        $this->assertTrue($this->service->isEnabled());
    }

    #[Test]
    public function it_can_be_disabled_via_config(): void
    {
        config(['blade-cache.enabled' => false]);
        $service = new BladeCacheService();

        $this->assertFalse($service->isEnabled());
    }

    #[Test]
    public function it_stores_and_retrieves_content(): void
    {
        $key = 'test-fragment';
        $html = '<div>Hello World</div>';

        $stored = $this->service->put($key, $html);
        $this->assertTrue($stored);

        $cached = $this->service->get($key);
        $this->assertSame($html, $cached);
    }

    #[Test]
    public function it_returns_null_on_cache_miss(): void
    {
        $this->assertNull($this->service->get('nonexistent'));
    }

    #[Test]
    public function it_stores_with_tags(): void
    {
        $key = 'menu-fragment';
        $html = '<nav>Menu</nav>';
        $tags = ['menu', 'navigation'];

        $this->service->put($key, $html, $tags);

        $cached = $this->service->get($key, $tags);
        $this->assertSame($html, $cached);
    }

    #[Test]
    public function it_can_forget_a_key(): void
    {
        $key = 'deletable';
        $tags = ['test'];

        $this->service->put($key, 'content', $tags);
        $this->assertNotNull($this->service->get($key, $tags));

        $this->service->forget($key, $tags);
        $this->assertNull($this->service->get($key, $tags));
    }

    #[Test]
    public function it_can_flush_by_tags(): void
    {
        $tags = ['section-a'];
        $this->service->put('k1', 'v1', $tags);
        $this->service->put('k2', 'v2', $tags);

        $this->service->flush($tags);

        $this->assertNull($this->service->get('k1', $tags));
        $this->assertNull($this->service->get('k2', $tags));
    }

    #[Test]
    public function it_returns_false_when_disabled(): void
    {
        config(['blade-cache.enabled' => false]);
        $service = new BladeCacheService();

        $this->assertFalse($service->put('key', 'value'));
        $this->assertNull($service->get('key'));
    }

    #[Test]
    public function it_uses_default_ttl_from_config(): void
    {
        config(['blade-cache.ttl' => 7200]);
        $service = new BladeCacheService();

        $this->assertSame(7200, $service->getDefaultTtl());
    }
}