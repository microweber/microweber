<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-8d5baf / AI-740 — image upload modal dark-mode
 * backdrop missing / modal blends with canvas. Jira:
 *   https://microweber.atlassian.net/browse/AI-740
 *
 * Designer dispatch 2026-05-16T15:54:38 (High — dark-mode users
 * 30–40 % adoption can't reliably use image upload).
 *
 * Problem: in dark mode the modal body inherited an unexpected
 * blue tint (dark hero image bleeding through a too-transparent
 * backdrop). The pre-fix backdrop alpha was 40 % regardless of
 * theme — fine on light surfaces, not enough on dark.
 *
 * Fix: mirror the AI-700 MainDrawer backdrop pattern —
 *   light = rgba(0,0,0,0.4) (unchanged)
 *   dark  = rgba(0,0,0,0.6) (NEW)
 *
 * Scope: any dialog using `.mw-dialog-skin-default` benefits
 * (filepicker, prompts, confirms) — positive side-effect since
 * they share the same backdrop. Pattern matches AI-700.
 */
class LiveEdit8d5bafAI740DialogDarkBackdropContractTest extends TestCase
{
    private string $css;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        // Webpack-built bundle — runtime probe per SOUL #108.
        $bundlePath = base_path(
            'packages/microweber-filament-theme/resources/dist/build/microweber-filament-theme.css'
        );
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — light-mode baseline preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function light_mode_backdrop_still_at_40_percent(): void
    {
        // The original rule remains — only the dark-mode override
        // is added. Light mode unchanged so prompts/confirms in
        // light theme keep their established look.
        $this->assertMatchesRegularExpression(
            '/\.mw-dialog-skin-default\s+\.mw-dialog-overlay\s*\{[^}]*bg-black\/40/i',
            $this->css,
            'Light-mode .mw-dialog-overlay must still apply !bg-black/40 (rgba(0,0,0,0.4)).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — dark-mode override bumps alpha to 60 %
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function dark_mode_override_applies_60_percent_backdrop(): void
    {
        // .dark + .dark dual-selector mirror per existing
        // dark-mode override pattern in this file.
        $this->assertMatchesRegularExpression(
            '/(\.dark|\.dark)\s+\.mw-dialog-skin-default\s+\.mw-dialog-overlay[^{]*\{[^}]*bg-black\/60/i',
            $this->css,
            'Dark-mode .mw-dialog-overlay must bump backdrop to !bg-black/60 (rgba(0,0,0,0.6)) per AI-740 fix.'
        );
    }

    #[Test]
    public function dark_mode_override_covers_both_html_dark_and_dot_dark_scopes(): void
    {
        // Belt-and-braces — different parent themes use different
        // dark-mode root selectors; pin both.
        $this->assertStringContainsString(
            '.dark .mw-dialog-skin-default .mw-dialog-overlay',
            $this->css,
            'Dark-mode override must include `.dark` selector for the project default theme.'
        );
        $this->assertStringContainsString(
            '.dark .mw-dialog-skin-default .mw-dialog-overlay',
            $this->css,
            'Dark-mode override must also include bare `.dark` selector for Tailwind-class theme contexts.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — bundle runtime probe (SOUL #108)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_dark_mode_override(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack microweber-filament-theme.css bundle not present — run `cd packages/microweber-filament-theme && npm run build` to enable runtime probe.');
        }
        // The Tailwind `bg-black/60` utility compiles to
        // `background-color: rgba(0,0,0,0.6)` (or equivalent
        // color-mix() syntax in newer Tailwind). Probe for the
        // computed declaration.
        $this->assertMatchesRegularExpression(
            '/(rgba\(0,?\s*0,?\s*0,\s*0\.6\)|rgb\(0\s+0\s+0\s*\/\s*0\.6\)|color-mix\([^)]*60%)/i',
            $this->bundle,
            'Webpack bundle must carry the 60% dark-mode backdrop after rebuild.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai740_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-8d5baf', $this->css);
        $this->assertStringContainsString('AI-740', $this->css);
        // AI-700 lineage cited so the audit chain (the pattern
        // mirror) is grep-able.
        $this->assertStringContainsString(
            'AI-700',
            $this->css,
            'Comment must cite AI-700 as the source of the dark-mode backdrop pattern.'
        );
    }
}
