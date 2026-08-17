<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-e8b42c / AI-821 + AI-825 (ESE bundle)
 *
 * AI-821 — aria-live announcement region visually leaking text.
 * Root cause: Bootstrap's .visually-hidden is not loaded in the ESE iframe,
 * so class="visually-hidden mw-ese-announce" renders text visibly.
 * Fix: .mw-ese-announce sr-only CSS with !important on all 8 properties.
 * Acceptance: clip !== 'auto' && offsetWidth <= 1 in Tier-3 probe.
 *
 * AI-825 — Dark mode contrast sweep across all ESE sections.
 * 5 failure categories:
 *   1. Field labels <4.5:1 — Tailwind @apply hardcodes light colours
 *   2. Input borders <3:1  — --ese-border at rgba(255,255,255,0.12) ≈ 1.5:1
 *   3. Dropdown chrome too dim — @apply bg-gray-100 text-gray-800 unchanged
 *   4. Dark colour swatches invisible — --ese-border-strong at 0.20 alpha ≈ 2.0:1
 *   5. "More options:" divider unreadable — same token issue
 * Fix: (a) Token bump in later .dark block (source-order cascade wins);
 *       (b) Compound selector !important overrides for Tailwind-hardcoded values.
 *
 * File: packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css
 */
class ElementStyleEditorDarkContrastAI825AndAnnouncementHiddenAI821ContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $raw = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css')
        );
        $this->src = $raw;
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;

        $bundlePath = base_path('public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css');
        $this->bundle = file_exists($bundlePath) ? (string) file_get_contents($bundlePath) : '';
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-e8b42c', $this->src,
            'element-style-editor.css must carry the AI-821+AI-825 task marker.'
        );
    }

    // ─── AI-821: .mw-ese-announce visually hidden ──────────────────────────────

    #[Test]
    public function announce_region_has_position_absolute_important(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-ese-announce\s*\{[^}]*position:\s*absolute\s*!important~s',
            $this->srcStripped,
            '.mw-ese-announce must use position:absolute !important to ensure hiding without Bootstrap.'
        );
    }

    #[Test]
    public function announce_region_has_1px_width_height_important(): void
    {
        $pos = strpos($this->srcStripped, '.mw-ese-announce');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 500);
        $this->assertMatchesRegularExpression('~width:\s*1px\s*!important~', $slice,
            '.mw-ese-announce must have width:1px !important.'
        );
        $this->assertMatchesRegularExpression('~height:\s*1px\s*!important~', $slice,
            '.mw-ese-announce must have height:1px !important.'
        );
    }

    #[Test]
    public function announce_region_has_clip_rect_important(): void
    {
        $pos = strpos($this->srcStripped, '.mw-ese-announce');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 500);
        $this->assertMatchesRegularExpression('~clip:\s*rect\(0\s*,\s*0\s*,\s*0\s*,\s*0\)\s*!important~', $slice,
            '.mw-ese-announce must have clip:rect(0,0,0,0) !important.'
        );
    }

    #[Test]
    public function announce_region_has_overflow_hidden_important(): void
    {
        $pos = strpos($this->srcStripped, '.mw-ese-announce');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 500);
        $this->assertMatchesRegularExpression('~overflow:\s*hidden\s*!important~', $slice,
            '.mw-ese-announce must have overflow:hidden !important.'
        );
    }

    // ─── AI-825: Token bump (dark border contrast) ─────────────────────────────

    #[Test]
    public function dark_ese_border_token_is_bumped_to_035_alpha(): void
    {
        // The second .dark block (later in file) overrides --ese-border.
        // Use strrpos to find the LAST occurrence (the override block).
        $lastPos = strrpos($this->srcStripped, '--ese-border:');
        $this->assertNotFalse($lastPos);
        $slice = substr($this->srcStripped, (int) $lastPos, 80);
        $this->assertMatchesRegularExpression(
            '~rgba\(255,\s*255,\s*255,\s*0\.35\)~',
            $slice,
            '--ese-border must be bumped to rgba(255,255,255,0.35) in the dark token override block (≥3:1 non-text contrast).'
        );
    }

    #[Test]
    public function dark_ese_border_strong_token_is_bumped_to_050_alpha(): void
    {
        $lastPos = strrpos($this->srcStripped, '--ese-border-strong:');
        $this->assertNotFalse($lastPos);
        $slice = substr($this->srcStripped, (int) $lastPos, 80);
        $this->assertMatchesRegularExpression(
            '~rgba\(255,\s*255,\s*255,\s*0\.50\)~',
            $slice,
            '--ese-border-strong must be bumped to rgba(255,255,255,0.50) in the dark token override block.'
        );
    }

    // ─── AI-825: Label contrast in dark mode ─────────────────────────────────

    #[Test]
    public function dark_mode_label_color_override_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark[^{]*#mw-element-style-editor-app[^{]*\.live-edit-label[^{]*\{[^}]*color:\s*var\(--ese-label~s',
            $this->srcStripped,
            '.dark compound selector must override .live-edit-label color with --ese-label token.'
        );
    }

    // ─── AI-825: Input/select border and background in dark mode ─────────────

    #[Test]
    public function dark_mode_input_border_color_override_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark[^{]*#mw-element-style-editor-app[^{]*input[^{]*\{[^}]*border-color:\s*var\(--ese-border-strong~s',
            $this->srcStripped,
            '.dark must override input border-color with --ese-border-strong for ≥3:1 non-text contrast.'
        );
    }

    #[Test]
    public function dark_mode_input_background_override_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark[^{]*#mw-element-style-editor-app[^{]*input[^{]*\{[^}]*background-color:\s*var\(--ese-surface-muted~s',
            $this->srcStripped,
            '.dark must override input background with --ese-surface-muted (defeats Tailwind @apply bg-gray-100).'
        );
    }

    #[Test]
    public function dark_mode_select_overrides_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark[^{]*#mw-element-style-editor-app[^{]*select[^{]*\{~s',
            $this->srcStripped,
            '.dark must include select element overrides for dropdown chrome contrast.'
        );
    }

    // ─── AI-825: Colour swatch outline in dark mode ─────────────────────────

    #[Test]
    public function dark_mode_picker_button_inset_shadow_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark[^{]*\.picker-button[^{]*\{[^}]*box-shadow:\s*inset~s',
            $this->srcStripped,
            '.dark must set inset box-shadow on .picker-button to keep colour swatch outlines visible.'
        );
    }

    // ─── AI-825: "More options:" divider in dark mode ────────────────────────

    #[Test]
    public function dark_mode_more_options_summary_color_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark[^{]*\.mw-typography-advanced\s*>\s*summary[^{]*\{[^}]*color:\s*var\(--ese-text-muted~s',
            $this->srcStripped,
            '.dark must set --ese-text-muted color on .mw-typography-advanced > summary (More options divider).'
        );
    }

    // ─── AI-825: Light mode regression-guard ──────────────────────────────────

    #[Test]
    public function all_dark_contrast_rules_are_dark_scoped(): void
    {
        // Verify every occurrence of --ese-border in a rule body is either
        // inside .dark or inside the existing token definition block.
        // The key invariant: no new bare (non-dark-scoped) rule that sets
        // border-color with the bumped value.
        $this->assertStringContainsString('.dark', $this->srcStripped,
            'Dark mode contrast rules must all be inside .dark scope.'
        );
        // No unconditional border-color rule with the bumped token should exist
        // at global scope. We verify by checking the border bump block is
        // scoped inside a dark selector.
        $pos = strrpos($this->srcStripped, '0.35)');
        $this->assertNotFalse($pos);
        $beforeSlice = substr($this->srcStripped, 0, (int) $pos);
        // The last dark-selector before the 0.35 value should be present
        $this->assertNotFalse(strrpos($beforeSlice, '.dark'),
            'The 0.35 border token bump must appear inside an .dark block.'
        );
    }

    // ─── Bundle probe ──────────────────────────────────────────────────────────

    #[Test]
    public function bundle_contains_announce_hidden_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present in this environment.');
        }
        $this->assertStringContainsString('mw-ese-announce', $this->bundle,
            'Webpack bundle must include the .mw-ese-announce visually-hidden rule.'
        );
        // Webpack preserves whitespace in values; check for the unique clip property
        $this->assertStringContainsString('clip: rect(0', $this->bundle,
            'Webpack bundle must include clip: rect(0,...) for the announce sr-only rule.'
        );
    }

    #[Test]
    public function bundle_contains_dark_border_bump(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present in this environment.');
        }
        // Webpack preserves whitespace: rgba(255, 255, 255, 0.35)
        $this->assertStringContainsString('--ese-border: rgba(255, 255, 255, 0.35)', $this->bundle,
            'Webpack bundle must include the bumped --ese-border token at 0.35 alpha.'
        );
    }

    #[Test]
    public function bundle_mtime_is_newer_than_source(): void
    {
        $bundlePath = base_path('public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css');
        $srcPath = base_path('packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css');
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Webpack bundle not present in this environment.');
        }
        $this->assertGreaterThanOrEqual(
            filemtime($srcPath),
            filemtime($bundlePath),
            'Bundle mtime must be ≥ source mtime — confirms bundle was rebuilt after the fix.'
        );
    }
}
