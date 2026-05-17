<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-505ed5 / AI-708 (P1) — Disambiguate the live-edit
 * right-side panel headings.
 *
 * Designer-DOM-probed audit (Playwright, per the SOUL #108 verify-
 * before-accept contract): the right-side panel area rendered THREE
 * near-duplicate `<h3>` headings simultaneously — two instances of
 * `"Template Style Editor"` plus one `"Template settings"` — all
 * simultaneously mounted, all sliding from the right edge. Users
 * could not disambiguate them; screen readers announced the
 * duplicates twice.
 *
 * Naming hygiene rule (designer-defined):
 *   - "Element Style Editor" = per-element pane (unchanged in this slice)
 *   - "Theme Settings" = global theme/style toggles
 *   - "Template & Layout" = scope picker (template vs layout)
 *
 * Rename map applied here:
 *
 *   1. `RightSidebar.vue` line 8 — the `<h3>` inside the
 *      `role="complementary" aria-label="Theme settings sidebar"`
 *      wrapper at line 5 was titled "Template Style Editor". It now
 *      renders "Theme Settings" (matches the wrapper's aria-label
 *      semantics: global theme/style toggles).
 *
 *   2. `bootstrap.js` line 88 — the `mw.app.templateSettingsWidget =
 *      new mw.controlBox({ title: 'Template settings', id:
 *      'template-settings-teleport-widget', ... })` constructor was
 *      titled "Template settings". This is the ACTIVE template-
 *      settings controlBox (the one Vue's `<TemplateSettingsTeleport>`
 *      targets via `#template-settings-teleport-widget-content`). It
 *      now renders title "Template & Layout".
 *
 *   3. `live-edit.blade.php` (lines 138-192 previously) — STALE
 *      duplicate `mw.controlBox` with title "Template Style Editor"
 *      and id `mw-live-edit-templateSettings-editor-box`. It was
 *      unreachable (nothing called `.show()` on `mw.top().app.
 *      templateSettingsBox` anywhere) but still mounted its
 *      `<h3 class="mw-control-box-title">` simultaneously alongside
 *      the live controlBox + the Vue `<h3>`. REMOVED entirely along
 *      with the inert `<template>` dead-code block + the `<style>`
 *      block scoped only to this stale id. The wrapper div
 *      `#live-edit-global-template-settings-component-wrapper` is
 *      kept as a hidden back-compat DOM hook (its purpose was to be
 *      moved into the now-removed controlBox; without that consumer
 *      it sits empty + hidden = no visible effect).
 *
 *   4. `SettingsCustomize.vue` line 363-369 — the right-rail icon-bar
 *      button (`aria-label`, `title`, `<v-tooltip>` slot all
 *      previously said "Template settings") renamed to "Template &
 *      Layout" so the button label matches the panel it opens.
 *
 *   5. `LeftSidebar.vue` line 94 — the nav-menu item label that opens
 *      the same scope-picker panel renamed for cross-surface
 *      consistency.
 *
 * Ship-order constraint: this slice ships BEFORE AI-700 (drawer
 * consolidation) so the renamed surfaces carry into the drawer
 * cleanly. Per designer dispatch.
 */
class LiveEdit505ed5AI708HeadingDisambiguationContractTest extends TestCase
{
    private string $rightSidebar;
    private string $bootstrapJs;
    private string $liveEditBlade;
    private string $settingsCustomize;
    private string $leftSidebar;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rightSidebar = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/RightSidebar/RightSidebar.vue'
        ));
        $this->bootstrapJs = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/api-core/services/bootstrap.js'
        ));
        $this->liveEditBlade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php'
        ));
        $this->settingsCustomize = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue'
        ));
        $this->leftSidebar = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/LeftSidebar/LeftSidebar.vue'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Vue <h3> heading rename (RightSidebar.vue)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function right_sidebar_h3_renders_theme_settings(): void
    {
        // The Vue <h3> inside the `complementary` wrapper now renders
        // "Theme settings" — matches the wrapper's aria-label semantics
        // (global theme/style toggles) AND the MainDrawer "Theme settings"
        // entry-point label per AI-800 sentence-case sweep.
        //
        // task-2026-05-17-bce4b7 / AI-800a — pin-evolution from prior
        // "Theme Settings" Title Case (AI-708 shipped 2026-05-16). Single
        // logical contract preserved (label inside the complementary-
        // wrapper h3); evolved syntax (case-flip). AI-770 v2 pin-evolution
        // discipline — update existing pin in place, no parallel test.
        $this->assertMatchesRegularExpression(
            '/<h3[^>]*v-show="showTemplateSettings"[^>]*>\s*<Lang>\s*Theme settings\s*<\/Lang>\s*<\/h3>/',
            $this->rightSidebar,
            'RightSidebar.vue must render the complementary-wrapper h3 as "Theme settings" (sentence case per AI-800a).'
        );
    }

    #[Test]
    public function right_sidebar_complementary_wrapper_present(): void
    {
        // The complementary wrapper that hosts the renamed h3 must
        // still carry its semantic role + aria-label.
        $this->assertStringContainsString(
            'role="complementary" aria-label="Theme settings sidebar"',
            $this->rightSidebar,
            'RightSidebar.vue must keep the role="complementary" aria-label="Theme settings sidebar" wrapper — the renamed h3 lives inside it.'
        );
    }

    #[Test]
    public function right_sidebar_no_longer_renders_template_style_editor(): void
    {
        // Regression guard — the legacy "Template Style Editor" copy
        // must not return. The h3 currently renders <Lang>Theme Settings
        // </Lang>; the test asserts no occurrence of "Template Style
        // Editor" appears as Vue-rendered visible copy (we strip Vue
        // comments first so the migration docblock's prose reference
        // does not false-fail).
        $stripped = preg_replace('/<!--[\s\S]*?-->/', '', $this->rightSidebar);
        $this->assertStringNotContainsString(
            'Template Style Editor',
            $stripped,
            'RightSidebar.vue must not render "Template Style Editor" anywhere in user-visible Vue templates (comments excluded).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — bootstrap.js controlBox title rename
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bootstrap_template_settings_widget_titled_template_and_layout(): void
    {
        // The active template-settings controlBox (target of the Vue
        // Teleport) renders title "Templates & layouts" now — sentence
        // case + plural-plural per AI-800a, matching the MainDrawer
        // "Templates & layouts" entry-point label.
        //
        // task-2026-05-17-bce4b7 / AI-800a — pin-evolution from prior
        // "Template & Layout" Title Case singular (AI-708 shipped
        // 2026-05-16). Single logical contract preserved (controlBox
        // title); evolved syntax (case-flip + plural-plural). AI-770 v2
        // pin-evolution discipline — update existing pin in place, no
        // parallel test.
        $this->assertMatchesRegularExpression(
            "/mw\\.app\\.templateSettingsWidget\\s*=\\s*new mw\\.controlBox\\(\\{[\\s\\S]*?id:\\s*`template-settings-teleport-widget`[\\s\\S]*?title:\\s*mw\\.lang\\('Templates & layouts'\\)/",
            $this->bootstrapJs,
            'bootstrap.js mw.app.templateSettingsWidget controlBox must render title "Templates & layouts" (sentence case + plural-plural per AI-800a).'
        );
    }

    #[Test]
    public function bootstrap_no_longer_titles_widget_template_settings_verbatim(): void
    {
        // The literal `mw.lang('Template settings')` (verbatim) must
        // not survive in the template-settings widget controlBox call.
        // We strip JS comments first to skip the migration docblock's
        // prose. (Slash-star block comments AND // line comments.)
        $stripped = preg_replace('/\/\*[\s\S]*?\*\//', '', $this->bootstrapJs);
        $stripped = preg_replace('/(?<!:)\/\/[^\n]*/', '', $stripped);
        $this->assertStringNotContainsString(
            "mw.lang('Template settings')",
            $stripped,
            'bootstrap.js must not pass `mw.lang(\'Template settings\')` to mw.controlBox (verbatim string check, comments stripped).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Stale controlBox removed from live-edit.blade.php
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function stale_controlbox_constructor_removed(): void
    {
        // The stale controlBox's `new (mw.top()).controlBox({...})` call
        // must be gone. Pin both the title arg AND the unique id arg.
        $this->assertStringNotContainsString(
            "id: 'mw-live-edit-templateSettings-editor-box'",
            $this->liveEditBlade,
            'Stale `mw-live-edit-templateSettings-editor-box` controlBox constructor must be removed from live-edit.blade.php.'
        );
        // The previous `title: mw.lang('Template Style Editor')` call
        // is removed (live + executable JS — not in a Blade comment).
        // Strip Blade {{-- --}} comments first so the migration docblock
        // doesn't false-fail this guard.
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->liveEditBlade);
        $this->assertStringNotContainsString(
            "mw.lang('Template Style Editor')",
            $stripped,
            'live-edit.blade.php must not include `mw.lang(\'Template Style Editor\')` outside Blade comments — the stale controlBox is gone.'
        );
    }

    #[Test]
    public function templateSettingsBox_global_reference_removed(): void
    {
        // The dead `mw.top().app.templateSettingsBox = tsEditor;` line
        // must also go (nothing references the global anyway).
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->liveEditBlade);
        $this->assertStringNotContainsString(
            'templateSettingsBox',
            $stripped,
            'mw.top().app.templateSettingsBox global reference must be removed from live-edit.blade.php (only the docblock may mention it for migration context).'
        );
    }

    #[Test]
    public function wrapper_div_kept_for_back_compat(): void
    {
        // Belt-and-braces: the hidden wrapper div is preserved so any
        // external admin code that grepped for the id still finds it.
        $this->assertStringContainsString(
            'id="live-edit-global-template-settings-component-wrapper"',
            $this->liveEditBlade,
            'Wrapper div `#live-edit-global-template-settings-component-wrapper` must be preserved as a back-compat DOM hook.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Right-rail button + nav-menu label renames
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function settings_customize_button_renamed_to_template_and_layout(): void
    {
        // Pin-evolved 2026-05-17 / task-c3f232 / AI-800b — cross-surface
        // case cascade continued from AI-800a. The right-rail button is a
        // SECOND entry point to the same panel; all entry points must
        // carry the same label per the cross-surface-consistency principle
        // (MainDrawer + bootstrap.js controlBox + RightSidebar h3 all
        // render "Templates & layouts"). Updated in place per pin-evolution
        // discipline (AI-770 v2 / AI-805/805a Path B) — not a parallel
        // test. Prior shape: aria-label + title + Lang = "Template & Layout"
        // (AI-708). New shape: "Templates & layouts" sentence-case plural-
        // plural matching the cascade. `&` is HTML-escaped as `&amp;` in
        // the `<Lang>` slot (Vue template parses HTML entities); aria-label
        // + title attribute values use literal `&` since they're attribute
        // values not Vue template body.
        $this->assertMatchesRegularExpression(
            "/aria-label=\"Templates & layouts\"\\s+title=\"Templates & layouts\"/",
            $this->settingsCustomize,
            'SettingsCustomize.vue button must use aria-label + title "Templates & layouts" (AI-800b cross-surface match).'
        );
        $this->assertStringContainsString(
            '<Lang>Templates &amp; layouts</Lang>',
            $this->settingsCustomize,
            'SettingsCustomize.vue v-tooltip slot must render <Lang>Templates &amp; layouts</Lang> (AI-800b cross-surface match; `&` HTML-escaped in template body).'
        );
    }

    #[Test]
    public function settings_customize_does_not_render_legacy_template_settings_label(): void
    {
        // Regression guard for the button area only — strip Vue
        // comments and CSS so the migration docblock + CSS selector
        // rules (which reference the class `.mw-live-edit-right-sidebar
        // -template-sidebar`, that's the wrapper class, not the
        // button label) don't false-fail. Selector-self-match guard
        // UNIFORMITY (post-task-7aa48a default-on protocol).
        $stripped = preg_replace('/<!--[\s\S]*?-->/', '', $this->settingsCustomize);
        // Also strip the <style> block at the top — CSS selectors
        // reference template-sidebar as a class name, not as user copy.
        $stripped = preg_replace('/<style[\s\S]*?<\/style>/', '', $stripped);
        // Now the only "Template settings" string left would be user-
        // visible Vue/HTML copy. There should be none.
        $this->assertStringNotContainsString(
            'aria-label="Template settings"',
            $stripped,
            'SettingsCustomize.vue must not render legacy `aria-label="Template settings"` (with no & Layout) on any button.'
        );
        $this->assertStringNotContainsString(
            '<Lang>Template settings</Lang>',
            $stripped,
            'SettingsCustomize.vue must not render legacy `<Lang>Template settings</Lang>` in any v-tooltip.'
        );
        // AI-800b additional pin-evolution regression-guards: the
        // AI-708-era "Template & Layout" Title-Case singular shape is
        // also obsolete now that the cascade matches "Templates & layouts".
        $this->assertStringNotContainsString(
            'aria-label="Template & Layout"',
            $stripped,
            'SettingsCustomize.vue must not render legacy AI-708-era `aria-label="Template & Layout"` (Title-Case singular) — superseded by AI-800b "Templates & layouts" sentence-case plural-plural cascade.'
        );
        $this->assertStringNotContainsString(
            '<Lang>Template & Layout</Lang>',
            $stripped,
            'SettingsCustomize.vue must not render legacy `<Lang>Template & Layout</Lang>` in any v-tooltip — superseded by AI-800b cascade.'
        );
    }

    #[Test]
    public function left_sidebar_nav_item_renamed_to_template_and_layout(): void
    {
        // Pin-evolved 2026-05-17 / task-c3f232 / AI-800b — recon-driven
        // Slice B bundle: LeftSidebar.vue is the second sibling cascade
        // site (alongside SettingsCustomize.vue from the AI-800b dispatch).
        // Designer's dispatch named only SettingsCustomize.vue; recon
        // found this nav-item per the recon-driven Slice B uniformity
        // rule (task-46127c). Updated in place per pin-evolution discipline.
        $this->assertStringContainsString(
            '<Lang>Templates &amp; layouts</Lang>',
            $this->leftSidebar,
            'LeftSidebar.vue nav-item label must render <Lang>Templates &amp; layouts</Lang> (AI-800b cross-surface cascade match).'
        );
    }

    #[Test]
    public function left_sidebar_does_not_render_legacy_template_settings_label(): void
    {
        // Strip Vue comments before checking — migration docblock
        // mentions the old label as prose (selector-self-match guard
        // UNIFORMITY per task-7aa48a default-on protocol).
        $stripped = preg_replace('/<!--[\s\S]*?-->/', '', $this->leftSidebar);
        $this->assertStringNotContainsString(
            '<Lang>Template settings</Lang>',
            $stripped,
            'LeftSidebar.vue must not render legacy `<Lang>Template settings</Lang>` (with no `& Layout`) on any nav-item label.'
        );
        // AI-800b regression-guard: AI-708-era Title-Case singular shape
        // is also obsolete now that the cascade matches "Templates & layouts".
        $this->assertStringNotContainsString(
            '<Lang>Template &amp; Layout</Lang>',
            $stripped,
            'LeftSidebar.vue must not render legacy `<Lang>Template &amp; Layout</Lang>` (AI-708-era Title-Case singular) — superseded by AI-800b cascade.'
        );
    }

    #[Test]
    public function toolbar_tools_dropdown_renamed_to_templates_and_layouts(): void
    {
        // task-2026-05-17-c3f232 / AI-800b — recon-driven Slice B bundle:
        // ToolbarToolsDropdown.vue is the THIRD entry point to the
        // template-settings panel (alongside SettingsCustomize button +
        // LeftSidebar nav-item). Designer's AI-800b dispatch named the
        // right-rail button only; recon found this 3-dots dropdown as a
        // sibling cascade site per the recon-driven Slice B uniformity
        // rule (task-46127c).
        $dropdown = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/ToolbarToolsDropdown.vue'
        ));
        $this->assertStringContainsString(
            'Templates &amp; layouts',
            $dropdown,
            'ToolbarToolsDropdown.vue 3-dots dropdown anchor must render "Templates &amp; layouts" text (AI-800b cross-surface cascade match).'
        );
    }

    #[Test]
    public function toolbar_tools_dropdown_does_not_render_legacy_template_layout_label(): void
    {
        $dropdown = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/ToolbarToolsDropdown.vue'
        ));
        // Strip Vue comments — migration docblock mentions the old shapes
        // as prose (selector-self-match guard UNIFORMITY).
        $stripped = preg_replace('/<!--[\s\S]*?-->/', '', $dropdown);
        // The dropdown body must NOT carry the AI-708-era "Template &amp; Layout"
        // Title-Case singular shape — superseded by AI-800b cascade.
        $this->assertStringNotContainsString(
            'Template &amp; Layout',
            $stripped,
            'ToolbarToolsDropdown.vue must not render legacy `Template &amp; Layout` (AI-708-era Title-Case singular) — superseded by AI-800b cascade.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Markers + naming-hygiene baseline
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function all_modified_files_carry_task_id_marker(): void
    {
        $files = [
            'RightSidebar.vue' => $this->rightSidebar,
            'bootstrap.js' => $this->bootstrapJs,
            'live-edit.blade.php' => $this->liveEditBlade,
            'SettingsCustomize.vue' => $this->settingsCustomize,
            'LeftSidebar.vue' => $this->leftSidebar,
        ];
        foreach ($files as $name => $contents) {
            $this->assertStringContainsString(
                'task-2026-05-16-505ed5',
                $contents,
                "{$name} must carry the task-2026-05-16-505ed5 marker (single-pass audit grep)."
            );
        }
    }

    #[Test]
    public function element_style_editor_naming_kept_separate(): void
    {
        // Naming hygiene baseline — "Element Style Editor" (the per-
        // element pane name) MUST remain distinct from the three
        // renamed surfaces. AI-708 does not touch the per-element ESE
        // controlBox in bootstrap.js (line 114-119 `guiEditor`), but
        // the SCOPE-PICKER controlBox (`templateSettingsWidget`) must
        // NOT be titled "Element Style Editor".
        $this->assertStringNotContainsString(
            '<Lang>Element Style Editor</Lang>',
            $this->rightSidebar,
            'RightSidebar.vue must not conflate the global Theme Settings heading with the per-element "Element Style Editor" name (which lives in ESE Vue components, not here).'
        );
        // Slice the templateSettingsWidget controlBox block (from its
        // `mw.app.templateSettingsWidget = new mw.controlBox({` opening
        // to the matching `});` close) and confirm "Element Style Editor"
        // does NOT appear inside it. This isolates the assertion to the
        // scope-picker controlBox while leaving the legitimate `guiEditor`
        // controlBox alone.
        $start = strpos($this->bootstrapJs, 'mw.app.templateSettingsWidget = new mw.controlBox({');
        $this->assertNotFalse($start, 'templateSettingsWidget controlBox declaration must exist.');
        $end = strpos($this->bootstrapJs, '});', $start);
        $this->assertNotFalse($end, 'templateSettingsWidget controlBox must close with });.');
        $slice = substr($this->bootstrapJs, $start, $end - $start);
        $this->assertStringNotContainsString(
            'Element Style Editor',
            $slice,
            'The scope-picker controlBox (mw.app.templateSettingsWidget) must NOT be titled "Element Style Editor" — that name is reserved for the per-element guiEditor controlBox.'
        );
    }
}
