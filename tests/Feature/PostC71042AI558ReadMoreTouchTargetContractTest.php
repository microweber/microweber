<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-c71042 / AI-558 MEDIUM — Post module read-more link touch target.
 *
 * Tester measured text-only read-more links at 16-21px height at 390×844.
 * Two named CSS classes cover the addressed skins:
 *   .skin-18--read-more-link  — skin-18.blade.php + pro_blog.blade.php
 *   .mw-post-22-post-read-more — skin-22.blade.php
 *
 * AI-558a follow-up candidate: remaining ~25 post skins use bare
 * `<a class="" itemprop="url">` anchors with no unique class — those require
 * adding a .mw-post-read-more class to each template before the touch rule
 * can be applied. Deferred pending explicit dispatch.
 *
 * The PM's suggested .blog-posts selector was investigated: the actual Post
 * module templates use skin-specific wrappers (.blog-posts-1, .blog-posts-2,
 * etc.) not a bare .blog-posts class — selector was not adopted.
 */
class PostC71042AI558ReadMoreTouchTargetContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $raw = (string) file_get_contents(
            base_path('Templates/Bootstrap/resources/assets/css/public-touch.css')
        );
        $this->src = $raw;
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;
    }

    #[Test]
    public function skin18_read_more_link_has_min_height_44px(): void
    {
        // Strip comments before slicing to avoid docblock prose
        $pos = strrpos($this->srcStripped, '.skin-18--read-more-link');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 200);
        $this->assertMatchesRegularExpression(
            '~\.skin-18--read-more-link[\s\S]*?min-height:\s*44px~s',
            $slice,
            '.skin-18--read-more-link must have min-height: 44px.'
        );
    }

    #[Test]
    public function skin22_read_more_has_min_height_44px(): void
    {
        $pos = strrpos($this->srcStripped, '.mw-post-22-post-read-more');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 200);
        $this->assertMatchesRegularExpression(
            '~\.mw-post-22-post-read-more[\s\S]*?min-height:\s*44px~s',
            $slice,
            '.mw-post-22-post-read-more must have min-height: 44px.'
        );
    }

    #[Test]
    public function read_more_links_use_inline_flex(): void
    {
        $pos = strrpos($this->srcStripped, '.skin-18--read-more-link');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 250);
        $this->assertStringContainsString('inline-flex', $slice,
            'Read-more link rule must use display: inline-flex.'
        );
        $this->assertStringContainsString('align-items: center', $slice,
            'Read-more link rule must use align-items: center.'
        );
    }

    #[Test]
    public function rule_is_inside_touch_media_query(): void
    {
        $pos = strrpos($this->srcStripped, '.skin-18--read-more-link');
        $this->assertNotFalse($pos);
        $before = substr($this->srcStripped, 0, (int) $pos);
        $mediaPos = strrpos($before, '@media');
        $this->assertNotFalse($mediaPos);
        $mediaSlice = substr($this->srcStripped, (int) $mediaPos, 60);
        $this->assertStringContainsString('1023.98px', $mediaSlice,
            'Rule must be inside the standard touch-viewport @media block.'
        );
    }

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-c71042', $this->src);
    }

    #[Test]
    public function ai558a_followup_documented(): void
    {
        // Ensures the follow-up candidate for bare-anchor skins is on record
        $this->assertStringContainsString('AI-558a', $this->src);
    }

    #[Test]
    public function served_mirror_is_byte_identical(): void
    {
        $servedPath = base_path('public/templates/bootstrap/css/public-touch.css');
        if (!file_exists($servedPath)) {
            $this->markTestSkipped('Served mirror not present.');
        }
        $this->assertSame(
            md5($this->src),
            md5((string) file_get_contents($servedPath))
        );
    }
}
