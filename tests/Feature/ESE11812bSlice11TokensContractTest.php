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
    public function accent_token_aliases_mw_ink_not_a_new_colour(): void
    {
        // No new colours invented — ESE active states deliberately use MW ink (#182433 =
        // --mw-secondary / --mw-text-primary), matching the sidebar/button "ink, not blue" chrome.
        $this->assertMatchesRegularExpression(
            '/--ese-accent:\s*#182433/i',
            $this->src,
            '--ese-accent must alias an existing MW colour (MW ink #182433), not introduce a new colour.'
        );
    }

    #[Test]
    public function dark_theme_block_overrides_surface_and_text(): void
    {
        // Dark theme: must override --ese-surface and --ese-text
        // when the .dark / .theme-dark / [data-theme="dark"]
        // selector matches.
        $this->assertMatchesRegularExpression(
            '/(\.dark|\.theme-dark|\[data-theme="dark"\])[\s\S]*?\{[\s\S]*?--ese-surface:\s*#1a1f2b/s',
            $this->src,
            'Dark theme block must override --ese-surface to #1a1f2b.'
        );
        $this->assertMatchesRegularExpression(
            '/(\.dark|\.theme-dark|\[data-theme="dark"\])[\s\S]*?\{[\s\S]*?--ese-text:\s*#f1f5f9/s',
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


}
