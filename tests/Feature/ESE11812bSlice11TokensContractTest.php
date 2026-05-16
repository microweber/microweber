<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-11812b — ESE design-system Slice 1.1: tokens
 *
 * Prerequisite for AI-684 (MwToolButton) and AI-685 (mobile
 * bottom-sheet). Spec: designer-agent/output/ese-design-spec-
 * 2026-05-16.md §2 (drop-in :root token block).
 *
 * Slice 1.1 is purely additive — no visual change yet, because
 * nothing references the tokens until AI-684 / AI-685 / F1.2 /
 * F1.6 land. This contract pins the token defs in source so a
 * future regression that deletes any of them will surface here
 * BEFORE the dependent slices try to consume them.
 *
 * Tested artifact: source CSS file (the Webpack bundle just
 * concatenates this); the bundle path is also pinned so the
 * served stylesheet carries the tokens.
 */
class ESE11812bSlice11TokensContractTest extends TestCase
{
    private string $src;
    private string $built;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css'
        ));
        $this->built = (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        ));
    }

    #[Test]
    public function source_declares_phi_spacing_scale(): void
    {
        // φ scale: hair / xs / sm / md / lg / xl / 2xl
        $tokens = [
            '--space-hair' => '0.236rem',
            '--space-xs'   => '0.382rem',
            '--space-sm'   => '0.618rem',
            '--space-md'   => '1.000rem',
            '--space-lg'   => '1.618rem',
            '--space-xl'   => '2.618rem',
            '--space-2xl'  => '4.236rem',
        ];
        foreach ($tokens as $name => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($name, '/') . ':\s*' . preg_quote($value, '/') . '/',
                $this->src,
                "Spacing token {$name} must equal {$value} (φ scale, base 1rem)."
            );
        }
    }

    #[Test]
    public function source_declares_border_radius_typography_tokens(): void
    {
        // Borders + radii + typography per spec §2
        $tokens = [
            '--border-hair' => '1px',
            '--border-xs'   => '1.9px',
            '--border-sm'   => '3.1px',
            '--radius-xs'   => '4px',
            '--radius-sm'   => '6px',
            '--radius-md'   => '10px',
            '--radius-pill' => '999px',
            '--font-label'   => '11px',
            '--font-control' => '13px',
            '--font-section' => '15px',
            '--font-title'   => '17px',
            '--weight-label'   => '500',
            '--weight-control' => '400',
            '--weight-section' => '600',
        ];
        foreach ($tokens as $name => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($name, '/') . ':\s*' . preg_quote($value, '/') . '/',
                $this->src,
                "Token {$name} must equal {$value}."
            );
        }
    }

    #[Test]
    public function source_declares_semantic_colour_aliases_light(): void
    {
        // 12 semantic aliases for light theme + motion tokens
        $aliases = [
            '--ese-surface',
            '--ese-surface-muted',
            '--ese-surface-hover',
            '--ese-border',
            '--ese-border-strong',
            '--ese-text',
            '--ese-text-muted',
            '--ese-label',
            '--ese-accent',
            '--ese-accent-soft',
            '--ese-danger',
            '--ese-track',
            '--ese-track-fill',
        ];
        foreach ($aliases as $name) {
            $this->assertStringContainsString(
                $name . ':',
                $this->src,
                "Semantic colour alias {$name} must be declared in :root."
            );
        }
    }

    #[Test]
    public function accent_token_aliases_bootstrap_blue_not_a_new_colour(): void
    {
        // No new colours invented — accent must equal MwColors::Blue = #0d6efd
        $this->assertMatchesRegularExpression(
            '/--ese-accent:\s*#0d6efd/i',
            $this->src,
            '--ese-accent must alias MwColors::Blue (#0d6efd), not introduce a new colour.'
        );
    }

    #[Test]
    public function dark_theme_block_overrides_surface_and_text(): void
    {
        // Dark theme: must override --ese-surface and --ese-text
        // when the html.dark / .theme-dark / [data-theme="dark"]
        // selector matches.
        $this->assertMatchesRegularExpression(
            '/(html\.dark|\.theme-dark|\[data-theme="dark"\])[\s\S]*?\{[\s\S]*?--ese-surface:\s*#1a1f2b/s',
            $this->src,
            'Dark theme block must override --ese-surface to #1a1f2b.'
        );
        $this->assertMatchesRegularExpression(
            '/(html\.dark|\.theme-dark|\[data-theme="dark"\])[\s\S]*?\{[\s\S]*?--ese-text:\s*#f1f5f9/s',
            $this->src,
            'Dark theme block must override --ese-text to #f1f5f9.'
        );
    }

    #[Test]
    public function motion_tokens_have_reduced_motion_override(): void
    {
        // Per spec §2: prefers-reduced-motion must collapse durations to 0
        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*reduce\)\s*\{[\s\S]*?--t-fast:\s*0ms[\s\S]*?--t-base:\s*0ms[\s\S]*?--t-slow:\s*0ms/s',
            $this->src,
            '@media (prefers-reduced-motion: reduce) must zero all 3 motion tokens.'
        );
    }

    #[Test]
    public function built_bundle_carries_tokens(): void
    {
        // The Webpack pipeline must include the source file in the
        // built bundle that ships to the browser. Probe a few
        // distinctive tokens.
        $this->assertStringContainsString('--space-hair', $this->built,
            'Built bundle must carry --space-hair (Webpack pipeline integration).');
        $this->assertStringContainsString('--ese-accent', $this->built);
        $this->assertStringContainsString('1.618rem', $this->built,
            'φ-scale --space-lg value (1.618rem) must reach the served bundle.');
    }

    #[Test]
    public function task_id_marker_pinned_for_audit_grep(): void
    {
        $this->assertStringContainsString('task-2026-05-16-11812b', $this->src);
    }

    #[Test]
    public function token_consumers_match_currently_authorised_slices(): void
    {
        // Slice-by-slice additive guard. Each authorising dispatch
        // adds a known number of token consumers; this assertion's
        // threshold gets bumped per slice as it lands. If a slice
        // ships without an authorising dispatch + threshold bump
        // here, this test fails — surfaces unscoped consumer leaks.
        //
        // Comment-stripped scan (block comments removed before counting)
        // so docblock prose that *describes* the migration and quotes
        // var(--…) token names does not register as a consumer.
        //
        // Current authorised slices in this file:
        //   - 1.1 (tokens):     1 consumer  (--ese-track-fill aliases --ese-accent)
        //   - 1.7 (AI-690):     6 consumers (5 migration loci, .picker-button has 2)
        //   - 1.2 (AI-689):    11 consumers (3× --space-lg, 2× --ese-track*,
        //                       2× --radius-pill on track+fill, 1× --ese-accent
        //                       on thumb, 1× --radius-pill on thumb, 2× --ese-
        //                       surface / --ese-surface-muted on thumb rings)
        //   - 1.3a (AI-684):   37 consumers — MwToolButton + MwSegmented
        //                       primitive declarations. Heaviest slice so far
        //                       because of the 3 variants × ~6 token refs each
        //                       (border/radius/font-size/font-weight/transition)
        //                       plus the segmented strip + cell defs +
        //                       active-state overrides.
        //   - 1.4 (AI-687):    37 consumers — MwField primitive (task-5fe1f9).
        //                       Wrapper row (4 tokens) + label (5) + control
        //                       wrapper (1) + <select> rules (7 normal + 1 hover
        //                       + 2 focus-visible) + number-input (7) + reset
        //                       (3 + 4 transition + 1 hover + 1 focus = 9) +
        //                       hover/focus overrides. SOUL #108 spec-doc-nit
        //                       compliance — every var() has a literal fallback.
        //   Total authorised: 92
        $stripped = preg_replace('/\/\*.*?\*\//s', '', $this->src);
        preg_match_all('/var\(--(ese-|space-|font-|weight-|line-|letter-|border-|radius-|t-fast|t-base|t-slow|ease)/', $stripped, $m);
        $useCount = count($m[0]);
        $this->assertSame(92, $useCount,
            "Authorised token consumers: 92 (1 from slice 1.1 + 6 from slice 1.7 + 11 from slice 1.2 + 37 from slice 1.3a + 37 from slice 1.4). "
            . "Found {$useCount}. If a new slice landed, bump this threshold + document in the comment."
        );
    }
}
