<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-177 / AI-247 + AI-248 (2026-05-11) — modal a11y shim
 * (P2 a11y batch).
 *
 *   AI-247 — Body scroll lock when modals / drawers open.
 *            Filament v5's modal blade uses Alpine's
 *            `x-trap.noscroll` which provides BOTH focus trap
 *            AND body scroll lock when @alpinejs/focus plugin
 *            is loaded. But agent-test reported scroll-not-
 *            locked at 390×844 across Live Edit + admin form
 *            modals — so x-trap is either not loaded in some
 *            iframe contexts or not working on touch devices.
 *
 *   AI-248 — Modal focus management:
 *            - Focus into modal on open
 *            - Tab cycles within modal (no leaking out)
 *            - Escape closes the modal
 *            - Focus returns to the trigger on close
 *
 * The shim hooks Filament's own `x-modal-opened` event
 * (vendor/filament/support/resources/js/components/modal.js
 * line 120) and `modal-closed` event (line 109). When
 * Alpine's x-trap IS working, the shim is a no-op (body
 * already has overflow:hidden, focus is already inside the
 * modal — checks fail and we exit early). When it's not, the
 * shim provides the missing behavior.
 *
 * WCAG: 2.4.3 Focus Order, 2.1.1 Keyboard, 2.1.2 No Keyboard
 * Trap.
 */
class Ai247Ai248ModalA11yShimContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_177_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/js/microweber-filament-theme.js');
        $this->assertMatchesRegularExpression('/[Cc]ycle-177/', $src,
            'microweber-filament-theme.js MUST carry the cycle-177 anchor.');
        $this->assertStringContainsString('AI-247', $src,
            'microweber-filament-theme.js MUST carry the AI-247 anchor.');
        $this->assertStringContainsString('AI-248', $src,
            'microweber-filament-theme.js MUST carry the AI-248 anchor.');
    }

    #[Test]
    public function ai_247_listens_for_modal_opened_and_closed(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/js/microweber-filament-theme.js');
        // The shim MUST hook Filament's own event names so it
        // composes with x-trap (no override, no race).
        $this->assertStringContainsString("'x-modal-opened'", $src,
            'shim MUST listen for Filament\'s x-modal-opened event '
            . '(vendor/filament/support/resources/js/components/'
            . 'modal.js line 120).');
        $this->assertStringContainsString("'modal-closed'", $src,
            'shim MUST listen for Filament\'s modal-closed event '
            . '(modal.js line 109).');
    }

    #[Test]
    public function ai_247_body_scroll_lock_is_stack_aware(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/js/microweber-filament-theme.js');

        // Only lock when the stack goes from 0 → 1.
        $this->assertMatchesRegularExpression(
            '/lockBodyScroll[\s\S]{0,400}openStack\.length\s*!==\s*1/',
            $src,
            'lockBodyScroll MUST guard on openStack.length !== 1 '
            . 'so nested modal opens do NOT re-lock the body '
            . '(stack-aware lock — only the first modal locks).'
        );
        // Only unlock when stack drains to 0.
        $this->assertMatchesRegularExpression(
            '/unlockBodyScroll[\s\S]{0,400}openStack\.length\s*!==\s*0/',
            $src,
            'unlockBodyScroll MUST guard on openStack.length !== 0 '
            . 'so closing an inner modal does NOT unlock the body '
            . 'while an outer modal is still open.'
        );
        // Sets body.style.overflow = 'hidden'.
        $this->assertMatchesRegularExpression(
            '/document\.body\.style\.overflow\s*=\s*[\'"]hidden[\'"]/',
            $src,
            'shim MUST set document.body.style.overflow = "hidden" '
            . 'on first modal open.'
        );
        // Saves and restores scroll position.
        $this->assertStringContainsString('mwModalScrollY', $src,
            'shim MUST save scrollY in body.dataset.mwModalScrollY '
            . 'so scroll position is preserved across modal lifecycle.');
        $this->assertMatchesRegularExpression(
            '/window\.scrollTo\(\s*0\s*,\s*y\s*\)/',
            $src,
            'unlockBodyScroll MUST restore scroll position via '
            . 'window.scrollTo(0, y).'
        );
    }

    #[Test]
    public function ai_247_no_op_when_filament_already_locked(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/js/microweber-filament-theme.js');

        // If body.style.overflow === 'hidden' already (Filament's
        // x-trap.noscroll did its job), the shim must short-circuit.
        $this->assertMatchesRegularExpression(
            '/lockBodyScroll[\s\S]{0,400}document\.body\.style\.overflow\s*===\s*[\'"]hidden[\'"]/',
            $src,
            'lockBodyScroll MUST short-circuit when body.style.'
            . 'overflow is already "hidden" — composes with '
            . 'Filament\'s x-trap.noscroll without double-locking.'
        );
    }

    #[Test]
    public function ai_248_focus_first_tabbable_on_open(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/js/microweber-filament-theme.js');

        // focusFirstTabbableIn must check modal.contains(activeElement)
        // first so we don't yank focus from an element Alpine's
        // x-trap just focused.
        $this->assertMatchesRegularExpression(
            '/focusFirstTabbableIn[\s\S]{0,300}modalEl\.contains\(document\.activeElement\)/',
            $src,
            'focusFirstTabbableIn MUST short-circuit when active '
            . 'element is already inside the modal (composes with '
            . 'Alpine\'s x-trap without yanking focus).'
        );
        // Tabbable list selector
        $this->assertMatchesRegularExpression(
            '/var\s+TABBABLE[\s\S]{0,200}a\[href\][\s\S]{0,200}\[tabindex\]/',
            $src,
            'shim MUST define a TABBABLE selector that includes '
            . 'a[href], buttons, inputs, selects, textareas, and '
            . '[tabindex]:not([tabindex="-1"]).'
        );
    }

    #[Test]
    public function ai_248_tab_cycles_within_modal(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/js/microweber-filament-theme.js');

        // Tab key handler — wrap from last to first.
        $this->assertMatchesRegularExpression(
            '/event\.key\s*===\s*[\'"]Tab[\'"][\s\S]{0,1500}preventDefault\(\)[\s\S]{0,300}first\.focus\(\)/m',
            $src,
            'shim MUST install a Tab key handler that wraps focus '
            . 'from last tabbable back to first when Tab is pressed '
            . 'without Shift on the last element.'
        );
        // Shift+Tab wraps from first back to last.
        $this->assertMatchesRegularExpression(
            '/event\.shiftKey[\s\S]{0,500}last\.focus\(\)/m',
            $src,
            'shim MUST also handle Shift+Tab to wrap from first '
            . 'tabbable back to last (reverse cycling).'
        );
    }

    #[Test]
    public function ai_248_escape_dispatches_close_modal(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/js/microweber-filament-theme.js');

        // Escape must dispatch Filament's close-modal event so
        // Filament's blade listener (x-on:close-modal.window in
        // vendor/filament/support/resources/views/components/
        // modal/index.blade.php line 100) handles the close.
        // The shim does NOT manage close state itself — it
        // delegates to Filament so close animations + state
        // sync work correctly.
        $this->assertMatchesRegularExpression(
            '/event\.key\s*===\s*[\'"]Escape[\'"][\s\S]{0,400}close-modal/',
            $src,
            'shim MUST dispatch Filament\'s close-modal event on '
            . 'Escape key — delegates close handling to Filament '
            . 'so animations and state sync work correctly.'
        );
        // CustomEvent with detail.id matching openStack top.
        $this->assertMatchesRegularExpression(
            '/new\s+CustomEvent\s*\(\s*[\'"]close-modal[\'"][\s\S]{0,400}detail:\s*\{\s*id:\s*topId/',
            $src,
            'close-modal dispatch MUST include detail.id matching '
            . 'the topmost open modal so Filament\'s blade window '
            . 'listener finds the right modal.'
        );
    }

    #[Test]
    public function ai_248_focus_returns_to_trigger_on_close(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/js/microweber-filament-theme.js');

        // triggerMap is a Map keyed by modal id.
        $this->assertStringContainsString('triggerMap = new Map()', $src,
            'shim MUST maintain a triggerMap (Map keyed by modal '
            . 'id) so each modal\'s trigger element is restored '
            . 'on its own close — handles nested modals correctly.');
        // Capture trigger on x-modal-opened.
        $this->assertMatchesRegularExpression(
            '/x-modal-opened[\s\S]{0,800}var\s+trigger\s*=\s*document\.activeElement[\s\S]{0,400}triggerMap\.set\(id,\s*trigger\)/',
            $src,
            'on x-modal-opened, shim MUST capture document.'
            . 'activeElement BEFORE focusing into the modal and '
            . 'store it in triggerMap so focus can return on close.'
        );
        // Restore on modal-closed.
        $this->assertMatchesRegularExpression(
            '/modal-closed[\s\S]{0,1000}triggerMap\.get\(id\)[\s\S]{0,300}trigger\.focus\(\)/',
            $src,
            'on modal-closed, shim MUST retrieve the trigger from '
            . 'triggerMap and call trigger.focus() so focus returns '
            . 'to the original trigger (WCAG 2.4.3 Focus Order).'
        );
    }

    #[Test]
    public function built_bundle_carries_a11y_shim(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.js';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme JS bundle missing.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson — minified bundle
        // still contains the load-bearing event names and
        // distinctive identifier strings.
        $this->assertStringContainsString('x-modal-opened', $built,
            'Built JS bundle MUST contain the x-modal-opened event '
            . 'listener — confirms the a11y shim is in the compiled '
            . 'output.');
        $this->assertStringContainsString('modal-closed', $built,
            'Built JS bundle MUST contain the modal-closed event '
            . 'listener.');
        $this->assertStringContainsString('mwModalScrollY', $built,
            'Built JS bundle MUST contain the mwModalScrollY '
            . 'data-attribute identifier (proves the scroll-position '
            . 'preservation code shipped).');
        $this->assertStringContainsString('close-modal', $built,
            'Built JS bundle MUST contain the close-modal event '
            . 'dispatch (Escape key handler).');
    }
}
