<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-3e91f4 / AI-837 — frontend /search stub elimination.
 * Jira: https://microweber.atlassian.net/browse/AI-837
 *
 * Stage-3 propagation-without-renderer-update closure (sibling lineage:
 * AI-735 admin route propagation / AI-793 admin 404 chrome / AI-795
 * frontend 404 chrome / AI-735b explicit follow-up flagged in
 * src/MicroweberPackages/Frontend/routes/web.php).
 *
 * Pre-fix /search?q=hello fell through the FrontendController catch-all
 * which detected "search" as an installed module name, set
 * $page['layout_file'] = 'clean.php', and rendered the Bootstrap
 * template's clean.blade.php with its hardcoded "My title / My text
 * content" placeholder markup at HTTP 200 with no noindex header.
 * Designer Round 13 audit flagged as High-priority SEO indexing risk
 * + impression-failure on a public search surface.
 *
 * Fix shape per the AI-795 standing chrome-application checklist:
 *   1. extends active template master with Bootstrap fallback
 *      (AI-757 pattern via $extendsView resolved in controller)
 *   2. semantic chrome container (.mw-frontend-search-results)
 *   3. recovery / empty-state context (search input + helper copy
 *      + Return home CTA)
 *   4. correct HTTP status (200 OK) + noindex headers
 *      (X-Robots-Tag + meta robots)
 *   5. pinned by this contract test
 *
 * This test is source-level only — runtime HTTP fetch is blocked by
 * middleware/session state in the test environment, so the assertions
 * pin the source invariants that the live build must carry forward.
 *
 * Slice A scope: minimum-viable chrome to close the stub-renderer
 * defect. AI-837b follow-up: wire SearchComponent Livewire for live
 * results (currently mounts on `keyword` query param, needs `q`
 * support per W3C convention). AI-838 follow-up: head meta for
 * /cart + /checkout (partially covered for /search by Slice A title).
 */
class Search3e91f4AI837SearchStubElimContractTest extends TestCase
{
    private const CONTROLLER = 'Modules/Search/Http/Controllers/SearchController.php';
    private const ROUTES = 'Modules/Search/routes/web.php';
    private const PROVIDER = 'Modules/Search/Providers/SearchServiceProvider.php';
    private const VIEW = 'resources/views/frontend/search/results.blade.php';
    private const FRONTEND_ROUTES = 'src/MicroweberPackages/Frontend/routes/web.php';

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — SearchController exists + proper handler shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function search_controller_file_exists(): void
    {
        $this->assertFileExists(
            base_path(self::CONTROLLER),
            'AI-837: Modules/Search/Http/Controllers/SearchController.php must exist to serve /search route.'
        );
    }

    #[Test]
    public function search_controller_index_method_present(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertMatchesRegularExpression(
            '/public\s+function\s+index\s*\(\s*Request\s+\$request\s*\)/',
            $source,
            'AI-837: SearchController::index(Request $request) handler signature must be present.'
        );
    }

    #[Test]
    public function search_controller_reads_both_q_and_keyword_query_params(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertStringContainsString(
            "\$request->query('q', '')",
            $source,
            "AI-837: SearchController must read the `q` query param (designer-named W3C convention)."
        );
        $this->assertStringContainsString(
            "\$request->query('keyword', '')",
            $source,
            "AI-837: SearchController must also read the `keyword` query param (back-compat with SearchComponent Livewire mount())."
        );
    }

    #[Test]
    public function search_controller_emits_noindex_xrobots_header(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertMatchesRegularExpression(
            '/X-Robots-Tag.*=>.*noindex,\s*nofollow/i',
            $source,
            'AI-837: SearchController response must carry `X-Robots-Tag: noindex, nofollow` — search-result URLs must NOT be indexed by crawlers.'
        );
    }

    #[Test]
    public function search_controller_emits_xfallback_message_header(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertMatchesRegularExpression(
            "/'X-Fallback-Message'\s*=>\s*'search-results'/",
            $source,
            'AI-837: SearchController response must carry `X-Fallback-Message: search-results` for audit-trail diagnostics (mirrors AI-793 + AI-795 pattern).'
        );
    }

    #[Test]
    public function search_controller_renders_canonical_view(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertMatchesRegularExpression(
            "/->view\(\s*'frontend\.search\.results'/",
            $source,
            'AI-837: SearchController must render the `frontend.search.results` view.'
        );
    }

    #[Test]
    public function search_controller_resolves_active_template_extends(): void
    {
        $source = $this->read(self::CONTROLLER);
        $this->assertStringContainsString(
            "resolveExtendsView",
            $source,
            'AI-837: SearchController must carry resolveExtendsView() helper (AI-757 active-template-master with Bootstrap fallback pattern).'
        );
        $this->assertStringContainsString(
            "templates.bootstrap::layouts.master",
            $source,
            'AI-837: SearchController fallback to `templates.bootstrap::layouts.master` must be present (active template might not ship its own master).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Route registration uncommented + wired
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function search_module_route_uncommented_and_targets_controller(): void
    {
        $source = $this->read(self::ROUTES);
        // Strip PHP block + line comments so docblock prose mentioning
        // the legacy commented shape doesn't false-positive the
        // selector-self-match guard family (16+ session-recurrences).
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $source);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertMatchesRegularExpression(
            "/Route::get\(\s*'search'\s*,\s*\[SearchController::class\s*,\s*'index'\]\s*\)/",
            $stripped,
            'AI-837: Modules/Search/routes/web.php must register a real `Route::get(\'search\', [SearchController::class, \'index\'])` mapping (pre-fix this was commented out + the SearchController did not exist).'
        );
    }

    #[Test]
    public function search_module_route_named_for_url_helper_consumers(): void
    {
        $source = $this->read(self::ROUTES);
        $this->assertStringContainsString(
            "->name('search.index')",
            $source,
            'AI-837: /search route must carry `->name(\'search.index\')` so `route(\'search.index\')` consumers (Livewire form action attrs etc.) resolve.'
        );
    }

    #[Test]
    public function search_service_provider_loads_routes_from_module(): void
    {
        $source = $this->read(self::PROVIDER);
        $this->assertStringContainsString(
            "loadRoutesFrom(module_path(\$this->moduleName, 'routes/web.php'))",
            $source,
            'AI-837: SearchServiceProvider::register() must explicitly load module routes — BaseModuleServiceProvider does NOT auto-load module web routes (scaffold/provider.stub:37 leaves the call commented).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — View carries the AI-795 chrome contract
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function results_view_file_exists(): void
    {
        $this->assertFileExists(
            base_path(self::VIEW),
            'AI-837: resources/views/frontend/search/results.blade.php must exist.'
        );
    }

    #[Test]
    public function results_view_extends_active_template_with_bootstrap_fallback(): void
    {
        $source = $this->read(self::VIEW);
        $this->assertMatchesRegularExpression(
            "/@extends\(\s*\\\$extendsView\s*\?\?\s*'templates\.bootstrap::layouts\.master'\s*\)/",
            $source,
            "AI-837: results view must `@extends(\$extendsView ?? 'templates.bootstrap::layouts.master')` (AI-757 active-template-master with Bootstrap fallback pattern)."
        );
    }

    #[Test]
    public function results_view_carries_semantic_chrome_container(): void
    {
        $source = $this->read(self::VIEW);
        $this->assertStringContainsString(
            'class="mw-frontend-search-results',
            $source,
            'AI-837: results view must wrap content in a `.mw-frontend-search-results` semantic chrome container (per AI-795 chrome-application checklist item 2).'
        );
    }

    #[Test]
    public function results_view_emits_noindex_meta_tag(): void
    {
        $source = $this->read(self::VIEW);
        $this->assertMatchesRegularExpression(
            '/<meta\s+name="robots"\s+content="noindex,nofollow">/i',
            $source,
            'AI-837: results view must emit `<meta name="robots" content="noindex,nofollow">` as belt-and-braces alongside the X-Robots-Tag header from the controller.'
        );
    }

    #[Test]
    public function results_view_carries_search_form_with_get_method(): void
    {
        $source = $this->read(self::VIEW);
        $this->assertMatchesRegularExpression(
            '/<form\b[^>]*method="get"[^>]*action="\{\{\s*url\(\'search\'\)\s*\}\}"/',
            $source,
            'AI-837: results view must include a `<form method="get" action="{{ url(\'search\') }}">` so users can refine the query (recovery context per AI-795 checklist item 3).'
        );
        $this->assertMatchesRegularExpression(
            '/<input\b[^>]*\bname="q"/',
            $source,
            'AI-837: results form input must carry `name="q"` (designer-named W3C-canonical query param).'
        );
    }

    #[Test]
    public function results_view_carries_return_home_cta(): void
    {
        $source = $this->read(self::VIEW);
        $this->assertMatchesRegularExpression(
            '~href="\{\{\s*url\(\'/\'\)\s*\}\}"[^>]*class="mw-frontend-search-results__cta"~',
            $source,
            'AI-837: results view must include a `Return home` CTA pointing at the site root (recovery context per AI-795 chrome-application checklist item 3).'
        );
    }

    #[Test]
    public function results_view_does_not_carry_the_clean_blade_placeholder(): void
    {
        $source = $this->read(self::VIEW);
        // Strip Blade `{{-- ... --}}` comments first — docblock prose
        // legitimately mentions the legacy "My title" / "My text
        // content" placeholder text being closed by this fix.
        // Selector-self-match guard family (18+ session-recurrences).
        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $source);

        $this->assertStringNotContainsString(
            '<h2 class="my-md-5 my-3">My title</h2>',
            $stripped,
            'AI-837: results view must NOT contain the literal `<h2>My title</h2>` from clean.blade.php — that placeholder is exactly what this fix replaces.'
        );
        $this->assertStringNotContainsString(
            'My text content.',
            $stripped,
            'AI-837: results view must NOT contain the literal `My text content.` from clean.blade.php — that placeholder is exactly what this fix replaces.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Belt-and-braces: catch-all exclusion regex carries `search`
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function frontend_catchall_exclusion_regex_carries_search(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        $this->assertMatchesRegularExpression(
            '/->where\(\s*\'slug\'\s*,\s*\'\^\(\?!vendor\|packages\|template\|modules\|css\|storage\|userfiles\|js\|admin\|search\)/',
            $source,
            'AI-837: frontend catch-all `->where(\'slug\', ...)` regex must exclude `search` (belt-and-braces: if Search module is ever disabled, /search 404s cleanly via Route::fallback() instead of regressing to the FrontendController stub renderer).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Task-id markers + AI-837 audit-trail discoverability
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_controller(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-17-3e91f4',
            $this->read(self::CONTROLLER),
            'AI-837: SearchController must carry the task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-837',
            $this->read(self::CONTROLLER),
            'AI-837: SearchController must carry the AI-837 ticket marker.'
        );
    }

    #[Test]
    public function task_id_marker_present_in_view(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-17-3e91f4',
            $this->read(self::VIEW),
            'AI-837: results view must carry the task-id marker.'
        );
    }

    #[Test]
    public function task_id_marker_present_in_provider(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-17-3e91f4',
            $this->read(self::PROVIDER),
            'AI-837: SearchServiceProvider must carry the task-id marker near the loadRoutesFrom call.'
        );
    }

    #[Test]
    public function ai837b_followup_flagged_in_view(): void
    {
        $source = $this->read(self::VIEW);
        $this->assertStringContainsString(
            'AI-837b',
            $source,
            'AI-837: results view docblock must flag the AI-837b follow-up (wire SearchComponent Livewire for live results — currently mounts on `keyword` only).'
        );
    }
}
