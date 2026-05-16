<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-225150 / AI-704 (Medium) — Re-cluster + Add and
 * Live Edit on the right side of admin top bar.
 *
 * Designer dispatch (admin-shell-improvements-2026-05-16.md §2 AD3,
 * per-ticket email 2026-05-16T13:39): + Add was visually isolated
 * top-left between the hamburger and the search field, competing
 * with the brand mark (AI-702) and far from the other primary
 * action Live Edit on the right. Fix is to move + Add into the
 * right-side action cluster next to Live Edit so the primary
 * actions read as a single unit.
 *
 * Target layout:
 *   [brand]  [☰]  ········  [search]  ········  [+ Add]  [Live Edit]  [bell]  [user]
 *                                                └─── primary actions ───┘
 *
 * Three-surface implementation:
 *
 *   1. FilamentAdminPanelProvider.php
 *      - Standalone `TOPBAR_START` hook for `admin-top-navigation-actions`
 *        REMOVED (was sitting at the left edge between the brand and
 *        the hamburger).
 *      - `GLOBAL_SEARCH_AFTER` hook now wraps both + Add Livewire
 *        component AND the Live Edit pill in a single
 *        `<div class="mw-admin-primary-actions">` flex container,
 *        followed by the existing search-quick-nav view.
 *
 *   2. top-navigation-actions.blade.php (Livewire view for + Add)
 *      - Button restyled as icon-only (Plus icon visible; "Add" label
 *        moved into `<span class="sr-only">` so AT users still hear it).
 *      - Explicit `aria-label="Add new content"` + `title="Add new content"`
 *        on the trigger so screen-reader output doesn't degrade to
 *        icon-only.
 *
 *   3. general-styles.css
 *      - `.mw-admin-primary-actions` wrapper: inline-flex with
 *        `gap: var(--space-sm, 8px)` per spec.
 *      - `.fi-btn.admin-toolbar-add`: MwToolButton default geometry
 *        (32×32 hit area, 16 px icon, transparent ghost,
 *        `--ese-surface-hover` on hover, `--ese-accent` focus-visible
 *        outline, `--radius-md` rounding).
 *      - `.fi-btn.admin-toolbar-live-edit`: v2 primary pill matching
 *        the AI-699 SAVE pill contract (`--ese-text` bg, `--ese-surface`
 *        text, `--radius-pill`, `var(--space-sm) var(--space-md)`
 *        padding, opacity 0.9 on hover).
 *      - Mobile `@media (max-width: 768px)` hides + Add per spec.
 *      - `prefers-reduced-motion: reduce` disables both transitions.
 *
 * Slice-2 / AI-704a follow-up candidate: render + Add as an explicit
 * item inside the hamburger drawer on mobile (vs the current
 * "hide via CSS" approach). Flagged in source comments + this
 * docblock.
 *
 * Token-scoping note (per SOUL #108 spec-doc-nit): every var() in
 * the AI-704 slice carries a literal fallback because Filament-rendered
 * topbar HTML may render in surfaces where the ESE token stylesheet
 * is later in the cascade.
 */
class Admin225150AI704PrimaryActionsClusterContractTest extends TestCase
{
    private string $panelProvider;
    private string $generalStyles;
    private string $addButtonBlade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->panelProvider = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php'
        ));
        $this->generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $this->addButtonBlade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Admin/resources/views/livewire/filament/top-navigation-actions.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Panel provider re-cluster
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function topnav_actions_no_longer_registered_as_topbar_start_hook(): void
    {
        // The standalone TOPBAR_START hook that rendered +Add at the
        // left edge must be removed. The marker is the exact line
        // shape "name: PanelsRenderHook::TOPBAR_START," followed
        // SHORTLY by "@livewire('admin-top-navigation-actions')".
        $this->assertDoesNotMatchRegularExpression(
            '/name:\s*PanelsRenderHook::TOPBAR_START,\s*\n\s*hook:\s*fn\(\)\s*:\s*string\s*=>\s*Blade::render\(\'@livewire\(\\\\?\'admin-top-navigation-actions\\\\?\'\)\'\)/',
            $this->panelProvider,
            'The standalone TOPBAR_START hook for `admin-top-navigation-actions` must be REMOVED (was sitting at the left edge before AI-704).'
        );
    }

    #[Test]
    public function topnav_actions_now_rendered_inside_global_search_after_cluster(): void
    {
        // After AI-704, the +Add Livewire component renders inside the
        // GLOBAL_SEARCH_AFTER hook, alongside Live Edit, wrapped in a
        // `.mw-admin-primary-actions` div. The marker chain is:
        // GLOBAL_SEARCH_AFTER -> mw-admin-primary-actions -> admin-top-navigation-actions.
        $this->assertMatchesRegularExpression(
            "/AI-704[\\s\\S]*?PanelsRenderHook::GLOBAL_SEARCH_AFTER[\\s\\S]*?mw-admin-primary-actions[\\s\\S]*?admin-top-navigation-actions/",
            $this->panelProvider,
            'After AI-704, +Add Livewire must render inside the GLOBAL_SEARCH_AFTER hook wrapped in .mw-admin-primary-actions, alongside Live Edit.'
        );
    }

    #[Test]
    public function primary_actions_wrapper_contains_both_add_and_live_edit(): void
    {
        // The wrapper must contain BOTH the +Add Livewire component
        // AND the Live Edit view, in that order, so the cluster reads
        // as "+ Add | Live Edit" left-to-right.
        $this->assertMatchesRegularExpression(
            '/<div\s+class="mw-admin-primary-actions">[\s\S]*?admin-top-navigation-actions[\s\S]*?top-navigation-go-live-edit[\s\S]*?<\/div>/',
            $this->panelProvider,
            '.mw-admin-primary-actions wrapper must contain +Add (admin-top-navigation-actions) FOLLOWED by Live Edit (top-navigation-go-live-edit) in that order.'
        );
    }

    #[Test]
    public function search_quick_nav_renders_outside_primary_actions_wrapper(): void
    {
        // search-quick-nav is NOT a primary action — it must render
        // OUTSIDE the .mw-admin-primary-actions wrapper, AFTER its
        // closing </div>, so it doesn't get the cluster gap styling.
        $this->assertMatchesRegularExpression(
            '/AI-704[\s\S]*?<\/div>[\s\S]*?search-quick-nav/',
            $this->panelProvider,
            'search-quick-nav must render OUTSIDE the .mw-admin-primary-actions wrapper (after the closing </div>).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Blade button restyle (icon-only + aria-label)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function add_button_blade_has_explicit_aria_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/aria-label="Add new content"/',
            $this->addButtonBlade,
            'The +Add trigger button must carry an explicit aria-label since the visible "Add" text is now visually hidden.'
        );
    }

    #[Test]
    public function add_button_blade_hides_text_label_via_sr_only(): void
    {
        $this->assertMatchesRegularExpression(
            '/<span\s+class="sr-only">\s*Add\s*<\/span>/',
            $this->addButtonBlade,
            'The +Add trigger button must wrap the "Add" text in <span class="sr-only"> for AT users while keeping the button icon-only visually.'
        );
    }

    #[Test]
    public function add_button_blade_retains_plus_icon(): void
    {
        $this->assertMatchesRegularExpression(
            '/icon="heroicon-m-plus"/',
            $this->addButtonBlade,
            'The +Add trigger button must keep its heroicon-m-plus icon (icon-only mode).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — CSS shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function primary_actions_wrapper_css_uses_space_sm_gap(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-admin-primary-actions\s*\{[^}]*display:\s*inline-flex[^}]*gap:\s*var\(--space-sm,\s*8px\)/s',
            $this->generalStyles,
            '.mw-admin-primary-actions must be inline-flex with gap: var(--space-sm, 8px) per spec.'
        );
    }

    #[Test]
    public function add_button_css_uses_ghost_geometry(): void
    {
        // 32×32 hit area, transparent ghost background.
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-add[\s\S]*?\{[^}]*background-color:\s*transparent\s*!important/s',
            $this->generalStyles,
            '.fi-btn.admin-toolbar-add must override the legacy MW-v2 light-blue pill with a transparent ghost background.'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-add[\s\S]*?\{[^}]*min-height:\s*32px\s*!important[^}]*min-width:\s*32px\s*!important/s',
            $this->generalStyles,
            '.fi-btn.admin-toolbar-add must lock min-height:32px + min-width:32px for the MwToolButton-default hit area.'
        );
    }

    #[Test]
    public function add_button_svg_icon_is_16px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-add\s+svg\s*\{[^}]*width:\s*16px\s*!important[^}]*height:\s*16px\s*!important/s',
            $this->generalStyles,
            'The Plus icon inside .fi-btn.admin-toolbar-add must be 16×16 px per spec.'
        );
    }

    #[Test]
    public function add_button_hover_uses_ese_surface_hover(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-add:hover[\s\S]*?\{[^}]*background-color:\s*var\(--ese-surface-hover/s',
            $this->generalStyles,
            'Hover state on .fi-btn.admin-toolbar-add must use --ese-surface-hover.'
        );
    }

    #[Test]
    public function live_edit_button_uses_save_pill_contract(): void
    {
        // v2 SAVE pill: --ese-text bg, --ese-surface text, --radius-pill.
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-live-edit[\s\S]*?\{[^}]*background-color:\s*var\(--ese-text/s',
            $this->generalStyles,
            '.fi-btn.admin-toolbar-live-edit background must use --ese-text (matches AI-699 SAVE pill contract).'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-live-edit[\s\S]*?\{[^}]*border-radius:\s*var\(--radius-pill/s',
            $this->generalStyles,
            '.fi-btn.admin-toolbar-live-edit must use --radius-pill (the SAVE pill contract).'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-live-edit[\s\S]*?\{[^}]*padding:\s*var\(--space-sm,\s*8px\)\s+var\(--space-md,\s*13px\)/s',
            $this->generalStyles,
            '.fi-btn.admin-toolbar-live-edit padding must be var(--space-sm) var(--space-md) per spec.'
        );
    }

    #[Test]
    public function live_edit_button_text_uses_ese_surface_colour(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-live-edit\s+span[\s\S]*?\{[^}]*color:\s*var\(--ese-surface,\s*#ffffff\)/s',
            $this->generalStyles,
            'Live Edit pill text colour must be --ese-surface so it reads on the dark pill background.'
        );
    }

    #[Test]
    public function mobile_hides_add_button(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*\{[\s\S]*?\.fi-btn\.admin-toolbar-add\s*\{[^}]*display:\s*none\s*!important/s',
            $this->generalStyles,
            'Mobile @media (max-width: 768px) must hide .fi-btn.admin-toolbar-add per spec (functions remain accessible via sidebar drawer).'
        );
    }

    #[Test]
    public function prefers_reduced_motion_disables_transitions(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[\s\S]*?admin-toolbar-add[\s\S]*?admin-toolbar-live-edit[\s\S]*?transition:\s*none\s*!important/s',
            $this->generalStyles,
            'prefers-reduced-motion: reduce must disable transitions on both AI-704 buttons.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_all_three_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-225150', $this->panelProvider);
        $this->assertStringContainsString('task-2026-05-16-225150', $this->generalStyles);
        $this->assertStringContainsString('task-2026-05-16-225150', $this->addButtonBlade);
    }

    #[Test]
    public function ai704a_followup_documented_in_source(): void
    {
        $this->assertStringContainsString(
            'AI-704a',
            $this->panelProvider,
            'AI-704a follow-up (render +Add as hamburger-menu item on mobile) must be flagged in panel-provider comments.'
        );
        $this->assertStringContainsString(
            'AI-704a',
            $this->generalStyles,
            'AI-704a follow-up must be flagged in general-styles.css comments.'
        );
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        $start = strpos($this->generalStyles, 'AI-704 — Re-cluster');
        $this->assertNotFalse($start, 'AI-704 task marker must be present in general-styles.css.');
        $slice = substr($this->generalStyles, $start);
        $tokens = [
            '--space-xs'         => '6px',
            '--space-sm'         => '8px',
            '--space-md'         => '13px',
            '--radius-md'        => '10px',
            '--radius-pill'      => '999px',
            '--ese-text'         => '#111827',
            '--ese-surface'      => '#ffffff',
            '--ese-surface-hover' => 'rgba(0, 0, 0, 0.04)',
            '--ese-accent'       => '#0d6efd',
            '--t-fast'           => '120ms',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-704 slice."
            );
        }
    }
}
