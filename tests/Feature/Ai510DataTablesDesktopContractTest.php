<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-510 — Desktop data-table improvements (≥1024px).
 *
 * Pins three coordinated shape facts inside the @media (min-width: 1024px)
 * block in packages/microweber-filament-theme/.../mobile-touch.css:
 *   1. Sort indicator visibility — `.fi-ta-header-cell[aria-sort="..."]`
 *      gets a background tint + font-weight bump on both admin and
 *      checkout panels, plus a dark-mode variant.
 *   2. Sticky first column — `position: sticky; left: 0` on the first
 *      th/td of every row in both panels, with opaque background so
 *      scrolling content does not bleed through (light + dark).
 *   3. Checkbox vertical-alignment normalisation — `:has(...)` cell
 *      selector pinning `vertical-align: middle` so the row-select
 *      checkbox stays centred when the adjacent cell wraps.
 *
 * Out-of-scope follow-ups documented in the CSS comment (deferred to
 * AI-510a/b/c) are NOT pinned here — those tickets land separately.
 *
 * Style: file-system reads only, no DB / Filament boot. Matches the
 * Big2MobileAuditContractTest pattern.
 */
class Ai510DataTablesDesktopContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    private function ai510Block(): string
    {
        $css = $this->read(self::MOBILE_TOUCH_CSS);
        $start = strpos($css, 'AI-510');
        $this->assertNotFalse($start, 'mobile-touch.css must contain the AI-510 marker comment.');
        // Slice from the marker to end of file — the AI-510 block is
        // the last block in the file by convention. Future blocks
        // should be appended after.
        return substr($css, $start);
    }

    #[Test]
    public function ai510_block_is_scoped_to_min_width_1024px(): void
    {
        $block = $this->ai510Block();
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*min-width:\s*1024px\s*\)/',
            $block,
            'AI-510 rules must live inside a @media (min-width: 1024px) block so they do not collide with AI-246 mobile card-view (<1024px).'
        );
    }

    /**
     * Each axis the AI-510 block must address.
     */
    public static function shapeFactsProvider(): array
    {
        return [
            'sort marker comment'        => ['Sort indicator visibility'],
            'sort aria asc'              => ['fi-ta-header-cell[aria-sort="ascending"]'],
            'sort aria desc'             => ['fi-ta-header-cell[aria-sort="descending"]'],
            'sort tint admin panel'      => ['body.fi-panel-admin .fi-ta-header-cell[aria-sort="ascending"]'],
            'sort tint checkout panel'   => ['body.fi-panel-checkout .fi-ta-header-cell[aria-sort="ascending"]'],
            'sort dark-mode variant'     => ['html.dark body.fi-panel-admin .fi-ta-header-cell[aria-sort="ascending"]'],
            'sticky position'            => ['position: sticky'],
            'sticky left 0'              => ['left: 0'],
            'sticky first th admin'      => ['body.fi-panel-admin .fi-ta-table > thead > tr > th:first-child'],
            'sticky first td admin'      => ['body.fi-panel-admin .fi-ta-table > tbody > tr > td:first-child'],
            'sticky first th checkout'   => ['body.fi-panel-checkout .fi-ta-table > thead > tr > th:first-child'],
            'sticky first td checkout'   => ['body.fi-panel-checkout .fi-ta-table > tbody > tr > td:first-child'],
            'sticky opaque background'   => ['background-color: var(--bs-body-bg'],
            'checkbox cell admin'        => ['body.fi-panel-admin .fi-ta-cell:has(input[type="checkbox"])'],
            'checkbox cell checkout'     => ['body.fi-panel-checkout .fi-ta-cell:has(input[type="checkbox"])'],
            'checkbox vertical-align'    => ['vertical-align: middle'],
        ];
    }

    #[Test]
    #[DataProvider('shapeFactsProvider')]
    public function ai510_block_contains_each_shape_fact(string $needle): void
    {
        $block = $this->ai510Block();
        $this->assertStringContainsString(
            $needle,
            $block,
            "AI-510 block must contain `{$needle}`."
        );
    }

    #[Test]
    public function ai510_does_not_break_ai246_mobile_block(): void
    {
        // AI-246 mobile card-view block must still exist with its
        // own @media (max-width: 1023.98px) and key selectors. The
        // AI-510 desktop block must not have been accidentally
        // appended into the AI-246 media query.
        $css = $this->read(self::MOBILE_TOUCH_CSS);

        $this->assertStringContainsString('AI-246', $css, 'AI-246 marker comment must still be present.');
        $this->assertStringContainsString('@media (max-width: 1023.98px)', $css, 'AI-246 @media block must still exist.');
        $this->assertStringContainsString('body.fi-panel-admin .fi-ta-row', $css, 'AI-246 stacked-card row rules must still exist.');
    }

    #[Test]
    public function ai510_does_not_globally_force_sticky_on_non_admin_tables(): void
    {
        $block = $this->ai510Block();

        // Regression guard — the sticky-first-column selectors must
        // ALL be scoped to `body.fi-panel-admin` or `body.fi-panel-checkout`.
        // A naked `.fi-ta-table > thead > tr > th:first-child` rule
        // would leak sticky positioning into ANY Filament panel
        // (marketplace, dashboards, future panels) and break tables
        // there. Guard the regression by asserting the unscoped
        // selector does NOT appear.
        $this->assertStringNotContainsString(
            "\n    .fi-ta-table > thead > tr > th:first-child",
            $block,
            'sticky first-column rules must be panel-scoped, not global.'
        );
    }

    #[Test]
    public function ai510_uses_filament_v5_aria_sort_attribute(): void
    {
        $block = $this->ai510Block();

        // Regression guard — Filament v5 emits the standard
        // `aria-sort` attribute on header cells. Earlier Filament
        // versions used a `data-sort` attribute or a `.fi-sorted` class.
        // Pin that AI-510 uses the v5-canonical aria-sort hook so
        // the tint actually fires.
        $this->assertStringContainsString('aria-sort="ascending"', $block);
        $this->assertStringContainsString('aria-sort="descending"', $block);
        $this->assertStringNotContainsString('data-sort=', $block);
    }
}
