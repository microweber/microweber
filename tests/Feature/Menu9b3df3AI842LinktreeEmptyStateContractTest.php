<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-9b3df3 / AI-842 — Menu linktree empty-state branch.
 * Jira: https://microweber.atlassian.net/browse/AI-842
 *
 * AI-808-family prong-2 hit (legacy-Blade audit class diagnostic) +
 * AI-809 sibling (the 6 Menu templates that gained the lnotif empty-
 * state branch with e($menu_name) escape).
 *
 * Pre-fix Modules/Menu/resources/views/templates/linktree.blade.php
 * carried @if ($mt != false) {!! $mt !!} @endif with NO @else branch.
 * When $mt === false (empty menu), the template rendered zero output —
 * editors got no signal that the configured menu was empty. Designer's
 * Round 13.3 closeout STATUS recon surfaced linktree as the only Menu
 * skin without an empty-state branch (the other 7 templates: default +
 * simple + small + skin-1 + navbar + images all carry the AI-809
 * lnotif pattern).
 *
 * Post-fix mirrors the AI-809 lnotif pattern across all sibling Menu
 * templates rather than the AI-808 mw-canvas-empty-state shape —
 * consistency across all 7 Menu templates wins over introducing a
 * second empty-state shape in one module family (designer Round 13.3
 * recommendation).
 *
 * Defence-in-depth: e($menu_name) carries forward the AI-809 lineage
 * (admin-controllable menu name MUST be escaped to prevent admin-to-
 * admin XSS via lnotif; lnotif is editmode-gated so no frontend leak,
 * but admin-to-admin defence still applies).
 *
 * Two-layer defence applied for the contract test (17+ session-
 * recurrences of selector-self-match guard): Layer 1 = preg_match_all
 * count-comparison shape (designer-validated post-AI-809 as cleaner
 * than slice-bounding); Layer 2 = source-side word-form prose in
 * docblock (no literal raw-$menu_name interpolation pattern reproduced
 * in this test file's prose).
 */
class Menu9b3df3AI842LinktreeEmptyStateContractTest extends TestCase
{
    private const LINKTREE = 'Modules/Menu/resources/views/templates/linktree.blade.php';
    private const SIBLING_AI809_TEMPLATES = [
        'Modules/Menu/resources/views/templates/default.blade.php',
        'Modules/Menu/resources/views/templates/simple.blade.php',
        'Modules/Menu/resources/views/templates/small.blade.php',
        'Modules/Menu/resources/views/templates/skin-1.blade.php',
        'Modules/Menu/resources/views/templates/navbar.blade.php',
        'Modules/Menu/resources/views/templates/images.blade.php',
    ];

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — @else empty-state branch present + mirrors AI-809 shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function linktree_carries_else_branch_in_mt_conditional(): void
    {
        $source = $this->read(self::LINKTREE);

        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$mt\s*!=\s*false\s*\)[\s\S]*?@else[\s\S]*?@endif/',
            $source,
            'AI-842: linktree.blade.php must carry @if($mt != false) ... @else ... @endif. Pre-fix the @else branch was missing — empty menus rendered zero output.'
        );
    }

    #[Test]
    public function linktree_else_branch_emits_lnotif_with_e_escape(): void
    {
        $source = $this->read(self::LINKTREE);

        // Layer 1 count-comparison: pin the EXACT shape mirroring AI-809.
        // No slice needed — preg_match_all on the complete pattern.
        $this->assertMatchesRegularExpression(
            "/\\{!!\\s*lnotif\\(\"There are no items in the menu <b>\"\\s*\\.\\s*e\\(\\\$menu_name\\)\\s*\\.\\s*'<\\/b>'\\s*\\)\\s*!!\\}/",
            $source,
            'AI-842: @else branch must emit `{!! lnotif("There are no items in the menu <b>" . e($menu_name) . \'</b>\') !!}` — mirrors AI-809 sibling shape verbatim per designer Round 13.3 dispatch.'
        );
    }

    #[Test]
    public function linktree_e_menu_name_wrap_present(): void
    {
        $source = $this->read(self::LINKTREE);

        $this->assertStringContainsString(
            'e($menu_name)',
            $source,
            'AI-842: AI-809 e($menu_name) defence-in-depth wrap MUST be present — admin-controllable menu name escapes admin-to-admin XSS via lnotif.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Pre-existing @if-mt-true branch preserved (no regression)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function linktree_preserves_mt_true_branch(): void
    {
        $source = $this->read(self::LINKTREE);

        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$mt\s*!=\s*false\s*\)\s*\R\s*\{!!\s*\$mt\s*!!\}/',
            $source,
            'AI-842: pre-existing `@if ($mt != false) {!! $mt !!}` branch must be preserved (renders the menu HTML when $mt is non-false).'
        );
    }

    #[Test]
    public function linktree_preserves_menu_filter_setup(): void
    {
        $source = $this->read(self::LINKTREE);

        $this->assertStringContainsString(
            "\$menu_filter['ul_class'] = 'linktree-nav';",
            $source,
            'AI-842: linktree menu_filter setup must be preserved (unrelated to the empty-state fix).'
        );
        $this->assertStringContainsString(
            '$mt = menu_tree($menu_filter);',
            $source,
            'AI-842: $mt = menu_tree($menu_filter) call must be preserved.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Cross-template consistency: all 7 Menu templates carry
    //          the lnotif empty-state pattern with e($menu_name) escape
    // ─────────────────────────────────────────────────────────────────────

    /**
     * @return array<string, array{0: string}>
     */
    public static function ai809SiblingTemplateProvider(): array
    {
        return [
            'default.blade.php' => ['Modules/Menu/resources/views/templates/default.blade.php'],
            'simple.blade.php' => ['Modules/Menu/resources/views/templates/simple.blade.php'],
            'small.blade.php' => ['Modules/Menu/resources/views/templates/small.blade.php'],
            'skin-1.blade.php' => ['Modules/Menu/resources/views/templates/skin-1.blade.php'],
            'navbar.blade.php' => ['Modules/Menu/resources/views/templates/navbar.blade.php'],
            'images.blade.php' => ['Modules/Menu/resources/views/templates/images.blade.php'],
        ];
    }

    #[Test]
    #[DataProvider('ai809SiblingTemplateProvider')]
    public function ai809_sibling_templates_carry_e_menu_name_wrap(string $relativePath): void
    {
        // Pin-evolution 2026-05-17 (task-2026-05-17-4f4f83 / AI-852):
        // the 6 AI-809 sibling templates were migrated from the
        // lnotif("...$menu_name...") empty-state shape to canonical
        // .mw-canvas-empty-state__cta chrome with fixed admin_url
        // ('settings/menus') CTA. AI-852 supersedes the AI-809 fix
        // by removing the $menu_name interpolation entirely. The
        // cross-template consistency contract evolves: every Menu
        // skin MUST carry EITHER the legacy AI-809 lnotif+e($menu_name)
        // shape OR the new AI-852 .mw-canvas-empty-state__cta shape.
        // Linktree itself still uses lnotif (covered by AI-842 fix);
        // future Menu skins may use either route.
        $source = $this->read($relativePath);

        $hasLegacyShape = (bool) preg_match('/e\(\s*\$menu_name\s*\)/', $source)
            && str_contains($source, 'lnotif');
        $hasCanonicalShape = str_contains($source, 'mw-canvas-empty-state__cta')
            && str_contains($source, 'data-mw-ai780-content-type="menu"');

        $this->assertTrue(
            $hasLegacyShape || $hasCanonicalShape,
            "AI-842 cross-template consistency: AI-809 sibling {$relativePath} must carry EITHER the legacy lnotif+e(\$menu_name) shape (pre-AI-852) OR the canonical .mw-canvas-empty-state__cta shape (post-AI-852)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Layer 1 alternative shape (count-comparison via
    //          preg_match_all): no raw $menu_name interpolation remains
    //          in linktree post-fix (would defeat the e() wrap)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai842_no_raw_menu_name_interpolation_in_linktree(): void
    {
        $source = $this->read(self::LINKTREE);

        // Layer 1 alternative (designer-validated post-AI-809): count-
        // comparison shape. Robust against any nesting depth.
        $rawMatches = [];
        preg_match_all('/\.\s*\$menu_name\s*\./', $source, $rawMatches);
        $escapedMatches = [];
        preg_match_all('/\.\s*e\(\$menu_name\)\s*\./', $source, $escapedMatches);

        $this->assertSame(
            0,
            count($rawMatches[0]),
            'AI-842 Layer 1 (count-comparison): zero raw `. $menu_name .` interpolations must remain in linktree.blade.php — every $menu_name reference MUST go through e() for admin-to-admin XSS defence-in-depth.'
        );
        $this->assertGreaterThanOrEqual(
            1,
            count($escapedMatches[0]),
            'AI-842 Layer 1 (count-comparison): at least one `. e($menu_name) .` reference MUST be present in linktree.blade.php (the @else branch lnotif).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Task-id markers + AI-808 + AI-809 lineage citation
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_linktree(): void
    {
        $source = $this->read(self::LINKTREE);

        $this->assertStringContainsString(
            'task-2026-05-17-9b3df3',
            $source,
            'AI-842: linktree.blade.php docblock must carry the task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-842',
            $source,
            'AI-842: linktree.blade.php docblock must carry the AI-842 ticket marker.'
        );
    }

    #[Test]
    public function ai808_and_ai809_lineage_citations_present(): void
    {
        $source = $this->read(self::LINKTREE);

        $this->assertStringContainsString(
            'AI-808',
            $source,
            'AI-842: linktree.blade.php docblock must cite AI-808 lineage (empty-state family) per designer Round 13.3 closeout.'
        );
        $this->assertStringContainsString(
            'AI-809',
            $source,
            'AI-842: linktree.blade.php docblock must cite AI-809 lineage (lnotif + e($menu_name) sibling pattern from the 6 Menu templates).'
        );
    }
}
