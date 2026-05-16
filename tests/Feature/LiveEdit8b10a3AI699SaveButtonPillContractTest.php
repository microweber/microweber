<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-8b10a3 / AI-699 (Medium) — SAVE button → v2-style
 * solid black pill + unsaved-state accent-ring pulse.
 *
 * Designer dispatch (live-edit-inspiration-from-v2-2026-05-16.md §2
 * P3 + P8, per-ticket email 2026-05-16T13:39): the current green
 * button reads as "neutral status badge"; v2's solid black pill
 * reads as "moment-of-truth action". Two render states:
 *
 *   idle (no unsaved changes, `_dirty === false`):
 *     opacity 0.4; cursor: default; button still clickable (user
 *     can force a save explicitly) but visually muted.
 *
 *   has-changes (unsaved changes, `_dirty === true`):
 *     full saturation; once-per-minute accent-ring pulse (transparent
 *     → 4px var(--ese-accent) → transparent over var(--t-slow), then
 *     stays transparent for the remainder of 60s).
 *
 *   prefers-reduced-motion: reduce:
 *     pulse animation suppressed (SOUL #108 designer motion pattern).
 *
 * The single-source-of-truth requirement ("Remove redundant 'Saved' /
 * 'Unsaved' badges from Layers, ESE header, and any other surface")
 * was a pre-emptive ask — grep across `packages/frontend-assets/`
 * and `src/MicroweberPackages/LiveEdit/` found ZERO redundant
 * Saved/Unsaved badges in current code. Already satisfied; no
 * additional removal needed.
 *
 * Implementation:
 *   1. SaveButton.vue: button :class binds 'mw-save-button' always +
 *      'mw-save-button--idle' when _dirty is false +
 *      'mw-save-button--has-changes' when _dirty is true. The _dirty
 *      flag is the existing single-source-of-truth, already wired
 *      to input / dblclick / drop / Editor 'change' / save()
 *      reset events.
 *   2. aria-pressed binding reflects _dirty so AT users get the
 *      same state signal as sighted users.
 *   3. live-edit-classes.css adds #save-button.mw-save-button rules
 *      for pill shape (--ese-text bg / --ese-surface text /
 *      --radius-pill / --space-sm --space-md padding), idle muting,
 *      has-changes 60s pulse animation, prefers-reduced-motion
 *      guard. Every var() carries a literal fallback for environments
 *      where ESE stylesheet hasn't loaded.
 *
 * Token-scoping note (SOUL #108 spec-doc-nit honoured): the rules
 * use #save-button.mw-save-button (id + class) so they win over the
 * existing #save-button green-fallback rule directly above. Legacy
 * btn / btn-dark classes preserved on the button so external code
 * targeting them still finds them.
 */
class LiveEdit8b10a3AI699SaveButtonPillContractTest extends TestCase
{
    private string $saveButton;
    private string $liveEditClasses;

    protected function setUp(): void
    {
        parent::setUp();
        $this->saveButton = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue'
        ));
        $this->liveEditClasses = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — SaveButton.vue class binding (state-driven)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function button_class_binding_emits_mw_save_button_always(): void
    {
        $this->assertMatchesRegularExpression(
            "/'mw-save-button':\s*true/",
            $this->saveButton,
            "Button :class binding must always emit 'mw-save-button' (the base AI-699 class)."
        );
    }

    #[Test]
    public function button_class_binding_emits_idle_when_not_dirty(): void
    {
        $this->assertMatchesRegularExpression(
            "/'mw-save-button--idle':\s*!_dirty/",
            $this->saveButton,
            "Button :class binding must emit 'mw-save-button--idle' when _dirty is false (no unsaved changes)."
        );
    }

    #[Test]
    public function button_class_binding_emits_has_changes_when_dirty(): void
    {
        $this->assertMatchesRegularExpression(
            "/'mw-save-button--has-changes':\s*_dirty/",
            $this->saveButton,
            "Button :class binding must emit 'mw-save-button--has-changes' when _dirty is true (unsaved changes present)."
        );
    }

    #[Test]
    public function aria_pressed_reflects_dirty_state(): void
    {
        // Accessibility — AT users get the same state signal sighted
        // users get from the visual pulse.
        $this->assertMatchesRegularExpression(
            "/:aria-pressed=\"_dirty\\s*\\?\\s*'true'\\s*:\\s*'false'\"/",
            $this->saveButton,
            ":aria-pressed must reflect the _dirty flag so AT users get the same state signal as sighted users."
        );
    }

    #[Test]
    public function legacy_btn_classes_preserved_for_back_compat(): void
    {
        // Per the live-edit-css-must-be-scoped skill — back-compat
        // hooks on toolbar elements must be preserved. The legacy
        // classes are on the static `class=` attribute (so they
        // always render); the AI-699 mw-save-button variants ride
        // the `:class` binding alongside.
        $this->assertStringContainsString(
            'class="btn btn-dark live-edit-toolbar-buttons"',
            $this->saveButton,
            "Legacy 'btn btn-dark live-edit-toolbar-buttons' classes must remain on the static class= attribute (external code targets them)."
        );
        // The button id #save-button is referenced by SaveButton.vue
        // itself + the existing CSS rule + (per check of save() code)
        // by JS that toggles btn-loading. MUST be preserved.
        $this->assertStringContainsString(
            'id="save-button"',
            $this->saveButton
        );
    }

    #[Test]
    public function dirty_flag_reset_on_save_unchanged(): void
    {
        // Regression guard — the AI-699 binding depends on `_dirty`
        // being the single source of truth. Pin the reset path in
        // save() so future refactors don't accidentally remove it.
        $this->assertStringContainsString(
            'self.$data._dirty = false',
            $this->saveButton,
            "save() success path must continue to reset _dirty = false — AI-699 has-changes pulse depends on it."
        );
        $this->assertStringContainsString(
            'this.$data._dirty = true',
            $this->saveButton,
            "markDirty() must continue to set _dirty = true — AI-699 has-changes pulse depends on it."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — CSS pill shape + idle state
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function pill_rule_uses_ese_text_background_and_radius_pill(): void
    {
        $this->assertMatchesRegularExpression(
            '/#save-button\.mw-save-button\s*\{[^}]*background-color:\s*var\(--ese-text,\s*#111827\)[^}]*color:\s*var\(--ese-surface,\s*#ffffff\)[^}]*border-radius:\s*var\(--radius-pill,\s*999px\)/s',
            $this->liveEditClasses,
            'Pill rule must set background to var(--ese-text), text to var(--ese-surface), and border-radius to var(--radius-pill) — with literal fallbacks for non-ESE-loaded contexts.'
        );
    }

    #[Test]
    public function pill_rule_uses_phi_spacing_padding(): void
    {
        $this->assertMatchesRegularExpression(
            '/#save-button\.mw-save-button\s*\{[^}]*padding:\s*var\(--space-sm,\s*8px\)\s+var\(--space-md,\s*13px\)/s',
            $this->liveEditClasses,
            'Pill padding must use phi-scale tokens var(--space-sm) var(--space-md) with literal fallbacks.'
        );
    }

    #[Test]
    public function idle_state_mutes_opacity_and_default_cursor(): void
    {
        $this->assertMatchesRegularExpression(
            '/#save-button\.mw-save-button\.mw-save-button--idle\s*\{[^}]*opacity:\s*0\.4[^}]*cursor:\s*default/s',
            $this->liveEditClasses,
            'Idle state must apply opacity: 0.4 and cursor: default (button still clickable; just visually muted).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Pulse animation + reduced-motion
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function has_changes_state_runs_60s_pulse_animation(): void
    {
        // Animation cycle is 60s linear infinite — designer spec
        // "pulse … once per minute".
        $this->assertMatchesRegularExpression(
            '/#save-button\.mw-save-button\.mw-save-button--has-changes\s*\{[^}]*opacity:\s*1[^}]*animation:\s*mw-save-button-pulse\s+60s\s+linear\s+infinite/s',
            $this->liveEditClasses,
            'has-changes state must run the mw-save-button-pulse keyframes once per minute (60s linear infinite).'
        );
    }

    #[Test]
    public function pulse_keyframes_use_ese_accent_ring(): void
    {
        // Pulse ring colour comes from var(--ese-accent). The keyframes
        // produce: transparent → accent ring → transparent over the
        // first fraction of the 60s cycle, then stay transparent.
        $this->assertMatchesRegularExpression(
            '/@keyframes\s+mw-save-button-pulse\s*\{[^}]*0%\s*\{[^}]*box-shadow:\s*0\s+0\s+0\s+0\s+transparent[^}]*\}/s',
            $this->liveEditClasses,
            'Pulse keyframes must start at box-shadow: 0 0 0 0 transparent.'
        );
        $this->assertStringContainsString(
            'box-shadow: 0 0 0 4px var(--ese-accent',
            $this->liveEditClasses,
            'Pulse keyframes must hit box-shadow: 0 0 0 4px var(--ese-accent) at the ring-visible point.'
        );
        $this->assertStringContainsString(
            '100% { box-shadow: 0 0 0 0 transparent; }',
            $this->liveEditClasses,
            'Pulse keyframes must return to 0 0 0 0 transparent at 100% (rest of the 60s cycle).'
        );
    }

    #[Test]
    public function prefers_reduced_motion_disables_pulse(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[\s\S]*?#save-button\.mw-save-button\.mw-save-button--has-changes\s*\{[^}]*animation:\s*none/s',
            $this->liveEditClasses,
            'prefers-reduced-motion: reduce must disable the pulse animation per SOUL #108 designer motion pattern.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Token-fallback hygiene + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function every_consumed_token_carries_literal_fallback(): void
    {
        // SOUL #108 spec-doc-nit ask — every var() in the AI-699
        // slice carries a literal fallback. Slice from the AI-699
        // marker to the prefers-reduced-motion block (the slice end).
        $start = strpos($this->liveEditClasses, 'AI-699 — v2-style solid black pill SAVE button');
        $this->assertNotFalse($start, 'AI-699 task marker must be present in live-edit-classes.css.');
        $end = strpos($this->liveEditClasses, '@media (prefers-reduced-motion: reduce)', $start);
        $this->assertNotFalse($end, 'AI-699 slice must contain a prefers-reduced-motion guard.');
        // Extend the slice to include the reduced-motion block so we
        // count its tokens too.
        $end = strpos($this->liveEditClasses, '}', $end);
        $slice = substr($this->liveEditClasses, $start, $end - $start);

        $tokens = [
            '--ese-text'    => '#111827',
            '--ese-surface' => '#ffffff',
            '--radius-pill' => '999px',
            '--space-sm'    => '8px',
            '--space-md'    => '13px',
            '--ese-accent'  => '#0d6efd',
            '--t-slow'      => '320ms',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-699 slice (SOUL #108 token-fallback hygiene)."
            );
        }
    }

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-16-8b10a3',
            $this->saveButton,
            'SaveButton.vue must carry the AI-699 task-id marker (audit-grep).'
        );
        $this->assertStringContainsString(
            'task-2026-05-16-8b10a3',
            $this->liveEditClasses,
            'live-edit-classes.css must carry the AI-699 task-id marker (audit-grep).'
        );
    }
}
