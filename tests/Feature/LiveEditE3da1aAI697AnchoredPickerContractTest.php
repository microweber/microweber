<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-e3da1a / AI-697 (Medium) — Anchor the Add-Content
 * picker modal to the +ADD button on desktop, replacing the centered-
 * modal+backdrop-dim default that breaks canvas flow for what should
 * be a quick add.
 *
 * Designer dispatch (live-edit-inspiration-from-v2-2026-05-16.md §2
 * P1, per-ticket email 2026-05-16T13:38) cites v2's anchored-dropdown
 * pattern as the model. Per-spec requirements applied here:
 *
 *   Desktop (>= 769px):
 *     - position: fixed
 *     - top: calc(var(--toolbar-height) + var(--space-xs))
 *     - inset-inline-start: 64px   (logical-property "left: 64px")
 *     - margin: 0                  (cancels Filament's body-margin
 *                                   centering on .fi-modal-window)
 *     - backdrop dim removed (transparent .fi-modal-close-overlay)
 *     - open animation: scale(0.95) translateY(-4px) -> scale(1)
 *       translateY(0), transform-origin: top left, over --t-fast.
 *
 *   Mobile (<= 768px):
 *     - No AI-697 rule applies; centered modal stays until AI-695
 *       mobile bottom-sheet ships (which adds backdrop dim per ESE
 *       spec §3.2).
 *
 * Scoping: every rule uses
 *
 *     .fi-modal:has(> .fi-modal-window-ctn .mw-content-picker-modal)
 *
 * so the override only applies to the Add-Content picker modal. Other
 * Filament modals on the same panel keep their default centered +
 * dimmed presentation.
 *
 * Token sources (all :root-scoped, resolve outside .mw-live-edit-page
 * since the modal is Filament-portaled to body):
 *   --toolbar-height (60px, frontend-assets/ui/css/index.css)
 *   --space-xs       (phi scale, ESE slice 1.1)
 *   --t-fast         (120ms, ESE slice 1.1)
 *   --ease           (motion easing, ESE slice 1.1)
 *
 * Each var() carries a literal fallback for environments where those
 * stylesheets haven't loaded yet. prefers-reduced-motion disables the
 * scale/translate animation per the SOUL #108 designer note.
 *
 * Per-component handling note: AI-697 is CSS-only. No PHP / Vue /
 * Blade DOM changes — the picker modal stays a Filament Action
 * mounted via `extraModalWindowAttributes(['class' => 'mw-content-
 * form-modal mw-content-picker-modal'])`; the anchor effect is
 * produced entirely by overriding the modal-window positioning
 * inside the existing `.fi-modal` overlay.
 */
class LiveEditE3da1aAI697AnchoredPickerContractTest extends TestCase
{
    private string $blade;
    private string $page;

    protected function setUp(): void
    {
        parent::setUp();
        // task-bc28fd CHANGE (designer per SOUL #108 verify-before-
        // accept): the AI-697 anchored-picker rule moved OUT of the
        // live-edit-module-settings.blade.php `<style>` block INTO
        // live-edit-classes.css (Webpack-bundled, loaded on
        // /admin/live-edit). The Blade-side rule never fired on the
        // live build because that Blade is the LiveEditModuleSettings
        // sub-form layout, not the live-edit canvas.
        $this->blade = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        ));
        $this->page = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Anchor positioning + scoping
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function rule_is_inside_min_width_769px_media_block(): void
    {
        // The anchor override must NOT fire on mobile (≤ 768px) so the
        // centered modal stays until AI-695 ships.
        $this->assertMatchesRegularExpression(
            '/AI-697 \(task-2026-05-16-e3da1a\)[\s\S]*?@media\s*\(\s*min-width:\s*769px\s*\)[\s\S]*?\.fi-modal:has\([^)]+\.mw-content-picker-modal/s',
            $this->blade,
            'AI-697 anchor rule must live inside @media (min-width: 769px) — mobile keeps the centered modal.'
        );
    }

    #[Test]
    public function modal_window_positioned_fixed_with_token_offsets(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal:has\(>\s*\.fi-modal-window-ctn\s+\.mw-content-picker-modal\)\s+\.fi-modal-window\.mw-content-picker-modal\s*\{[^}]*position:\s*fixed[^}]*top:\s*calc\(var\(--toolbar-height,\s*60px\)\s*\+\s*var\(--space-xs,\s*6px\)\)[^}]*inset-inline-start:\s*64px/s',
            $this->blade,
            'Modal window must be position: fixed at top = calc(--toolbar-height + --space-xs), inset-inline-start: 64px (with literal fallbacks 60px + 6px).'
        );
    }

    #[Test]
    public function modal_window_margin_zeroed(): void
    {
        // Filament's `.fi-modal-window` carries margin via body-centering;
        // we cancel it so the fixed top/left coords are not nudged.
        $this->assertMatchesRegularExpression(
            '/\.fi-modal-window\.mw-content-picker-modal\s*\{[^}]*margin:\s*0/s',
            $this->blade,
            'Modal window must zero its margin so Filament centering does not interfere with the fixed coords.'
        );
    }

    #[Test]
    public function transform_origin_top_left_for_anchored_open(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal-window\.mw-content-picker-modal\s*\{[^}]*transform-origin:\s*top\s+left/s',
            $this->blade,
            'transform-origin: top left so the scale grows from the anchor corner, not the centre.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Backdrop dim removal (canvas stays alive)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function backdrop_overlay_transparent_for_picker_only(): void
    {
        // .fi-modal-close-overlay is Filament's backdrop dim. We make
        // it transparent ONLY when the overlay contains the picker
        // modal — every other Filament modal keeps its default dim.
        // Supersession (task-2026-05-17-d994e7 / AI-697 v3): the rule now
        // comma-joins a `>`-child selector AND a descendant-variant selector
        // (for builds where Filament nests the overlay deeper) before the `{`,
        // and uses `transparent !important` to win the cascade. The scoped
        // transparent-backdrop contract is unchanged; allow the comma-joined
        // selector list between the `>` child selector and the `{`.
        $this->assertMatchesRegularExpression(
            '/\.fi-modal:has\(>\s*\.fi-modal-window-ctn\s+\.mw-content-picker-modal\)\s*>\s*\.fi-modal-close-overlay[\s\S]*?\{[^}]*background-color:\s*transparent/s',
            $this->blade,
            'Picker modal must NOT dim the canvas; backdrop is transparent. Scoped via :has() so other modals keep their dim.'
        );
    }

    #[Test]
    public function backdrop_dim_not_globally_disabled(): void
    {
        // Regression guard — the AI-697 slice must not declare a bare
        // `.fi-modal-close-overlay { background: transparent }` rule.
        // Slice from AI-697 marker to the AI-697 keyframes (or </style>)
        // and confirm every backdrop-overlay rule carries the :has()
        // scope.
        $start = strpos($this->blade, 'AI-697 (task-2026-05-16-e3da1a)');
        $this->assertNotFalse($start, 'AI-697 task-id marker must be present in the live-edit-classes.css style block (relocated per task-bc28fd CHANGE).');
        $end = strpos($this->blade, '@keyframes mw-add-content-picker-open', $start);
        $this->assertNotFalse($end, 'AI-697 keyframes block must follow the rule block.');
        $slice = substr($this->blade, $start, $end - $start);
        // Only RULE LINES (containing `{`) are checked — skip prose
        // (LESSONS selector-self-match guard from AI-691a / AI-708).
        $lines = preg_split('/\r?\n/', $slice);
        foreach ($lines as $line) {
            if (!str_contains($line, '{')) continue;
            if (str_contains($line, '.fi-modal-close-overlay')) {
                $this->assertStringContainsString(
                    '.mw-content-picker-modal',
                    $line,
                    "AI-697 CSS rule must scope .fi-modal-close-overlay with the .mw-content-picker-modal :has() guard — found unscoped selector line: {$line}"
                );
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Open animation + reduced-motion guard
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function animation_uses_t_fast_token_and_named_keyframes(): void
    {
        $this->assertMatchesRegularExpression(
            '/animation:\s*mw-add-content-picker-open\s+var\(--t-fast,\s*120ms\)\s+var\(--ease/',
            $this->blade,
            'Modal animation must reference the named keyframes + --t-fast token (with 120ms literal fallback).'
        );
    }

    #[Test]
    public function keyframes_scale_from_95_with_translate_y_negative_4(): void
    {
        // Designer spec: scale(0.95) translateY(-4px) → scale(1) translateY(0).
        $this->assertMatchesRegularExpression(
            '/@keyframes\s+mw-add-content-picker-open\s*\{[^}]*from\s*\{[^}]*transform:\s*scale\(0\.95\)\s+translateY\(-4px\)[^}]*\}\s*to\s*\{[^}]*transform:\s*scale\(1\)\s+translateY\(0\)/s',
            $this->blade,
            'Keyframes must animate scale(0.95) translateY(-4px) → scale(1) translateY(0) (designer spec).'
        );
    }

    #[Test]
    public function prefers_reduced_motion_disables_animation(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[\s\S]*?\.fi-modal-window\.mw-content-picker-modal\s*\{[^}]*animation:\s*none/s',
            $this->blade,
            'prefers-reduced-motion must disable the AI-697 open animation (SOUL #108 designer-recommended pattern).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Scoping baseline: picker modal class still emitted in PHP
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function picker_modal_scope_class_still_emitted_on_addcontentaction(): void
    {
        // AI-697's :has() scope depends on .fi-modal-window-ctn
        // containing a .mw-content-picker-modal element. That class
        // comes from extraModalWindowAttributes on addContentAction.
        // Pin the contract.
        $this->assertMatchesRegularExpression(
            "/->extraModalWindowAttributes\\(\\[\\s*'class'\\s*=>\\s*'[^']*mw-content-picker-modal/",
            $this->page,
            'addContentAction must keep mw-content-picker-modal in extraModalWindowAttributes — AI-697 :has() scope hinges on it.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_admin_style_block(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-16-e3da1a',
            $this->blade
        );
    }

    #[Test]
    public function every_token_var_has_literal_fallback(): void
    {
        // Token-scoping hygiene per SOUL #108 spec-doc-nit: tokens
        // consumed in CSS that may resolve OUTSIDE .mw-live-edit-page
        // must carry a literal fallback for environments where the
        // ESE / index.css stylesheets haven't loaded.
        // task-bc28fd CHANGE: relocation to live-edit-classes.css.
        // The first `AI-697 (task-...)` occurrence is in the
        // top-of-block docblock; sub-occurrences live above each
        // actual CSS rule. Walk past the docblock close `*/` to
        // start the slice at the CSS rules, then slice to EOF
        // (the AI-691a+697 block is the last thing in the file).
        // LESSONS selector-self-match guard reapplied (5th
        // session-occurrence) — don't inspect docblock prose.
        $markerStart = strpos($this->blade, 'AI-697 (task-2026-05-16-e3da1a)');
        $this->assertNotFalse($markerStart, 'AI-697 task-id marker must be present.');
        $docblockEnd = strpos($this->blade, '*/', $markerStart);
        $this->assertNotFalse($docblockEnd, 'AI-697 marker must be followed by a docblock close `*/`.');
        $slice = substr($this->blade, $docblockEnd + 2);

        // Each consumed token must use var(--name, fallback) form
        // somewhere in the slice.
        $tokens = [
            '--toolbar-height' => '60px',
            '--space-xs'       => '6px',
            '--t-fast'         => '120ms',
            '--ease'           => 'cubic-bezier',
        ];
        foreach ($tokens as $token => $fallbackSnippet) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallbackSnippet, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallbackSnippet}>) — protects environments where the defining stylesheet hasn't loaded."
            );
        }
    }
}
