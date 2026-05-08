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
}
