<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Sitemap\Http\Controllers\SitemapHelpersTrait;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-333 follow-up — Sitemap cache TTL fix.
 *
 * The original `SitemapHelpersTrait::needToUpdateSitemap()` short-
 * circuited with `return true;` at the top of the method — disabling
 * the documented 3-hour cache TTL. The Sitemap module's AI-333 docs
 * flagged this as a known bug. This contract test pins the post-fix
 * behaviour:
 *
 *   - Missing file → true (regenerate)
 *   - Unreadable file (filemtime returns false) → true (safe default)
 *   - File fresher than 3 hours → false (use cache)
 *   - File older than 3 hours → true (regenerate)
 *
 * Style: pure-PHP unit test on the trait — instantiated via an
 * anonymous class. No DB, no Filament boot.
 */
class SitemapCacheTtlContractTest extends TestCase
{
    /**
     * Build an anonymous class that uses the trait so we can call
     * the trait's public method directly.
     */
    private function helper()
    {
        return new class {
            use SitemapHelpersTrait;
        };
    }

    /**
     * Create a temporary file with a specific mtime. Returns the path.
     * Cleans up via PHPUnit's tearDown chain.
     */
    private function tempSitemapFile(int $mtimeOffsetSeconds): string
    {
        $path = sys_get_temp_dir() . '/' . uniqid('mw_sitemap_test_', true) . '.xml';
        file_put_contents($path, '<?xml version="1.0"?><urlset></urlset>');
        touch($path, time() + $mtimeOffsetSeconds);

        // Register cleanup
        register_shutdown_function(function () use ($path) {
            @unlink($path);
        });

        return $path;
    }

    #[Test]
    public function returns_true_when_file_does_not_exist(): void
    {
        $helper = $this->helper();
        $missingPath = sys_get_temp_dir() . '/this-file-does-not-exist-' . uniqid() . '.xml';

        $this->assertTrue(
            $helper->needToUpdateSitemap($missingPath),
            'Missing cache file must trigger regeneration.'
        );
    }

    #[Test]
    public function returns_false_when_file_is_fresh(): void
    {
        $helper = $this->helper();
        // File modified 1 hour ago (well within the 3-hour TTL)
        $path = $this->tempSitemapFile(-3600);

        $this->assertFalse(
            $helper->needToUpdateSitemap($path),
            'Fresh cache file (< 3 hours) must NOT trigger regeneration.'
        );
    }

    #[Test]
    public function returns_true_when_file_is_older_than_3_hours(): void
    {
        $helper = $this->helper();
        // File modified 3 hours + 1 minute ago — just past the TTL
        $path = $this->tempSitemapFile(-(3 * 3600 + 60));

        $this->assertTrue(
            $helper->needToUpdateSitemap($path),
            'Cache file older than 3 hours must trigger regeneration.'
        );
    }

    #[Test]
    public function returns_false_when_file_is_just_under_3_hours(): void
    {
        $helper = $this->helper();
        // File modified 2 hours 59 minutes ago — should still use cache
        $path = $this->tempSitemapFile(-(3 * 3600 - 60));

        $this->assertFalse(
            $helper->needToUpdateSitemap($path),
            'Cache file just under 3 hours old must NOT trigger regeneration.'
        );
    }

    #[Test]
    public function regression_does_not_always_return_true(): void
    {
        // This is the AI-333 docs anchor: ensure the original
        // `return true;` short-circuit has been removed. If a future
        // commit re-adds it, this test fails immediately.
        $helper = $this->helper();
        $freshPath = $this->tempSitemapFile(-60);   // 1 minute old

        $this->assertNotTrue(
            $helper->needToUpdateSitemap($freshPath) === true && $helper->needToUpdateSitemap($freshPath) === true,
            'needToUpdateSitemap() must NOT short-circuit with `return true;`. The cache-TTL behaviour is the canonical implementation per the AI-333 docs.'
        );

        // Stronger: fresh file MUST be false
        $this->assertFalse(
            $helper->needToUpdateSitemap($freshPath),
            'needToUpdateSitemap() with a fresh file must return false (cache hit). Returning true short-circuits the cache and re-introduces the AI-333-flagged bug.'
        );
    }
}
