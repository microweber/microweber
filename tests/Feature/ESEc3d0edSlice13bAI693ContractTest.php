<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-c3d0ed — ESE Slice 1.3b (AI-684 cont.) + AI-693
 * Add-Content modal icon palette, bundled per designer dispatch
 * 2026-05-16T12:59 (Path A: 1.3b standalone, AI-693 bundled because
 * the accent contract is identical and verifying both surfaces in
 * one ship is cheaper than two separate ones).
 *
 * Spec refs:
 *   - ESE design spec §4.5 (MwToolButton preset variant)
 *   - ESE design spec §4.3 (MwSegmented)
 *   - Add-Content spec §2 colour palette + AI-693 description
 *     ("Six icons → monochrome line-set + accent on hover/focus")
 *
 * Migrations in this slice:
 *
 *   ESE 1.3b:
 *   1. ElementStyleEditorSpacing.vue padding+margin preset rows —
 *      S/M/L/XL/trash buttons gain .mw-tool-btn.mw-tool-btn--preset;
 *      the icon-only "Fine-tune each side" cog gains default
 *      .mw-tool-btn (24x24 ghost, no preset variant). Both rows.
 *      Each button's :class binding adds .is-active alongside
 *      legacy .active.
 *   2. components/Align.vue text-align strip — outer .text-align div
 *      gains .mw-segmented; each .ta-left/.ta-center/.ta-right/
 *      .ta-justify cell gains .mw-segmented__cell; each cell's
 *      :class adds .is-active alongside .active.
 *
 *   AI-693 (bundled):
 *   3. add-content-modal.blade.php — drop per-type `match` map
 *      tinting (indigo/emerald/violet/rose/amber); replace with a
 *      single monochrome icon-set (`bg-gray-500/10` + `text-gray-
 *      600`/`text-gray-400`). New `.mw-add-content-icon` class on
 *      each icon wrapper so the admin-side CSS can apply the accent
 *      contract on hover/focus.
 *   4. live-edit-module-settings.blade.php — new CSS rules applying
 *      `--ese-accent-soft` bg + `--ese-accent` foreground to the
 *      `.mw-add-content-icon` (+ inner SVG) on
 *      :hover / :focus-visible. ESE tokens resolve from :root
 *      so the modal (Filament-portaled outside .mw-live-edit-page)
 *      still picks them up.
 *
 * Back-compat: legacy classes (.btn .btn-icon / .text-align /
 * .ta-* / .active / per-card .group / hover utilities) preserved
 * alongside new primitives per the migration plan.
 */
class ESEc3d0edSlice13bAI693ContractTest extends TestCase
{
    private string $spacingVue;
    private string $alignVue;
    private string $modalBlade;
    private string $adminStyleBlade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->spacingVue = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorSpacing.vue'
        ));
        $this->alignVue = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/components/Align.vue'
        ));
        $this->modalBlade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        ));
        // task-2026-05-16-651cc2 CHANGE (designer per SOUL #108
        // verify-before-accept): AI-693 hover-accent rule was NOT
        // firing on the live build — same defect class as AI-691a /
        // AI-697. live-edit-module-settings.blade.php is the
        // LiveEditModuleSettings sub-form layout, never renders on
        // the live-edit canvas. Rule relocated to live-edit-
        // classes.css (Webpack-bundled, loaded on /admin/live-edit).
        // $adminStyleBlade now reads the new host file so existing
        // selector + token assertions continue to verify presence
        // at the correct location.
        $this->adminStyleBlade = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — ESE 1.3b Spacing preset rows
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function spacing_preset_buttons_carry_mw_tool_btn_preset(): void
    {
        // 10 preset buttons (5 inner + 5 outer) — S/M/L/XL/trash per row.
        $occurrences = substr_count(
            $this->spacingVue,
            'class="btn btn-icon mw-tool-btn mw-tool-btn--preset"'
        );
        $this->assertGreaterThanOrEqual(
            10,
            $occurrences,
            'At least 10 preset buttons (5 inner + 5 outer = S/M/L/XL/trash) must carry the .mw-tool-btn.mw-tool-btn--preset class set alongside legacy .btn .btn-icon.'
        );
    }

    #[Test]
    public function spacing_settings_cog_uses_default_mw_tool_btn(): void
    {
        // The "Fine-tune each side" icon-only cog is the default variant
        // (24x24 ghost) — NOT preset. Two occurrences (inner + outer rows).
        $this->assertMatchesRegularExpression(
            '/class="btn btn-icon mw-tool-btn"\s+title="Fine-tune each side"/',
            $this->spacingVue,
            'Settings cog must carry default .mw-tool-btn (no preset modifier) on both inner+outer rows.'
        );
    }

    #[Test]
    public function spacing_preset_buttons_add_is_active_alongside_active(): void
    {
        // The :class binding must carry both 'active' (back-compat) and
        // 'is-active' (new primitive selector) for every preset button.
        $this->assertMatchesRegularExpression(
            "/:class=\"\\{ 'active': activePadding === 30, 'is-active': activePadding === 30 \\}\"/",
            $this->spacingVue,
            'Spacing preset buttons must add .is-active alongside .active for activePadding === 30 (and the equivalent for other values).'
        );
        $this->assertMatchesRegularExpression(
            "/:class=\"\\{ 'active': activeMargin === 100, 'is-active': activeMargin === 100 \\}\"/",
            $this->spacingVue,
            'Spacing preset buttons must add .is-active alongside .active for activeMargin === 100 (and the equivalent for other values).'
        );
    }

    #[Test]
    public function spacing_carries_task_id_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-16-c3d0ed', $this->spacingVue);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — ESE 1.3b Align segmented strip
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function align_strip_outer_div_gains_mw_segmented(): void
    {
        $this->assertStringContainsString(
            'class="text-align mw-segmented"',
            $this->alignVue,
            'Align outer .text-align div must gain .mw-segmented (full-width strip per spec §4.3).'
        );
    }

    #[Test]
    public function align_cells_carry_mw_segmented_cell(): void
    {
        foreach (['ta-left', 'ta-center', 'ta-right', 'ta-justify'] as $cell) {
            $this->assertStringContainsString(
                "class=\"{$cell} mw-segmented__cell\"",
                $this->alignVue,
                "Align cell .{$cell} must gain .mw-segmented__cell alongside the legacy class."
            );
        }
    }

    #[Test]
    public function align_cells_add_is_active_alongside_active(): void
    {
        foreach (['left', 'center', 'right', 'justify'] as $value) {
            $this->assertMatchesRegularExpression(
                "/:class=\"\\{ 'active': textAlign === '{$value}', 'is-active': textAlign === '{$value}' \\}\"/",
                $this->alignVue,
                "Align cell for textAlign='{$value}' must add .is-active alongside .active."
            );
        }
    }

    #[Test]
    public function align_carries_task_id_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-16-c3d0ed', $this->alignVue);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — AI-693 monochrome icon at rest (per-type tinting removed)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function per_type_icon_tint_match_map_removed(): void
    {
        // The five per-type colour tokens MUST NOT appear in the blade
        // anywhere except inside comments. Strip Blade comments before
        // checking so the AI-693 docblock referencing the old tints in
        // prose doesn't false-fail this guard.
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->modalBlade);
        foreach (['indigo', 'emerald', 'violet', 'rose', 'amber'] as $tint) {
            // Only flag colour utility usages — `bg-{tint}-500/10`, `text-{tint}-600`, etc.
            $this->assertDoesNotMatchRegularExpression(
                '/(?:bg|text|group-hover:bg|dark:group-hover:bg|dark:text)-' . $tint . '-/',
                $stripped,
                "AI-693 — per-type {$tint} tint utility must be removed; six cards share one monochrome line-set."
            );
        }
    }

    #[Test]
    public function icon_wrapper_carries_mw_add_content_icon_class(): void
    {
        // The new class is the hover-target for the AI-693 accent CSS.
        // Both primary + secondary loops must emit it. Two occurrences.
        $count = substr_count($this->modalBlade, 'class="mw-add-content-icon flex items-center justify-center');
        $this->assertGreaterThanOrEqual(
            2,
            $count,
            'Both primary + secondary card loops must give the icon wrapper the .mw-add-content-icon class.'
        );
    }

    #[Test]
    public function monochrome_neutral_icon_classes_present(): void
    {
        // After the migration, the rest-state icon uses neutral gray
        // utilities — confirm both bg and foreground share the new class.
        // Count occurrences of the canonical neutral-icon class string
        // emitted by both foreach loops.
        // Pin-evolved: task-2026-05-27-de93c9 / AI-1168 updated secondary-card icons
        // from text-gray-600 dark:text-gray-400 → text-gray-700 dark:text-gray-300
        // for WCAG 4.5:1 contrast compliance. Primary cards use text-white (on
        // accent-bg), so only secondary cards carry this neutral-icon class.
        $count = substr_count(
            $this->modalBlade,
            "'h-6 w-6 transition duration-150 text-gray-700 dark:text-gray-300'"
        );
        $this->assertGreaterThanOrEqual(
            1,
            $count,
            'Secondary card loop must emit the neutral gray SVG class string (text-gray-700 + dark:text-gray-300, per AI-1168 WCAG 4.5:1 fix).'
        );
    }

    #[Test]
    public function modal_blade_carries_task_id_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-16-c3d0ed', $this->modalBlade);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — AI-693 accent contract CSS in admin-side style block
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function accent_contract_bg_rule_present_and_scoped(): void
    {
        // .mw-content-picker-modal .mw-add-content-modal-action-wrapper:hover
        //   .mw-add-content-icon { background-color: var(--ese-accent-soft); }
        // task-651cc2 update: var() now carries literal fallback
        // per SOUL #108 token-fallback contract — regex updated to
        // accept `var(--ese-accent-soft)` or `var(--ese-accent-soft,
        // <fallback>)`.
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper:hover\s+\.mw-add-content-icon[\s\S]*?background-color:\s*var\(--ese-accent-soft(?:,[^)]*)?\)/',
            $this->adminStyleBlade,
            'AI-693 accent contract: :hover on .mw-add-content-modal-action-wrapper must set --ese-accent-soft on .mw-add-content-icon (scoped to .mw-content-picker-modal).'
        );
    }

    #[Test]
    public function accent_contract_svg_color_rule_present(): void
    {
        // task-651cc2 update: accept var() with optional literal
        // fallback per SOUL #108 token-fallback contract.
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper:hover\s+\.mw-add-content-icon\s+svg[\s\S]*?color:\s*var\(--ese-accent(?:,[^)]*)?\)/',
            $this->adminStyleBlade,
            'AI-693 accent contract: :hover SVG must inherit --ese-accent foreground (scoped).'
        );
    }

    #[Test]
    public function accent_contract_also_fires_on_focus_visible(): void
    {
        // Keyboard accessibility — accent must also light up on
        // :focus-visible, not only :hover.
        $this->assertStringContainsString(
            '.mw-content-picker-modal .mw-add-content-modal-action-wrapper:focus-visible .mw-add-content-icon',
            $this->adminStyleBlade,
            'Accent contract must also apply on :focus-visible (keyboard nav).'
        );
    }

    #[Test]
    public function accent_contract_uses_ese_tokens_not_inline_literals(): void
    {
        // Guard against re-introducing per-type tinted colours or
        // standalone hex/rgb/rgba literals in the AI-693 CSS slice.
        //
        // task-2026-05-16-651cc2 update: file relocated to
        // live-edit-classes.css. AI-693 block now sits AFTER the
        // AI-691a+AI-697 block (per task-bc28fd ordering) — slice-
        // end marker walks forward to next `/* ─` or EOF (LESSONS
        // selector-self-match slice pattern). Token-fallback
        // contract from SOUL #108 means var() calls now carry
        // literal fallbacks (`var(--ese-accent-soft, rgba(13, 110,
        // 253, 0.12))`) — those rgba() literals INSIDE var()
        // fallbacks are legitimate. The guard rejects literals on
        // CSS rule lines that DON'T contain `var(`.
        $start = strpos($this->adminStyleBlade, 'AI-693 (task-2026-05-16-c3d0ed)');
        $this->assertNotFalse($start, 'AI-693 task-id marker must be present in the admin-side style block.');
        $sliceStart = strpos($this->adminStyleBlade, '*/', $start);
        $this->assertNotFalse($sliceStart, 'AI-693 docblock close `*/` must follow the marker.');
        $sliceStart += 2;
        $next = strpos($this->adminStyleBlade, '/* ─', $sliceStart);
        $sliceEnd = $next !== false ? $next : strlen($this->adminStyleBlade);
        $slice = substr($this->adminStyleBlade, $sliceStart, $sliceEnd - $sliceStart);

        // Scan declaration lines only; skip lines that wrap their
        // literal inside a var() fallback.
        $lines = preg_split('/\r?\n/', $slice);
        foreach ($lines as $line) {
            if (! str_contains($line, ':')) continue; // only declaration lines
            if (str_contains($line, 'var(')) continue; // var() with fallback is allowed
            $this->assertDoesNotMatchRegularExpression(
                '/#[0-9a-fA-F]{3,8}\b|rgb\s*\(|rgba\s*\(/',
                $line,
                "AI-693 CSS slice must consume var(--ese-*) tokens — found standalone literal in line: {$line}"
            );
        }
    }

    #[Test]
    public function admin_style_blade_carries_task_id_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-16-c3d0ed', $this->adminStyleBlade);
    }
}
