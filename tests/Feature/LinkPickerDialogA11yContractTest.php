<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-95 / AI-84 / TICKET-YY — Link picker dialog a11y + CSP +
 * autocomplete + focus-restore regression coverage.
 *
 * Pins:
 *   - The Link picker dialog gains a real `<button>` close control
 *     with `aria-label="Close"` (was a `<span onclick="...">` —
 *     CSP-violating inline handler + no keyboard semantics).
 *   - The dialog frame gets `role="dialog"`, `aria-modal="true"`,
 *     and `aria-labelledby` linked to the dialog title id, so
 *     screen-reader users hear "Link Settings dialog" on open.
 *   - Focus-restore: the element that had focus before the dialog
 *     opened is captured at build-time and re-focused on confirm
 *     OR cancel, so SR/keyboard users don't get dropped to
 *     `<body>` after dismissing.
 *   - The URL controller's URL `<input>` gains
 *     `autocomplete="url"` + `inputmode="url"` + `spellcheck="false"`
 *     so browsers offer URL suggestions from history, mobile
 *     keyboards promote slash/dot keys, and Safari/Chrome stop
 *     underlining URLs as typos.
 *
 * Style after the cycle-52..94 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class LinkPickerDialogA11yContractTest extends TestCase
{
    private const LINK_EDITOR  = 'packages/frontend-assets/resources/assets/components/link-editor.js';
    private const FORM_CTRLS   = 'packages/frontend-assets/resources/assets/components/form-controls.js';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function close_button_is_a_real_button_not_inline_onclick_span(): void
    {
        $src = $this->read(self::LINK_EDITOR);
        // Strip JS comments so the audit-trail doc-block that mentions
        // the literal pre-fix `onclick="..."` doesn't trigger a false
        // positive.
        $stripped = preg_replace('!//.*$!m', '', $src);
        $stripped = preg_replace('!/\\*[\\s\\S]*?\\*/!', '', $stripped);

        // Negative: the legacy `<span onclick="mw.dialog.get(this).remove()">` shape must be gone.
        $this->assertStringNotContainsString(
            '<span onclick="mw.dialog.get(this).remove()"',
            $stripped,
            'link-editor.js: legacy `<span onclick="mw.dialog.get(this).remove()">` close button must be gone (CSP-violation)'
        );

        // Positive: a real <button type="button"> with aria-label="Close",
        // no inline handler, wired via addEventListener.
        $this->assertStringContainsString(
            "closeBtn.type = 'button'",
            $src,
            'link-editor.js: close button must be created as a real <button type="button">'
        );
        $this->assertStringContainsString(
            "closeBtn.setAttribute('aria-label', mw.lang('Close'))",
            $src,
            'link-editor.js: close button must carry aria-label="Close" (localized)'
        );
        $this->assertStringContainsString(
            "closeBtn.addEventListener('click', function ()",
            $src,
            'link-editor.js: close button must wire its click via addEventListener (no inline onclick)'
        );
    }

    #[Test]
    public function dialog_gets_role_aria_modal_and_aria_labelledby(): void
    {
        $src = $this->read(self::LINK_EDITOR);

        $this->assertMatchesRegularExpression(
            "/dlgRoot\\.setAttribute\\(\\s*'role',\\s*'dialog'\\s*\\)/",
            $src,
            'link-editor.js: dialog frame must get role="dialog"'
        );
        $this->assertMatchesRegularExpression(
            "/dlgRoot\\.setAttribute\\(\\s*'aria-modal',\\s*'true'\\s*\\)/",
            $src,
            'link-editor.js: dialog frame must get aria-modal="true"'
        );
        $this->assertStringContainsString(
            "dlgRoot.setAttribute('aria-labelledby', titleNode.id)",
            $src,
            'link-editor.js: dialog must wire aria-labelledby to the title node id'
        );
    }

    #[Test]
    public function focus_restore_captures_and_restores_previous_active_element(): void
    {
        $src = $this->read(self::LINK_EDITOR);

        // Pre-open capture.
        $this->assertStringContainsString(
            'previouslyFocused',
            $src,
            'link-editor.js: must capture pre-open active element as `previouslyFocused`'
        );
        $this->assertMatchesRegularExpression(
            '/var\\s+previouslyFocused\\s*=\\s*\\(function\\s*\\(\\)\\s*\\{[\\s\\S]*?return\\s*\\([^)]*activeElement[^)]*\\)/',
            $src,
            'link-editor.js: previouslyFocused must read activeElement from mw.top().win.document (or fallback to document)'
        );

        // restoreFocus helper.
        $this->assertMatchesRegularExpression(
            '/var\\s+restoreFocus\\s*=\\s*function\\s*\\(\\)\\s*\\{[\\s\\S]*?previouslyFocused\\.focus\\(\\s*\\{\\s*preventScroll:\\s*true\\s*\\}\\s*\\)/',
            $src,
            'link-editor.js: restoreFocus() must call previouslyFocused.focus({ preventScroll: true })'
        );

        // Wired into BOTH onConfirm AND onCancel callbacks.
        $this->assertMatchesRegularExpression(
            '/this\\.onConfirm\\(function \\(\\)\\{[\\s\\S]*?scope\\.dialog\\.remove\\(\\);[\\s\\S]*?restoreFocus\\(\\);/',
            $src,
            'link-editor.js: onConfirm callback must call restoreFocus() after dialog.remove()'
        );
        $this->assertMatchesRegularExpression(
            '/this\\.onCancel\\(function \\(\\)\\{[\\s\\S]*?scope\\.dialog\\.remove\\(\\);[\\s\\S]*?restoreFocus\\(\\);/',
            $src,
            'link-editor.js: onCancel callback must call restoreFocus() after dialog.remove()'
        );
    }

    #[Test]
    public function url_field_gains_autocomplete_inputmode_and_spellcheck(): void
    {
        $src = $this->read(self::FORM_CTRLS);

        $this->assertStringContainsString(
            "urlField.setAttribute('autocomplete', 'url')",
            $src,
            'form-controls.js URL controller: must set autocomplete="url" on the url <input>'
        );
        $this->assertStringContainsString(
            "urlField.setAttribute('inputmode', 'url')",
            $src,
            'form-controls.js URL controller: must set inputmode="url" so mobile keyboards promote slash/dot keys'
        );
        $this->assertStringContainsString(
            "urlField.setAttribute('spellcheck', 'false')",
            $src,
            'form-controls.js URL controller: must set spellcheck="false" so the URL isn\'t flagged as a typo'
        );
    }
}
