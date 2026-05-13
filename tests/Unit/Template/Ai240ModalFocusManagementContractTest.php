<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-240 modal focus management contract test (task-2026-05-13-0fb704).
 *
 * Filament's `.fi-modal` already provides focus-trap + Escape + focus-
 * return via Alpine's `x-trap` + `x-on:keydown.window.escape` (vendor
 * code, not pinned here). The Microweber Livewire `mw-modal` overlay
 * ran outside Alpine and had none of those affordances — this commit
 * adds them via vanilla JS in the existing `<script>` block of
 * `mw-modal.blade.php`.
 *
 * The contract test pins the structural shape so a future refactor of
 * the bespoke modal layer cannot silently regress the WCAG 2.1.1 /
 * 2.1.2 / 2.4.3 a11y behaviours:
 *   - the modal wrapper carries role="dialog" + aria-modal="true" +
 *     tabindex="-1"
 *   - the script declares a tabbable-element selector list that
 *     matches the standard focus-trap-library set
 *   - the keydown handler covers Tab + Shift+Tab + Escape
 *   - Escape dispatches Livewire's `closeModal` event
 *   - `activeModalComponentChanged` wires the trap, `closeModal`
 *     releases it — symmetric open/close
 *   - the `mwModalFocusStack` array enables nested-modal focus
 *     restoration
 */
class Ai240ModalFocusManagementContractTest extends TestCase
{
    private const MW_MODAL_BLADE = __DIR__ . '/../../../src/MicroweberPackages/Livewire/resources/views/mw-modal.blade.php';

    #[Test]
    public function modal_wrapper_carries_role_dialog_aria_modal_and_tabindex(): void
    {
        $blade = $this->readFile(self::MW_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            '/class="js-modal-livewire[^"]*"[^>]*\srole="dialog"/s',
            $blade,
            'AI-240: .js-modal-livewire wrapper must carry role="dialog" so assistive tech announces it as a modal.'
        );

        $this->assertMatchesRegularExpression(
            '/class="js-modal-livewire[^"]*"[\s\S]{0,400}?aria-modal="true"/s',
            $blade,
            'AI-240: .js-modal-livewire wrapper must carry aria-modal="true" so background content is treated as inert.'
        );

        $this->assertMatchesRegularExpression(
            '/class="js-modal-livewire[^"]*"[\s\S]{0,400}?tabindex="-1"/s',
            $blade,
            'AI-240: .js-modal-livewire wrapper must carry tabindex="-1" so the focus-trap fallback can programmatically focus the wrapper when no tabbable child exists.'
        );
    }

    #[Test]
    public function script_declares_tabbable_selector_matching_standard_focus_trap_set(): void
    {
        $blade = $this->readFile(self::MW_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            "/MW_MODAL_TABBABLE_SELECTOR\\s*=\\s*\\[[\\s\\S]*?'a\\[href\\]'[\\s\\S]*?'button:not\\(\\[disabled\\]\\)'[\\s\\S]*?'input:not\\(\\[disabled\\]\\):not\\(\\[type=\"hidden\"\\]\\)'/",
            $blade,
            'AI-240: MW_MODAL_TABBABLE_SELECTOR must include a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]) — the standard focus-trap library tabbable set.'
        );

        $this->assertStringContainsString(
            "'[tabindex]:not([tabindex=\"-1\"])'",
            $blade,
            'AI-240: tabbable selector must include [tabindex]:not([tabindex="-1"]) so elements with explicit positive tabindex are reachable.'
        );
    }

    #[Test]
    public function keydown_handler_traps_tab_and_closes_on_escape(): void
    {
        $blade = $this->readFile(self::MW_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            '/function\s+mwModalKeydownHandler\s*\(\s*event\s*\)\s*\{[\s\S]*?event\.key\s*===\s*\'Escape\'/s',
            $blade,
            "AI-240: mwModalKeydownHandler must close on event.key === 'Escape'."
        );

        $this->assertMatchesRegularExpression(
            '/event\.key\s*!==\s*\'Tab\'\s*&&\s*event\.keyCode\s*!==\s*9/s',
            $blade,
            "AI-240: mwModalKeydownHandler must recognise the Tab key via event.key !== 'Tab' && event.keyCode !== 9 (early-return for non-Tab non-Escape keys)."
        );

        $this->assertMatchesRegularExpression(
            "/window\\.Livewire\\.dispatch\\('closeModal'\\)/",
            $blade,
            "AI-240: Escape handling must dispatch Livewire's 'closeModal' event so the trap close matches the click-to-close path."
        );
    }

    #[Test]
    public function shift_tab_wraps_from_first_to_last_focusable(): void
    {
        $blade = $this->readFile(self::MW_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            '/event\.shiftKey[\s\S]*?active\s*===\s*first[\s\S]*?last\.focus\(\)/s',
            $blade,
            "AI-240: when shiftKey is true AND active === first, focus must wrap to last (Shift+Tab cycle)."
        );

        $this->assertMatchesRegularExpression(
            '/else\s*\{[\s\S]*?active\s*===\s*last[\s\S]*?first\.focus\(\)/s',
            $blade,
            "AI-240: when shiftKey is false AND active === last, focus must wrap to first (Tab cycle)."
        );
    }

    #[Test]
    public function open_event_wires_trap_and_close_event_releases_it(): void
    {
        $blade = $this->readFile(self::MW_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            "/Livewire\\.on\\(\\s*'activeModalComponentChanged'[\\s\\S]*?mwTrapFocusForModal\\(\\)/s",
            $blade,
            "AI-240: activeModalComponentChanged handler must call mwTrapFocusForModal() on open."
        );

        $this->assertMatchesRegularExpression(
            "/Livewire\\.on\\(\\s*'closeModal'[\\s\\S]*?mwReleaseFocusForModal\\(\\)/s",
            $blade,
            "AI-240: closeModal handler must call mwReleaseFocusForModal() to restore focus to the trigger."
        );
    }

    #[Test]
    public function focus_stack_supports_nested_modal_restore(): void
    {
        $blade = $this->readFile(self::MW_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            '/let\s+mwModalFocusStack\s*=\s*\[\]/',
            $blade,
            'AI-240: mwModalFocusStack must be a stack (array), not a single ref, so nested modals stack-restore correctly.'
        );

        $this->assertMatchesRegularExpression(
            '/mwModalFocusStack\.push\(document\.activeElement[\s\S]*?\)/s',
            $blade,
            'AI-240: mwTrapFocusForModal must push document.activeElement onto the stack on open.'
        );

        $this->assertMatchesRegularExpression(
            '/mwModalFocusStack\.pop\(\)/',
            $blade,
            'AI-240: mwReleaseFocusForModal must pop the stack to retrieve the saved trigger on close.'
        );
    }

    #[Test]
    public function release_only_unbinds_keydown_when_stack_is_empty(): void
    {
        $blade = $this->readFile(self::MW_MODAL_BLADE);

        // The mwUnbindModalKeydown function must early-return if the
        // focus stack still has entries — otherwise a nested-modal
        // close would tear down the trap before the outer modal closes.
        $this->assertMatchesRegularExpression(
            '/function\s+mwUnbindModalKeydown\s*\(\s*\)\s*\{[\s\S]*?mwModalFocusStack\.length\s*>\s*0\s*\)\s*return/s',
            $blade,
            'AI-240: mwUnbindModalKeydown must NOT unbind the keydown listener while mwModalFocusStack still has entries (nested modals would lose their trap).'
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
