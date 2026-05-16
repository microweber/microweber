<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-af3348 / AI-718 (High) — Right-rail eats 64 px
 * (16%) on mobile + 2-line text labels.
 *
 * Designer dispatch (per-ticket email 2026-05-16T13:58): the
 * right vertical rail at mobile 390 renders 5 cells each 64×56 px
 * with 2-line text labels under each icon (the AI-178 /
 * task-8149b5 treatment). The rail ate ~16% of viewport width
 * for navigation chrome.
 *
 * Two options offered — designer recommended shipping Option A
 * as interim, then upgrading to Option B when AI-685 lands:
 *
 *   A (interim, this ticket):
 *     - Drop text labels at ≤768px (keep `title=` for desktop
 *       hover + aria-label for AT).
 *     - Reduce rail width 72px → 40px.
 *     - Reduce button width 64px → 40px.
 *     - Preserve min-height 44px on buttons (WCAG 2.5.5
 *       vertical tap area unchanged).
 *
 *   B (recommended final, blocked on AI-685):
 *     - Collapse rail entirely; replace with bottom-anchored
 *       Tools pill + bottom-sheet. Saves the full 40px column.
 *     - Flagged AI-718b follow-up candidate.
 *
 * Single-file CSS-only fix in
 * `packages/microweber-filament-theme/resources/assets/css/
 * microweber/live-edit-mobile.css`, inside the existing
 * `@media (max-width: 768px)` block that already hosts the
 * task-8149b5 rules AI-718 overrides.
 *
 * Scope discipline: the `::after { content: attr(aria-label) }`
 * label suppression is scoped INSIDE
 * `.mw-live-edit-right-sidebar-template-sidebar` only —
 * top-toolbar buttons keep their AI-178 2-line labels per
 * the original task-8149b5 spec.
 */
class LiveEditAf3348AI718RightRailNarrowContractTest extends TestCase
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
    // Group A — Label suppression scoped to right rail only
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function label_after_pseudo_suppressed_inside_right_rail(): void
    {
        // The AI-178/task-8149b5 `::after { content: attr(aria-label) }`
        // pseudo must be overridden with `content: none` ONLY for
        // buttons inside .mw-live-edit-right-sidebar-template-
        // sidebar — top-toolbar buttons keep their labels.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-template-sidebar[\s\S]{0,500}\.btn-icon\.live-edit-toolbar-buttons::after[\s\S]{0,500}content:\s*none\s*!important/s',
            $this->css,
            'AI-718 must suppress the ::after pseudo-label inside `.mw-live-edit-right-sidebar-template-sidebar` only.'
        );
    }

    #[Test]
    public function label_suppression_includes_filament_icon_btn_alias(): void
    {
        // task-8149b5 covers three button selector shapes:
        // .live-edit-toolbar-buttons.mw-toolbar-icon-btn,
        // .btn-icon.live-edit-toolbar-buttons, and the AI-141
        // Filament alias .fi-icon-btn. The AI-718 suppression
        // must override all three inside the right rail.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-template-sidebar[\s\S]{0,500}\.fi-icon-btn::after/s',
            $this->css,
            'AI-718 label suppression must include the .fi-icon-btn alias variant so Filament-styled buttons in the right rail also lose their labels.'
        );
    }

    #[Test]
    public function top_toolbar_label_rule_left_intact(): void
    {
        // Critical regression guard: the AI-178/task-8149b5
        // rule that renders the 2-line label on top-toolbar
        // buttons must still be present (only the right-rail
        // scope gets the override).
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.live-edit-toolbar-buttons\.mw-toolbar-icon-btn::after[\s\S]{0,500}content:\s*attr\(aria-label\)/s',
            $this->css,
            'AI-178/task-8149b5 top-toolbar 2-line label rule must remain — AI-718 only suppresses inside the right-rail scope.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Rail width 40px + button width 40px
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function rail_width_is_40px(): void
    {
        // The previous task-8149b5 rule (72px) is preserved as
        // a record but overridden by the AI-718 rule further
        // down the cascade. Multiple `width:` declarations
        // may match — assert the AI-718-marked block applies
        // 40px.
        $afterAi718 = strpos($this->css, 'AI-718 narrow rail');
        $this->assertNotFalse($afterAi718, 'AI-718 narrow-rail marker must be present.');
        $slice = substr($this->css, $afterAi718, 800);
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-template-sidebar\s*\{[^}]*width:\s*40px\s*!important/',
            $slice,
            'AI-718 must set right-rail width to 40px !important.'
        );
    }

    #[Test]
    public function button_width_is_40px_with_44px_min_height(): void
    {
        $afterAi718 = strpos($this->css, 'AI-718 narrow rail');
        $this->assertNotFalse($afterAi718);
        $slice = substr($this->css, $afterAi718, 1200);

        $this->assertMatchesRegularExpression(
            '/\.btn-icon\.live-edit-toolbar-buttons[\s\S]{0,500}width:\s*40px\s*!important/',
            $slice,
            'AI-718 right-rail button width must be 40px !important.'
        );
        $this->assertMatchesRegularExpression(
            '/\.btn-icon\.live-edit-toolbar-buttons[\s\S]{0,500}min-height:\s*44px\s*!important/',
            $slice,
            'AI-718 right-rail button must preserve min-height: 44px !important (WCAG 2.5.5 vertical tap area).'
        );
    }

    #[Test]
    public function advanced_settings_popup_also_narrows(): void
    {
        // .mw-live-edit-advanced-settings-popup is the popover-
        // style button that sits in the same rail and was
        // sized alongside .btn-icon in task-8149b5.
        $afterAi718 = strpos($this->css, 'AI-718 narrow rail');
        $this->assertNotFalse($afterAi718);
        $slice = substr($this->css, $afterAi718, 1200);
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-advanced-settings-popup[\s\S]{0,200}width:\s*40px/',
            $slice,
            'AI-718 must narrow .mw-live-edit-advanced-settings-popup to 40px alongside the regular rail buttons.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Icon stays centred + 22px
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function icon_svg_is_22px(): void
    {
        // Without the label, the icon must do the full job —
        // 22px (the same size task-8149b5 used inside the
        // 64×56 padded button).
        $afterAi718 = strpos($this->css, 'AI-718 narrow rail');
        $this->assertNotFalse($afterAi718);
        $slice = substr($this->css, $afterAi718, 1600);
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-template-sidebar[\s\S]{0,500}svg\s*\{[^}]*width:\s*22px[^}]*height:\s*22px/s',
            $slice,
            'AI-718 must size right-rail SVG icons to 22×22 so they read clearly without the label.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Mobile @media scope + AI-718b followup flag
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai718_rules_inside_mobile_media_query(): void
    {
        // Slice from the AI-718 marker walking UP to the
        // nearest preceding @media; assert max-width: 768px.
        $aiPos = strpos($this->css, 'AI-718 (Option A — interim');
        $this->assertNotFalse($aiPos, 'AI-718 docblock marker must be present.');
        $beforeMarker = substr($this->css, 0, $aiPos);
        $lastMediaPos = strrpos($beforeMarker, '@media');
        $this->assertNotFalse($lastMediaPos);
        $mediaLine = substr($this->css, $lastMediaPos, 150);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*768px/',
            $mediaLine,
            'AI-718 rules must live inside @media (max-width: 768px) so desktop is unaffected.'
        );
    }

    #[Test]
    public function ai718b_followup_documented(): void
    {
        $this->assertStringContainsString(
            'AI-718b',
            $this->css,
            'AI-718b follow-up candidate (Option B — collapse rail + bottom-sheet, blocked on AI-685) must be flagged in source comments.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-16-af3348', $this->css);
    }

    #[Test]
    public function ai718_marker_present(): void
    {
        $this->assertStringContainsString('AI-718', $this->css);
    }
}
