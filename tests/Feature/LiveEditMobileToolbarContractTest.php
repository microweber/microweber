<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-86 / AI-73 / TICKET-I — live-edit mobile one-row toolbar +
 * canvas iframe padding-top regression coverage.
 *
 * Pins:
 *   - The toolbar collapses to a single horizontally-scrolling row
 *     at < 768px (flex-nowrap + overflow-x: auto + fixed 56px
 *     min/max-height + flex-shrink: 0 on every child).
 *   - The canvas iframe wrapper (.fi-main-ctn / #iframe-holder /
 *     .live-edit-canvas-wrapper) gets padding-top: 56px so toolbar
 *     buttons no longer overlap the canvas top.
 *   - Verbose toolbar text labels (back-to-admin "ADMIN" + VIEW
 *     button text) collapse to icon-only at < 768px to save
 *     horizontal space; aria-labels preserve AT semantics.
 *   - The mobile rules live INSIDE a `@media (max-width: 767.98px)`
 *     block — the < 768px breakpoint matches the brief.
 *
 * Style after the cycle-52..85 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class LiveEditMobileToolbarContractTest extends TestCase
{
    private string $cssSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cssSrc = file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        ));
    }

    #[Test]
    public function mobile_breakpoint_uses_767_98px_max_width(): void
    {
        // The brief's "< 768px" target maps to the canonical
        // `max-width: 767.98px` Bootstrap breakpoint (avoids the
        // off-by-one fractional pixel where viewport is exactly
        // 768.000... and neither rule wins).
        $this->assertStringContainsString(
            '@media (max-width: 767.98px)',
            $this->cssSrc,
            'live-edit-classes.css: cycle-86 rules must live inside @media (max-width: 767.98px)'
        );
    }

    #[Test]
    public function toolbar_collapses_to_single_horizontally_scrolling_row(): void
    {
        // flex-nowrap forces the row not to break; overflow-x: auto
        // turns excess width into a horizontal scroll. flex-shrink: 0
        // on every child stops them collapsing to nothing.
        $this->assertMatchesRegularExpression(
            '/@media\\s*\\(max-width:\\s*767\\.98px\\)\\s*\\{[\\s\\S]*?#toolbar\\s*\\{[\\s\\S]*?flex-wrap:\\s*nowrap[\\s\\S]*?overflow-x:\\s*auto/s',
            $this->cssSrc,
            'live-edit-classes.css: #toolbar must declare flex-wrap: nowrap + overflow-x: auto inside the mobile @media block'
        );
        $this->assertMatchesRegularExpression(
            '/@media\\s*\\(max-width:\\s*767\\.98px\\)[\\s\\S]*?#toolbar\\s*>\\s*\\*\\s*\\{[\\s\\S]*?flex-shrink:\\s*0/s',
            $this->cssSrc,
            'live-edit-classes.css: every #toolbar > * child must declare flex-shrink: 0 so it does not collapse'
        );
    }

    #[Test]
    public function toolbar_has_fixed_56px_height_at_mobile(): void
    {
        // Both min-height AND max-height locked to 56px so the
        // canvas-iframe padding-top can match a stable value.
        // Without max-height the toolbar would still grow if any
        // child overflowed vertically.
        $this->assertMatchesRegularExpression(
            '/#toolbar\\s*\\{[\\s\\S]*?min-height:\\s*56px[\\s\\S]*?max-height:\\s*56px/s',
            $this->cssSrc,
            'live-edit-classes.css: #toolbar must declare min-height AND max-height = 56px at mobile so the canvas padding-top matches'
        );
    }

    #[Test]
    public function canvas_iframe_wrapper_gets_56px_padding_top(): void
    {
        // The Filament admin layout's .fi-main-ctn AND the legacy
        // #iframe-holder AND any .live-edit-canvas-wrapper get the
        // padding so different mount points all behave the same.
        $required = ['.fi-layout .fi-main-ctn', '#iframe-holder', '.live-edit-canvas-wrapper'];
        foreach ($required as $sel) {
            $this->assertStringContainsString(
                $sel,
                $this->cssSrc,
                "live-edit-classes.css: canvas wrapper rule must include selector `{$sel}` so all mount-points pad the toolbar"
            );
        }
        $this->assertMatchesRegularExpression(
            '/\\.fi-layout\\s+\\.fi-main-ctn,\\s*\\n\\s*#iframe-holder,\\s*\\n\\s*\\.live-edit-canvas-wrapper\\s*\\{\\s*\\n\\s*padding-top:\\s*56px/s',
            $this->cssSrc,
            'live-edit-classes.css: canvas wrappers must declare padding-top: 56px (matches #toolbar fixed height)'
        );
    }

    #[Test]
    public function verbose_text_labels_collapse_to_icons_at_mobile(): void
    {
        // back-to-admin "ADMIN" text label hides; the arrow SVG +
        // aria-label="Back to admin" still convey the affordance.
        $this->assertMatchesRegularExpression(
            '/#toolbar\\s+#mw-live-edit-toolbar-back-to-admin-link\\s+span\\s*\\{[\\s\\S]*?display:\\s*none\\s*!important/s',
            $this->cssSrc,
            'live-edit-classes.css: back-to-admin "ADMIN" text label must hide at mobile (arrow SVG + aria-label preserve affordance)'
        );
        // VIEW button collapses to icon-only via font-size: 0 trick.
        // The ::before pseudo-element renders the eye glyph.
        $this->assertMatchesRegularExpression(
            '/#toolbar\\s+#mw-page-set-preview-mode\\s*\\{[\\s\\S]*?font-size:\\s*0[\\s\\S]*?padding-inline:\\s*0\\.5rem/s',
            $this->cssSrc,
            'live-edit-classes.css: VIEW button must collapse to icon-only via font-size: 0 + padding-inline at mobile'
        );
        $this->assertMatchesRegularExpression(
            '/#toolbar\\s+#mw-page-set-preview-mode::before\\s*\\{[\\s\\S]*?content:\\s*"[^"]+"/s',
            $this->cssSrc,
            'live-edit-classes.css: VIEW button must render an icon glyph via ::before content'
        );
    }
}
