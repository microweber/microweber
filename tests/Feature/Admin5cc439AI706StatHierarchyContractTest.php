<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-5cc439 / AI-706 (Low) — Stat-card hierarchy:
 * hero chart 2× + compact 4-col secondary strip.
 *
 * Designer dispatch (admin-shell-improvements-2026-05-16.md §2 AD5,
 * per-ticket email 2026-05-16T13:39): the dashboard weights the
 * Statistics chart and the 4 secondary cards (Emails / Comments /
 * Sales / Orders) equally — chart sparse, secondary cards
 * over-claiming attention for low-info values. Fix: establish
 * "one hero + a strip" hierarchy.
 *
 * Slice-1 implementation (this commit):
 *
 *   1. TrafficChartWidget.php
 *      - `protected ?string $maxHeight = '480px';` bumps Filament's
 *        ChartWidget canvas height to ~2× the default Chart.js 2:1
 *        aspect ratio (which previously yielded ~240-280 px). The
 *        chart now claims the hero position on the dashboard.
 *
 *   2. general-styles.css
 *      - `.mw-quick-stats-grid` switches from 2×2 grid (legacy
 *        `microweber-theme-v3.scss` shape) to 4 cols at desktop,
 *        2 cols at tablet (≤1023.98 px), 1 col at mobile (≤480 px).
 *      - Card body padding compacts from `--space-lg × 2` to
 *        `--space-sm × --space-md` per spec "half current height".
 *      - Card icon shrinks from `--space-xl` to `--space-lg`.
 *      - Label/value typography tightens to `--font-label` /
 *        `--font-section`.
 *      - `margin-block-start: var(--space-lg)` on the strip wrapper
 *        adds the spec-mandated vertical gap between hero and strip.
 *
 * Slice-2 / AI-706a follow-up candidate (NOT shipped here, flagged
 * in inline source comments + this docblock): move the KPIs
 * (Online / Visitors % / Conversion) into a left-rail INSIDE the
 * chart card. Requires a custom widget view and KPI computation;
 * out of slice-1 scope.
 *
 * Token-scoping note (per SOUL #108 spec-doc-nit): every var() in
 * the AI-706 slice carries a literal fallback. Dashboard widgets
 * render inside body.fi-panel-admin where :root ESE tokens resolve.
 */
class Admin5cc439AI706StatHierarchyContractTest extends TestCase
{
    private string $chartWidgetClass;
    private string $generalStyles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->chartWidgetClass = (string) file_get_contents(base_path(
            'app/Filament/Admin/Widgets/TrafficChartWidget.php'
        ));
        $this->generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Hero chart bump
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function chart_widget_pins_max_height_at_480px(): void
    {
        // Filament's ChartWidget exposes `$maxHeight` (vendor file
        // ChartWidget.php line 32 default null). When non-null, the
        // chart Blade emits `style="max-height: …"` on the canvas
        // container. 480px is roughly 2× the default Chart.js 2:1
        // aspect on a typical wide viewport.
        $this->assertMatchesRegularExpression(
            "/protected\\s+\\?string\\s+\\\$maxHeight\\s*=\\s*'480px'/",
            $this->chartWidgetClass,
            "TrafficChartWidget must set \$maxHeight = '480px' to bump the hero chart to ~2× the default ChartWidget canvas height."
        );
    }

    #[Test]
    public function chart_widget_carries_ai706_marker(): void
    {
        $this->assertStringContainsString(
            'AI-706',
            $this->chartWidgetClass,
            'AI-706 marker must be present in TrafficChartWidget.php (audit grep across surfaces).'
        );
        $this->assertStringContainsString(
            'task-2026-05-16-5cc439',
            $this->chartWidgetClass,
            'task-2026-05-16-5cc439 marker must be present in TrafficChartWidget.php.'
        );
    }

    #[Test]
    public function ai706a_followup_documented_in_chart_widget(): void
    {
        $this->assertStringContainsString(
            'AI-706a',
            $this->chartWidgetClass,
            'AI-706a follow-up candidate (KPIs left-rail INSIDE the chart card) must be flagged in TrafficChartWidget docblock.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Secondary strip grid responsiveness
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function quick_stats_grid_is_4col_at_desktop(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-quick-stats-grid\s*\{[^}]*grid-template-columns:\s*repeat\(4,\s*1fr\)/s',
            $this->generalStyles,
            '.mw-quick-stats-grid must default to 4 columns at desktop per spec (was 2×2 in legacy SCSS).'
        );
    }

    #[Test]
    public function quick_stats_grid_collapses_to_2col_at_tablet(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*1023\.98px\s*\)\s*\{[\s\S]*?\.mw-quick-stats-grid\s*\{[^}]*grid-template-columns:\s*repeat\(2,\s*1fr\)/s',
            $this->generalStyles,
            'Tablet @media (max-width: 1023.98px) must collapse the strip to 2×2 per spec.'
        );
    }

    #[Test]
    public function quick_stats_grid_collapses_to_1col_at_mobile(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*480px\s*\)\s*\{[\s\S]*?\.mw-quick-stats-grid\s*\{[^}]*grid-template-columns:\s*1fr/s',
            $this->generalStyles,
            'Mobile @media (max-width: 480px) must collapse the strip to 1 column per spec.'
        );
    }

    #[Test]
    public function quick_stats_grid_has_space_lg_top_margin(): void
    {
        // Vertical gap between hero chart and secondary strip per spec.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-quick-stats-grid\s*\{[^}]*margin-block-start:\s*var\(--space-lg,\s*21px\)/s',
            $this->generalStyles,
            '.mw-quick-stats-grid must carry margin-block-start: var(--space-lg) for the hero ↔ strip vertical gap per spec.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Compact card geometry
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function card_body_padding_is_compact(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-quick-stat-card-body\s*\{[^}]*padding:\s*var\(--space-sm,\s*8px\)\s+var\(--space-md,\s*13px\)/s',
            $this->generalStyles,
            'Card body padding must compact to var(--space-sm) var(--space-md) per spec "half current height".'
        );
    }

    #[Test]
    public function card_icon_shrinks_to_space_lg(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-quick-stat-card-icon\s*\{[^}]*width:\s*var\(--space-lg,\s*21px\)[^}]*height:\s*var\(--space-lg,\s*21px\)/s',
            $this->generalStyles,
            'Card icon must shrink to var(--space-lg) × var(--space-lg) per the compact-strip aesthetic.'
        );
    }

    #[Test]
    public function card_label_uses_font_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-quick-stat-card-label\s*\{[^}]*font-size:\s*var\(--font-label/s',
            $this->generalStyles,
            'Card label must use var(--font-label) typography per spec.'
        );
    }

    #[Test]
    public function card_value_uses_font_section_and_600_weight(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-quick-stat-card-value\s*\{[^}]*font-size:\s*var\(--font-section[^}]*font-weight:\s*600/s',
            $this->generalStyles,
            'Card value must use var(--font-section) typography with font-weight:600.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-5cc439', $this->chartWidgetClass);
        $this->assertStringContainsString('task-2026-05-16-5cc439', $this->generalStyles);
    }

    #[Test]
    public function ai706a_followup_documented_in_css(): void
    {
        $this->assertStringContainsString(
            'AI-706a',
            $this->generalStyles,
            'AI-706a follow-up candidate must be flagged in general-styles.css comments.'
        );
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        $start = strpos($this->generalStyles, 'AI-706 — Stat-card hierarchy');
        $this->assertNotFalse($start, 'AI-706 task marker must be present in general-styles.css.');
        $slice = substr($this->generalStyles, $start);
        $tokens = [
            '--space-sm'     => '8px',
            '--space-md'     => '13px',
            '--space-lg'     => '21px',
            '--font-label'   => '11px',
            '--font-section' => '15px',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-706 slice."
            );
        }
    }
}
