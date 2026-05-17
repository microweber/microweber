<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-7a9913 / AI-798 — Live-edit MainDrawer hierarchy.
 * Jira: https://microweber.atlassian.net/browse/AI-798
 *
 * Lineage:
 *   - AI-700 (task-2026-05-16-7326d6) — original MainDrawer ship
 *   - AI-708 (task-2026-05-16-505ed5) — sidebar heading disambiguation
 *   - AI-701 (task-2026-05-16-66ceca) — PageChip (receives the new
 *     `mwOpenPageChip` CustomEvent for Slice C "Pages" item)
 *
 * Pre-fix shape: drawer rendered a flat 7-item list under a single
 * `<nav>` with identical visual weight (h 40px, font 13/400, color
 * slate-200, identical padding). Designer's DOM probe confirmed zero
 * hierarchy. The 7 items represent three semantically distinct
 * concerns: EDIT (canvas slide-overs) / NAVIGATE (leave live-edit) /
 * PREFERENCES (toggle). Heuristic-5 (section continuity) failure.
 *
 * Fix shape — 3 slices in one ship:
 *   - Slice A: split flat <nav> into 3 labeled <ul class="mw-main-drawer__section">
 *     with `<h3 class="mw-main-drawer__section-header">` uppercase-muted
 *     headers (EDIT / NAVIGATE / PREFERENCES). aria-labelledby wires
 *     each section to its header.
 *   - Slice B: per-section affordance — chevron right `>` on EDIT items
 *     (slide-over triggers), up-right arrow on NAVIGATE external items
 *     (leave live-edit), pill toggle-track-with-thumb on PREFERENCES.
 *   - Slice C: NEW "Pages" item in NAVIGATE. Click dispatches a
 *     `mwOpenPageChip` CustomEvent on `window`; PageChip.vue's mounted
 *     hook listens for it and calls its own open(). Drawer closes
 *     FIRST (via requestAnimationFrame) so PageChip anchor measurement
 *     reads the post-close layout cleanly. Verb-bridge pattern per
 *     CLAUDE.md `liveEditSaveCallMountedAction` family.
 *
 * Log out stays in the footer as the destructive-action slot — separate
 * from the 3 categories so it's visually de-prioritised.
 *
 * Designer Tier-3 acceptance: 3 labeled sections render with uppercase
 * muted headers; per-section affordances differ; "Pages" item opens
 * the PageChip popover from within the drawer.
 */
class LiveEdit7a9913AI798MainDrawerHierarchyContractTest extends TestCase
{
    private string $drawer;
    private string $pageChip;
    private string $css;
    private string $cssBundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->drawer = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/MainDrawer.vue'
        ));
        $this->pageChip = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/PageChip.vue'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $bundlePath = base_path('public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css');
        $this->cssBundle = file_exists($bundlePath) ? (string) file_get_contents($bundlePath) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Slice A: 3 labeled sections (EDIT / NAVIGATE / PREFERENCES)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function slice_a_three_section_headers_render_in_canonical_order(): void
    {
        // EDIT header must appear first, NAVIGATE second, PREFERENCES third.
        $editIdx = strpos($this->drawer, 'id="mw-main-drawer-section-edit">Edit</h3>');
        $navIdx = strpos($this->drawer, 'id="mw-main-drawer-section-navigate">Navigate</h3>');
        $prefIdx = strpos($this->drawer, 'id="mw-main-drawer-section-preferences">Preferences</h3>');

        $this->assertNotFalse($editIdx, 'Drawer must render <h3 id="mw-main-drawer-section-edit">Edit</h3>.');
        $this->assertNotFalse($navIdx, 'Drawer must render <h3 id="mw-main-drawer-section-navigate">Navigate</h3>.');
        $this->assertNotFalse($prefIdx, 'Drawer must render <h3 id="mw-main-drawer-section-preferences">Preferences</h3>.');
        $this->assertLessThan($navIdx, $editIdx, 'EDIT section must come before NAVIGATE.');
        $this->assertLessThan($prefIdx, $navIdx, 'NAVIGATE section must come before PREFERENCES.');
    }

    #[Test]
    public function slice_a_each_section_carries_aria_labelledby_and_data_attr(): void
    {
        // Each <ul> section must be aria-labelledby its header AND
        // carry data-mw-section="<slug>" for runtime probes.
        $this->assertMatchesRegularExpression(
            '/<ul[^>]*data-mw-section="edit"[^>]*aria-labelledby="mw-main-drawer-section-edit"|<ul[^>]*aria-labelledby="mw-main-drawer-section-edit"[^>]*data-mw-section="edit"/',
            $this->drawer,
            'EDIT <ul> must carry data-mw-section="edit" + aria-labelledby="mw-main-drawer-section-edit".'
        );
        $this->assertMatchesRegularExpression(
            '/<ul[^>]*data-mw-section="navigate"[^>]*aria-labelledby="mw-main-drawer-section-navigate"|<ul[^>]*aria-labelledby="mw-main-drawer-section-navigate"[^>]*data-mw-section="navigate"/',
            $this->drawer,
            'NAVIGATE <ul> must carry data-mw-section="navigate" + aria-labelledby="mw-main-drawer-section-navigate".'
        );
        $this->assertMatchesRegularExpression(
            '/<ul[^>]*data-mw-section="preferences"[^>]*aria-labelledby="mw-main-drawer-section-preferences"|<ul[^>]*aria-labelledby="mw-main-drawer-section-preferences"[^>]*data-mw-section="preferences"/',
            $this->drawer,
            'PREFERENCES <ul> must carry data-mw-section="preferences" + aria-labelledby="mw-main-drawer-section-preferences".'
        );
    }

    #[Test]
    public function slice_a_section_header_typography_is_uppercase_muted(): void
    {
        // Per designer spec: uppercase muted sub-headers.
        $start = strpos($this->css, '.mw-main-drawer__section-header {');
        $this->assertNotFalse($start);
        $end = strpos($this->css, '}', $start);
        $rule = substr($this->css, $start, $end - $start);

        $this->assertStringContainsString(
            'text-transform: uppercase',
            $rule,
            '.mw-main-drawer__section-header must be uppercase per designer spec.'
        );
        $this->assertStringContainsString(
            '--ese-text-muted',
            $rule,
            '.mw-main-drawer__section-header must consume --ese-text-muted token for muted colour.'
        );
        $this->assertMatchesRegularExpression(
            '/font-size:\s*0\.6875rem/',
            $rule,
            '.mw-main-drawer__section-header must use 11px (0.6875rem) sub-header font-size.'
        );
        $this->assertMatchesRegularExpression(
            '/letter-spacing:\s*0\.0[56]em/',
            $rule,
            '.mw-main-drawer__section-header must have widened letter-spacing for uppercase legibility.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Slice B: per-section affordances
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function slice_b_edit_items_carry_chevron_affordance(): void
    {
        // EDIT items have `.mw-main-drawer__item--edit` modifier + trailing
        // chevron polyline `9 6 15 12 9 18` (right-pointing chevron).
        $editCount = substr_count($this->drawer, 'class="mw-main-drawer__item mw-main-drawer__item--edit"');
        $this->assertSame(
            3,
            $editCount,
            'Exactly 3 EDIT items must render (Layers / Template & Layout / Theme Settings).'
        );
        // All 3 EDIT items carry the right-chevron polyline affordance.
        $this->assertGreaterThanOrEqual(
            3,
            substr_count($this->drawer, '<polyline points="9 6 15 12 9 18">'),
            'All 3 EDIT items must carry the right-chevron polyline affordance.'
        );
    }

    #[Test]
    public function slice_b_navigate_external_items_carry_up_right_arrow(): void
    {
        // Back to Admin / Users / See website are NAVIGATE-external items
        // — they have `.mw-main-drawer__item--external` modifier + the
        // up-right-arrow SVG path `M7 17L17 7M7 7h10v10`.
        $externalCount = substr_count($this->drawer, 'class="mw-main-drawer__item mw-main-drawer__item--external"');
        $this->assertSame(
            3,
            $externalCount,
            'Exactly 3 NAVIGATE-external items must render (Back to Admin / Users / See website).'
        );
        $this->assertGreaterThanOrEqual(
            3,
            substr_count($this->drawer, '<path d="M7 17L17 7M7 7h10v10"></path>'),
            'All 3 NAVIGATE-external items must carry the up-right-arrow affordance path.'
        );
    }

    #[Test]
    public function slice_b_preferences_toggle_uses_pill_chrome(): void
    {
        // PREFERENCES Light/Dark mode item uses pill toggle chrome —
        // `.mw-main-drawer__item-affordance--toggle` wrapper around
        // `.mw-main-drawer__toggle-track` + `.mw-main-drawer__toggle-thumb`,
        // with `:data-state="off|on"` reflecting the theme.
        $this->assertStringContainsString(
            'class="mw-main-drawer__item mw-main-drawer__item--toggle"',
            $this->drawer,
            'Light/Dark mode item must carry the .mw-main-drawer__item--toggle modifier.'
        );
        $this->assertMatchesRegularExpression(
            '/mw-main-drawer__item-affordance--toggle[^>]*:data-state="theme === \'dark\'/',
            $this->drawer,
            'Toggle affordance must reflect the theme via :data-state="\'on\'|\'off\'" binding.'
        );
        $this->assertStringContainsString(
            'class="mw-main-drawer__toggle-track"',
            $this->drawer,
            'Toggle must render .mw-main-drawer__toggle-track pill background.'
        );
        $this->assertStringContainsString(
            'class="mw-main-drawer__toggle-thumb"',
            $this->drawer,
            'Toggle must render .mw-main-drawer__toggle-thumb sliding indicator.'
        );
    }

    #[Test]
    public function slice_b_css_for_toggle_chrome_is_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer__item-affordance--toggle\[data-state="on"\]\s+\.mw-main-drawer__toggle-track\s*\{[^}]*background-color:\s*var\(--ese-accent/',
            $this->css,
            'Toggle track must tint accent when data-state="on" (dark mode).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer__item-affordance--toggle\[data-state="on"\]\s+\.mw-main-drawer__toggle-thumb\s*\{[^}]*left:\s*16px/',
            $this->css,
            'Toggle thumb must slide from left:2px to left:16px when data-state="on".'
        );
    }

    #[Test]
    public function slice_b_reduced_motion_guard_collapses_transitions(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*reduce\)\s*\{[^}]*\.mw-main-drawer__item-affordance[\s\S]*?transition:\s*none/',
            $this->css,
            'A `@media (prefers-reduced-motion: reduce)` guard must collapse the affordance + toggle transitions.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Slice C: Pages item dispatches mwOpenPageChip
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function slice_c_pages_item_present_in_navigate_section_first(): void
    {
        // Pages is in NAVIGATE, FIRST item (top of NAVIGATE so it's the
        // most discoverable entry into the page-switching flow).
        $navUlStart = strpos($this->drawer, 'data-mw-section="navigate"');
        $this->assertNotFalse($navUlStart);

        $pagesIdx = strpos($this->drawer, '<span class="mw-main-drawer__item-label">Pages</span>', $navUlStart);
        $backToAdminIdx = strpos($this->drawer, '<span class="mw-main-drawer__item-label">Back to admin</span>', $navUlStart);

        $this->assertNotFalse($pagesIdx, 'Pages item must render inside the NAVIGATE section.');
        $this->assertNotFalse($backToAdminIdx, 'Back to Admin item must still render inside NAVIGATE.');
        $this->assertLessThan(
            $backToAdminIdx,
            $pagesIdx,
            'Pages must come BEFORE Back to Admin in NAVIGATE (most discoverable position).'
        );
    }

    #[Test]
    public function slice_c_pages_item_dispatches_mwopenpagechip_after_close_and_raf(): void
    {
        // openPagesList() method must close drawer first, then dispatch
        // 'mwOpenPageChip' on the next animation frame.
        $this->assertStringContainsString(
            'openPagesList()',
            $this->drawer,
            'MainDrawer must declare an openPagesList() method.'
        );
        $this->assertMatchesRegularExpression(
            '/openPagesList\(\)\s*\{[\s\S]*?this\.close\(\);[\s\S]*?window\.requestAnimationFrame[\s\S]*?new CustomEvent\(\s*[\'"]mwOpenPageChip[\'"]\s*\)/',
            $this->drawer,
            'openPagesList() must close() drawer FIRST, then dispatch `mwOpenPageChip` CustomEvent on next animation frame.'
        );
    }

    #[Test]
    public function slice_c_pagechip_listens_for_mwopenpagechip_and_removes_on_unmount(): void
    {
        // PageChip mounted() registers `mwOpenPageChip` listener that
        // calls this.open(); beforeUnmount + beforeDestroy remove it.
        $this->assertMatchesRegularExpression(
            "/this\\._openVerbHandler\\s*=\\s*\\(\\)\\s*=>\\s*\\{\\s*this\\.open\\(\\);\\s*\\}/",
            $this->pageChip,
            'PageChip.vue must register an _openVerbHandler closure that calls this.open() (Slice C verb-bridge).'
        );
        $this->assertMatchesRegularExpression(
            "/window\\.addEventListener\\(\\s*['\"]mwOpenPageChip['\"]\\s*,\\s*this\\._openVerbHandler\\s*\\)/",
            $this->pageChip,
            'PageChip.vue must addEventListener for `mwOpenPageChip` on window in mounted().'
        );
        $this->assertMatchesRegularExpression(
            "/beforeUnmount\\(\\)[\\s\\S]*?removeEventListener\\(\\s*['\"]mwOpenPageChip['\"]\\s*,\\s*this\\._openVerbHandler\\s*\\)/",
            $this->pageChip,
            'PageChip.vue beforeUnmount() must remove the `mwOpenPageChip` listener (leak-prevention).'
        );
        $this->assertMatchesRegularExpression(
            "/beforeDestroy\\(\\)[\\s\\S]*?removeEventListener\\(\\s*['\"]mwOpenPageChip['\"]\\s*,\\s*this\\._openVerbHandler\\s*\\)/",
            $this->pageChip,
            'PageChip.vue beforeDestroy() must also remove the `mwOpenPageChip` listener (Vue 2/3 compat).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Log out preserved + back-compat
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function logout_stays_in_footer_as_destructive_action_slot(): void
    {
        // Log out item must STILL render in <footer class="mw-main-drawer__footer">
        // — NOT moved into the PREFERENCES section. Designer's audit listed
        // only 7 items in the 3 categories; Log out is the 8th destructive-action
        // slot, separate from the categorisation.
        $this->assertMatchesRegularExpression(
            '/<footer\s+class="mw-main-drawer__footer">[\s\S]*?mw-main-drawer__item--logout[\s\S]*?<\/footer>/',
            $this->drawer,
            'Log out anchor must render inside <footer class="mw-main-drawer__footer">.'
        );
    }

    #[Test]
    public function backward_compat_existing_handlers_preserved(): void
    {
        // The 4 existing slide-over handlers (openLayers / openTemplateAndLayout /
        // openThemeSettings / toggleTheme) must remain wired so AI-700 functionality
        // isn't regressed by the hierarchy refactor.
        $this->assertStringContainsString('openLayers()', $this->drawer);
        $this->assertStringContainsString('openTemplateAndLayout()', $this->drawer);
        $this->assertStringContainsString('openThemeSettings()', $this->drawer);
        $this->assertStringContainsString('toggleTheme()', $this->drawer);
        $this->assertStringContainsString("top.app.domTree.show()", $this->drawer);
        $this->assertStringContainsString("top.app.templateSettingsWidget.toggle()", $this->drawer);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — runtime bundle probe + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function css_bundle_carries_section_header_and_toggle_rules(): void
    {
        if ($this->cssBundle === '') {
            $this->markTestSkipped('Served filament-theme bundle absent.');
        }
        $this->assertStringContainsString(
            '.mw-main-drawer__section-header',
            $this->cssBundle,
            'Served theme bundle must carry the .mw-main-drawer__section-header rule.'
        );
        $this->assertStringContainsString(
            '.mw-main-drawer__toggle-track',
            $this->cssBundle,
            'Served theme bundle must carry the .mw-main-drawer__toggle-track rule.'
        );
        $this->assertStringContainsString(
            '.mw-main-drawer__item--edit',
            $this->cssBundle,
            'Served theme bundle must carry the .mw-main-drawer__item--edit modifier.'
        );
    }

    #[Test]
    public function bundle_mtime_at_least_source_mtime(): void
    {
        // Two-stage CSS shipping verification per task-bc28fd LESSONS:
        // source-level test pins source PRESENCE; bundle-mtime test
        // pins runtime DELIVERY. Catches the "forgot to rebuild" footgun.
        $sourcePath = base_path('packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css');
        $bundlePath = base_path('public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css');

        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Served theme bundle absent.');
        }

        $sourceMtime = filemtime($sourcePath);
        $bundleMtime = filemtime($bundlePath);

        $this->assertGreaterThanOrEqual(
            $sourceMtime,
            $bundleMtime,
            'Webpack bundle mtime must be >= source mtime — rebuild the bundle after editing general-styles.css.'
        );
    }

    #[Test]
    public function task_id_and_ai798_markers_present_across_three_surfaces(): void
    {
        $this->assertStringContainsString('task-2026-05-17-7a9913', $this->drawer);
        $this->assertStringContainsString('AI-798', $this->drawer);
        $this->assertStringContainsString('task-2026-05-17-7a9913', $this->pageChip);
        $this->assertStringContainsString('AI-798', $this->pageChip);
        $this->assertStringContainsString('task-2026-05-17-7a9913', $this->css);
        $this->assertStringContainsString('AI-798', $this->css);
    }

    #[Test]
    public function drawer_docblock_cites_ai700_ai708_ai701_lineage(): void
    {
        $this->assertStringContainsString(
            'AI-700',
            $this->drawer,
            'MainDrawer docblock must cite AI-700 (original drawer ship).'
        );
        $this->assertStringContainsString(
            'AI-708',
            $this->drawer,
            'MainDrawer docblock must cite AI-708 (sidebar disambiguation lineage).'
        );
        $this->assertStringContainsString(
            'AI-701',
            $this->drawer,
            'MainDrawer docblock must cite AI-701 (PageChip — receives mwOpenPageChip CustomEvent).'
        );
    }
}
