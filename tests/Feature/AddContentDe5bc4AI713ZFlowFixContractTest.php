<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-de5bc4 / AI-713 (High) — Two-group layout
 * renders inside outer 2-col grid (Z-flow regression).
 *
 * Designer drunk-designer audit caught a layout regression on the
 * AI-692 two-group rework: Filament's default `.fi-grid` form
 * wrapper inside the picker modal was forcing the picker view
 * into a 2-column grid at 1440 px. Result at desktop:
 *   col1: Search input
 *   col2: "On this page" header + primary "Add a block" card
 *         (same vertical band as the search input)
 *   col1: "New content" header + 5-card 2-col grid (with
 *         "Category" orphaning in the last row)
 * Reading path was Z, not F. Primary card same width as a
 * secondary card → zero hierarchy. Mobile unaffected because
 * the 1-col stack hides the conflict.
 *
 * Fix (designer spec § A2 + § A3): delete the outer 2-column
 * grid wrapper. One vertical column flow:
 *   1. Full-width search row
 *   2. ON THIS PAGE header
 *   3. Full-width "Add a block" card (≥ 1.5× height of secondary,
 *      --ese-accent-soft bg)
 *   4. NEW CONTENT header
 *   5. 3-col secondary grid (was effectively 2-col only because
 *      the outer grid ate one column)
 *
 * Implementation: rules in
 * `packages/microweber-filament-theme/resources/assets/css/
 * microweber/live-edit-classes.css` (Webpack-bundled, loaded on
 * /admin/live-edit). Scoped via `.mw-content-picker-modal`
 * (extraModalWindowAttributes on the addContentAction).
 *
 * Lesson from task-bc28fd applied: source-level test + runtime
 * bundle-probe test. The Filament `.fi-grid` reset rule had
 * originally been in `live-edit-module-settings.blade.php` and
 * never fired on the live build; the same load-path bug is
 * sidestepped here by hosting in live-edit-classes.css from
 * the start.
 */
class AddContentDe5bc4AI713ZFlowFixContractTest extends TestCase
{
    private string $css;
    private ?string $bundle = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        ));
        $bundlePath = base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        );
        if (file_exists($bundlePath)) {
            $this->bundle = (string) file_get_contents($bundlePath);
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — source-level: outer grid collapse
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function source_resets_filament_outer_grid_to_single_column(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.fi-grid[\s\S]{0,200}grid-template-columns:\s*minmax\(0,\s*1fr\)\s*!important/',
            $this->css,
            "`.mw-content-picker-modal .fi-grid` must reset grid-template-columns to minmax(0, 1fr) !important — the Z-flow root cause was Filament's default .fi-grid forcing 2-col."
        );
    }

    #[Test]
    public function source_scope_covers_canonical_fi_grid_variants(): void
    {
        // The Filament form grid surfaces under .fi-grid,
        // .fi-sc.fi-grid, .fi-fo-component-ctn, .fi-fo-field-content-col.
        // All four must be in the AI-713 reset selector list so the
        // single-column collapse holds regardless of which Filament
        // wrapper class the picker modal's form schema renders.
        $start = strpos($this->css, 'AI-713 (task-2026-05-16-de5bc4)');
        $this->assertNotFalse($start, 'AI-713 task-id marker must be present.');
        $docblockEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($docblockEnd);
        $slice = substr($this->css, $docblockEnd + 2);
        foreach ([
            '.mw-content-picker-modal .fi-grid',
            '.mw-content-picker-modal .fi-sc.fi-grid',
            '.mw-content-picker-modal .fi-fo-component-ctn',
            '.mw-content-picker-modal .fi-fo-field-content-col',
        ] as $expected) {
            $this->assertStringContainsString(
                $expected,
                $slice,
                "AI-713 outer-grid-collapse selector list must include `{$expected}` so all Filament form-grid variants in the picker modal collapse to a single column."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — primary card visual hierarchy
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function primary_card_uses_accent_soft_background(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.mw-add-content-group--primary\s+\.mw-add-content-modal-action-wrapper\s*\{[^}]*background-color:\s*var\(--ese-accent-soft/s',
            $this->css,
            'Primary "Add a block" card must use var(--ese-accent-soft) background per spec §A3.'
        );
    }

    #[Test]
    public function primary_card_uses_accent_border(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.mw-add-content-group--primary\s+\.mw-add-content-modal-action-wrapper\s*\{[^}]*border-color:\s*var\(--ese-accent/s',
            $this->css,
            'Primary card must use var(--ese-accent) border so it reads as the primary action.'
        );
    }

    #[Test]
    public function primary_card_min_height_is_at_least_1_5x_secondary(): void
    {
        // Spec: "≥ 1.5× height of secondary". Implemented via
        // `min-height: calc(var(--space-xl, 42px) * 1.5)` —
        // 1.5× the secondary card's natural --space-xl baseline.
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.mw-add-content-group--primary\s+\.mw-add-content-modal-action-wrapper\s*\{[^}]*min-height:\s*calc\(\s*var\(--space-xl,\s*42px\)\s*\*\s*1\.5\s*\)/s',
            $this->css,
            'Primary card must lock min-height: calc(var(--space-xl) * 1.5) per spec "≥ 1.5× height of secondary".'
        );
    }

    #[Test]
    public function primary_card_hover_preserves_accent_treatment(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.mw-add-content-group--primary\s+\.mw-add-content-modal-action-wrapper:hover\s*\{[^}]*background-color:\s*var\(--ese-accent-soft/s',
            $this->css,
            'Primary card :hover must keep an accent-soft background (slightly stronger alpha) — primary should always read as primary.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — runtime-probe (built bundle)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_contains_outer_grid_collapse_rule(): void
    {
        if ($this->bundle === null) {
            $this->markTestSkipped('Served bundle absent — run `cd packages/microweber-filament-theme && npm run build` first.');
        }
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal[^{]*\.fi-grid[\s\S]{0,300}grid-template-columns:\s*minmax\(0,\s*1fr\)/',
            $this->bundle,
            'Served bundle must contain the AI-713 outer-grid-collapse rule — task-bc28fd runtime-probe lesson reapplied.'
        );
    }

    #[Test]
    public function bundle_contains_primary_card_accent_soft_bg(): void
    {
        if ($this->bundle === null) {
            $this->markTestSkipped('Served bundle absent.');
        }
        $this->assertMatchesRegularExpression(
            '/\.mw-add-content-group--primary[\s\S]{0,300}background-color:\s*var\(--ese-accent-soft/',
            $this->bundle,
            'Served bundle must contain the primary card --ese-accent-soft background rule.'
        );
    }

    #[Test]
    public function bundle_mtime_at_least_source_mtime(): void
    {
        $bundlePath = base_path('public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css');
        $sourcePath = base_path('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css');
        if (! file_exists($bundlePath)) {
            $this->markTestSkipped('Served bundle absent.');
        }
        $this->assertGreaterThanOrEqual(
            filemtime($sourcePath),
            filemtime($bundlePath),
            'Served bundle mtime must be ≥ source mtime — run `npm run build` to refresh after editing live-edit-classes.css.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-16-de5bc4',
            $this->css,
            'AI-713 task-id marker must be present in live-edit-classes.css for audit grep.'
        );
        $this->assertStringContainsString('AI-713', $this->css);
    }

    #[Test]
    public function lessons_lineage_cited_in_docblock(): void
    {
        // The AI-713 docblock must cite the task-bc28fd companion
        // lesson (source-level test pin source presence, NOT
        // runtime delivery) so future agents reading the slice
        // pick up the runtime-probe + correct-host-file rule.
        $this->assertStringContainsString(
            'task-bc28fd',
            $this->css,
            'AI-713 docblock must cite task-bc28fd (the load-path lesson) so future agents understand why this rule lives in live-edit-classes.css and not in live-edit-module-settings.blade.php.'
        );
    }
}
