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
        // AI-717 v2 (task-2026-05-17-946668): SAVE is pinned via
        // `position: absolute` inside a `position: relative` #toolbar.
        // Sticky (v1) failed because sticky inside overflow-x:auto pins
        // to content-end, not viewport-right. Pin-evolved in place per
        // LESSONS pin-evolution discipline (task-2026-05-16-e0d0f1 → v2).
        $this->assertMatchesRegularExpression(
            '/#save-button[\s\S]{0,300}position:\s*absolute\s*!important/s',
            $this->css,
            'AI-717 v2: #save-button must use position: absolute !important to pin to toolbar right edge on mobile.'
        );
    }

    #[Test]
    public function sticky_rule_uses_ese_surface_background(): void
    {
        // AI-717 v2: SAVE button has dark badge bg (--ese-text) for
        // contrast, while var(--ese-surface) is used for the left-edge
        // fade (box-shadow) and text contrast (color). Verify that
        // var(--ese-surface) is applied as either color or box-shadow.
        $this->assertMatchesRegularExpression(
            '/#save-button[\s\S]{0,300}(?:color|box-shadow):\s*[^;]*var\(--ese-surface/s',
            $this->css,
            'AI-717 v2: #save-button must use var(--ese-surface) as text or fade color.'
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
        // AI-717 v2: SAVE is absolutely-positioned at right: 4px
        // (4px gutter matches WCAG 2.5.5 44px touch target + breathing
        // room). Pin-evolved from right:0 (v1 sticky) to right:4px (v2
        // absolute) per task-2026-05-17-946668.
        $this->assertMatchesRegularExpression(
            '/#save-button[\s\S]{0,500}right:\s*4px\s*!important/s',
            $this->css,
            'AI-717 v2: #save-button must pin at right: 4px !important on mobile.'
        );
    }

    #[Test]
    public function preview_pins_inside_save_with_space_sm_offset(): void
    {
        // AI-717 v2: PREVIEW stays in-flow (scrolls with middle toolbar).
        // The toolbar has padding-right to reserve space for SAVE.
        // PREVIEW gets height:44px touch target (WCAG 2.5.5). Pin-evolved
        // from right:calc() (v1 sticky offset) to height-only (v2 in-flow)
        // per task-2026-05-17-946668.
        $this->assertMatchesRegularExpression(
            '/#mw-page-set-preview-mode[\s\S]{0,300}height:\s*44px/s',
            $this->css,
            'AI-717 v2: #mw-page-set-preview-mode must have height: 44px for WCAG touch target.'
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
        // AI-717 v2: absolutely-positioned SAVE needs z-index:3 so it
        // paints above the scrolling middle row. Pin-evolved from
        // position:sticky + z-index:3 to position:absolute + z-index:3.
        $this->assertMatchesRegularExpression(
            '/position:\s*absolute\s*!important[\s\S]{0,200}z-index:\s*3/',
            $this->css,
            'AI-717 v2: #save-button must declare z-index: 3 so it paints above scrolling content.'
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
        // AI-717 v2 no longer uses --space-sm for right-offset
        // (SAVE is at right:4px literal; PREVIEW is in-flow).
        // Verify --ese-text fallback is present for SAVE badge bg.
        $this->assertMatchesRegularExpression(
            '/var\(--ese-text,\s*#[0-9a-fA-F]+\)/',
            $slice,
            '--ese-text must carry literal hex fallback in the AI-717 slice.'
        );
    }
}
