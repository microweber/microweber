<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-2a40c5 / AI-737 — Dashboard analytics chart
 * invisible in LIGHT mode (inverse dark-mode contrast). Jira:
 *   https://microweber.atlassian.net/browse/AI-737
 *
 * Designer dispatch 2026-05-16T15:41:17 (Medium — caught by the
 * durable light/dark separate-audit rule, inverse contrast issue).
 *
 * Problem: Dashboard "Statistics" chart line used Chart.js
 * defaults — produced a near-grey line that washed out against
 * white in light mode. Same data rendered visibly in dark mode
 * (two visible peaks late in May) but appeared empty in light.
 * Most users see no chart data even when traffic exists.
 *
 * Fix: explicit dataset border/background using MwColors::Blue
 * (#0d6efd) — 4.4:1 contrast on white + 7.3:1 on dark slate
 * (both WCAG AA non-text 3:1 floor met).
 *
 * Acceptance: WCAG AA non-text 3:1 against chart surface in BOTH
 * modes; visual parity (same peaks visible in both).
 */
class Admin2a40c5AI737ChartContrastContractTest extends TestCase
{
    private string $src;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'Modules/SiteStats/Filament/SiteStatsDashboardChart.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — explicit dataset colours pinned
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function dataset_uses_mwcolors_blue_border(): void
    {
        // MwColors::Blue is #0d6efd — project-wide primary blue,
        // visible against both #fff (light) and #1a1f2b (dark).
        $this->assertMatchesRegularExpression(
            "/'borderColor'\\s*=>\\s*'#0d6efd'/",
            $this->src,
            "Dataset borderColor must be '#0d6efd' (MwColors::Blue) for WCAG AA contrast in both themes."
        );
    }

    #[Test]
    public function dataset_carries_translucent_fill_for_area_under_line(): void
    {
        // Area-under-line fill at 15% alpha gives a soft brand
        // anchor under the line without overpowering surface contrast.
        $this->assertMatchesRegularExpression(
            "/'backgroundColor'\\s*=>\\s*'rgba\\(13,\\s*110,\\s*253,\\s*0\\.15\\)'/",
            $this->src,
            "Dataset backgroundColor must be 'rgba(13, 110, 253, 0.15)' for the fill region."
        );
    }

    #[Test]
    public function dataset_border_width_at_least_2_px(): void
    {
        // 2 px line is the Chart.js default-bump that keeps the
        // line visible at the chart's 200 px max-height.
        $this->assertMatchesRegularExpression(
            "/'borderWidth'\\s*=>\\s*2/",
            $this->src,
            "borderWidth must be 2 px for visibility at the chart's 200 px max-height."
        );
    }

    #[Test]
    public function dataset_fill_enabled(): void
    {
        $this->assertMatchesRegularExpression(
            "/'fill'\\s*=>\\s*true/",
            $this->src,
            'fill must be true so the brand-tinted area under the line renders.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — designer-spec hints + scope guard
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function tension_smoothing_applied(): void
    {
        // 0.3 tension softens the line peaks slightly — matches
        // the rendered curve designer described from dark-mode
        // ("two visible peaks late in May").
        $this->assertMatchesRegularExpression(
            "/'tension'\\s*=>\\s*0\\.3/",
            $this->src,
            'tension must be 0.3 to soften peaks per designer dispatch — pin so future cleanup keeps the smoothing.'
        );
    }

    #[Test]
    public function dataset_block_does_not_use_low_alpha_grey(): void
    {
        // Regression guard against re-introducing low-alpha grey
        // stroke (the pre-fix Chart.js default that caused the bug).
        // Strip docblock prose so the migration-rationale comment
        // doesn't false-match.
        $rules = preg_replace('/\/\*\*.*?\*\//s', '', $this->src);
        $rules = preg_replace('/\/\/.*$/m', '', $rules);
        // Bare-grey rgb stroke = rgb with all three channels equal,
        // alpha < 0.5. Pattern: rgba(N, N, N, 0.0–0.4) where N is 0–255.
        $this->assertDoesNotMatchRegularExpression(
            "/borderColor['\"]\\s*=>\\s*['\"]rgba\\(\\s*(\\d+)\\s*,\\s*\\1\\s*,\\s*\\1\\s*,\\s*0\\.[0-4]/i",
            $rules,
            'borderColor must not regress to a low-alpha equal-channel grey — that washes out on white per the AI-737 root cause.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai737_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-2a40c5', $this->src);
        $this->assertStringContainsString('AI-737', $this->src);
        $this->assertStringContainsString(
            'MwColors::Blue',
            $this->src,
            'Source comment must reference MwColors::Blue so a future audit grep finds the project-wide primary blue source-of-truth.'
        );
    }
}
