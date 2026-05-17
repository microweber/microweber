<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-66a21a / AI-849 — frontend /shop family silent-stub elimination.
 * Jira: https://microweber.atlassian.net/browse/AI-849
 *
 * Stage-3 propagation-without-renderer-update closure, 4th instance in
 * the silent-stub family (sibling lineage: AI-755 → AI-795 → AI-837 → this).
 * FIRST instance on the primary commerce surface — every prior instance
 * was on an auxiliary route. P1 severity per designer because /shop is
 * the canonical Google Shopping / social product card landing.
 *
 * Pre-fix /shop, /shop/products, /shop/category, /shop/featured, and
 * /shop/categories all fell through the FrontendController catch-all
 * which detected "shop" as an installed module name, set
 * $page['layout_file'] = 'clean.php', and rendered the Bootstrap
 * template's clean.blade.php with hardcoded fixture markup ("Describe
 * your company", "Call to action", "The Feature Title", "Your Story
 * Should Evolve Over Time", "Pictures In The Sky", "$79 iWork '08")
 * at HTTP 200 OK with no noindex header. Designer's Round 16 silent-
 * stub probe surfaced 5 fixture-text instances per subroute × 5
 * subroutes = 25 visible stub-leak instances on the primary commerce
 * nav target. SEO indexing risk + commerce conversion impact.
 *
 * Fix shape per the AI-795 standing chrome-application checklist for
 * URL fall-through paths (mirrors AI-837 search-stub-elim verbatim):
 *   1. extends active template master with Bootstrap fallback
 *      (AI-757 pattern via $extendsView resolved in controller)
 *   2. semantic chrome container (.mw-frontend-shop)
 *   3. recovery / empty-state context (heading + product grid embed
 *      via <module type="shop" /> + Return home CTA in empty branch)
 *   4. correct HTTP status (200 OK — /shop is a legitimate commerce
 *      surface, not an error) + X-Robots-Tag: noindex ONLY when
 *      catalogue is empty (commerce surfaces with real products MUST
 *      be indexable for SEO; empty-catalogue branch noindexes to avoid
 *      ranking installs for "No products yet" stub copy)
 *   5. pinned by this contract test
 *
 * This test is source-level only — runtime HTTP fetch is blocked by
 * middleware / session state in the test environment, so the assertions
 * pin the source invariants that the live build must carry forward.
 *
 * Slice A scope: minimum-viable chrome to close the stub-renderer
 * defect across all 5 designer-named subroutes. AI-849b follow-up:
 * per-subroute differentiation (/shop/category filters to category
 * slug, /shop/featured filters to featured products, etc.) currently
 * all subroutes render the same chrome which is sufficient to drop
 * the stub leak.
 *
 * Selector-self-match guard UNIFORMITY (post-task-7aa48a default-on
 * protocol): docblock + inline source comments legitimately mention
 * legacy fixture strings + the pre-fix commented-out loadRoutesFrom
 * shape. Absence assertions pre-strip PHP/Blade comments before
 * grepping.
 */
class Shop66a21aAI849ShopStubElimContractTest extends TestCase
{
    private const CONTROLLER = 'Modules/Shop/Http/Controllers/ShopController.php';
    private const ROUTES = 'Modules/Shop/routes/web.php';
    private const PROVIDER = 'Modules/Shop/Providers/ShopServiceProvider.php';
    private const VIEW = 'resources/views/frontend/shop/index.blade.php';
    private const FRONTEND_ROUTES = 'src/MicroweberPackages/Frontend/routes/web.php';

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — ShopController exists + proper handler shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function shop_controller_file_exists(): void
    {
        $this->assertFileExists(
            base_path(self::CONTROLLER),
            'AI-849: Modules/Shop/Http/Controllers/ShopController.php must exist to serve /shop + /shop/{path} routes.'
        );
    }

    #[Test]
    public function shop_controller_index_method_accepts_optional_path(): void
    {
        $source = $this->read(self::CONTROLLER);
        // index(Request $request, ?string $path = null) — the optional
        // $path parameter captures /shop/{path} wildcard segments
        // (products, category, featured, categories per designer's
        // recon; any future subpath via the `.*` route regex).
        $this->assertMatchesRegularExpression(
            '/public\s+function\s+index\s*\(\s*Request\s+\$request\s*,\s*\??string\s+\$path\s*=\s*null\s*\)/',
            $source,
            'AI-849: ShopController::index(Request $request, ?string $path = null) signature must accept the optional $path parameter so the wildcard /shop/{path} route works.'
        );
    }

    #[Test]
    public function shop_controller_emits_noindex_only_when_empty_catalogue(): void
    {
        $source = $this->read(self::CONTROLLER);
        // The noindex header must be conditional on $hasProducts === false
        // (empty catalogue). Commerce surfaces with real catalogue MUST be
        // indexable — unlike AI-837 /search which is always noindex.
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*!\s*\$hasProducts\s*\)\s*\{\s*\$headers\[\'X-Robots-Tag\'\]\s*=\s*\'noindex,\s*nofollow\'/',
            $source,
            'AI-849: ShopController must conditionally emit X-Robots-Tag noindex ONLY when $hasProducts === false (commerce surfaces with real catalogue MUST be indexable for SEO).'
        );
    }

    #[Test]
    public function shop_controller_emits_xfallback_message_header(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertMatchesRegularExpression(
            "/'X-Fallback-Message'\s*=>\s*'shop-landing'/",
            $source,
            'AI-849: ShopController response must carry `X-Fallback-Message: shop-landing` for audit-trail diagnostics (mirrors AI-793 + AI-795 + AI-837 pattern).'
        );
    }

    #[Test]
    public function shop_controller_renders_canonical_view(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertMatchesRegularExpression(
            "/->view\(\s*'frontend\.shop\.index'/",
            $source,
            'AI-849: ShopController must render the `frontend.shop.index` view.'
        );
    }

    #[Test]
    public function shop_controller_resolves_active_template_extends(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertStringContainsString(
            'resolveExtendsView',
            $source,
            'AI-849: ShopController must carry resolveExtendsView() helper (AI-757 active-template-master with Bootstrap fallback pattern).'
        );
        $this->assertStringContainsString(
            'templates.bootstrap::layouts.master',
            $source,
            'AI-849: ShopController fallback to `templates.bootstrap::layouts.master` must be present (active template might not ship its own master).'
        );
    }

    #[Test]
    public function shop_controller_carries_has_any_product_helper(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertStringContainsString(
            'hasAnyProduct',
            $source,
            'AI-849: ShopController must carry hasAnyProduct() helper that drives the noindex-when-empty conditional + the view\'s product-grid vs empty-state branch.'
        );
        // The helper must query content table for content_type=product
        // (mirror of how the Shop module identifies products elsewhere).
        $this->assertStringContainsString(
            "->where('content_type', 'product')",
            $source,
            'AI-849: hasAnyProduct() must query DB::table(\'content\')->where(\'content_type\', \'product\') for the catalogue presence check.'
        );
    }

    #[Test]
    public function shop_controller_wraps_db_query_in_try_catch(): void
    {
        $source = $this->read(self::CONTROLLER);
        // First-boot install state or missing migrations should default
        // to "no products" rather than crashing the route.
        $this->assertMatchesRegularExpression(
            '/try\s*\{[\s\S]*?\}\s*catch\s*\(\s*\\\\?Throwable\s+\$e\s*\)\s*\{[\s\S]*?return\s+false/',
            $source,
            'AI-849: hasAnyProduct() must wrap the DB query in try/catch so missing migrations or first-boot install states default to no-products rather than crashing the route.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Route registration uncommented + wired (mirror AI-837)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function shop_module_root_route_registered(): void
    {
        $source = $this->read(self::ROUTES);
        // Strip PHP block + line comments so docblock prose mentioning
        // the legacy commented shape doesn't false-positive the
        // selector-self-match guard.
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $source);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertMatchesRegularExpression(
            "/Route::get\(\s*'shop'\s*,\s*\[ShopController::class\s*,\s*'index'\]\s*\)/",
            $stripped,
            'AI-849: Modules/Shop/routes/web.php must register `Route::get(\'shop\', [ShopController::class, \'index\'])` for the root /shop URL.'
        );
    }

    #[Test]
    public function shop_module_wildcard_subroute_registered(): void
    {
        $source = $this->read(self::ROUTES);
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $source);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);

        // The wildcard route catches /shop/products, /shop/category,
        // /shop/featured, /shop/categories, and any future subpath
        // per designer's recon (all 5 subroutes resolved to the same
        // FrontendController fallthrough pre-fix).
        $this->assertMatchesRegularExpression(
            "/Route::get\(\s*'shop\/\{path\}'\s*,\s*\[ShopController::class\s*,\s*'index'\]\s*\)/",
            $stripped,
            'AI-849: Modules/Shop/routes/web.php must register `Route::get(\'shop/{path}\', ...)` wildcard for /shop/{path?} subroutes.'
        );
        $this->assertMatchesRegularExpression(
            "/->where\(\s*'path'\s*,\s*'\.\*'\s*\)/",
            $stripped,
            'AI-849: /shop/{path} route must carry `->where(\'path\', \'.*\')` regex so deeply-nested subroutes (/shop/category/foo etc.) also match.'
        );
    }

    #[Test]
    public function shop_module_routes_named_for_url_helper_consumers(): void
    {
        $source = $this->read(self::ROUTES);
        $this->assertStringContainsString(
            "->name('shop.index')",
            $source,
            'AI-849: root /shop route must carry `->name(\'shop.index\')` so `route(\'shop.index\')` consumers (nav links / breadcrumbs / etc.) resolve.'
        );
        $this->assertStringContainsString(
            "->name('shop.subroute')",
            $source,
            'AI-849: wildcard /shop/{path} route must carry `->name(\'shop.subroute\')` for named-route resolution of subpath URLs.'
        );
    }

    #[Test]
    public function shop_service_provider_loads_routes_from_module(): void
    {
        $source = $this->read(self::PROVIDER);
        // Strip PHP block + line comments — the pre-fix shape was the
        // entire loadRoutesFrom call as a single-line comment at :34.
        // Selector-self-match guard UNIFORMITY.
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $source);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertStringContainsString(
            "loadRoutesFrom(module_path(\$this->moduleName, 'routes/web.php'))",
            $stripped,
            'AI-849: ShopServiceProvider::register() must explicitly load module routes — BaseModuleServiceProvider does NOT auto-load module web routes (scaffold/provider.stub:37 leaves the call commented).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — View carries the AI-795 chrome contract
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function shop_view_file_exists(): void
    {
        $this->assertFileExists(
            base_path(self::VIEW),
            'AI-849: resources/views/frontend/shop/index.blade.php must exist.'
        );
    }

    #[Test]
    public function shop_view_extends_active_template_with_bootstrap_fallback(): void
    {
        $source = $this->read(self::VIEW);
        $this->assertMatchesRegularExpression(
            "/@extends\(\s*\\\$extendsView\s*\?\?\s*'templates\.bootstrap::layouts\.master'\s*\)/",
            $source,
            "AI-849: shop view must `@extends(\$extendsView ?? 'templates.bootstrap::layouts.master')` (AI-757 active-template-master with Bootstrap fallback pattern)."
        );
    }

    #[Test]
    public function shop_view_carries_semantic_chrome_container(): void
    {
        $source = $this->read(self::VIEW);
        $this->assertStringContainsString(
            'class="mw-frontend-shop',
            $source,
            'AI-849: shop view must wrap content in a `.mw-frontend-shop` semantic chrome container (per AI-795 chrome-application checklist item 2).'
        );
    }

    #[Test]
    public function shop_view_emits_conditional_noindex_meta_tag(): void
    {
        $source = $this->read(self::VIEW);
        // The view must emit the noindex meta ONLY when $hasProducts is
        // false (mirrors the controller's conditional X-Robots-Tag header).
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*!\s*\$hasProducts\s*\)[\s\S]*?<meta\s+name="robots"\s+content="noindex,nofollow">/i',
            $source,
            'AI-849: shop view must emit `<meta name="robots" content="noindex,nofollow">` ONLY inside `@if (! $hasProducts)` block (empty-catalogue noindex; real-catalogue indexable).'
        );
    }

    #[Test]
    public function shop_view_carries_product_grid_module_embed(): void
    {
        $source = $this->read(self::VIEW);
        // When there ARE products, the view embeds the existing
        // ShopComponent Livewire grid via the Microweber module parser.
        $this->assertMatchesRegularExpression(
            '/parse_modules_html\(\s*\'<module type="shop"\s*\/?>\'\s*\)/',
            $source,
            'AI-849: shop view must embed the existing ShopComponent grid via `parse_modules_html(\'<module type="shop" />\')` when $hasProducts is true.'
        );
    }

    #[Test]
    public function shop_view_carries_empty_state_branch(): void
    {
        $source = $this->read(self::VIEW);
        // Empty-state branch must render a friendly "no products yet"
        // copy + recovery CTA (Return home).
        $this->assertStringContainsString(
            'mw-frontend-shop__empty',
            $source,
            'AI-849: shop view must carry an empty-state branch with `.mw-frontend-shop__empty` semantic container.'
        );
        $this->assertMatchesRegularExpression(
            '~href="\{\{\s*url\(\'/\'\)\s*\}\}"[^>]*class="mw-frontend-shop__cta[^"]*"~',
            $source,
            'AI-849: shop empty-state must include a `Return home` CTA pointing at the site root (recovery context per AI-795 chrome-application checklist item 3).'
        );
    }

    #[Test]
    public function shop_view_does_not_carry_clean_blade_fixture_strings(): void
    {
        $source = $this->read(self::VIEW);
        // Strip Blade `{{-- ... --}}` comments — docblock prose
        // legitimately mentions the legacy fixture strings being closed
        // by this fix. Selector-self-match guard UNIFORMITY.
        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $source);

        $fixtures = [
            'Describe your company',
            'Call to action',
            'The Feature Title',
            'Your Story Should Evolve Over Time',
            'Pictures In The Sky',
            'My title',
            'My text content',
        ];
        foreach ($fixtures as $fixture) {
            $this->assertStringNotContainsString(
                $fixture,
                $stripped,
                "AI-849: shop view must NOT contain the literal fixture string `{$fixture}` from clean.blade.php — these placeholders are exactly what this fix replaces."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Belt-and-braces: catch-all exclusion regex carries `shop`
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function frontend_catchall_exclusion_regex_carries_shop(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        $this->assertMatchesRegularExpression(
            '/->where\(\s*\'slug\'\s*,\s*\'\^\(\?!vendor\|packages\|template\|modules\|css\|storage\|userfiles\|js\|admin\|search\|shop\)/',
            $source,
            'AI-849: frontend catch-all `->where(\'slug\', ...)` regex must exclude `shop` (belt-and-braces: if Shop module is ever disabled, /shop 404s cleanly via Route::fallback() instead of regressing back to the FrontendController stub renderer).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Task-id markers + AI-849 audit-trail discoverability
    // ─────────────────────────────────────────────────────────────────────

    /**
     * @return array<string, array{0: string}>
     */
    public static function fileWithMarker(): array
    {
        return [
            'controller' => ['Modules/Shop/Http/Controllers/ShopController.php'],
            'routes' => ['Modules/Shop/routes/web.php'],
            'provider' => ['Modules/Shop/Providers/ShopServiceProvider.php'],
            'view' => ['resources/views/frontend/shop/index.blade.php'],
            'frontend_routes' => ['src/MicroweberPackages/Frontend/routes/web.php'],
        ];
    }

    #[Test]
    #[DataProvider('fileWithMarker')]
    public function task_id_marker_present_in_modified_files(string $relativePath): void
    {
        $this->assertStringContainsString(
            'task-2026-05-17-66a21a',
            (string) file_get_contents(base_path($relativePath)),
            "AI-849: {$relativePath} must carry the task-id marker for cross-surface audit grep."
        );
        $this->assertStringContainsString(
            'AI-849',
            (string) file_get_contents(base_path($relativePath)),
            "AI-849: {$relativePath} must carry the AI-849 ticket marker."
        );
    }

    #[Test]
    public function controller_docblock_cites_silent_stub_family_lineage(): void
    {
        $source = $this->read(self::CONTROLLER);
        // The docblock must cite the sibling lineage so future audits
        // grep AI-755 / AI-795 / AI-837 / AI-849 and find the connecting
        // ticket chain in one pass.
        foreach (['AI-755', 'AI-795', 'AI-837'] as $sibling) {
            $this->assertStringContainsString(
                $sibling,
                $source,
                "AI-849: ShopController docblock must cite sibling lineage marker `{$sibling}` (silent-stub family chain)."
            );
        }
    }

    #[Test]
    public function controller_docblock_cites_ai849b_followup(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertStringContainsString(
            'AI-849b',
            $source,
            'AI-849: ShopController docblock must flag the AI-849b follow-up (per-subroute differentiation — currently every subroute renders the same chrome which is sufficient to drop the stub leak).'
        );
    }
}
