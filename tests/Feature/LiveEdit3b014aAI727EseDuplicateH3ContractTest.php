<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-3b014a / AI-727 (Medium) — ESE duplicate h3
 * "Element Style Editor" (same root pattern as AI-708).
 *
 * Designer dispatch (per-ticket email 2026-05-16T14:45 —
 * adjacent finding from AI-708 verification): two `<h3>`s reading
 * "Element Style Editor" rendered simultaneously when the ESE
 * opens:
 *   1. `#mw-element-style-editor-app-container > h3.fs-2.font-
 *      weight-bold` — the Vue component's canonical header
 *      (renamed to "Element styles" in AI-710 — task-6c4e74).
 *   2. `#mw-live-edit-gui-editor-box > h3.mw-control-box-title`
 *      — the mw.controlBox wrapper's auto-generated heading
 *      (created at `bootstrap.js:114-119`).
 *
 * Both rendered visibly. Screen readers announced the heading
 * twice in immediate succession.
 *
 * Same defect class as AI-708 (Theme Settings / Template &
 * Layout duplicate h3 fix at task-505ed5). Same fix shape — keep
 * the wrapper div for back-compat, suppress the duplicate h3
 * by passing `title: false` to the guiEditor controlBox.
 *
 * The `mw.controlBox` library at `control_box.js:168` gates h3
 * creation behind `if (this.settings.title)`, so a falsy `title`
 * (`false`, `null`, empty string) prevents the wrapper h3 from
 * rendering. Wrapper div + closeButton remain.
 *
 * Single-file fix in `bootstrap.js` — pure config change, no
 * markup or selector changes elsewhere.
 *
 * Note on the visible Vue heading: AI-710 (task-6c4e74) renamed
 * the Vue `<h3>` from "Element Style Editor" to "Element styles"
 * — so after AI-727 lands, the only visible heading on the ESE
 * panel reads "Element styles" (singular, canonical).
 */
class LiveEdit3b014aAI727EseDuplicateH3ContractTest extends TestCase
{
    private string $bootstrap;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrap = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/api-core/services/bootstrap.js'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — guiEditor controlBox config
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function gui_editor_controlbox_title_is_false(): void
    {
        // The guiEditor controlBox at bootstrap.js:114-ish must
        // pass `title: false` so mw.controlBox skips the
        // wrapper-h3 creation gate at control_box.js:168.
        // Slice from the AI-727 marker to the next non-trivial
        // line so this test doesn't false-match the templateSettings
        // controlBox config block immediately above.
        $aiMarker = strpos($this->bootstrap, 'AI-727 / task-2026-05-16-3b014a');
        $this->assertNotFalse($aiMarker, 'AI-727 task-id marker must be present in bootstrap.js.');
        // The new controlBox call follows the docblock. Walk
        // forward to find the `new (mw.top()).controlBox({` opener
        // associated with the AI-727 block.
        $controlBoxStart = strpos($this->bootstrap, 'new (mw.top()).controlBox', $aiMarker);
        $this->assertNotFalse($controlBoxStart, 'AI-727 docblock must be followed by a new controlBox call.');
        // Slice to the closing brace.
        $controlBoxEnd = strpos($this->bootstrap, '});', $controlBoxStart);
        $this->assertNotFalse($controlBoxEnd);
        $block = substr($this->bootstrap, $controlBoxStart, $controlBoxEnd - $controlBoxStart);

        $this->assertMatchesRegularExpression(
            '/title:\s*false/',
            $block,
            'guiEditor controlBox must pass `title: false` so the wrapper <h3.mw-control-box-title> is suppressed.'
        );
    }

    #[Test]
    public function gui_editor_controlbox_does_not_pass_element_style_editor_string(): void
    {
        // Negative regression-guard: the legacy `title: mw.lang('Element Style Editor')`
        // must NOT come back. Slice to the guiEditor controlBox
        // block only — the other controlBox above
        // (`mw.app.templateSettingsWidget`) had `'Element Style
        // Editor'` historically removed at AI-708 / task-505ed5,
        // so a top-level absence guard would double up with that.
        $aiMarker = strpos($this->bootstrap, 'AI-727 / task-2026-05-16-3b014a');
        $this->assertNotFalse($aiMarker);
        $controlBoxStart = strpos($this->bootstrap, 'new (mw.top()).controlBox', $aiMarker);
        $this->assertNotFalse($controlBoxStart);
        $controlBoxEnd = strpos($this->bootstrap, '});', $controlBoxStart);
        $block = substr($this->bootstrap, $controlBoxStart, $controlBoxEnd - $controlBoxStart);

        $this->assertDoesNotMatchRegularExpression(
            "/title:\\s*mw\\.lang\\(\\s*['\"]Element Style Editor['\"]\\s*\\)/",
            $block,
            "guiEditor controlBox must NOT regress to `title: mw.lang('Element Style Editor')` — that's the pre-AI-727 form."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — wrapper div + behaviour preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function gui_editor_controlbox_id_preserved(): void
    {
        // The wrapper div #mw-live-edit-gui-editor-box stays —
        // back-compat anchor for the appendChild call below it
        // and any other code that targets the ESE wrapper. Only
        // the h3 inside the wrapper is suppressed.
        $aiMarker = strpos($this->bootstrap, 'AI-727 / task-2026-05-16-3b014a');
        $this->assertNotFalse($aiMarker);
        $controlBoxStart = strpos($this->bootstrap, 'new (mw.top()).controlBox', $aiMarker);
        $this->assertNotFalse($controlBoxStart);
        $controlBoxEnd = strpos($this->bootstrap, '});', $controlBoxStart);
        $block = substr($this->bootstrap, $controlBoxStart, $controlBoxEnd - $controlBoxStart);

        $this->assertMatchesRegularExpression(
            "/id:\\s*['\"]mw-live-edit-gui-editor-box['\"]/",
            $block,
            'guiEditor controlBox wrapper id="mw-live-edit-gui-editor-box" must be preserved for back-compat appendChild targeting.'
        );
    }

    #[Test]
    public function gui_editor_controlbox_close_button_preserved(): void
    {
        $aiMarker = strpos($this->bootstrap, 'AI-727 / task-2026-05-16-3b014a');
        $controlBoxStart = strpos($this->bootstrap, 'new (mw.top()).controlBox', $aiMarker);
        $controlBoxEnd = strpos($this->bootstrap, '});', $controlBoxStart);
        $block = substr($this->bootstrap, $controlBoxStart, $controlBoxEnd - $controlBoxStart);

        $this->assertMatchesRegularExpression(
            '/closeButton:\s*true/',
            $block,
            'guiEditor controlBox must retain closeButton: true — only the title is suppressed.'
        );
    }

    #[Test]
    public function append_child_call_preserved_after_controlbox(): void
    {
        // Regression-guard: the line
        // `guiEditor.boxContent.appendChild(document.getElementById(
        // 'mw-element-style-editor-app'))` immediately after the
        // controlBox call must remain — that's what actually
        // mounts the Vue ESE app inside the wrapper.
        // Supersession: the mount was refactored from an inline
        // `appendChild(document.getElementById('mw-element-style-editor-app'))`
        // to a null-guarded two-step:
        //   var eseApp = document.getElementById('mw-element-style-editor-app');
        //   if (eseApp) { guiEditor.boxContent.appendChild(eseApp); }
        // Still mounts the same Vue ESE app into the guiEditor box. Pin both
        // the element lookup and the appendChild onto guiEditor.boxContent.
        $this->assertMatchesRegularExpression(
            "/document\\.getElementById\\(\\s*['\"]mw-element-style-editor-app['\"]\\s*\\)/",
            $this->bootstrap,
            'ESE app element lookup (mw-element-style-editor-app) must remain.'
        );
        $this->assertMatchesRegularExpression(
            '/guiEditor\\.boxContent\\.appendChild\\(\\s*eseApp\\s*\\)/',
            $this->bootstrap,
            'guiEditor.boxContent.appendChild(eseApp) mount call must remain — pure config change, no markup teardown.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-16-3b014a',
            $this->bootstrap,
            'AI-727 task-id marker must be present in bootstrap.js source comments.'
        );
        $this->assertStringContainsString('AI-727', $this->bootstrap);
    }

    #[Test]
    public function ai708_lineage_cited_in_docblock(): void
    {
        // The AI-727 docblock should reference AI-708 — same
        // defect class, same fix pattern. Future agents reading
        // this block pick up the lineage.
        $aiMarker = strpos($this->bootstrap, 'AI-727 / task-2026-05-16-3b014a');
        $this->assertNotFalse($aiMarker);
        // Slice the docblock — from marker to closing */ or to
        // the controlBox call.
        $controlBoxStart = strpos($this->bootstrap, 'new (mw.top()).controlBox', $aiMarker);
        $docblock = substr($this->bootstrap, $aiMarker, $controlBoxStart - $aiMarker);

        $this->assertStringContainsString(
            'AI-708',
            $docblock,
            'AI-727 docblock must cite AI-708 — same defect class, same fix pattern lineage.'
        );
    }
}
