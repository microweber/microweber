<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-ea3b1f / AI-302 MEDIUM — BeforeAfter module touch target.
 *
 * Tester measured .twentytwenty-handle at 38×38px at 390×844 — below the
 * WCAG 2.5.5 44×44px floor for interactive elements. The handle is the primary
 * drag affordance of the BeforeAfter image split comparison module.
 *
 * Fix: `min-width: 44px; min-height: 44px` on `.twentytwenty-handle` inside the
 * standard touch-viewport @media block in public-touch.css. The 38px visual glyph
 * stays native on desktop; only the interactive touch area is enlarged.
 *
 * AI-558a follow-up: bare `<a class="" itemprop="url">` read-more links in other
 * post skins cannot be targeted without adding a class to the templates.
 */
class PublicEa3b1fAI302BeforeAfterHandleContractTest extends TestCase
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
    public function twentytwenty_handle_has_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.twentytwenty-handle\s*\{[^}]*min-width:\s*44px~s',
            $this->srcStripped,
            '.twentytwenty-handle must have min-width: 44px.'
        );
    }

    #[Test]
    public function twentytwenty_handle_has_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.twentytwenty-handle\s*\{[^}]*min-height:\s*44px~s',
            $this->srcStripped,
            '.twentytwenty-handle must have min-height: 44px.'
        );
    }

    #[Test]
    public function rule_is_inside_touch_media_query(): void
    {
        // Slice to the @media block containing the rule
        $pos = strrpos($this->srcStripped, '.twentytwenty-handle');
        $this->assertNotFalse($pos, 'Rule selector must be present.');

        // Search backward from rule position for the nearest @media
        $before = substr($this->srcStripped, 0, $pos);
        $mediaPos = strrpos($before, '@media');
        $this->assertNotFalse($mediaPos, '@media must precede .twentytwenty-handle rule.');

        // Confirm the @media is the touch-viewport query
        $mediaSlice = substr($this->srcStripped, (int) $mediaPos, 60);
        $this->assertStringContainsString('1023.98px', $mediaSlice,
            'Rule must be inside the standard touch-viewport @media block.'
        );
    }

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-ea3b1f', $this->src);
    }

    #[Test]
    public function served_mirror_is_byte_identical(): void
    {
        $servedPath = base_path('public/templates/bootstrap/css/public-touch.css');
        if (!file_exists($servedPath)) {
            $this->markTestSkipped('Served mirror not present in this environment.');
        }
        $this->assertSame(
            md5($this->src),
            md5((string) file_get_contents($servedPath)),
            'Served mirror must be byte-identical to the source file.'
        );
    }

    // ─── AI-877 + AI-900 regression guards ────────────────────────────────────

    #[Test]
    public function ai877_link_color_rule_still_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-ef3960', $this->src);
    }

    #[Test]
    public function ai900_button_sm_rule_still_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-10ee46', $this->src);
    }
}
