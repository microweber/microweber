<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-a596d2 / AI-793 — admin route 404 propagation.
 * Jira: https://microweber.atlassian.net/browse/AI-793
 *
 * Designer's R10-6 audit caught /admin/seo-settings + /admin/language
 * + /admin/backup (plus any other unmatched /admin/* URL) returning
 * a plain-text response:
 *
 *     Page not found at url: admin/seo-settings
 *
 * Root cause: AI-735 slice 1 (task-2026-05-16-256d49) added `admin`
 * to the frontend catch-all exclusion regex so unmatched admin URLs
 * propagate to Route::fallback() instead of falling into the
 * FrontendController content-resolver. But the fallback itself was
 * returning `text/plain` for ALL URLs — admin-prefix detection was
 * never added.
 *
 * AI-793 closes that gap:
 *   - Route::fallback() now branches on URL prefix.
 *   - Admin-prefix URLs render `resources/views/admin/errors/404.blade.php`
 *     — a self-contained HTML page that loads the Filament theme
 *     bundle + uses the .mw-admin-empty-state / .mw-table-empty-cta
 *     tokens shipped earlier today (AI-789 + AI-731 lineage).
 *   - Non-admin URLs keep the existing text/plain behaviour (front-
 *     end Microweber signal preserved).
 *   - Returns HTTP 404 with the rendered HTML (not redirect).
 *
 * Test surfaces:
 *   - Group A: source-level pins on routes/web.php fallback branch
 *   - Group B: source-level pins on admin/errors/404.blade.php
 *   - Group C: runtime HTTP probes — admin URL → admin-styled HTML
 *     404; front-end URL → text/plain 404
 */
class AdminA596d2AI793Admin404PropagationContractTest extends TestCase
{
    private string $routesSource;
    private string $errorView;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routesSource = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Frontend/routes/web.php'
        ));
        $this->errorView = (string) file_get_contents(base_path(
            'resources/views/admin/errors/404.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — fallback branches on admin-prefix URL
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function fallback_detects_admin_prefix_and_renders_styled_view(): void
    {
        // Source-level pin: the fallback must compute $adminPrefix
        // from mw_admin_prefix_url() and branch on str_starts_with
        // OR the URL equalling the admin prefix exactly.
        $this->assertStringContainsString(
            "mw_admin_prefix_url() ?: 'admin'",
            $this->routesSource,
            'Fallback must compute admin prefix via mw_admin_prefix_url() with literal "admin" fallback.'
        );
        $this->assertStringContainsString(
            "str_starts_with(\$normalised, \$adminPrefix . '/')",
            $this->routesSource,
            'Fallback must check str_starts_with for admin-prefix detection.'
        );
        $this->assertStringContainsString(
            "->view('admin.errors.404'",
            $this->routesSource,
            'Fallback must render the admin.errors.404 view for admin URLs.'
        );
    }

    #[Test]
    public function fallback_preserves_text_plain_for_non_admin_urls(): void
    {
        // Regression guard — front-end URLs that didn't match any
        // route still return the existing text/plain shape with the
        // legacy `Page not found at url: ...` body. Microweber-side
        // signal preserved.
        $this->assertStringContainsString(
            "'Content-Type' => 'text/plain'",
            $this->routesSource,
            'Fallback must keep text/plain Content-Type for non-admin URLs.'
        );
        $this->assertStringContainsString(
            "'Page not found at url: ' . \$url",
            $this->routesSource,
            'Fallback must keep the legacy front-end 404 body string.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — admin/errors/404.blade.php structure
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function error_view_loads_filament_theme_bundle(): void
    {
        $this->assertStringContainsString(
            "asset('vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css')",
            $this->errorView,
            'Admin 404 view must load the Filament theme bundle to inherit token typography + colours.'
        );
    }

    #[Test]
    public function error_view_uses_admin_empty_state_chrome(): void
    {
        // Reuses the AI-789 partial shape (heading + body + CTA)
        // via the established class names. No new chrome classes.
        $this->assertStringContainsString('mw-admin-empty-state__heading', $this->errorView);
        $this->assertStringContainsString('mw-admin-empty-state__body', $this->errorView);
        $this->assertStringContainsString('mw-table-empty-cta', $this->errorView);
    }

    #[Test]
    public function error_view_escapes_requested_url(): void
    {
        // Defence-in-depth: the requested URL is user-supplied; must
        // pass through Blade's default {{ }} escape (no {!! !!} usage).
        $this->assertMatchesRegularExpression(
            '/aria-label="Requested URL">\{\{\s*\$requestedUrl\s*\}\}<\/div>/',
            $this->errorView,
            'Requested URL must be echoed via {{ }} (Blade-escaped), never {!! !!}.'
        );
        $this->assertStringNotContainsString(
            '{!! $requestedUrl',
            $this->errorView,
            'Requested URL must NEVER use {!! !!} (would allow HTML injection from URL).'
        );
    }

    #[Test]
    public function error_view_has_dashboard_cta_back_to_admin(): void
    {
        $this->assertStringContainsString(
            "url(mw_admin_prefix_url() ?: 'admin')",
            $this->errorView,
            'CTA must link to the configurable admin URL (mw_admin_prefix_url with literal "admin" fallback).'
        );
        $this->assertStringContainsString('Back to dashboard', $this->errorView);
    }

    #[Test]
    public function error_view_has_noindex_robots_meta(): void
    {
        // 404 pages must never be indexed by search engines.
        $this->assertStringContainsString(
            '<meta name="robots" content="noindex,nofollow">',
            $this->errorView,
            'Admin 404 page must declare noindex,nofollow robots meta.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — runtime HTTP probes
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function admin_url_unknown_returns_styled_html_404(): void
    {
        // Genuinely-unmatched admin URLs (original AI-793 targets like
        // /admin/seo-settings now resolve to real routes; this contract is about
        // ANY unmatched /admin/* URL rendering the styled 404).
        $urls = [
            '/admin/this-does-not-exist',
            '/admin/totally-fake-xyz-123',
            '/admin/no-such-admin-page-456',
        ];
        foreach ($urls as $url) {
            $response = $this->get($url);
            $response->assertStatus(404);
            $content = $response->getContent();
            // Admin-styled 404 carries the AI-793 chrome classes.
            $this->assertStringContainsString(
                'mw-admin-empty-state__heading',
                $content,
                sprintf('GET %s must render the admin-styled 404 view (got %s chars of body).', $url, strlen($content))
            );
            $this->assertStringContainsString(
                'Page not found',
                $content,
                sprintf('GET %s must include "Page not found" heading text.', $url)
            );
            $this->assertStringContainsString(
                'Back to dashboard',
                $content,
                sprintf('GET %s must include the "Back to dashboard" CTA.', $url)
            );
            // X-Fallback-Message header signals the admin-404 path
            // for log analysis.
            $this->assertSame(
                'admin-404',
                $response->headers->get('X-Fallback-Message'),
                sprintf('GET %s must set X-Fallback-Message: admin-404.', $url)
            );
        }
    }

    #[Test]
    public function non_admin_text_plain_branch_preserved_in_fallback_source(): void
    {
        // Note: in normal traffic, front-end URLs are caught by
        // FrontendController (via the {slug} route OR the content
        // resolver) BEFORE Route::fallback fires. The text/plain
        // branch only fires for edge cases (e.g. URLs that bypass
        // the frontend pipeline entirely). The branch must remain
        // present in source as the documented Microweber signal —
        // any future refactor that drops it should consider what
        // the new shape is for genuinely-unmatched URLs.
        //
        // Source-level invariant: the fallback closure must still
        // carry BOTH a code path that returns text/plain AND the
        // admin-prefix branch added by AI-793. Renaming this from
        // a runtime probe to a source-pin matches reality: the
        // text/plain branch is reachable in theory but not in
        // standard request paths.
        $this->assertStringContainsString(
            "'Content-Type' => 'text/plain'",
            $this->routesSource,
            'Fallback source must keep the text/plain branch even if normal traffic does not reach it (FrontendController catches first).'
        );
        $this->assertStringContainsString(
            "X-Fallback-Message",
            $this->routesSource,
            'Fallback must keep an X-Fallback-Message header on both branches for log analysis.'
        );
        // Source-level branch ordering: admin-prefix check MUST come
        // BEFORE the text/plain default so admin URLs always get the
        // styled view (not the text/plain fall-through).
        $adminBranchPos = strpos($this->routesSource, "->view('admin.errors.404'");
        $textPlainPos = strpos($this->routesSource, "'Content-Type' => 'text/plain'");
        $this->assertNotFalse($adminBranchPos);
        $this->assertNotFalse($textPlainPos);
        $this->assertLessThan(
            $textPlainPos,
            $adminBranchPos,
            'Admin-prefix branch must appear BEFORE the text/plain default in Route::fallback source — otherwise admin URLs would fall through to text/plain.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai793_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-a596d2', $this->routesSource);
        $this->assertStringContainsString('AI-793', $this->routesSource);
        $this->assertStringContainsString('task-2026-05-17-a596d2', $this->errorView);
        $this->assertStringContainsString('AI-793', $this->errorView);
        // AI-735 lineage reference must remain so future audits can
        // trace the propagation chain.
        $this->assertStringContainsString('AI-735', $this->routesSource);
    }
}
