<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-51c8e8 / AI-712 (Medium) — Template Settings panel:
 * unify three internal content-block patterns.
 *
 * Designer dispatch (DESIGN_AUDIT.md L2.5, per-ticket email
 * 2026-05-16T13:40): the Template Settings panel mixes three
 * different content-block patterns with no consistent rhythm:
 *   1. "Choose where to edit" — 2-card segmented selector.
 *   2. "Website design settings" — link rows with chevrons.
 *   3. "AI Assistant" — input + voice-input mic + full-width dark
 *      Submit button (visually dominant).
 *
 * Spec fix:
 *   - All "select between two options" surfaces use MwSegmented
 *     (AI-684).
 *   - All "navigate to a sub-screen" rows use MwField (AI-687)
 *     with a trailing MwToolButton chevron.
 *   - AI Assistant collapses to a single MwField row with an
 *     inline send icon.
 *   - Three section headers visually identical: --font-label /
 *     --ese-label / --weight-label / --letter-label.
 *
 * Dependency: spec is "After AI-684 + AI-687 (primitives
 * production-stable)". AI-684 (MwToolButton + MwSegmented) shipped
 * at ESE slice 1.3a (SOUL #99); AI-687 (MwField) is ESE Phase 1
 * slice 1.4 — NOT YET SHIPPED as of this commit.
 *
 * Slice-1 implementation (this commit):
 *
 *   1. TemplateSettings.vue
 *      - Wrapper `<div>` gains class `.mw-template-settings-panel`
 *        for scoped CSS targeting.
 *      - "Choose where to edit" toggle migrated to MwSegmented
 *        primitive: `.edit-mode-toggle-container` gains `.mw-segmented`
 *        alongside its legacy class; each `.edit-mode-option` gains
 *        `.mw-segmented__cell`; active-state binding now toggles
 *        BOTH `.active` (legacy) AND `.is-active` (AI-684 contract)
 *        per the same migration pattern as ESE slice 1.3a/1.3b
 *        consumer migrations.
 *      - "Choose where to edit" label gains
 *        `.mw-template-settings-section-header` class.
 *      - "Website design settings" span gains
 *        `.mw-template-settings-section-header` class.
 *
 *   2. general-styles.css
 *      - New `.mw-template-settings-panel
 *        .mw-template-settings-section-header` rule consuming
 *        `--font-label` + `--weight-label` + `--letter-label` +
 *        `--ese-text-muted` per spec; `text-transform: uppercase`
 *        matches the spec's "same case" requirement (mirrors the
 *        existing AI Assistant ::before pseudo-label uppercase
 *        treatment).
 *
 * Slice-2 / AI-712a follow-up candidate (NOT shipped here, flagged
 * in inline source comments + this docblock + CSS docblock):
 *   - Convert "Website design settings" link rows
 *     (`mainStyleGroups` v-for) to MwField rows with trailing
 *     MwToolButton chevron (requires AI-687).
 *   - Collapse AI Assistant (FieldAiChangeDesign) to a single
 *     MwField with inline send icon (requires AI-687).
 *
 * Token-scoping note (per SOUL #108 spec-doc-nit): every var()
 * carries a literal fallback. Panel renders inside
 * `body.fi-panel-admin` where :root ESE tokens resolve.
 */
class LiveEdit51c8e8AI712TemplateSettingsUnifyContractTest extends TestCase
{
    private string $templateSettings;
    private string $generalStyles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->templateSettings = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/RightSidebar/TemplateSettings/TemplateSettings.vue'
        ));
        $this->generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Vue template structural changes
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function panel_wrapper_carries_scope_class(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+class="mw-template-settings-panel"/',
            $this->templateSettings,
            'Template Settings panel outermost wrapper must carry class="mw-template-settings-panel" so the AI-712 scoped CSS rules resolve.'
        );
    }

    #[Test]
    public function choose_where_to_edit_label_has_section_header_class(): void
    {
        // The label above the segmented selector must wear the
        // unified section-header class.
        $this->assertMatchesRegularExpression(
            '/<label\s+class="live-edit-label\s+mw-template-settings-section-header[^"]*"[^>]*>\s*Choose where to edit\s*<\/label>/',
            $this->templateSettings,
            '"Choose where to edit" label must carry both .live-edit-label (legacy) and .mw-template-settings-section-header (AI-712 unification).'
        );
    }

    #[Test]
    public function website_design_settings_span_has_section_header_class(): void
    {
        $this->assertMatchesRegularExpression(
            '/<span\s+class="live-edit-label\s+mw-template-settings-section-header"\s*>\s*Website design settings\s*<\/span>/',
            $this->templateSettings,
            '"Website design settings" span must carry both .live-edit-label (legacy) and .mw-template-settings-section-header (AI-712 unification).'
        );
    }

    #[Test]
    public function edit_mode_toggle_container_wears_mw_segmented(): void
    {
        // AI-684 MwSegmented primitive migrated alongside legacy class.
        $this->assertMatchesRegularExpression(
            '/class="edit-mode-toggle-container\s+mw-segmented"/',
            $this->templateSettings,
            '.edit-mode-toggle-container must wear the .mw-segmented primitive class alongside its legacy class per AI-684 consumer-migration pattern.'
        );
    }

    #[Test]
    public function edit_mode_option_cells_wear_mw_segmented_cell(): void
    {
        // Both Template and Layout cells must carry the primitive.
        $cellCount = preg_match_all(
            '/class="edit-mode-option\s+mw-segmented__cell"/',
            $this->templateSettings
        );
        $this->assertSame(
            2,
            $cellCount,
            'Both .edit-mode-option cells (Template + Layout) must wear .mw-segmented__cell. Found ' . $cellCount . '.'
        );
    }

    #[Test]
    public function active_state_binds_both_legacy_and_is_active_classes(): void
    {
        // Template cell active-state binding.
        $this->assertMatchesRegularExpression(
            "/:class=\"\\{\\s*active:\\s*applyMode\\s*===\\s*'template'\\s*,\\s*'is-active':\\s*applyMode\\s*===\\s*'template'\\s*\\}\"/",
            $this->templateSettings,
            "Template cell :class must toggle both `active` (legacy) AND `is-active` (AI-684 contract) for applyMode === 'template'."
        );
        // Layout cell active-state binding.
        $this->assertMatchesRegularExpression(
            "/:class=\"\\{\\s*active:\\s*applyMode\\s*===\\s*'layout'\\s*,\\s*'is-active':\\s*applyMode\\s*===\\s*'layout'\\s*\\}\"/",
            $this->templateSettings,
            "Layout cell :class must toggle both `active` (legacy) AND `is-active` (AI-684 contract) for applyMode === 'layout'."
        );
    }

    #[Test]
    public function click_handlers_preserved_for_both_modes(): void
    {
        // Regression-guard: slice 1 is MIGRATION ONLY. The
        // handleApplyModeChange handlers must remain untouched.
        $this->assertStringContainsString(
            "@click=\"handleApplyModeChange('template')\"",
            $this->templateSettings,
            'Template cell @click="handleApplyModeChange(\'template\')" must remain — pure additive migration.'
        );
        $this->assertStringContainsString(
            "@click=\"handleApplyModeChange('layout')\"",
            $this->templateSettings,
            'Layout cell @click="handleApplyModeChange(\'layout\')" must remain — pure additive migration.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — CSS section-header rule
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function section_header_rule_consumes_font_label_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-template-settings-panel\s+\.mw-template-settings-section-header\s*\{[^}]*font-size:\s*var\(--font-label,\s*11px\)/s',
            $this->generalStyles,
            'Section-header rule must consume var(--font-label, 11px) per spec.'
        );
    }

    #[Test]
    public function section_header_rule_consumes_weight_label_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-template-settings-panel[\s\S]*?\.mw-template-settings-section-header\s*\{[^}]*font-weight:\s*var\(--weight-label,\s*500\)/s',
            $this->generalStyles,
            'Section-header rule must consume var(--weight-label, 500) per spec.'
        );
    }

    #[Test]
    public function section_header_rule_consumes_letter_label_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-template-settings-panel[\s\S]*?\.mw-template-settings-section-header\s*\{[^}]*letter-spacing:\s*var\(--letter-label,\s*0\.01em\)/s',
            $this->generalStyles,
            'Section-header rule must consume var(--letter-label, 0.01em) per spec.'
        );
    }

    #[Test]
    public function section_header_rule_uses_muted_text_colour(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-template-settings-panel[\s\S]*?\.mw-template-settings-section-header\s*\{[^}]*color:\s*var\(--ese-text-muted/s',
            $this->generalStyles,
            'Section-header rule must use --ese-text-muted so labels read as metadata.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Markers + AI-712a follow-up flag
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-51c8e8', $this->templateSettings);
        $this->assertStringContainsString('task-2026-05-16-51c8e8', $this->generalStyles);
    }

    #[Test]
    public function ai712_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('AI-712', $this->templateSettings);
        $this->assertStringContainsString('AI-712', $this->generalStyles);
    }

    #[Test]
    public function ai712a_followup_documented_in_both_files(): void
    {
        // AI-687 dependency means link rows + AI Assistant collapse
        // can't ship in slice 1. AI-712a flag MUST be discoverable
        // from both surfaces so the next agent picks up the
        // deferred work cleanly.
        $this->assertStringContainsString(
            'AI-712a',
            $this->templateSettings,
            'AI-712a follow-up candidate (MwField link rows + AI Assistant collapse) must be flagged in TemplateSettings.vue comments.'
        );
        $this->assertStringContainsString(
            'AI-712a',
            $this->generalStyles,
            'AI-712a follow-up candidate must be flagged in general-styles.css comments.'
        );
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        // Slice from the END of the AI-712 docblock to the next
        // AI-block marker so docblock prose mentioning tokens
        // doesn't false-pass (LESSONS selector-self-match family,
        // 4th occurrence this session).
        $marker = strpos($this->generalStyles, 'AI-712 — Template Settings panel section-header');
        $this->assertNotFalse($marker, 'AI-712 task marker must be present in general-styles.css.');
        $docblockEnd = strpos($this->generalStyles, '*/', $marker);
        $this->assertNotFalse($docblockEnd);
        $sliceStart = $docblockEnd + 2;
        $nextBlock = strpos($this->generalStyles, '/* ──', $sliceStart);
        $sliceEnd = $nextBlock !== false ? $nextBlock : strlen($this->generalStyles);
        $slice = substr($this->generalStyles, $sliceStart, $sliceEnd - $sliceStart);

        $tokens = [
            '--font-label'     => '11px',
            '--weight-label'   => '500',
            '--letter-label'   => '0.01em',
            '--ese-text-muted' => '#6b7280',
            '--space-sm'       => '8px',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-712 slice."
            );
        }
    }
}
