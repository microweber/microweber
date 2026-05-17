<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-28d21d / AI-850 — /customer surface JS-fragment leak elimination.
 * Jira: https://microweber.atlassian.net/browse/AI-850
 *
 * DIFFERENT defect-family from the silent-stub family (AI-755 → AI-795
 * → AI-837 → AI-849). The silent-stub family applies to URLs that
 * resolve to a module which HAS a real frontend template — the
 * placeholder leak is the clean.blade.php "My title / My text content"
 * fixture text rendered around the (empty) module embed. AI-850's
 * /customer surface is different: the Customer module is BACKEND-ONLY
 * (admin Filament resource + API endpoints; no frontend templates
 * exist at Modules/Customer/resources/views/templates/), so the
 * `<module type="customer" />` embed rendered to NOTHING and the body
 * collapsed to JUST the ApijsScriptTag meta-tag chrome (CSRF fetch
 * wrapper) + empty <title>.
 *
 * Pre-fix shape at /customer (from designer's body sample):
 *   (function () { if (typeof window === "undefined" || !window.fetch) return;
 *     if (window.__mwCsrfFetchWrapped) return; window.__mwCsrfFetchWrapped = true;
 *     ...
 *
 * That's the literal output of ApijsScriptTag.php's `mw-js-csrf-vanilla`
 * <script> tag with nothing else surrounding it. Empty <title>, no
 * HTML chrome, no visible content.
 *
 * Per designer's Slice A hypothesis 3 ("Shouldn't exist → 404 chrome-
 * wrapped per AI-795 pattern"): /customer is NOT a public surface.
 * Admin-side customer management is at /admin/customers (Filament
 * resource); end-customer use is at /profile (Modules/Profile Filament
 * panel id 'profile', path '/profile'). /customer URL is therefore
 * AT MOST a typo target / legacy bookmark.
 *
 * Fix shape (Slice A — 2 surface edits):
 *   (1) Add `customer` to the FrontendController catch-all exclusion
 *       regex at src/MicroweberPackages/Frontend/routes/web.php so
 *       /customer reaches Route::fallback() instead of falling through
 *       to FrontendController's is_installed("customer") branch.
 *   (2) Extend Route::fallback() so non-admin frontend URLs render the
 *       AI-795 chrome-404 view (instead of the original text/plain
 *       response). This covers /customer + future excluded-prefix URLs
 *       that need a styled 404.
 *
 * AI-795 view at resources/views/frontend/errors/404.blade.php is
 * reused as-is — no view edits.
 *
 * Selector-self-match guard UNIFORMITY (post-task-7aa48a default-on
 * protocol): docblock + inline source comments legitimately mention
 * the legacy "customer falls through to clean.blade.php" pre-fix shape
 * + the JS-fragment body sample. Absence assertions pre-strip PHP
 * comments before grepping.
 */
class Customer28d21dAI850CustomerSurfaceContractTest extends TestCase
{
    private const FRONTEND_ROUTES = 'src/MicroweberPackages/Frontend/routes/web.php';
    private const AI795_VIEW = 'resources/views/frontend/errors/404.blade.php';

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Catch-all exclusion regex carries `customer`
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function frontend_catchall_exclusion_regex_carries_customer(): void
    {
        // Anchored on `customer` being IN the exclusion list rather than
        // the exact set of prefixes (future-proof shape; mirrors the AI-837
        // + AI-849 sibling tests pin-evolved by this ship).
        $source = $this->read(self::FRONTEND_ROUTES);
        $this->assertMatchesRegularExpression(
            '/->where\(\s*\'slug\'\s*,\s*\'\^\(\?![^)]*\|customer\b[^)]*\)/',
            $source,
            'AI-850: frontend catch-all `->where(\'slug\', ...)` regex must include `customer` in its exclusion list so /customer reaches Route::fallback() instead of falling through to FrontendController\'s is_installed("customer") + clean.blade.php stub branch.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Route::fallback now renders AI-795 chrome-404 view
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function fallback_renders_ai795_chrome_view_for_non_admin_urls(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        // The fallback must reference the AI-795 view path so excluded-
        // prefix URLs land on a chrome-wrapped 404 instead of text/plain.
        $this->assertMatchesRegularExpression(
            "/->view\(\s*'frontend\.errors\.404'/",
            $source,
            'AI-850: Route::fallback() must render the AI-795 chrome-404 view (`frontend.errors.404`) for non-admin URLs.'
        );
    }

    #[Test]
    public function fallback_resolves_active_template_extends_inline(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        // The fallback must resolve $extendsView inline (AI-757 active-
        // template-master with Bootstrap fallback) — no dependency on
        // FrontendController.
        $this->assertStringContainsString(
            "get_option('current_template', 'template')",
            $source,
            'AI-850: Route::fallback() must resolve the active template inline via get_option(\'current_template\', \'template\') so the AI-795 view extends the correct master.'
        );
        $this->assertStringContainsString(
            'templates.bootstrap::layouts.master',
            $source,
            'AI-850: Route::fallback() must include the Bootstrap fallback (`templates.bootstrap::layouts.master`) for the AI-757 pattern.'
        );
    }

    #[Test]
    public function fallback_emits_noindex_x_robots_header_for_non_admin(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        // The non-admin fallback path must carry X-Robots-Tag: noindex
        // (404 surfaces must not be indexed). The admin branch already
        // does this implicitly via the admin chrome view; this assertion
        // pins the non-admin branch added by AI-850.
        $this->assertMatchesRegularExpression(
            '/X-Robots-Tag.*=>.*noindex,\s*nofollow/i',
            $source,
            'AI-850: Route::fallback() non-admin branch must emit `X-Robots-Tag: noindex, nofollow` (404 surfaces must not be indexed).'
        );
    }

    #[Test]
    public function fallback_emits_xfallback_message_frontend_404(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        $this->assertMatchesRegularExpression(
            "/'X-Fallback-Message'\s*=>\s*'frontend-404'/",
            $source,
            'AI-850: Route::fallback() non-admin branch must carry `X-Fallback-Message: frontend-404` for audit-trail diagnostics (mirrors AI-793 admin-404 + AI-837 search-results).'
        );
    }

    #[Test]
    public function fallback_passes_extends_view_to_ai795_template(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        $this->assertStringContainsString(
            "'extendsView' => \$extendsView",
            $source,
            'AI-850: Route::fallback() must pass `extendsView` to the AI-795 view so it extends the active template master.'
        );
    }

    #[Test]
    public function fallback_passes_requested_url_to_ai795_template(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        // The AI-795 view shows the requested URL in its "Page not found"
        // body. requestedUrl param must be passed through.
        $this->assertMatchesRegularExpression(
            "/'requestedUrl'\s*=>\s*'\/'\s*\.\s*ltrim/",
            $source,
            'AI-850: Route::fallback() must pass a normalised `requestedUrl` param to the AI-795 view so the 404 page shows which URL was attempted.'
        );
    }

    #[Test]
    public function fallback_preserves_view_exists_guard(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        // First-install / template-removal edge case: if the AI-795 view
        // is missing for any reason, the fallback should still respond
        // (degrade to text/plain shape) instead of crashing the route.
        $this->assertMatchesRegularExpression(
            '/view\(\)->exists\(\s*\'frontend\.errors\.404\'\s*\)/',
            $source,
            'AI-850: Route::fallback() must guard the AI-795 view-render with `view()->exists(\'frontend.errors.404\')` to degrade to text/plain shape if the view is missing.'
        );
    }

    #[Test]
    public function fallback_preserves_text_plain_degraded_fallback(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        // The original text/plain fallback shape must remain for the
        // missing-view degraded path (audit-trail compatibility).
        $this->assertStringContainsString(
            "'Content-Type' => 'text/plain'",
            $source,
            'AI-850: Route::fallback() must preserve the original text/plain degraded-fallback shape for the missing-view edge case (audit-trail compatibility).'
        );
    }

    #[Test]
    public function fallback_preserves_admin_404_chrome_branch(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        // The AI-793 admin-404 branch must remain intact — AI-850 only
        // EXTENDS the non-admin path, doesn't touch admin.
        $this->assertMatchesRegularExpression(
            "/->view\(\s*'admin\.errors\.404'/",
            $source,
            'AI-850 regression-guard: AI-793 admin-404 chrome branch must remain present in Route::fallback().'
        );
        $this->assertMatchesRegularExpression(
            "/'X-Fallback-Message'\s*=>\s*'admin-404'/",
            $source,
            'AI-850 regression-guard: admin-404 `X-Fallback-Message` header must remain present.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — AI-795 view dependency present + carries chrome contract
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai795_view_exists_at_canonical_path(): void
    {
        $this->assertFileExists(
            base_path(self::AI795_VIEW),
            'AI-850 depends on the AI-795 view at resources/views/frontend/errors/404.blade.php existing. If it was removed, AI-850 fallback degrades to text/plain.'
        );
    }

    #[Test]
    public function ai795_view_extends_active_template_with_bootstrap_fallback(): void
    {
        $source = $this->read(self::AI795_VIEW);
        $this->assertMatchesRegularExpression(
            "/@extends\(\s*\\\$extendsView\s*\?\?\s*'templates\.bootstrap::layouts\.master'\s*\)/",
            $source,
            'AI-850 depends on AI-795 view extending `$extendsView ?? \'templates.bootstrap::layouts.master\'` so the AI-757 active-template-master pattern works.'
        );
    }

    #[Test]
    public function ai795_view_emits_noindex_meta_tag(): void
    {
        $source = $this->read(self::AI795_VIEW);
        $this->assertMatchesRegularExpression(
            '/<meta\s+name="robots"\s+content="noindex,nofollow"/i',
            $source,
            'AI-850 depends on AI-795 view emitting `<meta name="robots" content="noindex,nofollow">` as belt-and-braces alongside the X-Robots-Tag header from Route::fallback().'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Customer module is verified BACKEND-ONLY (justifies the 404 fix)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function customer_module_has_no_frontend_template(): void
    {
        // The decision to 404 /customer rests on Customer module being
        // backend-only. If anyone ever adds a frontend template to
        // Modules/Customer/resources/views/templates/, AI-850's 404
        // decision needs to be revisited.
        $this->assertDirectoryDoesNotExist(
            base_path('Modules/Customer/resources/views/templates'),
            'AI-850 decision-anchor: Customer module must remain backend-only (no `resources/views/templates/` directory). If a frontend template is added, /customer should become a real chrome-wrapped surface (per designer\'s hypothesis 1) instead of a 404.'
        );
    }

    #[Test]
    public function customer_service_provider_does_not_load_frontend_routes(): void
    {
        $source = $this->read('Modules/Customer/Providers/CustomerServiceProvider.php');
        // Strip PHP // line comments — the docblock prose legitimately
        // mentions the commented-out loadRoutesFrom('routes/web.php')
        // call as evidence that Customer has no frontend routes. Selector-
        // self-match guard UNIFORMITY.
        $stripped = (string) preg_replace('~//[^\n]*~', '', $source);

        // CustomerServiceProvider should NOT load the routes/web.php file
        // (it's empty stub). It loads routes/api.php only.
        $this->assertStringNotContainsString(
            "loadRoutesFrom(module_path(\$this->moduleName, 'routes/web.php'))",
            $stripped,
            'AI-850 decision-anchor: CustomerServiceProvider must NOT load routes/web.php (web routes are empty stub; loading would imply public surface). If web routes are ever added + loaded, /customer 404 decision needs revisiting.'
        );
        // The api.php loading IS expected — backend-only confirms.
        $this->assertStringContainsString(
            "loadRoutesFrom(module_path(\$this->moduleName, 'routes/api.php'))",
            $source,
            'AI-850 expected-shape: CustomerServiceProvider loads routes/api.php (backend API endpoints).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Task-id markers + AI-850 audit-trail discoverability
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_frontend_routes(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        $this->assertStringContainsString(
            'task-2026-05-17-28d21d',
            $source,
            'AI-850: frontend routes file must carry the task-id marker for cross-surface audit grep (anchored near the customer exclusion-regex addition AND the Route::fallback extension).'
        );
        $this->assertStringContainsString(
            'AI-850',
            $source,
            'AI-850: frontend routes file must carry the AI-850 ticket marker.'
        );
    }

    #[Test]
    public function lineage_marker_cites_ai795_in_frontend_routes(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);
        // The AI-850 fallback extension reuses the AI-795 chrome view —
        // future audits need the AI-795 cite to find the dependency
        // chain in one pass.
        $this->assertStringContainsString(
            'AI-795',
            $source,
            'AI-850: frontend routes docblock must cite AI-795 (the chrome-view dependency this ship reuses) for audit-trail discoverability.'
        );
    }
}
