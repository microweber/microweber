<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-fc5060 / AI-585 — Slider nav buttons, CTA, and
 * pagination bullets touch-target floor.
 *
 * Tester FAIL table (2026-05-22 dispatch):
 *   - Nav buttons: `.mw-slider-v2-buttons-slide` measured 40×40 px
 *     (slider.css hard-sets `width: 40px; height: 40px`). Both
 *     `default.blade.php` (`<button>`) and `swiper-skin-1.blade.php`
 *     (`<div>`) share this class.
 *   - CTA button: `.slider-button` measured ~36px wide on narrow slides.
 *     AI-603 added `min-height: 44px` but omitted `min-width: 44px`.
 *   - Bullets: `.swiper-pagination-bullet` already has `min-width: 44px;
 *     min-height: 44px; padding: 18px` inside the touch media query
 *     (shipped earlier). Regression-guarded here to confirm it still
 *     holds. `swiper-skin-1.blade.php` inline style uses `width: 12px
 *     !important; height: 12px !important` — different properties from
 *     `min-width`/`min-height`, so the constraint algorithm applies
 *     after the cascade regardless.
 *
 * Fix (this task):
 *   - Nav buttons: added `.mw-slider-v2-buttons-slide { min-width: 44px;
 *     min-height: 44px; }` inside the touch media query in public-touch.css.
 *   - CTA: extended AI-603 `.slider-button` rule to include `min-width: 44px`.
 *   - Bullets: no change needed; regression guard added below.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class SliderFc5060AI585NavButtonTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH_CSS = 'public/templates/bootstrap/css/public-touch.css';

    private string $css;
    private string $cssStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $raw = (string) file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
        $this->css = $raw;
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-fc5060', $this->css,
            'public-touch.css must carry the AI-585 task marker.');
    }

    // ─── Nav buttons: .mw-slider-v2-buttons-slide ─────────────────────────────

    #[Test]
    public function nav_button_rule_is_inside_touch_media_query(): void
    {
        // Locate the touch @media block. Strategy: find the last
        // occurrence of `.mw-slider-v2-buttons-slide` in stripped CSS,
        // then walk backward to confirm a `@media` precedes it without
        // an intervening `}` that would mean it closed the block.
        $pos = strrpos($this->cssStripped, '.mw-slider-v2-buttons-slide');
        $this->assertNotFalse($pos,
            '.mw-slider-v2-buttons-slide rule must be present in public-touch.css');

        // The rule must appear INSIDE the touch media query — assert
        // that a `@media` block opener precedes it in the source.
        $before = substr($this->cssStripped, 0, (int) $pos);
        $lastMedia = strrpos($before, '@media');
        $this->assertNotFalse($lastMedia,
            '.mw-slider-v2-buttons-slide must appear inside a @media block');

        // The rule must appear before the media block closes (i.e. the
        // last `}` before $pos closes the @media, not before the rule).
        $betweenMediaAndRule = substr($this->cssStripped, (int) $lastMedia, (int) $pos - (int) $lastMedia);
        // Count braces: opening `{` should outnumber closing `}` by at
        // least 1 if we are still inside the media block.
        $opens  = substr_count($betweenMediaAndRule, '{');
        $closes = substr_count($betweenMediaAndRule, '}');
        $this->assertGreaterThan($closes, $opens,
            '.mw-slider-v2-buttons-slide must be nested inside a @media block (unclosed braces count)');
    }

    #[Test]
    public function nav_button_min_width_44(): void
    {
        $pos = strrpos($this->cssStripped, '.mw-slider-v2-buttons-slide');
        $this->assertNotFalse($pos);
        $slice = substr($this->cssStripped, (int) $pos, 200);
        $this->assertMatchesRegularExpression(
            '~\.mw-slider-v2-buttons-slide\s*\{[^}]*min-width:\s*44px~s',
            $slice,
            '.mw-slider-v2-buttons-slide must have min-width: 44px'
        );
    }

    #[Test]
    public function nav_button_min_height_44(): void
    {
        $pos = strrpos($this->cssStripped, '.mw-slider-v2-buttons-slide');
        $this->assertNotFalse($pos);
        $slice = substr($this->cssStripped, (int) $pos, 200);
        $this->assertMatchesRegularExpression(
            '~\.mw-slider-v2-buttons-slide\s*\{[^}]*min-height:\s*44px~s',
            $slice,
            '.mw-slider-v2-buttons-slide must have min-height: 44px'
        );
    }

    #[Test]
    public function nav_button_dom_class_present_in_both_templates(): void
    {
        $default = (string) file_get_contents(
            base_path('Modules/Slider/resources/views/templates/default.blade.php')
        );
        $swiper = (string) file_get_contents(
            base_path('Modules/Slider/resources/views/templates/swiper-skin-1.blade.php')
        );
        $this->assertStringContainsString('mw-slider-v2-buttons-slide', $default,
            'default.blade.php must render .mw-slider-v2-buttons-slide');
        $this->assertStringContainsString('mw-slider-v2-buttons-slide', $swiper,
            'swiper-skin-1.blade.php must render .mw-slider-v2-buttons-slide');
    }

    // ─── CTA button: .slider-button ──────────────────────────────────────────

    #[Test]
    public function cta_button_has_min_width_44(): void
    {
        // `.slider-button { min-width: 44px; min-height: 44px; }` — AI-603
        // originally only set min-height; AI-585 adds min-width.
        $pos = strrpos($this->cssStripped, '.slider-button');
        $this->assertNotFalse($pos);
        $slice = substr($this->cssStripped, (int) $pos, 200);
        $this->assertMatchesRegularExpression(
            '~\.slider-button\s*\{[^}]*min-width:\s*44px~s',
            $slice,
            '.slider-button must have min-width: 44px (AI-585 adds to AI-603 min-height)'
        );
    }

    #[Test]
    public function cta_button_retains_min_height_44(): void
    {
        $pos = strrpos($this->cssStripped, '.slider-button');
        $this->assertNotFalse($pos);
        $slice = substr($this->cssStripped, (int) $pos, 200);
        $this->assertMatchesRegularExpression(
            '~\.slider-button\s*\{[^}]*min-height:\s*44px~s',
            $slice,
            '.slider-button must retain min-height: 44px (regression guard on AI-603)'
        );
    }

    // ─── Pagination bullets regression guard ─────────────────────────────────

    #[Test]
    public function bullets_have_min_width_and_height_44(): void
    {
        // Swiper `width: 12px !important; height: 12px !important` in
        // swiper-skin-1 inline style are different CSS properties from
        // `min-width`/`min-height` — the constraint algorithm applies
        // after the cascade, so min-size wins regardless of `!important`
        // on the fixed dimensions. Regression guard only: no change.
        $this->assertMatchesRegularExpression(
            '~\.swiper-pagination-bullet\s*\{[^}]*min-width:\s*44px[^}]*min-height:\s*44px~s',
            $this->cssStripped,
            '.swiper-pagination-bullet must retain min-width: 44px; min-height: 44px (regression guard)'
        );
    }

    // ─── Served mirror byte-identity ─────────────────────────────────────────

    #[Test]
    public function served_mirror_is_byte_identical_with_source(): void
    {
        $source = (string) file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
        $served = (string) file_get_contents(base_path(self::SERVED_TOUCH_CSS));
        $this->assertSame($source, $served,
            'public-touch.css served mirror must be byte-identical with the source');
    }
}
