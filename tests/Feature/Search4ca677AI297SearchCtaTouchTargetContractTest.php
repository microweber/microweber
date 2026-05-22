<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-4ca677 / AI-297 — Search module "← Return home" CTA touch-target.
 *
 * Tester (AI-297 batch mobile P6 audit) measured the "← Return home" link at
 * 107×23px at 390×844 — 21px below the WCAG 2.5.5 44px interactive-element floor.
 *
 * Root cause: `resources/views/frontend/search/results.blade.php:77` renders the
 * link as bare `<a class="mw-frontend-search-results__cta">` with no padding,
 * no `min-height`, and no Bootstrap button class. Text-bound height only.
 *
 * Fix: add `.mw-frontend-search-results__cta { min-height: 44px; display:
 * inline-flex; align-items: center; }` inside the standard touch-viewport
 * `@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)` block
 * in public-touch.css. Same pattern as AI-528/535/558 read-more and nav links.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Search4ca677AI297SearchCtaTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH  = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH  = 'public/templates/bootstrap/css/public-touch.css';
    private const SEARCH_BLADE  = 'resources/views/frontend/search/results.blade.php';

    private string $css;
    private string $cssStripped;
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();

        $rawCss = (string) file_get_contents(base_path(self::PUBLIC_TOUCH));
        $this->css = $rawCss;
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawCss) ?? $rawCss;

        $this->blade = (string) file_get_contents(base_path(self::SEARCH_BLADE));
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-4ca677', $this->css,
            'public-touch.css must carry the AI-297 task marker.');
    }

    // ─── CTA class exists in template ────────────────────────────────────────

    #[Test]
    public function search_cta_class_in_blade_template(): void
    {
        $this->assertStringContainsString('mw-frontend-search-results__cta', $this->blade,
            'results.blade.php must contain the .mw-frontend-search-results__cta class');
    }

    // ─── CSS rule assertions ──────────────────────────────────────────────────

    #[Test]
    public function cta_min_height_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-frontend-search-results__cta\s*\{[^}]*min-height:\s*44px~s',
            $this->cssStripped,
            '.mw-frontend-search-results__cta must have min-height: 44px'
        );
    }

    #[Test]
    public function cta_rule_has_display_inline_flex(): void
    {
        $pos = strrpos($this->cssStripped, '.mw-frontend-search-results__cta');
        $this->assertNotFalse($pos);
        $slice = substr($this->cssStripped, (int) $pos, 200);
        $this->assertStringContainsString('inline-flex', $slice,
            '.mw-frontend-search-results__cta must set display: inline-flex');
    }

    #[Test]
    public function cta_rule_is_inside_touch_media_query(): void
    {
        $rulePos = strrpos($this->cssStripped, '.mw-frontend-search-results__cta');
        $this->assertNotFalse($rulePos);

        $before   = substr($this->cssStripped, 0, (int) $rulePos);
        $lastAt   = strrpos($before, '@media');
        $this->assertNotFalse($lastAt, 'CTA rule must be preceded by an @media block');

        $mediaSlice = substr($this->cssStripped, (int) $lastAt, 80);
        $this->assertStringContainsString('max-width', $mediaSlice,
            '.mw-frontend-search-results__cta must sit inside the touch-viewport @media block');
    }

    // ─── Byte-identical mirror ────────────────────────────────────────────────

    #[Test]
    public function served_mirror_is_byte_identical_to_source(): void
    {
        $source = (string) file_get_contents(base_path(self::PUBLIC_TOUCH));
        $served = (string) file_get_contents(base_path(self::SERVED_TOUCH));
        $this->assertSame($source, $served,
            'public/templates/bootstrap/css/public-touch.css must be byte-identical to source');
    }

    // ─── Regression guard ────────────────────────────────────────────────────

    #[Test]
    public function existing_search_touch_targets_unchanged(): void
    {
        // AI-528 category nav links and AI-535 breadcrumb links must still be present.
        $this->assertStringContainsString('nav-list li a', $this->cssStripped,
            'AI-528 category nav-list link rule must remain');
        $this->assertStringContainsString('breadcrumb-item a', $this->cssStripped,
            'AI-535 breadcrumb link rule must remain');
    }
}
