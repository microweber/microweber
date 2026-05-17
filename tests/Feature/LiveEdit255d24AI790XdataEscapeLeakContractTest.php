<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-255d24 / AI-790 — Add Content modal Alpine x-data
 * template-attribute escape leak. [URGENT P1]
 * Jira: https://microweber.atlassian.net/browse/AI-790
 *
 * Designer's R10-4 audit captured the user-visible body of the Add
 * Content modal rendering with a paragraph of raw Alpine.js source
 * code BEFORE the "What do you want to add?" search input — actual
 * `innerText` excerpt:
 *
 *   (c.dataset.mwAddContentHaystack || '').includes(needle)); },
 *   resultAnnouncement() { const total = $root.querySelectorAll(...)
 *
 * Root cause: `//` line comments inside the inline `x-data="{ ... }"`
 * attribute contained literal `"` characters (e.g.
 * `// primary "Add a block" card` at line 97; `// data-mw-add-content-group="primary"`
 * at line 99). The HTML attribute parser terminates the attribute
 * at the first embedded `"`, dumping the remainder of the JS
 * expression as visible text content.
 *
 * Canonical fix (per designer's dispatch): extract the entire state
 * object to a named Alpine.data() registration. Blade attribute now
 * carries only a bare identifier `x-data="addContentModal"` — no
 * quoting issues possible. The script block at the bottom of the
 * Blade is a regular <script> body where `"` characters are fine.
 *
 * This test pins the AI-790 fix shape + carries the designer's
 * generic Tier-3 contract probe (no Alpine internals leaked to
 * innerText) as a source-level regex guard that catches the same
 * defect class in any future modal.
 *
 * **Stage-5 lesson family** (designer's words from the dispatch):
 *
 *   Template attribute escape leak — when an Alpine/Vue/Livewire
 *   directive attribute value contains characters that confuse the
 *   templating engine (typically `"` inside an inline JS string or
 *   expression), the attribute terminates early and the remainder
 *   of the directive becomes visible text. Diagnostic: any visible
 *   text matching /x-init|x-data|\$nextTick|\$refs|querySelectorAll/.
 *   Guard: extract complex x-data to a named Alpine.data()
 *   registration; keep Blade attributes simple references.
 *
 * Sibling lineage in the same defect family:
 *   - AI-692 (task-968a71) — slash-star-style block comments with
 *     embedded double-quotes in prose terminated x-data attribute.
 *     AI-790 supersedes that fix because slash-slash comments
 *     containing double-quotes hit the same terminator class.
 */
class LiveEdit255d24AI790XdataEscapeLeakContractTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — modal root x-data is a bare named reference (no inline JS)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function modal_root_xdata_is_named_alpine_data_reference(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-data="addContentModal"/',
            $this->blade,
            'Modal root must declare `x-data="addContentModal"` — bare named-reference, no inline JS.'
        );
    }

    #[Test]
    public function modal_root_xdata_does_not_carry_inline_object(): void
    {
        // Strip Blade `{{-- ... --}}` comments first (selector-self-match
        // guard — the AI-790 docblock + script-block docblock both
        // mention `x-data="{...}"` as the BEFORE state).
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->blade);
        $this->assertDoesNotMatchRegularExpression(
            '/mw-add-content-modal-root[\s\S]{0,200}x-data="\{/',
            $stripped,
            'Modal root must NOT carry inline `x-data="{ ... }"` JS — extract to Alpine.data() registration.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Alpine.data('addContentModal', ...) registration shipped
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function alpine_data_registration_present_at_bottom_of_blade(): void
    {
        $this->assertStringContainsString(
            "Alpine.data('addContentModal', () => ({",
            $this->blade,
            "Blade must register `Alpine.data('addContentModal', () => ({ ... }))` for the named reference to resolve."
        );
        // Registration must happen inside an `alpine:init` listener so
        // it fires before Alpine evaluates the x-data attribute.
        $this->assertStringContainsString(
            "document.addEventListener('alpine:init',",
            $this->blade,
            'Registration must happen inside an `alpine:init` event listener.'
        );
    }

    #[Test]
    public function registration_is_idempotent_against_repeated_render(): void
    {
        // Filament modals are often torn down + re-rendered in the same
        // page lifetime. Idempotent guard prevents double-registration
        // warnings + duplicate handler hits.
        $this->assertStringContainsString(
            'window.__mwAddContentModalRegistered',
            $this->blade,
            'Registration must check a window-scoped sentinel flag to be idempotent across multiple Blade renders.'
        );
    }

    #[Test]
    public function registration_uses_window_alpine_safety_check(): void
    {
        // Defence-in-depth: if Alpine is not loaded (rare but possible
        // in stripped-down Filament panels), the script must noop
        // rather than throwing.
        $this->assertStringContainsString(
            "if (typeof window.Alpine === 'undefined' || typeof window.Alpine.data !== 'function')",
            $this->blade,
            'Registration must guard against missing window.Alpine before calling .data().'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — every state method from the OLD inline x-data preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function all_state_methods_present_in_registration(): void
    {
        // Behaviour parity: every method from the OLD x-data inline
        // object must still exist in the Alpine.data() body.
        $methods = [
            'visibleCards()',
            'focusFirstVisibleCard()',
            'focusLastVisibleCard()',
            'focusNextCard(current)',
            'focusPrevCard(current)',
            'activateFirstVisibleCard()',
            'visibleCount()',
            'hasVisibleCardsInGroup(group)',
            'resultAnnouncement()',
        ];
        foreach ($methods as $method) {
            $this->assertStringContainsString(
                $method . ' {',
                $this->blade,
                "Alpine.data() body must preserve state method `{$method}` from the pre-AI-790 x-data object."
            );
        }
        // The `q: ''` initial state field must also remain.
        $this->assertMatchesRegularExpression(
            "/q:\\s*''/",
            $this->blade,
            "Alpine.data() body must initialise `q: ''` (search query state)."
        );
    }

    #[Test]
    public function state_method_bodies_use_this_refs_and_this_root(): void
    {
        // Lifting from inline x-data to Alpine.data() changes the
        // binding of `$root` / `$refs` — inside the function-form
        // factory, those magics are accessed via `this.$root` /
        // `this.$refs`. Pin the new shape so refactors can't drop
        // the `this.` prefix.
        $this->assertStringContainsString(
            'this.$root.querySelectorAll',
            $this->blade,
            'Alpine.data() body must reference `this.$root.querySelectorAll(...)` (not bare `$root`).'
        );
        $this->assertStringContainsString(
            'this.$refs.search.focus()',
            $this->blade,
            'Alpine.data() body must reference `this.$refs.search.focus()` (not bare `$refs`).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Stage-5 leak diagnostic (no Alpine internals in body text)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function first_body_text_after_modal_root_is_not_leaked_js(): void
    {
        // Stage-5 leak detection — when the x-data attribute terminates
        // early, the closing `}"` becomes text content followed by JS.
        // Structural check: the first NON-WHITESPACE characters after
        // the modal-root opening tag's `>` must be a `<` (next element)
        // or a Blade `{{` directive — NOT raw text matching JS shapes
        // like `}", `, `}); `, `() {`. Strip Blade comments first
        // (selector-self-match guard).
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->blade);

        // Locate the modal root opening tag — must walk past the
        // attribute block whose last attribute is x-init. The `>` we
        // want is the one closing the opening tag.
        $rootStart = strpos($stripped, '<div class="mw-add-content-modal-root');
        $this->assertNotFalse($rootStart);
        // The opening tag of .mw-add-content-modal-root ends at the
        // `>` after the x-init attribute. Find that specific `>` by
        // first locating the x-init attribute then the next `>` after
        // its closing `"`.
        $xInitPos = strpos($stripped, 'x-init="', $rootStart);
        $this->assertNotFalse($xInitPos);
        $xInitClose = strpos($stripped, '"', $xInitPos + 8); // +8 = after x-init="
        $this->assertNotFalse($xInitClose);
        $rootTagClose = strpos($stripped, '>', $xInitClose);
        $this->assertNotFalse($rootTagClose);

        // First non-whitespace chars after the opening tag's close.
        $tail = ltrim(substr($stripped, $rootTagClose + 1, 100));
        // Must start with `<` (next element) — anything else is text
        // content that shouldn't be there.
        $this->assertMatchesRegularExpression(
            '/^</',
            $tail,
            sprintf(
                'First non-whitespace after `.mw-add-content-modal-root` opening tag must be `<` (next element). Got: %s — indicates a Stage-5 template-attribute escape leak.',
                var_export(mb_substr($tail, 0, 80), true)
            )
        );
        // Doubly-paranoid: no JS-shape signatures in the first 100 chars.
        $this->assertDoesNotMatchRegularExpression(
            '/\}",|\}\);|\(\)\s*\{|=>\s*[a-z]/',
            $tail,
            'No JS-expression shapes (`}", `, `});`, `() {`, `=> x`) may appear in the body region immediately after modal-root opening.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai790_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-255d24', $this->blade);
        $this->assertStringContainsString('AI-790', $this->blade);
    }
}
