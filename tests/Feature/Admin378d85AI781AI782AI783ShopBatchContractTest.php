<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-378d85 / AI-781 + AI-782 + AI-783 — Shop module batch
 * (Products form, Products list, Orders empty state).
 * Jira:
 *   https://microweber.atlassian.net/browse/AI-781
 *   https://microweber.atlassian.net/browse/AI-782
 *   https://microweber.atlassian.net/browse/AI-783
 *
 * Three small ships bundled because they share the Shop surface
 * group + the AI-784 AdminFixtureGuard umbrella auto-closes the
 * faker-leak parts of AI-781.
 *
 * AI-781 — Products form Title placeholder + Menus faker leak.
 *   - Title placeholder was hard-coded "e.g. My first post" in TWO
 *     sections (compactGeneralInformationSection + the full-form
 *     more-options section). Both now resolve via match($content_type)
 *     so Products see "e.g. Blue cotton t-shirt", Pages see "e.g.
 *     About us", default Posts still "e.g. My first post".
 *   - Menus faker leak ("Commodi Sunt" / "Reprehenderit Voluptate")
 *     auto-closed by AI-784: AdminFixtureGuard::shouldRenderItem
 *     now includes looksLikeFakerLorem() detection. Product extends
 *     Content so the same menusSection options closure runs for both.
 *
 * AI-782 — Products list polish.
 *   - Paginator chrome auto-hides at ≤10 products via ListProducts
 *     override (mirrors the AI-736 Pages-list pattern).
 *   - `In/Out/Low Stock` badge wrap fixed via single
 *     body.fi-panel-admin .fi-badge { white-space: nowrap } CSS rule.
 *   - Row-actions "5+ icon-only actions crowd each row" is DEFERRED —
 *     recon shows actions already collapsed inside ActionGroup with
 *     ⋯ icon (3 actions: live_edit/edit/delete). Designer's screenshot
 *     may have included bulk-action icons or a different page. Flag
 *     for follow-up in SHIP report.
 *
 * AI-783 — Orders empty state CTA + stat-card underline.
 *   - Header CTA label "Create Order" → "+ Add order" matches the
 *     empty-state body CTA. Same primary color + plus icon. Both
 *     CTAs now consistent.
 *   - Stat-card label underline fixed via CSS rule on
 *     .fi-wi-stats-overview-stat-label / -description (text-decoration:
 *     none).
 */
class Admin378d85AI781AI782AI783ShopBatchContractTest extends TestCase
{
    private string $contentResource;
    private string $listProducts;
    private string $listOrders;
    private string $css;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentResource = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource.php'
        ));
        $this->listProducts = (string) file_get_contents(base_path(
            'Modules/Product/Filament/Admin/Resources/ProductResource/Pages/ListProducts.php'
        ));
        $this->listOrders = (string) file_get_contents(base_path(
            'Modules/Order/Filament/Admin/Resources/OrderResource/Pages/ListOrders.php'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $this->bundle = file_exists(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) ? (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — AI-781 type-aware Title placeholders
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai781_title_placeholder_resolves_per_content_type(): void
    {
        // Both placeholder sites must use match() against content_type.
        // Pattern signature (after stripping comments): match($get('content_type'))
        $stripped = preg_replace('!/\*.*?\*/!s', '', $this->contentResource);
        $stripped = preg_replace('!//.*$!m', '', $stripped);
        $matchCount = preg_match_all(
            "/->placeholder\(function \(Schemas\\\\Components\\\\Utilities\\\\Get \\\$get\) \{\s*return match \(\\\$get\('content_type'\)\)/",
            $stripped
        );
        $this->assertGreaterThanOrEqual(
            2,
            $matchCount,
            'At least 2 title placeholders (compactGeneralInformationSection + full-form section) must resolve via match($get(content_type)).'
        );

        // The 3 expected return strings must appear (post-fix labels).
        $this->assertStringContainsString("'page' => 'e.g. About us'", $stripped);
        $this->assertStringContainsString("'product' => 'e.g. Blue cotton t-shirt'", $stripped);
        $this->assertStringContainsString("default => 'e.g. My first post'", $stripped);
    }

    #[Test]
    public function ai781_legacy_hardcoded_placeholder_is_gone_from_both_sites(): void
    {
        // After AI-781 the hard-coded literal `->placeholder('e.g. My first post')`
        // must NOT remain. The default-branch string still appears INSIDE
        // the match() expression (legitimate) but the dedicated
        // ->placeholder('e.g. My first post') call form must be gone.
        $stripped = preg_replace('!/\*.*?\*/!s', '', $this->contentResource);
        $stripped = preg_replace('!//.*$!m', '', $stripped);
        $this->assertDoesNotMatchRegularExpression(
            "/->placeholder\('e\.g\. My first post'\)/",
            $stripped,
            "Hardcoded `->placeholder('e.g. My first post')` call form must be gone after AI-781."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — AI-782 Products list paginated(false) when ≤10
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai782_list_products_overrides_table_with_paginated_guard(): void
    {
        $this->assertStringContainsString('public function table(Table $table): Table', $this->listProducts);
        $this->assertStringContainsString("Content::where('content_type', 'product')", $this->listProducts);
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*\$productCount\s*<=\s*10\s*\)\s*\{\s*\$table->paginated\(false\)/',
            $this->listProducts,
            'ListProducts must call $table->paginated(false) when productCount ≤ 10.'
        );
    }

    #[Test]
    public function ai782_badge_no_wrap_rule_present_in_css_and_bundle(): void
    {
        // Source rule
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-badge\s*\{\s*white-space:\s*nowrap\s*[;}]/',
            $this->css,
            'general-styles.css must declare body.fi-panel-admin .fi-badge { white-space: nowrap } for AI-782.'
        );
        // Served bundle (minification-tolerant)
        if ($this->bundle === '') {
            $this->markTestSkipped('Served theme bundle absent.');
        }
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-badge\s*\{[^}]*white-space:\s*nowrap/',
            $this->bundle,
            'Served theme bundle must carry the AI-782 .fi-badge nowrap rule.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — AI-783 Orders header CTA + stat-card underline
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai783_list_orders_header_cta_label_matches_empty_state(): void
    {
        // Header CTA must use the "+ Add order" label (matches the
        // existing empty-state body CTA in the shared
        // Modules/Content/.../empty-state.blade.php).
        $this->assertMatchesRegularExpression(
            "/Actions\\\\CreateAction::make\(\)\s*->label\('\\+\\s*Add order'\)/",
            $this->listOrders,
            'ListOrders header CTA must use label "+ Add order" to match the empty-state body CTA.'
        );
        // Primary color matches the AI-736 pattern.
        $this->assertStringContainsString("->color('primary')", $this->listOrders);
        // Legacy "Create Order" label must be gone (strip comments first).
        $strippedOrders = preg_replace('!//.*$!m', '', $this->listOrders);
        $this->assertDoesNotMatchRegularExpression(
            "/->label\('Create Order'\)/",
            $strippedOrders,
            'Legacy "Create Order" header label must be gone after AI-783.'
        );
    }

    #[Test]
    public function ai783_stat_card_label_underline_fix_present_in_css_and_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-wi-stats-overview-stat-label[\s\S]*?text-decoration:\s*none/',
            $this->css,
            'general-styles.css must remove text-decoration on stat-overview labels for AI-783.'
        );
        if ($this->bundle === '') {
            $this->markTestSkipped('Served theme bundle absent.');
        }
        $this->assertMatchesRegularExpression(
            '/\.fi-wi-stats-overview-stat-label[\s\S]*?text-decoration:\s*none/',
            $this->bundle,
            'Served theme bundle must carry the AI-783 stat-label text-decoration: none rule.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai_markers_present_across_ships(): void
    {
        $this->assertStringContainsString('task-2026-05-17-378d85', $this->contentResource);
        $this->assertStringContainsString('AI-781', $this->contentResource);
        $this->assertStringContainsString('task-2026-05-17-378d85', $this->listProducts);
        $this->assertStringContainsString('AI-782', $this->listProducts);
        $this->assertStringContainsString('task-2026-05-17-378d85', $this->listOrders);
        $this->assertStringContainsString('AI-783', $this->listOrders);
        $this->assertStringContainsString('task-2026-05-17-378d85', $this->css);
        $this->assertStringContainsString('AI-782', $this->css);
        $this->assertStringContainsString('AI-783', $this->css);
    }
}
