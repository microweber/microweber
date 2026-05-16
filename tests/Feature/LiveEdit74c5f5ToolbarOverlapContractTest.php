<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-74c5f5 — Live Edit toolbar must NOT overlap the
 * right-side `.mw-live-edit-right-sidebar-wrapper` icon strip on
 * narrow / touch viewports.
 *
 * Root cause (browser-verified at viewport 560×440 before fix):
 *
 *   #toolbar { height: 60px } AND
 *   @media (max-width: 768px), (pointer: coarse) {
 *     .mw-admin-live-edit-page #toolbar { flex-wrap: wrap }
 *   }
 *
 * combined cause toolbar children to wrap onto a second visual row
 * at y=60..116. The right sidebar is `position: absolute; top: 60`
 * — sitting in exactly the space the wrapped Menu / Save & Publish
 * buttons fall into. The buttons get visually clipped / covered by
 * the right sidebar (and its tooltip labels at narrow breakpoint).
 *
 * Fix (one CSS source change):
 *   - flex-wrap: wrap !important → flex-wrap: nowrap !important
 *   - keep the existing overflow-x: auto + scroll-snap so the
 *     toolbar scrolls horizontally instead of growing vertically
 *
 * Source lives at packages/microweber-filament-theme/resources/assets/
 * css/microweber/live-edit-mobile.css. Built via Webpack and served
 * from public/vendor/microweber-packages/microweber-filament-theme/build/.
 */
class LiveEdit74c5f5ToolbarOverlapContractTest extends TestCase
{
    private string $source;
    private string $served;

    protected function setUp(): void
    {
        parent::setUp();
        $this->source = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css'
        ));
        $this->served = (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        ));
    }

    #[Test]
    public function source_declares_nowrap_on_the_narrow_viewport_toolbar(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s*#toolbar,\s*\.mw-live-edit-page\s*#toolbar\s*\{[^}]*flex-wrap:\s*nowrap\s*!important/s',
            $this->source,
            'Source CSS must declare flex-wrap: nowrap on the narrow-viewport toolbar.'
        );
    }

    #[Test]
    public function source_does_not_resurrect_flex_wrap_wrap_on_toolbar(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-(admin-)?live-edit-page\s*#toolbar[^{]*\{[^}]*flex-wrap:\s*wrap/s',
            $this->source,
            'Toolbar must not regress to flex-wrap: wrap (would re-introduce the overlap).'
        );
    }

    #[Test]
    public function source_keeps_horizontal_scroll_safety_net(): void
    {
        // flex-wrap: nowrap on its own would clip overflow; the safety
        // net is overflow-x: auto on the same rule.
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s*#toolbar,\s*\.mw-live-edit-page\s*#toolbar\s*\{[^}]*overflow-x:\s*auto/s',
            $this->source,
            'Toolbar rule must keep overflow-x: auto so children remain reachable when they exceed viewport width.'
        );
    }

    #[Test]
    public function source_children_carry_flex_shrink_zero(): void
    {
        // Without flex-shrink: 0 the children would compress instead
        // of overflowing — the scroll-snap would never trigger and
        // hit-targets would shrink below 44px.
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s*#toolbar\s*>\s*\*,\s*\.mw-live-edit-page\s*#toolbar\s*>\s*\*\s*\{[^}]*flex-shrink:\s*0/s',
            $this->source
        );
    }

    #[Test]
    public function served_bundle_contains_the_fixed_rule(): void
    {
        // Webpack output minifies but preserves the keyword tokens. We
        // assert the pair of selectors + the nowrap keyword co-occur
        // within a small window.
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s*#toolbar,\s*\.mw-live-edit-page\s*#toolbar\s*\{[^}]*flex-wrap:\s*nowrap/s',
            $this->served,
            'Built bundle at public/vendor/.../microweber-filament-theme.css must carry the nowrap fix.'
        );
    }

    #[Test]
    public function task_id_is_pinned_in_the_source_comment(): void
    {
        $this->assertStringContainsString('task-2026-05-16-74c5f5', $this->source);
    }
}
