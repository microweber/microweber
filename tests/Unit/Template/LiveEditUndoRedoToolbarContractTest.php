<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-274 + AI-268 contract test (task-2026-05-13-b07dd6).
 *
 * Pins the structural shape of the Live Edit toolbar undo/redo
 * affordance fix:
 *
 *   - `UndoRedo.vue` continues to expose undo + redo buttons with the
 *     existing aria-labels AND now carries `title` attributes so pointer
 *     users see the keyboard shortcut on hover.
 *   - The undo button's title is exactly `Undo (Ctrl+Z)`; the redo
 *     button's title is exactly `Redo (Ctrl+Y)`. The two attributes
 *     (aria-label + title) match copy so screen-reader and pointer
 *     users hear/see the same hint.
 *   - The `UndoRedo` mounted hook binds Ctrl+Z → undo, Ctrl+Y → redo
 *     AND Ctrl+Shift+Z → redo (Mac-style alias) via
 *     `mw.app.editor.on(...)` so the keyboard surface matches the
 *     button surface.
 *   - Both click handlers (`undo()` / `redo()`) early-return when the
 *     corresponding disabled flag is set, so a held key never dispatches
 *     past the end of the history buffer.
 *   - `Toolbar.vue` removes the `hidden` class from the
 *     `.live-edit-undo-redo-buttons-wrapper` so the buttons surface.
 *   - `SaveButton.vue` gains a `title="Save & Publish (Ctrl+S)"`
 *     attribute alongside its existing aria-label.
 *   - The built `live-edit-app.js` bundle carries every new string, so
 *     a future refactor that forgets to rebuild fails fast in unit CI.
 *
 * Deliberately NOT pinned (deferred to AI-274 follow-ups):
 *   - Settings gear → keyboard-shortcut reference panel (multi-component
 *     scope).
 *   - Mobile long-press hint (touch-event handler — fragile, browser-
 *     dependent; the new `title` attribute already gives Android/iOS
 *     long-press affordance for free).
 *   - First-time tooltip ("Tip: Press Ctrl+Z to undo") — needs user-
 *     preferences plumbing, same blocker as AI-272b.
 */
class LiveEditUndoRedoToolbarContractTest extends TestCase
{
    private const UNDO_REDO_VUE   = __DIR__ . '/../../../packages/frontend-assets/resources/assets/ui/components/Toolbar/UndoRedo.vue';
    private const TOOLBAR_VUE     = __DIR__ . '/../../../packages/frontend-assets/resources/assets/ui/components/Toolbar/Toolbar.vue';
    private const SAVE_BUTTON_VUE = __DIR__ . '/../../../packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue';
    private const LIVE_EDIT_BUNDLE = __DIR__ . '/../../../public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js';

    #[Test]
    public function undo_button_carries_visible_hover_tooltip_and_aria_label_with_shortcut(): void
    {
        $vue = $this->readFile(self::UNDO_REDO_VUE);

        // AI-719 (task-2026-05-16-d2e562): aria-label collapsed to bare "Undo"
        // so the mobile ::after pseudo (content: attr(aria-label)) renders just
        // "Undo" without the shortcut hint that bloats mobile toolbar width.
        // The "(Ctrl+Z)" shortcut is preserved in title= for desktop hover.
        $this->assertMatchesRegularExpression(
            '/v-on:click="undo\(\)"[^>]*aria-label="Undo"[^>]*title="Undo \(Ctrl\+Z\)"/s',
            $vue,
            'Undo button must carry aria-label="Undo" AND title="Undo (Ctrl+Z)".'
        );
    }

    #[Test]
    public function redo_button_carries_visible_hover_tooltip_and_aria_label_with_shortcut(): void
    {
        $vue = $this->readFile(self::UNDO_REDO_VUE);

        // AI-719 (task-2026-05-16-d2e562): same rationale as Undo above.
        $this->assertMatchesRegularExpression(
            '/v-on:click="redo\(\)"[^>]*aria-label="Redo"[^>]*title="Redo \(Ctrl\+Y\)"/s',
            $vue,
            'Redo button must carry aria-label="Redo" AND title="Redo (Ctrl+Y)".'
        );
    }

    #[Test]
    public function undo_and_redo_handlers_early_return_when_disabled(): void
    {
        $vue = $this->readFile(self::UNDO_REDO_VUE);

        $this->assertMatchesRegularExpression(
            '/undo\(\)\s*\{[^}]*if\s*\(\s*this\.undoIsDisabled\s*\)\s*\{[^}]*return/s',
            $vue,
            'undo() must early-return when this.undoIsDisabled is true so a held Ctrl+Z cannot dispatch past the history buffer.'
        );

        $this->assertMatchesRegularExpression(
            '/redo\(\)\s*\{[^}]*if\s*\(\s*this\.redoIsDisabled\s*\)\s*\{[^}]*return/s',
            $vue,
            'redo() must early-return when this.redoIsDisabled is true.'
        );
    }

    #[Test]
    public function ctrl_z_and_ctrl_y_keyboard_shortcuts_are_wired_via_mw_editor(): void
    {
        $vue = $this->readFile(self::UNDO_REDO_VUE);

        $this->assertMatchesRegularExpression(
            '/mw\.app\.editor\.on\(\s*\'Ctrl\+Z\'\s*,/',
            $vue,
            'UndoRedo.vue must bind Ctrl+Z via mw.app.editor.on().'
        );

        $this->assertMatchesRegularExpression(
            '/mw\.app\.editor\.on\(\s*\'Ctrl\+Y\'\s*,/',
            $vue,
            'UndoRedo.vue must bind Ctrl+Y via mw.app.editor.on().'
        );

        $this->assertMatchesRegularExpression(
            '/mw\.app\.editor\.on\(\s*\'Ctrl\+Shift\+Z\'\s*,/',
            $vue,
            'UndoRedo.vue must also bind Ctrl+Shift+Z (Mac-style redo alias).'
        );
    }

    #[Test]
    public function toolbar_wrapper_no_longer_carries_the_hidden_class(): void
    {
        $vue = $this->readFile(self::TOOLBAR_VUE);

        $this->assertStringNotContainsString(
            'live-edit-undo-redo-buttons-wrapper hidden',
            $vue,
            'Toolbar.vue must not keep the `hidden` class on .live-edit-undo-redo-buttons-wrapper — the undo/redo buttons need to be visible.'
        );

        $this->assertMatchesRegularExpression(
            '/class="live-edit-undo-redo-buttons-wrapper(?!\s+hidden)[^"]*"\s*>\s*\n\s*<UndoRedo/s',
            $vue,
            'The .live-edit-undo-redo-buttons-wrapper must still wrap the <UndoRedo> child component (no class name regression).'
        );
    }

    #[Test]
    public function save_button_carries_visible_tooltip_with_ctrl_s_shortcut(): void
    {
        $vue = $this->readFile(self::SAVE_BUTTON_VUE);

        // task-2026-05-16-3a464f: label shortened to bare "Save" so the copy
        // fits cleanly in narrow viewports. Saving IS publishing on the live-edit
        // surface so the longer "Save & Publish" wording is redundant.
        // The Ctrl+S shortcut hint remains in title= for hover affordance.
        $this->assertMatchesRegularExpression(
            '/id="save-button"[^>]*aria-label="Save"[^>]*title="Save \(Ctrl\+S\)"/s',
            $vue,
            'SaveButton.vue must carry aria-label="Save" AND title="Save (Ctrl+S)".'
        );
    }

    #[Test]
    public function built_live_edit_bundle_carries_every_new_toolbar_string(): void
    {
        if (!file_exists(self::LIVE_EDIT_BUNDLE)) {
            $this->markTestSkipped('Built live-edit-app.js missing — run `npm run build` in packages/frontend-assets.');
        }

        $built = $this->readFile(self::LIVE_EDIT_BUNDLE);

        $this->assertStringContainsString(
            'Undo (Ctrl+Z)',
            $built,
            'Built bundle must carry the "Undo (Ctrl+Z)" tooltip string.'
        );
        $this->assertStringContainsString(
            'Redo (Ctrl+Y)',
            $built,
            'Built bundle must carry the "Redo (Ctrl+Y)" tooltip string.'
        );
        $this->assertStringContainsString(
            'Save (Ctrl+S)',
            $built,
            'Built bundle must carry the "Save (Ctrl+S)" tooltip string.'
        );
        $this->assertStringContainsString(
            'Ctrl+Shift+Z',
            $built,
            'Built bundle must carry the Ctrl+Shift+Z keyboard binding string.'
        );
        $this->assertStringContainsString(
            'live-edit-undo-redo-buttons-wrapper',
            $built,
            'Built bundle must continue to carry the .live-edit-undo-redo-buttons-wrapper class so the wrapper renders in production.'
        );
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");

        $contents = file_get_contents($real);
        $this->assertNotFalse($contents, "Could not read: {$path}");
        $this->assertNotEmpty($contents, "File is empty: {$path}");

        return $contents;
    }
}
