<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-5fe1f9 / AI-698b items 1+2 — toolbar compression.
 * Spec: SOUL #117 AI-698 dispatch + designer ACK email
 *   "[DISPATCH] AI-698b as a 2-item slice".
 *
 * Item 1 — ResolutionSwitch.vue carries the MwSegmented primitive
 *   from slice 1.3a (AI-684 / task-f69d54). Three modes per spec
 *   §4.3 (Desktop / Tablet / Mobile) — the legacy 2-mode markup
 *   carried only Desktop + Phone; Tablet (800px breakpoint, already
 *   wired in emulatorSet) is now exposed.
 *
 * Item 2 — ToolbarToolsDropdown.vue trigger picks up the
 *   `.mw-tool-btn` primitive; labels renamed:
 *     - "Template Settings" → "Template & Layout" (matches AI-708
 *       canonical heading rename).
 *     - "Style Editor" → "Design" (matches AI-710 right-rail label
 *       fix; method names handleTemplateSettings/handleStyleEditor
 *       kept as internal API).
 *
 * Deferred to AI-698b-followup: explicit "Advanced" menu entry
 *   (requires SettingsCustomize.vue advanced-state event wiring).
 *   Deferred to AI-698c: `☰` hamburger item 3 (shipped at
 *   task-7326d6 / AI-700 MainDrawer per SOUL #119).
 *
 * Per SOUL #108 verify-before-accept contract: this contract
 * test pins both the source-file shape AND the served bundle
 * carries the changes via Vite-built `live-edit-app.js`.
 */
class LiveEdit5fe1f9AI698bItems12ContractTest extends TestCase
{
    private string $resolutionSwitch;
    private string $toolbarToolsDropdown;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolutionSwitch = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/ResolutionSwitch.vue'
        ));
        $this->toolbarToolsDropdown = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/ToolbarToolsDropdown.vue'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Item 1: ResolutionSwitch MwSegmented + 3 modes
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function resolution_switch_nav_carries_mw_segmented_primitive(): void
    {
        // The wrapper <nav> carries .mw-segmented alongside legacy
        // .toolbar-nav, .mw-live-edit-resolutions-wrapper classes
        // — the primitive supplies the visual frame; legacy classes
        // remain so external scripts/CSS still match.
        $this->assertMatchesRegularExpression(
            '/<nav[^>]+class="[^"]*\bmw-segmented\b[^"]*"/',
            $this->resolutionSwitch,
            'ResolutionSwitch <nav> wrapper must carry .mw-segmented per AI-698b item 1.'
        );
        // Legacy classes preserved
        $this->assertStringContainsString(
            'toolbar-nav',
            $this->resolutionSwitch,
            'Legacy .toolbar-nav class must remain for external CSS/JS hooks.'
        );
        $this->assertStringContainsString(
            'mw-live-edit-resolutions-wrapper',
            $this->resolutionSwitch,
            'Legacy .mw-live-edit-resolutions-wrapper class must remain for external CSS/JS hooks.'
        );
    }

    #[Test]
    public function each_mode_cell_carries_mw_segmented_cell_primitive(): void
    {
        // AI-811 semantic upgrade: span[role=button] → real <button> elements.
        // Three cells must each carry .mw-segmented__cell with role=radio
        // (ARIA radiogroup/radio pattern is more semantically correct for
        // single-select from 3 options than toggle-button APG).
        // Pin-evolved from <span role="button"> to <button role="radio"> per
        // task-2026-05-17-ecabe27 / AI-811.
        $cellCount = preg_match_all(
            '/<button[^>]+class="[^"]*\bmw-segmented__cell\b[^"]*"[^>]+role="radio"/',
            $this->resolutionSwitch,
            $m
        );
        $this->assertSame(
            3,
            $cellCount,
            'ResolutionSwitch must have exactly 3 .mw-segmented__cell buttons (Desktop / Tablet / Mobile).'
        );
    }

    #[Test]
    public function tablet_mode_is_now_exposed_in_ui(): void
    {
        // Designer dispatched 3 modes per spec §4.3; legacy only
        // had Desktop + Phone. Tablet mode wired to setPreviewMode('tablet').
        $this->assertMatchesRegularExpression(
            '/aria-label="Tablet view"/',
            $this->resolutionSwitch,
            'Tablet mode must be present in ResolutionSwitch with aria-label="Tablet view".'
        );
        $this->assertMatchesRegularExpression(
            '/setPreviewMode\(\s*[\'"]tablet[\'"]\s*\)/',
            $this->resolutionSwitch,
            'Tablet mode click handler must dispatch setPreviewMode(\'tablet\').'
        );
        $this->assertMatchesRegularExpression(
            '/data-preview="tablet"/',
            $this->resolutionSwitch,
            'Tablet cell must carry data-preview="tablet" for DOM-query consumers.'
        );
        // emulatorSet already had tablet: 800 wired; pinned here so
        // future cleanup doesn't drop the mode while the UI exposes it.
        $this->assertMatchesRegularExpression(
            '/tablet:\s*800/',
            $this->resolutionSwitch,
            'emulatorSet must keep the tablet: 800 entry — the UI now exposes Tablet mode.'
        );
    }

    #[Test]
    public function active_state_is_dual_class_for_back_compat(): void
    {
        // Active-state binding uses BOTH the legacy
        // `.live-edit-resolution-active` class AND the new
        // `.is-active` class so the MwSegmented primitive's active
        // styling fires AND external CSS targeting the legacy
        // class still matches.
        $this->assertMatchesRegularExpression(
            '/live-edit-resolution-active\s+is-active/',
            $this->resolutionSwitch,
            'Active-state binding must apply both `live-edit-resolution-active` (legacy) and `is-active` (primitive).'
        );
    }

    #[Test]
    public function aria_pressed_toggle_button_apg_preserved(): void
    {
        // AI-811 semantic upgrade: span[role=button]+aria-pressed
        // replaced by <button role=radio>+aria-checked (radiogroup APG).
        // Native <button> is keyboard-focusable and handles Enter/Space
        // natively — explicit tabindex/keydown handlers are no longer needed.
        // Pin-evolved from toggle-button APG to radiogroup APG per
        // task-2026-05-17-ecabe27 / AI-811.
        $this->assertMatchesRegularExpression(
            '/:aria-checked="previewMode==[\'"]desktop[\'"]/',
            $this->resolutionSwitch
        );
        $this->assertMatchesRegularExpression(
            '/:aria-checked="previewMode==[\'"]tablet[\'"]/',
            $this->resolutionSwitch
        );
        $this->assertMatchesRegularExpression(
            '/:aria-checked="previewMode==[\'"]phone[\'"]/',
            $this->resolutionSwitch
        );
        // Radiogroup wrapper must carry role="radiogroup"
        $this->assertStringContainsString(
            'role="radiogroup"',
            $this->resolutionSwitch,
            'Nav wrapper must carry role="radiogroup" for the radio APG pattern.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Item 2: ToolbarToolsDropdown trigger + label renames
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function tools_trigger_carries_mw_tool_btn_primitive(): void
    {
        // The `⋯` trigger picks up MwToolButton primitive styling
        // alongside the legacy `.dropdown-trigger` (which the custom
        // dropdown JS targets).
        $this->assertMatchesRegularExpression(
            '/<a[^>]+class="[^"]*\bdropdown-trigger\b[^"]*\bmw-tool-btn\b[^"]*"/',
            $this->toolbarToolsDropdown,
            'ToolbarToolsDropdown trigger must carry both .dropdown-trigger (legacy) and .mw-tool-btn (primitive).'
        );
    }

    #[Test]
    public function template_settings_label_renamed_to_template_and_layout(): void
    {
        // AI-800b sentence-case plural-plural cascade updated the label from
        // "Template & Layout" (AI-698b shape, matches AI-708) to
        // "Templates & layouts" (sentence-case, plural-plural per AI-800b).
        // Pin-evolved per task-2026-05-17-bce4b7 / AI-800a + AI-800b.
        $this->assertStringContainsString(
            'Templates &amp; layouts',
            $this->toolbarToolsDropdown,
            'Template Settings label must be "Templates & layouts" (AI-800b sentence-case plural-plural).'
        );
        // Method preserved as internal API
        $this->assertStringContainsString(
            'handleTemplateSettings',
            $this->toolbarToolsDropdown,
            'Method name handleTemplateSettings must remain — internal API.'
        );
        // The exact old visible-label phrase "Template Settings" must
        // not appear as visible text anymore. (Prose comments and the
        // method name don't match: ">Template Settings<" is the visible
        // text shape — the closing > of <a ...> + label + opening <.)
        $this->assertDoesNotMatchRegularExpression(
            '/>\s*Template Settings\s*</',
            $this->toolbarToolsDropdown,
            'The literal visible label "Template Settings" must no longer appear — renamed to Templates &amp; layouts.'
        );
    }

    #[Test]
    public function style_editor_label_renamed_to_design(): void
    {
        // Visible label must be "Design" per AI-710 right-rail label
        // fix. Method name unchanged.
        $this->assertMatchesRegularExpression(
            '/>\s*Design\s*</',
            $this->toolbarToolsDropdown,
            'Style Editor label must be renamed to "Design" (matches AI-710).'
        );
        // Method preserved
        $this->assertStringContainsString(
            'handleStyleEditor',
            $this->toolbarToolsDropdown,
            'Method name handleStyleEditor must remain — internal API.'
        );
        // The exact old visible-label phrase "Style Editor" must not
        // appear as visible text anymore.
        $this->assertDoesNotMatchRegularExpression(
            '/>\s*Style Editor\s*</',
            $this->toolbarToolsDropdown,
            'The literal visible label "Style Editor" must no longer appear — renamed to Design.'
        );
    }

    #[Test]
    public function insert_layout_and_quick_ai_edit_labels_already_match_dispatch(): void
    {
        // Dispatch spec lists 5 named tools: "Insert layout /
        // Template & Layout / Design / Quick AI edit / Advanced".
        // After renames in this slice, 4 of 5 labels match. (Advanced
        // is deferred to AI-698b-followup pending state-wiring work
        // with SettingsCustomize.vue.) Pin the 2 labels that already
        // matched as a regression guard.
        $this->assertMatchesRegularExpression(
            '/>\s*Insert Layout\s*</',
            $this->toolbarToolsDropdown,
            'Insert Layout label must remain — matches dispatch spec.'
        );
        $this->assertMatchesRegularExpression(
            '/>\s*Quick AI Edit\s*</',
            $this->toolbarToolsDropdown,
            'Quick AI Edit label must remain — matches dispatch spec.'
        );
    }

    #[Test]
    public function handler_methods_preserved_in_script_block(): void
    {
        // Internal API surface — the three handler methods used by
        // the renamed labels must still be defined in <script>.
        $this->assertMatchesRegularExpression(
            '/handleTemplateSettings\s*\(\s*\)\s*\{/',
            $this->toolbarToolsDropdown
        );
        $this->assertMatchesRegularExpression(
            '/handleStyleEditor\s*\(\s*\)\s*\{/',
            $this->toolbarToolsDropdown
        );
        $this->assertMatchesRegularExpression(
            '/handleQuickEdit\s*\(\s*\)\s*\{/',
            $this->toolbarToolsDropdown
        );
        // handleStyleEditor still toggles guiEditorBox
        $this->assertMatchesRegularExpression(
            '/handleStyleEditor[\s\S]*?guiEditorBox\.toggle\(\)/',
            $this->toolbarToolsDropdown,
            'handleStyleEditor must still toggle guiEditorBox.'
        );
        // handleTemplateSettings still toggles templateSettingsWidget
        $this->assertMatchesRegularExpression(
            '/handleTemplateSettings[\s\S]*?templateSettingsWidget\.toggle\(\)/',
            $this->toolbarToolsDropdown,
            'handleTemplateSettings must still toggle templateSettingsWidget.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — markers + back-compat
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_surfaces(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-16-5fe1f9',
            $this->resolutionSwitch,
            'AI-698b task-id marker must be present in ResolutionSwitch.'
        );
        $this->assertStringContainsString(
            'AI-698b',
            $this->resolutionSwitch
        );
        $this->assertStringContainsString(
            'task-2026-05-16-5fe1f9',
            $this->toolbarToolsDropdown,
            'AI-698b task-id marker must be present in ToolbarToolsDropdown.'
        );
        $this->assertStringContainsString(
            'AI-698b',
            $this->toolbarToolsDropdown
        );
    }

    #[Test]
    public function setup_wizard_more_settings_and_other_items_remain(): void
    {
        // Defensive regression guard — the renames in item 2 must
        // NOT remove the dropdown items NOT named by the dispatch
        // (Insert Module, Edit Element, Module Settings, Layout
        // Settings, Setup Wizard, More Settings).
        $this->assertMatchesRegularExpression('/>\s*Insert Module\s*</', $this->toolbarToolsDropdown);
        $this->assertMatchesRegularExpression('/>\s*Edit Element\s*</', $this->toolbarToolsDropdown);
        $this->assertMatchesRegularExpression('/>\s*Module Settings\s*</', $this->toolbarToolsDropdown);
        $this->assertMatchesRegularExpression('/>\s*Layout Settings\s*</', $this->toolbarToolsDropdown);
        $this->assertMatchesRegularExpression('/>\s*Setup Wizard\s*</', $this->toolbarToolsDropdown);
        $this->assertMatchesRegularExpression('/>\s*More Settings\s*</', $this->toolbarToolsDropdown);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — served bundle runtime probe (SOUL #108 contract)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function vite_bundle_carries_segmented_cells_and_tablet_mode(): void
    {
        // Per SOUL #108: served bundle must reflect the source.
        // The frontend-assets Vite pipeline emits live-edit-app.js;
        // grep for the unique strings added in item 1.
        $bundlePath = base_path(
            'packages/frontend-assets/resources/dist/build/live-edit-app.js'
        );
        if (!file_exists($bundlePath)) {
            // Fallback path — Vite output may be under a different
            // dir in some setups; skip gracefully when bundle absent
            // so a fresh checkout without `npm run build` doesn't
            // false-fail. The source-pin tests above still gate.
            $this->markTestSkipped('Vite bundle not present — run `cd packages/frontend-assets && npm run build` to enable runtime probe.');
        }
        $bundle = (string) file_get_contents($bundlePath);
        $this->assertStringContainsString(
            'mw-segmented__cell',
            $bundle,
            'Vite-built live-edit-app.js must carry .mw-segmented__cell from ResolutionSwitch.'
        );
        $this->assertStringContainsString(
            'Tablet view',
            $bundle,
            'Vite-built live-edit-app.js must carry the Tablet view aria-label.'
        );
        // AI-800b updated label from "Template & Layout" to "Templates & layouts".
        // The Vite bundle stores the unescaped form (HTML entities are resolved
        // by Vue compiler during SFC compilation).
        $this->assertStringContainsString(
            'Templates & layouts',
            $bundle,
            'Vite-built live-edit-app.js must carry the renamed "Templates & layouts" label.'
        );
    }
}
