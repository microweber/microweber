<?php

declare(strict_types=1);

namespace MicroweberPackages\BladeCache\Tests;

use Illuminate\Support\Facades\Blade;
use MicroweberPackages\BladeCache\BladeCacheService;
use PHPUnit\Framework\Attributes\Test;

class CacheDirectiveTest extends TestCase
{
    #[Test]
    public function cache_directive_is_registered(): void
    {
        $directives = Blade::getCustomDirectives();

        $this->assertArrayHasKey('cache', $directives);
        $this->assertArrayHasKey('endcache', $directives);
    }

    #[Test]
    public function cache_directive_renders_and_caches_content(): void
    {
        $service = $this->app->make(BladeCacheService::class);

        // First render – cache miss, content should be rendered and stored
        $blade = "@cache('test-dir', ['tag1'], 600) Hello @endcache";
        $output1 = Blade::render($blade);
        $this->assertStringContainsString('Hello', trim($output1));

        // Verify it was stored
        $cached = $service->get('test-dir', ['tag1']);
        $this->assertNotNull($cached);
        $this->assertStringContainsString('Hello', $cached);

        // Second render – cache hit
        $output2 = Blade::render($blade);
        $this->assertStringContainsString('Hello', trim($output2));
    }

    #[Test]
    public function cache_directive_skips_cache_when_disabled(): void
    {
        config(['blade-cache.enabled' => false]);
        // Re-create the singleton so it picks up new config
        $this->app->singleton(BladeCacheService::class, fn () => new BladeCacheService());

        $blade = "@cache('disabled-key') Rendered @endcache";
        $output = Blade::render($blade);
        $this->assertStringContainsString('Rendered', trim($output));

        $service = $this->app->make(BladeCacheService::class);
        $this->assertNull($service->get('disabled-key'));
    }

    #[Test]
    public function cache_directive_works_with_dynamic_content(): void
    {
        $blade = "@cache('dyn', ['dynamic'], 300) Value={{ \$val }} @endcache";

        $output = Blade::render($blade, ['val' => 42]);
        $this->assertStringContainsString('Value=42', $output);

        // Second render with different data should return cached version
        $output2 = Blade::render($blade, ['val' => 99]);
        $this->assertStringContainsString('Value=42', $output2); // still cached
    }
}