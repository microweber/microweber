<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-29342d / AI-703 (Medium) — Responsive sidebar:
 * pinned ≥ 1280 px, rail/overlay/full-screen below.
 *
 * Designer dispatch (admin-shell-improvements-2026-05-16.md §2 AD2,
 * per-ticket email 2026-05-16T13:39): current admin hides primary
 * navigation behind a hamburger at all viewports — every nav costs
 * two clicks and users lose "where am I" at a glance. Fix is to
 * enable Filament's responsive sidebar (pinned at desktop, overlay
 * below) and persist the user's mode preference.
 *
 * Slice-1 implementation (this commit):
 *
 *   1. FilamentAdminPanelProvider.php
 *      - ->sidebarCollapsibleOnDesktop() enabled (was commented out).
 *        Filament's native lg+ (1024px) breakpoint pins the sidebar
 *        open with a collapse-to-rail toggle.
 *      - ->sidebarWidth('240px') per spec (was 16rem = 256px).
 *      - New PanelsRenderHook::BODY_END renderHook injects a small
 *        <script> that mirrors Filament's body classes
 *        (`fi-sidebar-open`, `fi-sidebar-collapsed-on-desktop`) to
 *        `localStorage.admin_sidebar_mode` with values
 *        'pinned' | 'rail' | 'collapsed' per designer spec.
 *
 *   2. general-styles.css
 *      - body.fi-panel-admin .fi-sidebar { --sidebar-width: 240px; }
 *        — defence-in-depth pin.
 *      - At @media (min-width: 1024px) on .fi-sidebar-open body, the
 *        pinned sidebar gets a 1px --ese-border right-edge divider
 *        (with dark-theme variant) for tasteful chrome separation.
 *
 * Slice-2 / AI-703a follow-up candidate (NOT shipped here, flagged
 * in inline source comments + this docblock): shift the desktop
 * breakpoint from Filament's native 1024px to the designer-spec
 * 1280px. Requires undoing several `lg:` utility classes that
 * Filament bakes into the rendered HTML; out of scope for slice-1.
 *
 * Token-scoping note (per SOUL #108 spec-doc-nit): the AI-703 CSS
 * renders on the admin chrome (inside body.fi-panel-admin), where
 * :root ESE tokens resolve naturally. Every var() carries a literal
 * fallback for defence-in-depth.
 */
class Admin29342dAI703ResponsiveSidebarContractTest extends TestCase
{
    private string $panelProvider;
    private string $generalStyles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->panelProvider = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php'
        ));
        $this->generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Panel provider PHP config
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function sidebar_collapsible_on_desktop_is_enabled(): void
    {
        // Active (uncommented) call — leading whitespace allowed, no `//`.
        $this->assertMatchesRegularExpression(
            '/^\s*->sidebarCollapsibleOnDesktop\(\)/m',
            $this->panelProvider,
            '->sidebarCollapsibleOnDesktop() must be ACTIVE (uncommented) so the desktop pinned-rail pattern is enabled.'
        );
    }

    #[Test]
    public function sidebar_collapsible_call_is_not_commented_out(): void
    {
        // Negative regression-guard: ensure the prior `// ->sidebarCollapsibleOnDesktop()`
        // commented form is gone (or only present in the historical-note docblock).
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\/\/\s*->sidebarCollapsibleOnDesktop\(\)/m',
            $this->panelProvider,
            'The pre-AI-703 `// ->sidebarCollapsibleOnDesktop()` commented form must be removed.'
        );
    }

    #[Test]
    public function sidebar_width_is_240px(): void
    {
        $this->assertMatchesRegularExpression(
            "/->sidebarWidth\(\s*'240px'\s*\)/",
            $this->panelProvider,
            "->sidebarWidth('240px') must be set per designer spec (was 16rem = 256px)."
        );
    }

    #[Test]
    public function sidebar_width_does_not_carry_legacy_16rem_value(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            "/->sidebarWidth\(\s*'16rem'\s*\)/",
            $this->panelProvider,
            "->sidebarWidth('16rem') legacy value must be replaced by '240px' per spec."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — admin_sidebar_mode localStorage bridge
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function body_end_renderhook_registered_for_sidebar_mode_bridge(): void
    {
        $this->assertMatchesRegularExpression(
            '/AI-703[\s\S]*?\$panel->renderHook\(\s*name:\s*PanelsRenderHook::BODY_END/',
            $this->panelProvider,
            'AI-703 must register a render hook at PanelsRenderHook::BODY_END for the localStorage.admin_sidebar_mode bridge.'
        );
    }

    #[Test]
    public function bridge_script_uses_canonical_localstorage_key(): void
    {
        $this->assertMatchesRegularExpression(
            "/AI-703[\\s\\S]*?KEY\\s*=\\s*'admin_sidebar_mode'/",
            $this->panelProvider,
            "The localStorage key MUST be 'admin_sidebar_mode' per designer spec (admin-shell-improvements §2 AD2)."
        );
    }

    #[Test]
    public function bridge_script_reads_three_canonical_mode_values(): void
    {
        // Every spec'd mode value must appear as a literal return
        // string in the readMode() helper.
        foreach (['pinned', 'rail', 'collapsed'] as $mode) {
            $this->assertMatchesRegularExpression(
                "/AI-703[\\s\\S]*?return\\s+'{$mode}'/",
                $this->panelProvider,
                "Bridge script must return '{$mode}' as one of the three localStorage values."
            );
        }
    }

    #[Test]
    public function bridge_script_observes_body_class_via_mutationobserver(): void
    {
        // task-2026-05-17-6cb0d8 / AI-703 CHANGE — the observer
        // target moved from `body` to `.fi-sidebar` because
        // Filament v5 does NOT toggle the sidebar state classes on
        // body in this build. The MutationObserver primitive shape
        // is preserved (still attributeFilter: ['class']).
        $this->assertMatchesRegularExpression(
            "/AI-703[\\s\\S]*?new\\s+MutationObserver\\(\\s*writeMode\\s*\\)[\\s\\S]*?attributeFilter:\\s*\\['class'\\]/",
            $this->panelProvider,
            'Bridge script must use MutationObserver with attributeFilter: ["class"] to track Filament sidebar state.'
        );
    }

    #[Test]
    public function bridge_script_is_fi_panel_admin_scoped(): void
    {
        // Defence-in-depth: the BODY_END hook fires for every panel,
        // so the inline script must early-return if body isn't on the
        // admin panel — otherwise the checkout / profile panels would
        // also write the admin localStorage key.
        $this->assertMatchesRegularExpression(
            "/AI-703[\\s\\S]*?body\\.classList\\.contains\\(\\s*'fi-panel-admin'\\s*\\)/",
            $this->panelProvider,
            'Bridge script must early-return unless body.fi-panel-admin is set (avoid cross-panel writes to admin_sidebar_mode).'
        );
    }

    #[Test]
    public function bridge_script_reads_canonical_filament_state_classes(): void
    {
        // task-2026-05-17-6cb0d8 / AI-703 CHANGE — designer's
        // verification at 1440 + overlay + mobile + dark found the
        // pre-CHANGE bridge stuck because Filament v5 does NOT
        // toggle `fi-sidebar-collapsed-on-desktop` on body in this
        // build — the canonical state lives on `.fi-sidebar` itself
        // via x-bind:class="{ 'fi-sidebar-open': $store.sidebar.isOpen }"
        // (see vendor/filament/filament/resources/views/livewire/
        // sidebar.blade.php line 19). The bridge now observes the
        // `.fi-sidebar` element directly + uses window.innerWidth
        // as a 1024 px desktop/mobile tiebreaker for the rail mode.
        $this->assertMatchesRegularExpression(
            "/AI-703[\\s\\S]*?'fi-sidebar-open'/",
            $this->panelProvider,
            "Bridge script must read the canonical Filament class 'fi-sidebar-open' (the only class Filament v5 toggles in this build)."
        );
        $this->assertMatchesRegularExpression(
            "/AI-703[\\s\\S]*?DESKTOP_PX\\s*=\\s*1024/",
            $this->panelProvider,
            "Bridge script must define DESKTOP_PX = 1024 — the breakpoint that drives the rail/collapsed distinction post-CHANGE."
        );
        $this->assertMatchesRegularExpression(
            "/AI-703[\\s\\S]*?document\\.querySelector\\(\\s*'\\.fi-sidebar'\\s*\\)/",
            $this->panelProvider,
            "Bridge script must query the `.fi-sidebar` element directly — post-CHANGE observer target."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — CSS shape (defence-in-depth width pin + divider)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function css_pins_sidebar_width_240px(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-sidebar\s*\{[^}]*--sidebar-width:\s*240px/s',
            $this->generalStyles,
            'CSS must pin --sidebar-width: 240px on body.fi-panel-admin .fi-sidebar (defence-in-depth alongside PHP config).'
        );
    }

    #[Test]
    public function css_adds_pinned_sidebar_divider_at_lg(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*min-width:\s*1024px\s*\)\s*\{[\s\S]*?body\.fi-panel-admin\.fi-sidebar-open\s+\.fi-sidebar\s*\{[^}]*border-inline-end:\s*1px\s+solid\s+var\(--ese-border/s',
            $this->generalStyles,
            'CSS must add a 1px --ese-border right-edge divider on the pinned sidebar at @media (min-width: 1024px) when the sidebar is open.'
        );
    }

    #[Test]
    public function css_divider_has_dark_theme_variant(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.dark\s+body\.fi-panel-admin\.fi-sidebar-open\s+\.fi-sidebar\s*\{[^}]*border-inline-end-color:\s*var\(--ese-border/s',
            $this->generalStyles,
            'CSS must include a dark-theme override for the divider colour via .dark.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-29342d', $this->panelProvider);
        $this->assertStringContainsString('task-2026-05-16-29342d', $this->generalStyles);
    }

    #[Test]
    public function ai703a_followup_documented_in_source_comments(): void
    {
        // Slice-2 follow-up (1024px → 1280px breakpoint shift) MUST be
        // discoverable in the source so future agents pick up the
        // deferred work cleanly.
        $this->assertStringContainsString(
            'AI-703a',
            $this->panelProvider,
            'AI-703a follow-up candidate (breakpoint shift to 1280px) must be flagged in panel-provider comments.'
        );
        $this->assertStringContainsString(
            'AI-703a',
            $this->generalStyles,
            'AI-703a follow-up candidate must be flagged in general-styles.css comments.'
        );
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        $start = strpos($this->generalStyles, 'AI-703 — Microweber admin responsive sidebar');
        $this->assertNotFalse($start, 'AI-703 task marker must be present in general-styles.css.');
        $slice = substr($this->generalStyles, $start);

        // The two var() consumers in the slice — light + dark divider
        // colours — both reference --ese-border with literal rgba()
        // fallbacks per SOUL #108 spec-doc-nit.
        $this->assertMatchesRegularExpression(
            '/var\(--ese-border,\s*rgba\(0,\s*0,\s*0,\s*0\.08\)\)/',
            $slice,
            '--ese-border MUST carry a literal rgba(0, 0, 0, 0.08) fallback in the light-theme rule.'
        );
        $this->assertMatchesRegularExpression(
            '/var\(--ese-border,\s*rgba\(255,\s*255,\s*255,\s*0\.12\)\)/',
            $slice,
            '--ese-border MUST carry a literal rgba(255, 255, 255, 0.12) fallback in the dark-theme rule.'
        );
    }
}
