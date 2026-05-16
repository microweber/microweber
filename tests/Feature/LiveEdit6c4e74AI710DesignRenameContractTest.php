<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-6c4e74 / AI-710 (Medium) — Right-rail "Design"
 * icon renamed to "Element styles" to match the panel it opens.
 *
 * Designer dispatch (DESIGN_AUDIT.md L2.3, per-ticket email
 * 2026-05-16T13:40): the right-rail icon labelled "Design"
 * (`button[aria-label="Design"]`) opens a panel titled "Element
 * Style Editor". Two different names for the same surface — screen
 * readers announce "Design button" but it opens per-element styling.
 *
 * Fix:
 *   - aria-label="Element styles"
 *   - title="Element styles"
 *   - <Lang> tooltip text inside <v-tooltip> updated to match
 *   - Optionally: panel heading renamed to "Element styles"
 *     (downstream of AI-708 naming-hygiene rules)
 *
 * Two-surface implementation (label-only; no behaviour change):
 *
 *   1. SettingsCustomize.vue (right-rail button):
 *      aria-label, title, and <v-tooltip><Lang>...</Lang></v-tooltip>
 *      text all switched from "Design" to "Element styles".
 *      Click target `toggle('style-editor')`, state-class binding,
 *      and keyboard handlers are unchanged — pure label rewrite.
 *
 *   2. StyleEditor.vue (panel heading):
 *      `<h3 class="fs-2 font-weight-bold">Element Style Editor</h3>`
 *      renamed to `<h3>Element styles</h3>` so the button and the
 *      panel speak the same language.
 *
 * Naming-hygiene note: the surviving "Element Style Editor" string
 * inside the project lives in source-code COMMENTS in
 * RightSidebar.vue and SliderSmall.vue (and the per-element
 * `guiEditor` controlBox title in `bootstrap.js`, intentionally
 * kept distinct per AI-708 SOUL #113). Those are descriptive prose
 * references, not user-visible labels — out of scope for AI-710.
 */
class LiveEdit6c4e74AI710DesignRenameContractTest extends TestCase
{
    private string $settingsCustomize;
    private string $styleEditor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->settingsCustomize = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue'
        ));
        $this->styleEditor = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/StyleEditor/StyleEditor.vue'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Right-rail button label rename
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function button_aria_label_is_element_styles(): void
    {
        // The right-rail style-editor toggle MUST advertise itself
        // as "Element styles" so AT users hear the same label that
        // matches the panel they're opening.
        $this->assertMatchesRegularExpression(
            '/toggle\([\'"]style-editor[\'"]\)[\s\S]{0,800}/',
            $this->settingsCustomize,
            'Right-rail style-editor toggle handler must still exist (regression guard).'
        );

        // Slice to ONLY the style-editor toggle button block so we
        // don't false-match other buttons or unrelated occurrences
        // of the legacy "Design" string in this file.
        $marker = "v-on:click=\"toggle('style-editor')\"";
        $pos = strpos($this->settingsCustomize, $marker);
        $this->assertNotFalse($pos, 'style-editor toggle marker must be present in SettingsCustomize.vue.');
        // Walk back to the opening <button (this is the AI-710 button).
        $blockStart = strrpos(substr($this->settingsCustomize, 0, $pos), '<button');
        $this->assertNotFalse($blockStart, 'Opening <button tag must precede the style-editor toggle handler.');
        $blockEnd = strpos($this->settingsCustomize, '</button>', $pos);
        $this->assertNotFalse($blockEnd, 'Closing </button> tag must follow the style-editor toggle handler.');
        $block = substr($this->settingsCustomize, $blockStart, $blockEnd - $blockStart);

        $this->assertMatchesRegularExpression(
            '/aria-label="Element styles"/',
            $block,
            'The style-editor toggle button must carry aria-label="Element styles" per AI-710.'
        );
        $this->assertMatchesRegularExpression(
            '/title="Element styles"/',
            $block,
            'The style-editor toggle button must carry title="Element styles" per AI-710.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/aria-label="Design"/',
            $block,
            'The pre-AI-710 aria-label="Design" on the style-editor toggle button must be gone.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/title="Design"/',
            $block,
            'The pre-AI-710 title="Design" on the style-editor toggle button must be gone.'
        );
    }

    #[Test]
    public function button_vtooltip_lang_text_is_element_styles(): void
    {
        // The <v-tooltip> activator on the same button has its own
        // <Lang>…</Lang> body that drives the visible tooltip text.
        // It must match the aria-label / title.
        $marker = "v-on:click=\"toggle('style-editor')\"";
        $pos = strpos($this->settingsCustomize, $marker);
        $this->assertNotFalse($pos);
        $blockEnd = strpos($this->settingsCustomize, '</button>', $pos);
        $block = substr($this->settingsCustomize, $pos, $blockEnd - $pos);

        $this->assertMatchesRegularExpression(
            '/<v-tooltip[^>]*>\s*<Lang>\s*Element styles\s*<\/Lang>\s*<\/v-tooltip>/',
            $block,
            'The <v-tooltip> body on the style-editor toggle button must read <Lang>Element styles</Lang>.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<Lang>\s*Design\s*<\/Lang>/',
            $block,
            'The pre-AI-710 <Lang>Design</Lang> tooltip text on this button must be gone.'
        );
    }

    #[Test]
    public function button_click_handler_and_state_classes_unchanged(): void
    {
        // Regression-guard: the rename must NOT have touched the
        // click handler or the state-class binding. Only labels
        // changed.
        $this->assertStringContainsString(
            "v-on:click=\"toggle('style-editor')\"",
            $this->settingsCustomize,
            "Click handler v-on:click=\"toggle('style-editor')\" must remain unchanged."
        );
        $this->assertStringContainsString(
            'buttonIsActiveStyleEditor',
            $this->settingsCustomize,
            "State-class binding on `buttonIsActiveStyleEditor` must remain — label-only rename."
        );
        $this->assertStringContainsString(
            'live-edit-toolbar-button-css-editor-toggle',
            $this->settingsCustomize,
            'CSS class hook `live-edit-toolbar-button-css-editor-toggle` must remain — external code references it.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Panel heading rename
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function panel_heading_is_element_styles(): void
    {
        $this->assertMatchesRegularExpression(
            '/<h3[^>]*>\s*Element styles\s*<\/h3>/',
            $this->styleEditor,
            'StyleEditor.vue panel heading must read "Element styles" (matches the AI-710 button label).'
        );
    }

    #[Test]
    public function panel_heading_no_longer_says_element_style_editor(): void
    {
        // The legacy verbose "Element Style Editor" string must be
        // gone from the user-visible <h3>. (Source comments
        // referencing the old name are fine and out of scope —
        // we slice the <template> block only.)
        $templateStart = strpos($this->styleEditor, '<template>');
        $this->assertNotFalse($templateStart, '<template> tag must be present.');
        $templateEnd = strpos($this->styleEditor, '</template>');
        $this->assertNotFalse($templateEnd, '</template> tag must be present.');
        $templateBlock = substr($this->styleEditor, $templateStart, $templateEnd - $templateStart);

        $this->assertStringNotContainsString(
            'Element Style Editor',
            $templateBlock,
            'The verbose "Element Style Editor" string must no longer appear in StyleEditor.vue <template>.'
        );
    }

    #[Test]
    public function panel_heading_preserves_styling_classes(): void
    {
        $this->assertMatchesRegularExpression(
            '/<h3\s+class="fs-2\s+font-weight-bold">/',
            $this->styleEditor,
            'Panel heading must retain its `fs-2 font-weight-bold` Bootstrap classes — pure text rename.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-16-6c4e74',
            $this->settingsCustomize,
            'AI-710 task-id marker must be present in SettingsCustomize.vue.'
        );
        $this->assertStringContainsString(
            'task-2026-05-16-6c4e74',
            $this->styleEditor,
            'AI-710 task-id marker must be present in StyleEditor.vue.'
        );
    }

    #[Test]
    public function ai_710_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('AI-710', $this->settingsCustomize);
        $this->assertStringContainsString('AI-710', $this->styleEditor);
    }
}
