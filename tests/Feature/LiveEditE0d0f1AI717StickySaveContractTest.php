<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-e0d0f1 / AI-717 (High → effectively Critical) —
 * CRITICAL: SAVE button completely off-screen on mobile 390.
 *
 * Designer DOM-probe at mobile 390:
 *   #toolbar scrollWidth: 525, clientWidth: 390 (135 px overflow)
 *   #mw-page-set-preview-mode x=372 (only 18 px visible)
 *   #save-button             x=432 (off-screen by 42 px)
 *
 * SAVE is the most important affordance in the entire live-edit
 * chrome. It was invisible by default at the dominant mobile
 * viewport — users had to horizontally scroll the toolbar to
 * find it. Designer-classified as High priority but effectively
 * Critical (an unreachable Save button is a data-loss risk).
 *
 * Interim fix (before AI-698 full toolbar compression lands):
 * sticky PREVIEW + SAVE to the right edge at ≤ 768 px via
 * `position: sticky; right: 0` with `var(--ese-surface)` bg and
 * an 8 px left-fade box-shadow. The middle scrollable region
 * carries the rest; PREVIEW + SAVE never participate in the
 * overflow.
 *
 * Single-file CSS-only fix — rules live in
 * `packages/microweber-filament-theme/resources/assets/css/
 * microweber/live-edit-mobile.css` inside the existing
 * `@media (max-width: 768px), (pointer: coarse)` block (added
 * by task-2026-05-16-74c5f5 — the same block that introduced
 * the horizontal-scroll behaviour AI-717 builds on).
 *
 * Bundle-runtime probe absent here because the CSS is in the
 * already-Webpack-bundled live-edit-mobile.css; the source-
 * level contract covers the same surface as the existing
 * AI-698a + AI-700 source-level tests.
 */
class LiveEditE0d0f1AI717StickySaveContractTest extends TestCase
{
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — sticky PREVIEW + SAVE base rule
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function preview_and_save_are_position_sticky(): void
    {
        // Both selectors must apply `position: sticky` — the
        // root mechanism that pins them while the middle row
        // scrolls under.
        $this->assertMatchesRegularExpression(
            '/#toolbar\s+#mw-page-set-preview-mode[\s\S]{0,500}#toolbar\s+#save-button[\s\S]{0,500}position:\s*sticky/s',
            $this->css,
            'Both PREVIEW and SAVE buttons must share a `position: sticky` rule per AI-717 (effectively Critical Save-visibility fix).'
        );
    }

    #[Test]
    public function sticky_rule_uses_ese_surface_background(): void
    {
        // The sticky cells need an opaque background so the
        // scrolling content underneath doesn't show through.
        $this->assertMatchesRegularExpression(
            '/#save-button[\s\S]{0,200}background-color:\s*var\(--ese-surface/s',
            $this->css,
            'AI-717 sticky rule must use var(--ese-surface) bg so scrolling content doesn\'t bleed through.'
        );
    }

    #[Test]
    public function sticky_rule_has_left_edge_fade(): void
    {
        // Designer-specified 8 px left-edge fade so scrolling
        // content slides past cleanly without a hard cliff. box-
        // shadow `-8px 0 8px -4px <surface>` is the canonical
        // shape.
        $this->assertMatchesRegularExpression(
            '/box-shadow:\s*-8px\s+0\s+8px\s+-4px\s+var\(--ese-surface/',
            $this->css,
            'AI-717 sticky rule must include an 8 px left-fade box-shadow per spec "8 px left fade".'
        );
    }

    #[Test]
    public function save_pins_to_right_zero(): void
    {
        // SAVE is the rightmost sticky cell — `right: 0`.
        $this->assertMatchesRegularExpression(
            '/#save-button[\s\S]{0,500}right:\s*0\s*!important/s',
            $this->css,
            'AI-717: #save-button must pin to right: 0 — it\'s the rightmost sticky cell.'
        );
    }

    #[Test]
    public function preview_pins_inside_save_with_space_sm_offset(): void
    {
        // PREVIEW sits just inside SAVE — `right: calc(--space-sm + 80px)`
        // (the SAVE button's natural width + the spec --space-sm gap).
        $this->assertMatchesRegularExpression(
            '/#mw-page-set-preview-mode[\s\S]{0,500}right:\s*calc\(\s*var\(--space-sm,\s*8px\)\s*\+\s*80px\s*\)/s',
            $this->css,
            'AI-717: #mw-page-set-preview-mode must pin at right: calc(var(--space-sm) + 80px) so it sits just inside SAVE.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — sticky cells live INSIDE the mobile @media block
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function sticky_rules_inside_mobile_media_query(): void
    {
        // The fix MUST live inside `@media (max-width: 768px)`
        // (or the pointer:coarse variant). Otherwise the rules
        // fire on desktop where the toolbar already fits and
        // would create unwanted right-edge sticking.
        //
        // The existing mobile @media block was opened by
        // task-2026-05-16-74c5f5 with selector list `(max-width:
        // 768px), (pointer: coarse)`. Slice from AI-717 marker
        // walking UP to find the nearest preceding @media decl.
        $aiPos = strpos($this->css, 'AI-717');
        $this->assertNotFalse($aiPos, 'AI-717 marker must be present in live-edit-mobile.css.');
        $beforeMarker = substr($this->css, 0, $aiPos);
        $lastMediaPos = strrpos($beforeMarker, '@media');
        $this->assertNotFalse($lastMediaPos, 'AI-717 must sit inside an @media block.');
        $mediaLine = substr($this->css, $lastMediaPos, 150);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*768px/',
            $mediaLine,
            'AI-717 must live inside the @media (max-width: 768px) block so the sticky-right behaviour fires only on mobile.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — z-index so sticky paints above scrolling middle
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function sticky_rule_uses_z_index(): void
    {
        // Without z-index the scrolling content can paint above
        // the sticky cells when GPU compositing reorders layers.
        $this->assertMatchesRegularExpression(
            '/position:\s*sticky[\s\S]{0,200}z-index:\s*3/',
            $this->css,
            'AI-717 sticky rule must declare z-index: 3 so sticky cells paint above the scrolling middle row.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Markers + scope hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-16-e0d0f1', $this->css);
    }

    #[Test]
    public function ai717_marker_present(): void
    {
        $this->assertStringContainsString('AI-717', $this->css);
    }

    #[Test]
    public function selectors_scoped_to_live_edit_page(): void
    {
        // Every AI-717 rule must be scoped to the live-edit
        // wrapper (`.mw-admin-live-edit-page #toolbar` and/or
        // `.mw-live-edit-page #toolbar`) so the sticky behaviour
        // doesn't leak into any other surface that happens to
        // host an element with `id="save-button"`.
        $aiPos = strpos($this->css, 'AI-717');
        $this->assertNotFalse($aiPos);
        $docblockEnd = strpos($this->css, '*/', $aiPos);
        $this->assertNotFalse($docblockEnd);
        // Inspect just the rule block after the docblock.
        $slice = substr($this->css, $docblockEnd + 2, 3000);

        $stickyRefs = preg_match_all(
            '/#save-button|#mw-page-set-preview-mode/',
            $slice
        );
        $scopedRefs = preg_match_all(
            '/\.mw-(admin-)?live-edit-page\s+#toolbar\s+(#save-button|#mw-page-set-preview-mode)/',
            $slice
        );
        $this->assertGreaterThanOrEqual(
            $stickyRefs,
            $scopedRefs,
            'Every #save-button / #mw-page-set-preview-mode selector in the AI-717 slice must carry the `.mw-(admin-)?live-edit-page #toolbar` scope prefix — found ' . $stickyRefs . ' selectors, ' . $scopedRefs . ' scoped.'
        );
    }

    #[Test]
    public function token_fallback_hygiene(): void
    {
        // var(--ese-surface) and var(--space-sm) are consumed —
        // both must carry literal fallbacks per SOUL #108.
        // Slice past docblock to avoid self-match (LESSONS,
        // 9th session-occurrence).
        $aiPos = strpos($this->css, 'AI-717');
        $this->assertNotFalse($aiPos);
        $docblockEnd = strpos($this->css, '*/', $aiPos);
        $this->assertNotFalse($docblockEnd);
        $slice = substr($this->css, $docblockEnd + 2, 3000);

        $this->assertMatchesRegularExpression(
            '/var\(--ese-surface,\s*#ffffff\)/',
            $slice,
            '--ese-surface must carry literal #ffffff fallback in the AI-717 slice.'
        );
        $this->assertMatchesRegularExpression(
            '/var\(--space-sm,\s*8px\)/',
            $slice,
            '--space-sm must carry literal 8px fallback in the AI-717 slice.'
        );
    }
}
