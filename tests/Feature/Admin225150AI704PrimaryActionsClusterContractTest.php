<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-225150 / AI-704 — primary actions placement.
 *
 * **task-2026-06-08-addleft / AI-704 CHANGE — reverted to the v2 layout.**
 * AI-704 originally moved the +Add button into the RIGHT cluster next to
 * Live Edit (GLOBAL_SEARCH_AFTER) and made it icon-only. The PM asked to
 * put it back on the LEFT with a visible "+ Add" label, as in the
 * Microweber v2 admin ("want it on left as before — see v2 demo"). This
 * test was rewritten in place (pin-evolution) to pin the reverted layout:
 *
 * Target layout (v2):
 *   [brand]  [+ Add]  [☰]  ······  [search]  ······  [Live Edit]  [bell]  [user]
 *
 * Three-surface implementation:
 *   1. FilamentAdminPanelProvider.php — +Add Livewire renders in a
 *      TOPBAR_START hook (registered AFTER the brand mark, so brand is
 *      leftmost and +Add sits to its right) wrapped in
 *      `.mw-admin-primary-actions--left`. Live Edit stays in
 *      GLOBAL_SEARCH_AFTER; search-quick-nav renders outside the wrapper.
 *   2. top-navigation-actions.blade.php — the "Add" label is VISIBLE
 *      (`.admin-toolbar-add__label`, was `.sr-only`) so the button reads
 *      "+ Add"; aria-label + heroicon-m-plus retained.
 *   3. general-styles.css — `.mw-admin-primary-actions--left` left margin
 *      + `.admin-toolbar-add__label` typography; the `.admin-toolbar-add`
 *      pill geometry is reused.
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

    // ── Group A — +Add is on the LEFT (TOPBAR_START), not the right cluster ──

    #[Test]
    public function add_button_registered_in_a_topbar_start_hook(): void
    {
        $this->assertMatchesRegularExpression(
            "/PanelsRenderHook::TOPBAR_START,\s*\n\s*hook:\s*fn\(\)\s*:\s*string\s*=>\s*'<div class=\"mw-admin-primary-actions mw-admin-primary-actions--left\">'\s*\n?\s*\.\s*Blade::render\('@livewire\(\\\\'admin-top-navigation-actions\\\\'\)'\)/",
            $this->panelProvider,
            'AI-704 CHANGE: the +Add Livewire component must render in a TOPBAR_START hook wrapped in .mw-admin-primary-actions--left (v2 left placement).'
        );
    }

    #[Test]
    public function add_button_no_longer_inside_global_search_after_cluster(): void
    {
        // The +Add component must appear BEFORE the GLOBAL_SEARCH_AFTER hook
        // in source (it moved up to TOPBAR_START), and must NOT appear again
        // after GLOBAL_SEARCH_AFTER.
        $addPos = strpos($this->panelProvider, "admin-top-navigation-actions");
        $gsaPos = strpos($this->panelProvider, 'PanelsRenderHook::GLOBAL_SEARCH_AFTER');
        $this->assertNotFalse($addPos);
        $this->assertNotFalse($gsaPos);
        $this->assertLessThan(
            $gsaPos,
            $addPos,
            'AI-704 CHANGE: +Add (admin-top-navigation-actions) must be registered BEFORE the GLOBAL_SEARCH_AFTER hook (it now lives in TOPBAR_START).'
        );
        $this->assertStringNotContainsString(
            "admin-top-navigation-actions",
            substr($this->panelProvider, $gsaPos),
            'AI-704 CHANGE: +Add must NOT render inside the GLOBAL_SEARCH_AFTER hook anymore.'
        );
    }

    #[Test]
    public function brand_mark_still_registered_before_the_add_button(): void
    {
        // Brand must be the leftmost TOPBAR_START item; +Add sits to its right.
        $brandPos = strpos($this->panelProvider, 'mw-admin-brand-mark');
        $addPos = strpos($this->panelProvider, 'mw-admin-primary-actions--left');
        $this->assertNotFalse($brandPos);
        $this->assertNotFalse($addPos);
        $this->assertLessThan(
            $addPos,
            $brandPos,
            'AI-704 CHANGE: the brand mark must register before the +Add TOPBAR_START hook so it renders leftmost.'
        );
    }

    #[Test]
    public function global_search_after_still_carries_live_edit_and_quick_nav(): void
    {
        $gsaPos = strpos($this->panelProvider, 'PanelsRenderHook::GLOBAL_SEARCH_AFTER');
        $tail = substr($this->panelProvider, (int) $gsaPos);
        $this->assertStringContainsString(
            'top-navigation-go-live-edit',
            $tail,
            'AI-704 CHANGE: Live Edit must still render in the GLOBAL_SEARCH_AFTER right cluster.'
        );
        $this->assertStringContainsString(
            'search-quick-nav',
            $tail,
            'AI-704 CHANGE: search-quick-nav must still render after the GLOBAL_SEARCH_AFTER cluster.'
        );
    }

    // ── Group B — visible "+ Add" label ────────────────────────────────────

    #[Test]
    public function add_button_label_is_visible_not_sr_only(): void
    {
        $this->assertMatchesRegularExpression(
            '/<span\s+class="admin-toolbar-add__label">\s*Add\s*<\/span>/',
            $this->addButtonBlade,
            'AI-704 CHANGE: the "Add" label must be VISIBLE (.admin-toolbar-add__label) so the button reads "+ Add".'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<span\s+class="sr-only">\s*Add\s*<\/span>/',
            $this->addButtonBlade,
            'AI-704 CHANGE: the "Add" label must no longer be hidden via .sr-only.'
        );
    }

    #[Test]
    public function add_button_keeps_plus_icon_and_aria_label(): void
    {
        $this->assertMatchesRegularExpression('/icon="heroicon-m-plus"/', $this->addButtonBlade);
        $this->assertMatchesRegularExpression('/aria-label="Add new content"/', $this->addButtonBlade);
    }

    // ── Group C — CSS shape ────────────────────────────────────────────────

    #[Test]
    public function primary_actions_wrapper_css_uses_space_sm_gap(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-admin-primary-actions\s*\{[^}]*display:\s*inline-flex[^}]*gap:\s*var\(--space-sm,\s*8px\)/s',
            $this->generalStyles,
            '.mw-admin-primary-actions must be inline-flex with gap: var(--space-sm, 8px).'
        );
    }

    #[Test]
    public function left_modifier_adds_inline_start_margin(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-primary-actions--left\s*\{[^}]*margin-inline-start:\s*var\(--space-sm,\s*8px\)/s',
            $this->generalStyles,
            'AI-704 CHANGE: .mw-admin-primary-actions--left must add margin-inline-start for breathing room from the brand mark.'
        );
    }

    #[Test]
    public function add_button_label_has_visible_typography(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-add\s+\.admin-toolbar-add__label\s*\{[^}]*font-size:\s*14px/s',
            $this->generalStyles,
            'AI-704 CHANGE: the visible .admin-toolbar-add__label must carry readable typography.'
        );
    }

    #[Test]
    public function add_button_keeps_pill_geometry(): void
    {
        // The +Add pill is reused as-is (light-blue MW pill + pill radius).
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-add[\s\S]*?\{[^}]*border-radius:\s*var\(--radius-pill/s',
            $this->generalStyles,
            '.fi-btn.admin-toolbar-add must keep its pill radius.'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-btn\.admin-toolbar-add\s+svg\s*\{[^}]*width:\s*16px\s*!important/s',
            $this->generalStyles,
            'The Plus icon inside .fi-btn.admin-toolbar-add must stay 16px.'
        );
    }

    // ── Group D — markers ──────────────────────────────────────────────────

    #[Test]
    public function task_id_markers_present_in_all_three_files(): void
    {
        foreach ([$this->panelProvider, $this->generalStyles, $this->addButtonBlade] as $src) {
            $this->assertStringContainsString('task-2026-05-16-225150', $src);
            $this->assertStringContainsString('task-2026-06-08-addleft', $src);
        }
    }

    #[Test]
    public function ai704a_followup_documented_in_source(): void
    {
        $this->assertStringContainsString('AI-704a', $this->panelProvider);
        $this->assertStringContainsString('AI-704a', $this->generalStyles);
    }
}
