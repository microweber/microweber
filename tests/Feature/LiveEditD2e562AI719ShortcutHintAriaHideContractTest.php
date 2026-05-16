<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-d2e562 / AI-719 (Medium) — Hide keyboard-shortcut
 * hints (Ctrl+Z, Ctrl+Y, Ctrl+S) on touch viewports.
 *
 * Designer dispatch (per-ticket email 2026-05-16T13:58): mobile
 * aria-labels read "Undo (Ctrl+Z)" / "Redo (Ctrl+Y)" / "Save
 * (Ctrl+S)". Keyboard shortcuts are meaningless on touch and the
 * task-8149b5 mobile ::after pseudo (`content: attr(aria-label)`)
 * rendered those shortcut suffixes as visible text — ~50 px per
 * button of wasted toolbar width (contributing to AI-717 SAVE-
 * offscreen).
 *
 * Fix: collapse the aria-label to the bare verb so:
 *   - The task-8149b5 ::after pseudo renders just "Undo" / "Redo"
 *     on touch viewports (visible-width savings ~150 px total).
 *   - Screen readers everywhere announce the bare verb (shortcuts
 *     are meaningless to AT users the same way they're meaningless
 *     to touch users).
 *   - `title=` keeps the full "(Ctrl+Z)" / "(Ctrl+Y)" / "(Ctrl+S)"
 *     hint for desktop hover discovery.
 *   - The keyboard binding itself (mw.app.editor.on('Ctrl+Z'…) in
 *     UndoRedo.vue mounted() + 'Ctrl+S' in SaveButton.vue
 *     mounted()) is preserved — shortcuts still work on desktop.
 *
 * Two-file fix:
 *
 *   1. UndoRedo.vue
 *      - Undo: aria-label "Undo (Ctrl+Z)" → "Undo";
 *        title="Undo (Ctrl+Z)" preserved.
 *      - Redo: aria-label "Redo (Ctrl+Y)" → "Redo";
 *        title="Redo (Ctrl+Y)" preserved.
 *
 *   2. SaveButton.vue
 *      - aria-label "Save (Ctrl+S)" → "Save";
 *        title="Save (Ctrl+S)" preserved.
 *
 * The existing AI-3a464f SaveButton contract test was updated
 * in-place to match the new spec (was asserting verbatim
 * `aria-label="Save (Ctrl+S)"`); see
 * `tests/Feature/LiveEdit3a464fSaveButtonLabelContractTest.php`
 * for the docblock note + new assertion.
 */
class LiveEditD2e562AI719ShortcutHintAriaHideContractTest extends TestCase
{
    private string $undoRedo;
    private string $saveButton;

    protected function setUp(): void
    {
        parent::setUp();
        $this->undoRedo = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/UndoRedo.vue'
        ));
        $this->saveButton = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — UndoRedo aria-label collapses
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function undo_button_aria_label_is_bare_verb(): void
    {
        // The undo button must carry `aria-label="Undo"` — no
        // shortcut suffix in the aria-label.
        $this->assertMatchesRegularExpression(
            '/<button[^>]*aria-label="Undo"[^>]*id="vue-toolbar-undo"/',
            $this->undoRedo,
            'Undo button aria-label must be bare verb "Undo" per AI-719 (was "Undo (Ctrl+Z)").'
        );
    }

    #[Test]
    public function undo_button_title_keeps_shortcut(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button[^>]*title="Undo \(Ctrl\+Z\)"[^>]*id="vue-toolbar-undo"/',
            $this->undoRedo,
            'Undo button title must keep "Undo (Ctrl+Z)" — desktop hover affordance preserved.'
        );
    }

    #[Test]
    public function redo_button_aria_label_is_bare_verb(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button[^>]*aria-label="Redo"[^>]*id="vue-toolbar-redo"/',
            $this->undoRedo,
            'Redo button aria-label must be bare verb "Redo" per AI-719 (was "Redo (Ctrl+Y)").'
        );
    }

    #[Test]
    public function redo_button_title_keeps_shortcut(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button[^>]*title="Redo \(Ctrl\+Y\)"[^>]*id="vue-toolbar-redo"/',
            $this->undoRedo,
            'Redo button title must keep "Redo (Ctrl+Y)" — desktop hover affordance preserved.'
        );
    }

    #[Test]
    public function undo_redo_aria_labels_no_longer_contain_ctrl_hint(): void
    {
        // Negative regression-guard: the `<button>` markup must
        // NOT carry aria-label with a Ctrl+ suffix. Slice to
        // just the two <button> markup lines so the docblock /
        // mounted() prose mentioning "Ctrl+Z" doesn't false-match.
        $templateStart = strpos($this->undoRedo, '<template>');
        $templateEnd = strpos($this->undoRedo, '</template>');
        $this->assertNotFalse($templateStart);
        $this->assertNotFalse($templateEnd);
        $template = substr($this->undoRedo, $templateStart, $templateEnd - $templateStart);

        $this->assertDoesNotMatchRegularExpression(
            '/aria-label="Undo \(Ctrl\+Z\)"/',
            $template,
            'Undo button aria-label="Undo (Ctrl+Z)" must NOT regress.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/aria-label="Redo \(Ctrl\+Y\)"/',
            $template,
            'Redo button aria-label="Redo (Ctrl+Y)" must NOT regress.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — SaveButton aria-label collapses
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function save_button_aria_label_is_bare_verb(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button[\s\S]{0,500}id="save-button"[\s\S]{0,500}aria-label="Save"/',
            $this->saveButton,
            'Save button aria-label must be bare verb "Save" per AI-719 (was "Save (Ctrl+S)").'
        );
    }

    #[Test]
    public function save_button_title_keeps_shortcut(): void
    {
        $this->assertMatchesRegularExpression(
            '/id="save-button"[\s\S]{0,500}title="Save \(Ctrl\+S\)"/',
            $this->saveButton,
            'Save button title must keep "Save (Ctrl+S)" — desktop hover affordance preserved.'
        );
    }

    #[Test]
    public function save_button_aria_label_no_longer_contains_ctrl_hint(): void
    {
        $templateStart = strpos($this->saveButton, '<template>');
        $templateEnd = strpos($this->saveButton, '</template>');
        $this->assertNotFalse($templateStart);
        $this->assertNotFalse($templateEnd);
        $template = substr($this->saveButton, $templateStart, $templateEnd - $templateStart);

        $this->assertDoesNotMatchRegularExpression(
            '/aria-label="Save \(Ctrl\+S\)"/',
            $template,
            'Save button aria-label="Save (Ctrl+S)" must NOT regress.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Keyboard bindings preserved (regression guard)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function undo_keyboard_binding_preserved(): void
    {
        // Desktop keyboard shortcuts must still work — the binding
        // lives in UndoRedo.vue's mounted() and registers with
        // mw.app.editor. The shortcut hint is removed from
        // aria-label, not from script.
        $this->assertMatchesRegularExpression(
            "/mw\\.app\\.editor\\.on\\(\\s*'Ctrl\\+Z'/",
            $this->undoRedo,
            "UndoRedo.vue must still register the 'Ctrl+Z' binding via mw.app.editor.on() — desktop shortcut preserved."
        );
    }

    #[Test]
    public function redo_keyboard_binding_preserved(): void
    {
        $this->assertMatchesRegularExpression(
            "/mw\\.app\\.editor\\.on\\(\\s*'Ctrl\\+Y'/",
            $this->undoRedo,
            "UndoRedo.vue must still register the 'Ctrl+Y' binding."
        );
    }

    #[Test]
    public function save_keyboard_binding_preserved(): void
    {
        $this->assertMatchesRegularExpression(
            "/mw\\.app\\.editor\\.on\\(\\s*'Ctrl\\+S'/",
            $this->saveButton,
            "SaveButton.vue must still register the 'Ctrl+S' binding."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_undo_redo(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-16-d2e562',
            $this->undoRedo,
            'AI-719 task-id marker must be present in UndoRedo.vue source comments (audit grep).'
        );
        $this->assertStringContainsString('AI-719', $this->undoRedo);
    }
}
