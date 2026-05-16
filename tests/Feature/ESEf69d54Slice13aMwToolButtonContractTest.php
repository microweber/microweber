<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-f69d54 — ESE Slice 1.3a / AI-684 / F1.1
 * MwToolButton + MwSegmented primitives.
 *
 * Spec ref: designer-agent/output/ese-design-spec-2026-05-16.md
 *           §4.5 (MwToolButton) + §4.3 (MwSegmented).
 *
 * Slice 1.3 was the heaviest planned Phase-1 slice — primitives +
 * ~14-component consumer migration in one shot. Sub-sliced to
 * 1.3a (primitives + one proof-of-pattern consumer migration —
 * Typography's italic-toggle) and 1.3b (remaining ~13 consumer
 * migrations: Spacing presets, Border/Background/Position align
 * rows, etc.). 1.3b is the next dispatch.
 *
 * Variants per spec §4.5:
 *   .mw-tool-btn           — default: 24x24 ghost icon button
 *   .mw-tool-btn--toggle   — 32x32, accent-soft bg when .is-active
 *   .mw-tool-btn--preset   — 32x28 label-sized, accent-soft when .is-active
 *   (swatch variant deferred to slice 1.4 MwField per designer note)
 *
 * MwSegmented per spec §4.3 — full-width strip, internal dividers
 * only, active cell uses --ese-accent-soft + inset 1px accent
 * box-shadow.
 *
 * One consumer migrated in 1.3a:
 *   Typography italic-toggle: .mw-italic-toggle → adds
 *   .mw-tool-btn.mw-tool-btn--toggle classes; new .is-active class
 *   alongside the existing .active class so the new primitive
 *   selectors match without breaking the back-compat .mw-italic-
 *   toggle.active CSS that AI-690 already migrated.
 */
class ESEf69d54Slice13aMwToolButtonContractTest extends TestCase
{
    private string $src;
    private string $built;
    private string $typographyVue;
    private string $typographyBuiltJs;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css'
        ));
        $this->built = (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        ));
        $this->typographyVue = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorTypography.vue'
        ));
        $this->typographyBuiltJs = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/dist/build/element-style-editor-app.js'
        ));
    }

    #[Test]
    public function default_variant_geometry_and_token_consumption(): void
    {
        // Default variant — 24x24 hit, ghost, var(--font-control) size
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-tool-btn\s*\{[^}]*min-width:\s*24px[^}]*min-height:\s*24px/s',
            $this->src,
            '.mw-tool-btn default variant must be 24x24 minimum (spec §4.5).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-tool-btn\s*\{[^}]*border-radius:\s*var\(--radius-sm\)/s',
            $this->src
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-tool-btn\s*\{[^}]*font-size:\s*var\(--font-control\)/s',
            $this->src
        );
    }

    #[Test]
    public function default_variant_hover_uses_surface_hover_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-btn:hover\s*\{[^}]*background:\s*var\(--ese-surface-hover\)/s',
            $this->src
        );
    }

    #[Test]
    public function default_variant_focus_visible_uses_accent_outline(): void
    {
        // Spec §5 interaction matrix — 2px accent focus ring.
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-btn:focus-visible\s*\{[^}]*outline:\s*2px\s+solid\s+var\(--ese-accent\)/s',
            $this->src
        );
    }

    #[Test]
    public function toggle_variant_geometry_and_accent_when_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-btn\.mw-tool-btn--toggle\s*\{[^}]*min-width:\s*32px[^}]*min-height:\s*32px[^}]*border-color:\s*var\(--ese-border-strong\)/s',
            $this->src,
            'Toggle variant: 32x32 min size, --ese-border-strong default border.'
        );
        // Active state: accent-soft bg + accent text per spec §4.5
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-btn--toggle\.is-active[^{]*\{[^}]*background:\s*var\(--ese-accent-soft\)[^}]*border-color:\s*var\(--ese-accent\)[^}]*color:\s*var\(--ese-accent\)/s',
            $this->src
        );
        // Also matches via aria-pressed="true" for native button toggle pattern
        $this->assertStringContainsString(
            '.mw-tool-btn--toggle[aria-pressed="true"]',
            $this->src,
            'Toggle variant must also activate via aria-pressed="true" for native button toggles.'
        );
    }

    #[Test]
    public function preset_variant_geometry_and_label_typography(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-tool-btn\.mw-tool-btn--preset\s*\{[^}]*min-width:\s*32px[^}]*min-height:\s*28px[^}]*font-size:\s*var\(--font-label\)[^}]*font-weight:\s*var\(--weight-label\)/s',
            $this->src,
            'Preset variant: 32x28, label-sized typography (spec §4.5).'
        );
    }

    #[Test]
    public function segmented_strip_and_cell_geometry(): void
    {
        // Outer strip
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-segmented\s*\{[^}]*display:\s*inline-flex[^}]*border:\s*var\(--border-xs\)\s+solid\s+var\(--ese-border-strong\)/s',
            $this->src
        );
        // Cell
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-segmented__cell\s*\{[^}]*flex:\s*1\s+1\s+0[^}]*min-height:\s*32px[^}]*border-inline-start:\s*1px\s+solid\s+var\(--ese-border\)/s',
            $this->src
        );
        // First-child has no inline-start border (no outer-edge divider per spec §4.3)
        $this->assertMatchesRegularExpression(
            '/\.mw-segmented__cell:first-child\s*\{[^}]*border-inline-start:\s*0/s',
            $this->src,
            'First segmented cell must NOT carry an inner divider (outer edge per spec §4.3).'
        );
    }

    #[Test]
    public function segmented_active_cell_uses_accent_soft_and_inset_ring(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-segmented__cell\.is-active[^{]*\{[^}]*background:\s*var\(--ese-accent-soft\)[^}]*color:\s*var\(--ese-accent\)[^}]*box-shadow:\s*inset\s+0\s+0\s+0\s+1px\s+var\(--ese-accent\)/s',
            $this->src,
            'Active segmented cell: accent-soft bg + accent text + inset 1px accent ring (spec §4.3).'
        );
    }

    #[Test]
    public function typography_italic_toggle_migrated_to_primitive(): void
    {
        // Vue template — the button now carries BOTH the legacy
        // .mw-italic-toggle (back-compat) AND the new primitive classes.
        $this->assertMatchesRegularExpression(
            '/class="mw-italic-toggle mw-tool-btn mw-tool-btn--toggle"/',
            $this->typographyVue,
            'Typography italic-toggle must carry both back-compat .mw-italic-toggle AND new .mw-tool-btn.mw-tool-btn--toggle classes.'
        );
        // The :class binding must also add .is-active when fontStyle === italic
        $this->assertMatchesRegularExpression(
            "/'is-active':\\s*fontStyle\\s*===\\s*'italic'/",
            $this->typographyVue,
            'Typography italic-toggle must add .is-active when fontStyle === italic.'
        );
    }

    #[Test]
    public function built_bundle_carries_primitives_and_migration(): void
    {
        // CSS bundle has the new rules
        $this->assertStringContainsString('mw-tool-btn--toggle', $this->built,
            'Filament-theme built CSS must carry .mw-tool-btn--toggle rule.');
        $this->assertStringContainsString('mw-segmented__cell', $this->built);

        // Frontend.js built ESE chunk has the migrated Typography template
        $this->assertStringContainsString('mw-tool-btn--toggle', $this->typographyBuiltJs,
            'ESE built JS bundle must carry the migrated italic-toggle template.');
    }

    #[Test]
    public function task_id_marker_present_in_css_and_vue(): void
    {
        $this->assertStringContainsString('task-2026-05-16-f69d54', $this->src);
        $this->assertStringContainsString('task-2026-05-16-f69d54', $this->typographyVue);
    }
}
