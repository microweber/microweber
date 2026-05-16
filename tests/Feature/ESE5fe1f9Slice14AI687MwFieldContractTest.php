<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-5fe1f9 / AI-687 — ESE 1.4 MwField primitive.
 * Slice 6/7 of ESE Phase 1. Spec:
 *   designer-agent/output/ese-design-spec-2026-05-16.md §4.1
 *   "MwField — the universal field-row".
 *
 * Replaces the four ad-hoc field-row patterns currently in
 * Spacing / Grid / RoundedCorners / UlOl with a single primitive:
 *
 *   ┌────────────────────────────── 100%──────────────────────┐
 *   │ LABEL  ……………………………… [ CONTROL ] [ ↺ ]            │
 *   └─────────────────────────────────────────────────────────┘
 *     gap: var(--space-sm); height: 32px (44px touch via padding);
 *     margin-block: var(--space-sm) var(--space-md);
 *
 * Class names use `.mw-tool-field` (NOT `.mw-field`) — `.mw-field`
 * already owns a long-standing rule cluster in this same CSS
 * file (line ~239: .mw-multiple-fields, .rouded-corners .mw-field,
 * .mw-field.unit input + input, etc.) that styles the current
 * Spacing/Grid/Border dropdown+input markup. Renaming would
 * cascade into every consumer; the new primitive uses a distinct
 * class so consumers migrate incrementally. Same coexistence
 * pattern as MwToolButton's `.mw-tool-btn` (1.3a / AI-684).
 *
 * Proof-of-pattern consumer migrated in this slice:
 *   ElementStyleEditorRoundedCorners.vue — predefined border
 *   radius select. Legacy classes kept alongside .mw-tool-field
 *   for back-compat. AI-687a will migrate Spacing/Grid/UlOl.
 *
 * Slice scoping under `.mw-live-edit-page` per the live-edit-css-
 * must-be-scoped skill. Every var(--…) carries a literal fallback
 * per the SOUL #108 token-scoping spec-doc-nit ask.
 */
class ESE5fe1f9Slice14AI687MwFieldContractTest extends TestCase
{
    private string $css;
    private string $slice;
    private string $roundedCorners;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css'
        ));

        // Bound the MwField slice from the AI-687 marker docblock
        // open to the next `/* ===` block opener — keeps the
        // selector/rule scan inside slice 1.4 only and ignores
        // the slice 1.1 :root tokens / slice 1.2 MwSlider that
        // follow. (LESSONS slice-bounding pattern reapplied.)
        $start = strpos($this->css, 'MwField — slice 1.4 (AI-687');
        $this->assertNotFalse($start, 'AI-687 docblock opener must be present.');
        // Walk forward to closing */ of docblock; then to the
        // next /* ==== block opener after the rule cluster.
        $docEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($docEnd, 'AI-687 docblock must close with */.');
        $rulesStart = $docEnd + 2;
        $nextBlock = strpos($this->css, '/* ===', $rulesStart);
        $this->assertNotFalse($nextBlock, 'AI-687 slice must be followed by another /* === block.');
        $this->slice = substr($this->css, $rulesStart, $nextBlock - $rulesStart);

        $this->roundedCorners = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorRoundedCorners.vue'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — primitive presence + scoping
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function mw_tool_field_primitive_is_defined_and_scoped(): void
    {
        // Wrapper rule
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-tool-field\s*\{/',
            $this->slice,
            'Slice 1.4 must declare `.mw-live-edit-page .mw-tool-field { … }` (scoped wrapper rule).'
        );
        // Modifier elements
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-tool-field__label\s*\{/',
            $this->slice,
            'Slice 1.4 must declare `.mw-tool-field__label` for the label slot.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-tool-field__control\s*\{/',
            $this->slice,
            'Slice 1.4 must declare `.mw-tool-field__control` for the control slot.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-tool-field__reset\s*\{/',
            $this->slice,
            'Slice 1.4 must declare `.mw-tool-field__reset` for the reset-icon slot.'
        );
    }

    #[Test]
    public function mw_tool_field_does_not_use_legacy_mw_field_class(): void
    {
        // Defence-in-depth: the new primitive must not declare
        // `.mw-field` (no `__` suffix) selectors — that class is
        // owned by the legacy rule cluster further down the file.
        // Strip the docblock first so prose-mentions don't false-fail.
        $rulesOnly = $this->slice;
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-field\s*\{/',
            $rulesOnly,
            'Slice 1.4 must NOT declare `.mw-field` selectors — that class is owned by the legacy rule cluster.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — geometry per spec §4.1
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function wrapper_has_row_layout_44px_touch_floor_and_spec_gaps(): void
    {
        // display:flex + align-items:center for label-control row
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field\s*\{[^}]*display:\s*flex/s',
            $this->slice,
            'MwField wrapper must use `display: flex` for the row layout.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field\s*\{[^}]*align-items:\s*center/s',
            $this->slice,
            'MwField wrapper must align-items: center (baseline alignment of label and control).'
        );
        // 44px touch row total per WCAG 2.5.5
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field\s*\{[^}]*min-height:\s*44px/s',
            $this->slice,
            'MwField wrapper must guarantee a 44px touch row total per WCAG 2.5.5.'
        );
        // gap: --space-sm (per spec §4.1 "gap: var(--space-sm)")
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field\s*\{[^}]*gap:\s*var\(--space-sm/s',
            $this->slice,
            'MwField wrapper must use `gap: var(--space-sm)` per spec §4.1.'
        );
        // margin-block: --space-sm --space-md (per spec §4.1)
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field\s*\{[^}]*margin-block:\s*var\(--space-sm[^)]*\)\s+var\(--space-md/s',
            $this->slice,
            'MwField wrapper margin-block must be `var(--space-sm) var(--space-md)` per spec §4.1.'
        );
    }

    #[Test]
    public function label_consumes_label_tokens(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__label\s*\{[^}]*font-size:\s*var\(--font-label/s',
            $this->slice
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__label\s*\{[^}]*font-weight:\s*var\(--weight-label/s',
            $this->slice
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__label\s*\{[^}]*color:\s*var\(--ese-label/s',
            $this->slice,
            'Label colour must consume --ese-label (per spec §4.1 — the label token).'
        );
    }

    #[Test]
    public function select_control_uses_spec_geometry_120px_min_60pc_max(): void
    {
        // The control wrapper is capped at 60% max-width per spec §4.1.
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__control\s*\{[^}]*max-width:\s*60%/s',
            $this->slice,
            'MwField control wrapper must cap at 60% max-width per spec §4.1.'
        );
        // The inner <select> has 120px min-width.
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__control\s*>\s*select[^{]*\{[^}]*min-width:\s*120px/s',
            $this->slice,
            'MwField <select> must have min-width: 120px per spec §4.1 dropdown row.'
        );
        // Border radius via --radius-sm token (with literal fallback).
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__control\s*>\s*select[^{]*\{[^}]*border-radius:\s*var\(--radius-sm/s',
            $this->slice,
            'MwField <select> border-radius must consume --radius-sm.'
        );
    }

    #[Test]
    public function number_input_uses_52px_width_per_spec(): void
    {
        $this->assertMatchesRegularExpression(
            '/input\[type="number"\][^{]*\{[^}]*width:\s*52px/s',
            $this->slice,
            'MwField number-input width must be 52px per spec §4.1 number-input row.'
        );
        $this->assertMatchesRegularExpression(
            '/input\[type="number"\][^{]*\{[^}]*text-align:\s*right/s',
            $this->slice,
            'MwField number-input text-align must be right per spec §4.1.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — reset-icon behaviour
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function reset_is_ghost_24px_and_revealed_on_hover(): void
    {
        // 24px hit, transparent default, opacity:0 idle
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__reset\s*\{[^}]*width:\s*24px/s',
            $this->slice
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__reset\s*\{[^}]*height:\s*24px/s',
            $this->slice
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__reset\s*\{[^}]*background:\s*transparent/s',
            $this->slice
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__reset\s*\{[^}]*opacity:\s*0/s',
            $this->slice,
            'Reset icon must be opacity:0 in idle state per spec §4.1 "ghost, hover only".'
        );

        // Revealed on parent hover OR when .is-dirty is set
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field:hover\s+\.mw-tool-field__reset[^{]*\{[^}]*opacity:\s*1/s',
            $this->slice,
            'Reset icon must reveal on row hover per spec §4.1.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field\.is-dirty\s+\.mw-tool-field__reset[^{]*\{[^}]*opacity:\s*1/s',
            $this->slice,
            'Reset icon must reveal when row is .is-dirty (programmatic state).'
        );

        // Hover/focus turns the icon accent
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-field__reset:hover\s*\{[^}]*color:\s*var\(--ese-accent/s',
            $this->slice
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — proof-of-pattern consumer (RoundedCorners)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function rounded_corners_predefined_select_uses_mw_tool_field(): void
    {
        $this->assertStringContainsString(
            'class="form-control-live-edit-label-wrapper my-4 mw-tool-field"',
            $this->roundedCorners,
            'RoundedCorners predefined-radius wrapper must add .mw-tool-field alongside the legacy class.'
        );
        $this->assertStringContainsString(
            'class="live-edit-label mw-tool-field__label"',
            $this->roundedCorners,
            'RoundedCorners predefined-radius label must add .mw-tool-field__label alongside legacy class.'
        );
        $this->assertStringContainsString(
            '<span class="mw-tool-field__control">',
            $this->roundedCorners,
            'RoundedCorners predefined-radius select must be wrapped in .mw-tool-field__control.'
        );
    }

    #[Test]
    public function rounded_corners_legacy_hooks_preserved_for_back_compat(): void
    {
        // The migration MUST keep the existing class hooks alongside
        // the new ones so external scripts and shared CSS rules still
        // match. Same back-compat pattern as the 1.3a Typography
        // italic-toggle migration.
        $this->assertStringContainsString(
            'class="form-control-live-edit-input form-select"',
            $this->roundedCorners,
            'Legacy .form-control-live-edit-input .form-select classes must remain on the <select>.'
        );
        $this->assertStringContainsString(
            'id="borderRadiusSelect"',
            $this->roundedCorners,
            'Legacy id="borderRadiusSelect" must remain (form-control binding + DOM hook).'
        );
        $this->assertStringContainsString(
            'v-model="selectedBorderRadius"',
            $this->roundedCorners,
            'v-model binding must remain unchanged — primitive migration is markup-only.'
        );
        $this->assertStringContainsString(
            '@change="applyPredefinedRadius"',
            $this->roundedCorners,
            '@change handler must remain unchanged — primitive migration is markup-only.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — token-fallback hygiene + markers (SOUL #108 contract)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function every_var_in_slice_carries_a_literal_fallback(): void
    {
        // SOUL #108 spec-doc-nit ask: every consumed token must
        // carry a literal fallback so the rule degrades gracefully
        // when the :root token block hasn't loaded (e.g. inside a
        // Filament-portaled modal outside the .mw-live-edit-page
        // scope). Walk every var(--…) occurrence in declaration
        // lines and assert each one has a fallback.
        //
        // Skip comment lines (slice docblock + Vue/PHP inline
        // comments) — those are prose-mentions, not declarations.
        // Skip lines whose `var(` sits INSIDE a fallback of an
        // outer var() (rare nested case).
        $lines = preg_split('/\R/', $this->slice);
        $offenders = [];
        foreach ($lines as $i => $line) {
            $trim = trim($line);
            // Skip pure comment lines + blank lines + selector lines
            // (selectors have `{` and we only care about declarations).
            if ($trim === '' || str_starts_with($trim, '*') || str_starts_with($trim, '/*') || str_starts_with($trim, '//')) {
                continue;
            }
            // Find all var(--token) occurrences on the line.
            if (preg_match_all('/var\(\s*(--[a-z0-9-]+)(\s*,[^)]*)?\)/i', $line, $m, PREG_SET_ORDER)) {
                foreach ($m as $hit) {
                    if (!isset($hit[2]) || trim($hit[2]) === '') {
                        $offenders[] = sprintf('Line %d (token %s): %s', $i + 1, $hit[1], $trim);
                    }
                }
            }
        }
        $this->assertEmpty(
            $offenders,
            'Every var(--…) in slice 1.4 must carry a literal fallback per SOUL #108 token-scoping contract. Offenders: '
            . PHP_EOL . implode(PHP_EOL, $offenders)
        );
    }

    #[Test]
    public function slice_carries_required_markers(): void
    {
        // Markers live in the docblock just BEFORE the rule slice
        // (the slice scan deliberately starts after the docblock
        // close `*/` so prose mentions don't false-match against
        // rule grep). Assert against the full CSS file body.
        $this->assertStringContainsString('AI-687', $this->css);
        $this->assertStringContainsString('task-2026-05-16-5fe1f9', $this->css);
        $this->assertStringContainsString('§4.1', $this->css);
        // Consumer file also carries the task marker so an audit
        // grep for task-5fe1f9 lands in BOTH the CSS slice and
        // the Vue consumer migration.
        $this->assertStringContainsString('task-2026-05-16-5fe1f9', $this->roundedCorners);
        $this->assertStringContainsString('AI-687', $this->roundedCorners);
    }
}
