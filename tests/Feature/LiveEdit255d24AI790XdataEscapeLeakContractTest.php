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
 * code BEFORE the "What do you want to add?" search input. Root cause:
 * line-comments inside the inline `x-data="{ ... }"` attribute
 * contained literal `"` characters, terminating the HTML attribute
 * early and dumping the remainder of the JS expression as visible
 * text content.
 *
 * Original AI-790 fix: extract the entire state object to a named
 * Alpine.data() registration. Blade attribute carries only a bare
 * identifier `x-data="addContentModal"` — no quoting issues possible.
 *
 * task-2026-05-18-76a360 PIN-EVOLUTION — the original AI-790 ship
 * placed the registration <script> block AT THE BOTTOM of
 * `add-content-modal.blade.php`. That worked at the source level + at
 * Tier-1/Tier-2 verify but failed at Tier-3 runtime: the modal HTML is
 * Filament/Livewire-morph-inserted when the user clicks +ADD, and
 * embedded <script> tags from morph-inserted HTML DO NOT EXECUTE in
 * the browser. The factory never registered, Alpine could not resolve
 * `x-data="addContentModal"`, and the cards rendered invisible with
 * the entire user-visible defect being "modal empty / cards missing"
 * (designer-attached screenshot in task-2026-05-18-76a360).
 *
 * task-76a360 moves the factory registration to the parent admin-frame
 * layout `live-edit.blade.php` @push('scripts') block, where it rides
 * the initial admin-frame page render BEFORE the modal mounts. This
 * test pin-evolves in place per LESSONS pin-evolution discipline (no
 * parallel test; previous pins updated to target the new home).
 *
 * **Stage-5 lesson family** (designer's words from the dispatch):
 *
 *   Template attribute escape leak — when an Alpine/Vue/Livewire
 *   directive attribute value contains characters that confuse the
 *   templating engine (typically `"` inside an inline JS string or
 *   expression), the attribute terminates early and the remainder
 *   of the directive becomes visible text. Diagnostic: any visible
 *   text matching /x-init|x-data|nextTick|querySelectorAll/.
 *   Guard: extract complex x-data to a named Alpine.data()
 *   registration; keep Blade attributes simple references. Register
 *   the factory at INITIAL PAGE LOAD, not inside lazy-mounted
 *   modal HTML (scripts inside Livewire-morphed HTML do not run).
 *
 * Sibling lineage in the same defect family:
 *   - AI-692 (task-968a71) — slash-star-style block comments with
 *     embedded double-quotes in prose terminated x-data attribute.
 *   - task-bc28fd (AI-691a + AI-697 CHANGE) — same defect class but
 *     for CSS rules: source rule lived in `live-edit-module-settings.blade.php`
 *     which is only loaded on the module-settings sub-form layout,
 *     NOT on `/admin/live-edit` where the Add Content picker actually
 *     opens. task-76a360 follows the same fix-shape (move the
 *     ride-the-initial-page-render artefact to `live-edit-classes.css`
 *     or `live-edit.blade.php` @push('scripts')).
 */
class LiveEdit255d24AI790XdataEscapeLeakContractTest extends TestCase
{
    private string $modal;
    private string $liveEditLayout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->modal = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        ));
        $this->liveEditLayout = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php'
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
            $this->modal,
            'Modal root must declare `x-data="addContentModal"` — bare named-reference, no inline JS.'
        );
    }

    #[Test]
    public function modal_root_xdata_does_not_carry_inline_object(): void
    {
        // Strip Blade `{{-- ... --}}` comments first (selector-self-match
        // guard — the docblock + comment blocks mention the BEFORE state).
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->modal);
        $this->assertDoesNotMatchRegularExpression(
            '/mw-add-content-modal-root[\s\S]{0,200}x-data="\{/',
            $stripped,
            'Modal root must NOT carry inline `x-data="{ ... }"` JS — extract to Alpine.data() registration.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Alpine.data('addContentModal', ...) registration shipped
    //           IN THE PARENT ADMIN-FRAME LAYOUT (task-76a360 pin-evolved)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function alpine_data_registration_lives_in_live_edit_admin_frame_layout(): void
    {
        // task-76a360 pin-evolution: the registration was MOVED from
        // add-content-modal.blade.php (Filament-morph-inserted, scripts
        // do NOT execute) to live-edit.blade.php @push('scripts') (rides
        // initial admin-frame page render, scripts execute).
        $this->assertStringContainsString(
            "window.Alpine.data('addContentModal', factory)",
            $this->liveEditLayout,
            "live-edit.blade.php must register `window.Alpine.data('addContentModal', factory)` so the factory is available before Filament mounts the modal."
        );
    }

    #[Test]
    public function registration_supports_both_eager_and_alpine_init_paths(): void
    {
        // Dual-path init: works whether the script runs BEFORE or AFTER
        // Alpine boots. (a) Eager path: if Alpine is already running,
        // register immediately. (b) Deferred path: otherwise wait for
        // alpine:init. Both paths required because @push('scripts') may
        // land in head OR body depending on Filament panel config.
        $this->assertStringContainsString(
            "document.addEventListener('alpine:init',",
            $this->liveEditLayout,
            'Registration must listen for alpine:init as the deferred-path fallback.'
        );
        $this->assertStringContainsString(
            "if (typeof window.Alpine !== 'undefined' && typeof window.Alpine.data === 'function')",
            $this->liveEditLayout,
            'Registration must check eager-path readiness (Alpine already running) before falling back to alpine:init.'
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
            $this->liveEditLayout,
            'Registration must check a window-scoped sentinel flag to be idempotent across multiple renders.'
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
            $this->liveEditLayout,
            'Registration must guard against missing window.Alpine before calling .data().'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — every state method from the OLD inline x-data preserved
    //           in the NEW home (live-edit.blade.php pin-evolved)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function all_state_methods_present_in_registration(): void
    {
        // Behaviour parity: every method from the OLD x-data inline
        // object must still exist in the Alpine.data() body — now in
        // live-edit.blade.php per task-76a360.
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
                $this->liveEditLayout,
                "Alpine.data() body in live-edit.blade.php must preserve state method `{$method}` from the pre-AI-790 x-data object."
            );
        }
        // The `q: ''` initial state field must also remain.
        $this->assertMatchesRegularExpression(
            "/q:\\s*''/",
            $this->liveEditLayout,
            "Alpine.data() body must initialise `q: ''` (search query state)."
        );
    }

    #[Test]
    public function state_method_bodies_use_this_refs_and_this_root(): void
    {
        // Function-form factory: $root / $refs magics accessed via
        // `this.$root` / `this.$refs`. Pin the new shape so refactors
        // can't drop the `this.` prefix.
        $this->assertStringContainsString(
            'this.$root.querySelectorAll',
            $this->liveEditLayout,
            'Alpine.data() body must reference `this.$root.querySelectorAll(...)` (not bare `$root`).'
        );
        $this->assertStringContainsString(
            'this.$refs.search.focus()',
            $this->liveEditLayout,
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
        // Structural check on the modal blade (where the x-data lives).
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->modal);

        $rootStart = strpos($stripped, '<div class="mw-add-content-modal-root');
        $this->assertNotFalse($rootStart);
        $xInitPos = strpos($stripped, 'x-init="', $rootStart);
        $this->assertNotFalse($xInitPos);
        $xInitClose = strpos($stripped, '"', $xInitPos + 8);
        $this->assertNotFalse($xInitClose);
        $rootTagClose = strpos($stripped, '>', $xInitClose);
        $this->assertNotFalse($rootTagClose);

        $tail = ltrim(substr($stripped, $rootTagClose + 1, 100));
        $this->assertMatchesRegularExpression(
            '/^</',
            $tail,
            sprintf(
                'First non-whitespace after `.mw-add-content-modal-root` opening tag must be `<` (next element). Got: %s — indicates a Stage-5 template-attribute escape leak.',
                var_export(mb_substr($tail, 0, 80), true)
            )
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\}",|\}\);|\(\)\s*\{|=>\s*[a-z]/',
            $tail,
            'No JS-expression shapes (`}", `, `});`, `() {`, `=> x`) may appear in the body region immediately after modal-root opening.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — task-76a360 pin-evolution negative regression guards
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function alpine_data_factory_body_no_longer_in_modal_blade(): void
    {
        // task-76a360 negative regression guard: the factory registration
        // moved OUT of add-content-modal.blade.php. If a future agent
        // tries to put the registration BACK inside the modal blade,
        // this guard fails — Livewire-morphed scripts do not execute.
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->modal);
        $this->assertStringNotContainsString(
            "window.Alpine.data('addContentModal'",
            $stripped,
            'Factory registration must NOT live in add-content-modal.blade.php — scripts inside Filament/Livewire-morph-inserted modal HTML do NOT execute. Register in live-edit.blade.php @push("scripts") instead.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai790_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-255d24', $this->modal);
        $this->assertStringContainsString('AI-790', $this->modal);
    }

    #[Test]
    public function task_76a360_markers_present_in_both_files(): void
    {
        // Pin-evolution markers — both files carry the task-76a360 token
        // so future audits can grep the relocation across surfaces.
        $this->assertStringContainsString(
            'task-2026-05-18-76a360',
            $this->modal,
            'add-content-modal.blade.php docblock must reference task-76a360 (explains where the factory body moved).'
        );
        $this->assertStringContainsString(
            'task-2026-05-18-76a360',
            $this->liveEditLayout,
            'live-edit.blade.php @push("scripts") docblock must reference task-76a360 (explains why this is the canonical home for the factory).'
        );
    }
}
