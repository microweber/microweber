<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LiveEditCodeEditorScriptContractTest extends TestCase
{
    #[Test]
    public function html_editor_keeps_dirty_edits_until_admin_applies_or_discards_them(): void
    {
        $content = file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/editor-tools/render-code-editor.blade.php'
        ));

        $this->assertStringContainsString('let hasUnsavedChanges = false;', $content);
        $this->assertStringContainsString("change.origin === 'setValue'", $content);
        $this->assertStringContainsString('if (hasUnsavedChanges && !force) {', $content);
        $this->assertStringContainsString('mw-html-editor-unsaved-banner', $content);
        $this->assertStringContainsString('discardHtmlChanges2', $content);
    }

    #[Test]
    public function html_editor_uses_module_selector_diff_instead_of_stale_type_fallbacks(): void
    {
        $content = file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/editor-tools/render-code-editor.blade.php'
        ));

        $this->assertStringContainsString('collectModuleSelectors', $content);
        $this->assertStringContainsString("mw.top().app.editor.dispatch('moduleRemoved', node);", $content);
        $this->assertStringNotContainsString("id = $(this).attr('type');", $content);
    }

    #[Test]
    public function css_editor_uses_hot_reload_paths_instead_of_window_reload(): void
    {
        $content = file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/editor-tools/render-css-editor.blade.php'
        ));

        $this->assertStringNotContainsString('window.location.reload()', $content);
        $this->assertStringContainsString("dispatch('reloadCustomCss')", $content);
        $this->assertStringContainsString('const openerMw = getOpenerMw();', $content);
        $this->assertStringContainsString('} else if (mw.top && mw.top().app && mw.top().app.canvas) {', $content);
    }

    /**
     * task-2026-06-06-fmtcodebtn
     *
     * The Format-code affordance was commented out of the HTML editor toolbar
     * even though formatCode() / format_code2() are fully wired. Admins had no
     * way to auto-indent their HTML. It is restored as a real, focusable
     * <button> bound via addEventListener (the 2026-05-07 audit pattern), not
     * an inline-onclick span.
     */
    #[Test]
    public function html_editor_exposes_a_real_focusable_format_code_button(): void
    {
        $content = file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/editor-tools/render-code-editor.blade.php'
        ));

        // A real <button> with an id (the old one was commented out + had no id).
        $this->assertStringContainsString('<button id="mw-html-editor-format-btn"', $content);
        $this->assertStringContainsString('type="button"', $content);
        // Wired the audit-approved way: lookup by id + addEventListener.
        $this->assertMatchesRegularExpression(
            '/getElementById\([\'"]mw-html-editor-format-btn[\'"]\)/',
            $content
        );
        $this->assertMatchesRegularExpression(
            '/formatBtn\.addEventListener\([\'"]click[\'"],\s*function\s*\(\)\s*\{\s*format_code2\(\);/',
            $content
        );
        // The dead PHP-comment-wrapped variant must be gone.
        $this->assertStringNotContainsString('/*        <button onclick="format_code2();"', $content);
    }

    /**
     * task-2026-06-06-csstextareaesc
     *
     * Both CSS editor textareas previously printed the raw CSS source directly
     * between <textarea>...</textarea>. A CSS file containing the literal
     * </textarea> would break out of the textarea into the admin canvas iframe
     * (a DOM-injection sink). The content is now HTML-escaped — lossless,
     * because CodeMirror initialises from the browser-decoded .value.
     */
    #[Test]
    public function css_editor_textareas_escape_their_source_to_prevent_breakout(): void
    {
        $content = file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/editor-tools/render-css-editor.blade.php'
        ));

        $this->assertStringContainsString('htmlspecialchars((string) $custom_css, ENT_QUOTES)', $content);
        $this->assertStringContainsString('htmlspecialchars((string) $live_edit_css_content, ENT_QUOTES)', $content);
        // The unescaped raw-print variants must be gone.
        $this->assertStringNotContainsString('print $custom_css ', $content);
        $this->assertStringNotContainsString('print $live_edit_css_content ', $content);
    }
}
