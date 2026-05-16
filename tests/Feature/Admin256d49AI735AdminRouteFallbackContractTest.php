<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-256d49 / AI-735 — 404 / search / missing admin
 * routes all fall through to placeholder page. Jira:
 *   https://microweber.atlassian.net/browse/AI-735
 *
 * Designer dispatch 2026-05-16T15:40:46 (High priority). Verified
 * failure surfaces:
 *   - /this-page-does-not-exist-404-test → 200 OK, "My title"
 *     (should be 404). [surface 1 — front-end placeholder]
 *   - /search?keyword=test → 200 OK, "My title" (should be search
 *     results template). [surface 2 — search route missing]
 *   - /admin/media → front-end placeholder (should be admin
 *     Media library). [surface 3 — admin route fall-through]
 *   - /admin/custom-fields → front-end placeholder. [surface 4 —
 *     admin route fall-through]
 *
 * This ship addresses surfaces 3 + 4 (admin route fall-through)
 * via the smallest valid change: add `admin` to the catch-all
 * route's excluded-prefix regex in
 * src/MicroweberPackages/Frontend/routes/web.php. Unmatched
 * `/admin/*` URLs now propagate to Route::fallback() which
 * returns a clean 404.
 *
 * Surfaces 1 + 2 are tracked as AI-735a / AI-735b follow-ups:
 *   - AI-735a — front-end 404 returning 200 (FrontendController
 *     placeholder rendering when no content matches the slug).
 *   - AI-735b — search results template + Search-module web.php
 *     route registration.
 */
class Admin256d49AI735AdminRouteFallbackContractTest extends TestCase
{
    private string $routesSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routesSrc = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Frontend/routes/web.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — admin prefix added to the catch-all exclusion regex
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function catch_all_slug_route_excludes_admin_prefix(): void
    {
        // The fix: `admin` is now in the negative-lookahead set so
        // /admin/* requests don't match the catch-all `{slug}` and
        // fall through to Route::fallback() → 404.
        $this->assertMatchesRegularExpression(
            "/->where\\(\\s*['\"]slug['\"]\\s*,\\s*['\"][^'\"]*\\|admin[^'\"]*['\"]\\s*\\)/",
            $this->routesSrc,
            'Catch-all {slug} route must exclude `admin` prefix via negative-lookahead regex.'
        );
    }

    #[Test]
    public function catch_all_regex_preserves_existing_excluded_prefixes(): void
    {
        // Regression guard against accidentally dropping the
        // existing excluded prefixes (vendor, packages, template,
        // modules, css, storage, userfiles, js).
        $expected = ['vendor', 'packages', 'template', 'modules', 'css', 'storage', 'userfiles', 'js'];
        foreach ($expected as $prefix) {
            $this->assertMatchesRegularExpression(
                "/->where\\(\\s*['\"]slug['\"]\\s*,\\s*['\"][^'\"]*\\b{$prefix}\\b[^'\"]*['\"]\\s*\\)/",
                $this->routesSrc,
                "Catch-all {slug} regex must still exclude `{$prefix}` prefix."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Route::fallback() preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function route_fallback_preserved_with_404_status(): void
    {
        // Laravel's Route::fallback() is the destination for
        // unmatched `/admin/*` after the AI-735 exclusion. The
        // fallback must still return status 404, not 200.
        $this->assertMatchesRegularExpression(
            '/Route::fallback\s*\(\s*function\s*\(\s*\)\s*\{/',
            $this->routesSrc,
            'Route::fallback() handler must remain registered.'
        );
        $this->assertMatchesRegularExpression(
            '/return\s+response\s*\([^,]+,\s*404\s*\)/',
            $this->routesSrc,
            'Route::fallback() must return HTTP 404 — never 200 — per AI-735 acceptance.'
        );
    }

    #[Test]
    public function route_fallback_emits_diagnostic_header(): void
    {
        // The existing fallback emits an X-Fallback-Message header
        // for debug visibility. Pin so a future cleanup doesn't
        // drop the diagnostic surface that AI-735 verification
        // relies on.
        $this->assertStringContainsString(
            "'X-Fallback-Message' => 'true'",
            $this->routesSrc,
            'Route::fallback() must emit the X-Fallback-Message diagnostic header.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — markers + follow-up discoverability
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai735_markers_pinned(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-16-256d49',
            $this->routesSrc,
            'AI-735 task-id marker must be present in routes/web.php.'
        );
        $this->assertStringContainsString('AI-735', $this->routesSrc);
    }

    #[Test]
    public function followup_hints_discoverable_in_source(): void
    {
        // Source comments must hint at AI-735a (front-end 404) and
        // AI-735b (search route) so a future audit grep lands here.
        $this->assertStringContainsString(
            'AI-735a',
            $this->routesSrc,
            'Source comment must hint at AI-735a — front-end 404 follow-up.'
        );
        $this->assertStringContainsString(
            'AI-735b',
            $this->routesSrc,
            'Source comment must hint at AI-735b — search route follow-up.'
        );
    }
}
