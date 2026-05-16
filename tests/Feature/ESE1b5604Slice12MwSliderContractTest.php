<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-1b5604 — ESE Slice 1.2 / AI-689 / F1.6 MwSlider.
 *
 * Spec §4.2 locks slider geometry at token level so the prior
 * `!important` fortress (six rules guarding against non-existent
 * Vuetify defaults — per the vuetify-slider-in-mw-admin skill,
 * Vuetify CSS isn't actually loaded in the parent admin window)
 * can be deleted.
 *
 * Geometry constants (spec §4.2):
 *   --slider-track-h  2px
 *   --slider-thumb-d  16px
 *   track row height  var(--space-lg) (25.9px)
 *   track bg          var(--ese-track)
 *   filled track      var(--ese-track-fill)
 *   thumb fill        var(--ese-accent)
 *   thumb ring light  1px var(--ese-surface)
 *   thumb ring dark   2px var(--ese-surface-muted)   (N3 per spec v2)
 *
 * Surviving `!important` declarations (intentional, documented):
 *   - .v-slider-thumb positioning — Vuetify writes inline styles
 *     on every drag tick; !important guarantees the inline-fallback
 *     `inset-inline-start: 0%` wins on initial paint.
 *   - hidden input + .form-control-input-range-slider — fight
 *     higher-specificity rules in live-edit-input.css.
 *
 * Visual delta vs prior fortress: thumb fill switches from
 * `currentColor` (theme-dependent text colour) to `--ese-accent`
 * (Bootstrap blue, consistent both themes). Spec §4.2 explicitly
 * requests the blue accent — the active affordance reads as
 * interactive across themes.
 */
class ESE1b5604Slice12MwSliderContractTest extends TestCase
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
    public function slider_declares_geometry_local_tokens(): void
    {
        // The .mw-live-edit-slider-small selector must own the
        // slider-local geometry tokens so descendants can inherit
        // them without polluting :root.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s*\{[^}]*--slider-track-h:\s*2px/s',
            $this->src,
            '.mw-live-edit-slider-small must declare --slider-track-h: 2px (spec §4.2).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s*\{[^}]*--slider-thumb-d:\s*16px/s',
            $this->src,
            '.mw-live-edit-slider-small must declare --slider-thumb-d: 16px (spec §4.2).'
        );
    }

    #[Test]
    public function track_background_uses_ese_track_token_no_important(): void
    {
        // Track background must use the theme-auto-switching token
        // and MUST NOT carry !important (the prior fortress is gone).
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s+\.v-slider-track__background\s*\{[^}]*background:\s*var\(--ese-track\)\s*;/s',
            $this->src,
            'Track background must be `var(--ese-track);` (no !important).'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\.v-slider-track__background\s*\{[^}]*background:\s*currentColor\s*!important/s',
            $this->src,
            'Prior `background: currentColor !important` fortress rule must be deleted.'
        );
    }

    #[Test]
    public function filled_track_uses_track_fill_token_no_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s+\.v-slider-track__fill\s*\{[^}]*background:\s*var\(--ese-track-fill\)\s*;/s',
            $this->src
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\.v-slider-track__fill\s*\{[^}]*opacity:\s*0\.85\s*!important/s',
            $this->src,
            'Prior `opacity: 0.85 !important` rule on filled track must be deleted.'
        );
    }

    #[Test]
    public function thumb_surface_uses_accent_and_token_geometry(): void
    {
        // Thumb fill: --ese-accent (#0d6efd via MwColors::Blue alias)
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s+\.v-slider-thumb__surface\s*\{[^}]*background:\s*var\(--ese-accent\)/s',
            $this->src,
            'Thumb fill must use var(--ese-accent), not currentColor — spec §4.2.'
        );
        // Geometry from --slider-thumb-d
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s+\.v-slider-thumb__surface\s*\{[^}]*width:\s*var\(--slider-thumb-d\)/s',
            $this->src
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s+\.v-slider-thumb__surface\s*\{[^}]*border-radius:\s*var\(--radius-pill\)/s',
            $this->src
        );
    }

    #[Test]
    public function thumb_ring_split_by_theme_per_n3_accept(): void
    {
        // Light default: 1px var(--ese-surface)
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s+\.v-slider-thumb__surface\s*\{[^}]*box-shadow:\s*0\s+0\s+0\s+1px\s+var\(--ese-surface\)/s',
            $this->src,
            'Light-mode default thumb ring: 1px solid var(--ese-surface).'
        );
        // Dark override: 2px var(--ese-surface-muted)
        $this->assertMatchesRegularExpression(
            '/html\.dark[\s\S]*?\.v-slider-thumb__surface,[\s\S]*?\.theme-dark[\s\S]*?\.v-slider-thumb__surface,[\s\S]*?\[data-theme="dark"\][\s\S]*?\.v-slider-thumb__surface\s*\{[^}]*box-shadow:\s*0\s+0\s+0\s+2px\s+var\(--ese-surface-muted\)/s',
            $this->src,
            'Dark-mode thumb ring: 2px solid var(--ese-surface-muted) per N3.'
        );
    }

    #[Test]
    public function track_row_height_uses_space_lg_for_natural_centring(): void
    {
        // The slider container row must be --space-lg tall so the
        // 16px thumb centres naturally without absolute hack math.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s+\.v-slider__container\s*\{[^}]*min-height:\s*var\(--space-lg\)/s',
            $this->src
        );
    }

    #[Test]
    public function thumb_positioning_important_survivors_documented(): void
    {
        // Surviving !important on the .v-slider-thumb positioning
        // rules — these are intentional per the docblock (Vuetify
        // writes inline styles on every drag tick).
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-slider-small\s+\.v-slider-thumb\s*\{[^}]*inset-inline-start:\s*var\(--v-slider-thumb-position[^)]*\)\s*!important/s',
            $this->src,
            '.v-slider-thumb inset-inline-start must keep !important — Vuetify writes inline values.'
        );
    }

    #[Test]
    public function disabled_state_uses_opacity_per_spec_section_5(): void
    {
        // Spec §5 interaction matrix: disabled thumb opacity 0.4,
        // track opacity 0.5.
        $this->assertMatchesRegularExpression(
            '/\[aria-disabled="true"\][^}]*\.v-slider-thumb__surface\s*\{[^}]*opacity:\s*0\.4/s',
            $this->src
        );
        $this->assertMatchesRegularExpression(
            '/\[aria-disabled="true"\][^}]*\.v-slider-track\s*\{[^}]*opacity:\s*0\.5/s',
            $this->src
        );
    }

    #[Test]
    public function fortress_geometry_important_rules_deleted(): void
    {
        // The six explicit `!important` geometry rules that
        // formed the prior fortress must all be deleted.
        // We probe by combination — selector + property + !important
        // on a non-positioning property.
        $deletedPatterns = [
            // background: currentColor !important; on track/fill/thumb
            '/\.v-slider-track__background\s*\{[^}]*background:\s*currentColor\s*!important/s' => 'track background',
            '/\.v-slider-track__fill\s*\{[^}]*background:\s*currentColor\s*!important/s' => 'filled track background',
            '/\.v-slider-thumb__surface\s*\{[^}]*background:\s*currentColor\s*!important/s' => 'thumb background',
            // opacity: 0.30 !important / 0.85 !important
            '/\.v-slider-track__background\s*\{[^}]*opacity:\s*0\.30\s*!important/s' => 'track opacity',
            '/\.v-slider-track__fill\s*\{[^}]*opacity:\s*0\.85\s*!important/s' => 'filled track opacity',
            // 14px hardcoded thumb dimensions (now via --slider-thumb-d 16px token)
            '/\.v-slider-thumb__surface\s*\{[^}]*width:\s*14px\s*!important/s' => 'old 14px thumb width',
        ];
        foreach ($deletedPatterns as $pattern => $label) {
            $this->assertDoesNotMatchRegularExpression(
                $pattern,
                $this->src,
                "Prior fortress rule for {$label} must be deleted (use token geometry)."
            );
        }
    }

    #[Test]
    public function built_bundle_carries_slider_tokens_and_rules(): void
    {
        $this->assertStringContainsString('--slider-track-h', $this->built,
            'Built bundle must carry --slider-track-h (Webpack pipeline integration).');
        $this->assertStringContainsString('--slider-thumb-d', $this->built);
        $this->assertMatchesRegularExpression(
            '/\.v-slider-thumb__surface\s*\{[^}]*var\(--ese-accent\)/s',
            $this->built,
            'Built bundle must carry the thumb-fill → --ese-accent migration.'
        );
    }

    #[Test]
    public function task_id_marker_pinned_for_audit_grep(): void
    {
        $this->assertStringContainsString('task-2026-05-16-1b5604', $this->src);
    }
}
