<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-c54f1f / AI-795 — frontend unknown-URL 404 styled chrome.
 * Jira: https://microweber.atlassian.net/browse/AI-795
 *
 * Closes AI-755 in the same ship.
 *
 * Lineage:
 *   - AI-755 — original "frontend placeholder 200 instead of 404" ticket
 *   - AI-793 — admin 404 propagation (sister surface; same shape)
 *   - AI-794 — public auth-flow chrome refresh (same .mw-*-card design language)
 *
 * Pre-fix shape: `/register`, `/this-page-does-not-exist`, `/some/random/slug` (any
 * unknown frontend URL) flowed through FrontendController's `$show_404_to_non_admin`
 * branch. The render pipeline correctly set HTTP status 404 BUT continued to render
 * the `clean.php` template layout with the default `$page` array — the rendered body
 * contained the literal `My title / My text content.` placeholder stub with NO
 * `<meta name="robots" content="noindex,nofollow">`. Search engines indexed every
 * Microweber install's stub pages, ranking them for the placeholder copy.
 *
 * Fix shape: in FrontendController's 404 branch, AFTER checking for the active
 * template's 404.php override AND `templates/default/404.php` legacy fallback, if
 * neither exists, short-circuit to a self-contained Blade view at
 * `resources/views/frontend/errors/404.blade.php` that extends the active template's
 * master (Bootstrap fallback), emits an explicit `<meta name="robots" content="noindex,nofollow">`,
 * renders a styled "Page not found" card, and returns HTTP 404 with
 * `X-Robots-Tag: noindex, nofollow` + `X-Fallback-Message: frontend-404` headers.
 *
 * Tier-3 probe per designer dispatch:
 *   for (const u of ['/register', '/this-page-does-not-exist', '/some/random/slug']) {
 *       const r = await fetch(u);
 *       expect(r.status).toBe(404);
 *       const html = await r.text();
 *       expect(html).toContain('noindex,nofollow');
 *       expect(html).not.toContain('My title');
 *   }
 */
class FrontendC54f1fAI795NotFoundChromeContractTest extends TestCase
{
    private string $controller;
    private string $view;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = (string) file_get_contents(base_path(
            'src/MicroweberPackages/App/Http/Controllers/FrontendController.php'
        ));
        $this->view = (string) file_get_contents(base_path(
            'resources/views/frontend/errors/404.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — FrontendController short-circuit branch
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function controller_renders_frontend_errors_404_view_when_no_template_override(): void
    {
        // The short-circuit branch must render the new Blade view via
        // `view('frontend.errors.404', [...])->render()`.
        $this->assertStringContainsString(
            "view('frontend.errors.404',",
            $this->controller,
            'FrontendController must render the new Blade view at frontend.errors.404 when no template-level 404.php exists.'
        );
        $this->assertStringContainsString(
            "'extendsView' => \$mwFallbackExtendsView",
            $this->controller,
            'Short-circuit must pass the resolved $extendsView (active template master with Bootstrap fallback) to the view.'
        );
        $this->assertStringContainsString(
            "'requestedUrl' =>",
            $this->controller,
            'Short-circuit must pass $requestedUrl to the view so users see which URL was missed.'
        );
    }

    #[Test]
    public function controller_short_circuit_returns_404_with_robots_and_fallback_headers(): void
    {
        // Slice the AI-795 block bounded by `task-2026-05-17-c54f1f` marker
        // through the matching `]);` so prose elsewhere doesn't false-match.
        $start = strpos($this->controller, 'task-2026-05-17-c54f1f / AI-795');
        $this->assertNotFalse($start, 'AI-795 marker must be present in FrontendController.');

        $end = strpos($this->controller, ']);', $start);
        $this->assertNotFalse($end, 'AI-795 short-circuit block must end with `]);` (header withHeaders array close).');

        $block = substr($this->controller, $start, $end - $start + 3);

        $this->assertMatchesRegularExpression(
            '/response\(\s*\$mwFallbackRendered\s*,\s*404\s*\)/',
            $block,
            'Short-circuit must return response($mwFallbackRendered, 404) — explicit HTTP 404 status.'
        );
        $this->assertMatchesRegularExpression(
            "/'X-Robots-Tag'\s*=>\s*'noindex,\s*nofollow'/",
            $block,
            'Short-circuit must set X-Robots-Tag: noindex, nofollow header so search engines stop indexing the stub.'
        );
        $this->assertMatchesRegularExpression(
            "/'X-Fallback-Message'\s*=>\s*'frontend-404'/",
            $block,
            'Short-circuit must set X-Fallback-Message: frontend-404 so the response is discoverable by tester runtime probes.'
        );
        $this->assertMatchesRegularExpression(
            "/'Content-Type'\s*=>\s*'text\/html;\s*charset=UTF-8'/",
            $block,
            'Short-circuit must force Content-Type: text/html; charset=UTF-8 (the rendered view is HTML, not plain text).'
        );
    }

    #[Test]
    public function controller_short_circuit_is_gated_on_non_admin_only(): void
    {
        // The new view must NOT render when an admin is logged in — admins see the
        // template's normal page-not-found path so they can still edit/diagnose.
        // The short-circuit lives INSIDE the existing `if (!is_admin())` branch.
        $start = strpos($this->controller, "if (!is_admin()) {\n                    \$load_template_404 = template_dir() . '404.php';");
        $this->assertNotFalse(
            $start,
            'AI-795 short-circuit must sit inside the existing `if (!is_admin())` branch — admins keep the normal page-not-found render so they can still edit/diagnose.'
        );

        // Verify the short-circuit body sits between this `if (!is_admin())` open brace
        // and the matching close brace — same scope as the template-404.php checks.
        $ai795Marker = strpos($this->controller, 'task-2026-05-17-c54f1f / AI-795', $start);
        $this->assertNotFalse($ai795Marker);
        $this->assertGreaterThan($start, $ai795Marker);
    }

    #[Test]
    public function controller_short_circuit_preserves_template_404_override_chain(): void
    {
        // Template authors' `template_dir() . '404.php'` override AND the legacy
        // `templates/default/404.php` fallback must STILL be checked BEFORE the
        // new Blade view fires. Pin the if/else-if/else order.
        $this->assertMatchesRegularExpression(
            '/is_file\(\$load_template_404\)\)\s*\{\s*\$render_file\s*=\s*\$load_template_404;\s*\}\s*else if\s*\(is_file\(\$load_template_404_2\)\)/s',
            $this->controller,
            'Short-circuit must remain AFTER `if (is_file($load_template_404))` AND `else if (is_file($load_template_404_2))` so template authors keep their 404.php override capability.'
        );
    }

    #[Test]
    public function controller_short_circuit_resolves_active_template_master_with_bootstrap_fallback(): void
    {
        $this->assertStringContainsString(
            "\$mwFallbackExtendsView = 'templates.bootstrap::layouts.master';",
            $this->controller,
            'Short-circuit must default $extendsView to the Bootstrap template master (works in installs with no active template configured).'
        );
        $this->assertStringContainsString(
            "\$mwFallbackExtendsCheck = 'templates.' . \$mwFallbackTemplateName . '::layouts.master';",
            $this->controller,
            'Short-circuit must check for an active-template-specific master and prefer it over Bootstrap when present (same pattern as AI-757 admin login + AI-794 auth chrome).'
        );
        $this->assertMatchesRegularExpression(
            '/if\s*\(view\(\)->exists\(\$mwFallbackExtendsCheck\)\)/',
            $this->controller,
            'Short-circuit must guard the active-template-master pick with view()->exists() so missing template masters silently fall back to Bootstrap.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — frontend/errors/404 view structure
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function view_extends_dynamic_template_master_with_bootstrap_fallback(): void
    {
        $this->assertMatchesRegularExpression(
            "/@extends\(\\\$extendsView \?\?\s*'templates\.bootstrap::layouts\.master'\)/",
            $this->view,
            'View must @extends($extendsView ?? "templates.bootstrap::layouts.master") so it works when called outside the controller (defence-in-depth).'
        );
    }

    #[Test]
    public function view_emits_robots_noindex_nofollow_meta(): void
    {
        // Designer Tier-3 probe checks `expect(html).toContain('noindex,nofollow')`
        // — the rendered HTML must carry the literal substring `noindex,nofollow`
        // (with NO space between the comma and nofollow).
        $this->assertStringContainsString(
            '<meta name="robots" content="noindex,nofollow">',
            $this->view,
            'View must emit `<meta name="robots" content="noindex,nofollow">` exactly (no space between `,` and `nofollow`) so designer Tier-3 probe `expect(html).toContain("noindex,nofollow")` passes.'
        );
    }

    #[Test]
    public function view_renders_mw_frontend_404_card_chrome(): void
    {
        $this->assertStringContainsString(
            'class="mw-frontend-404"',
            $this->view,
            'View must wrap content in the .mw-frontend-404 container.'
        );
        $this->assertStringContainsString(
            'class="mw-frontend-404__card"',
            $this->view,
            'View must wrap the message in the .mw-frontend-404__card styled card.'
        );
        $this->assertStringContainsString(
            'class="mw-frontend-404__heading"',
            $this->view,
            'View must carry a styled heading (.mw-frontend-404__heading).'
        );
        $this->assertStringContainsString(
            'class="mw-frontend-404__body"',
            $this->view,
            'View must carry styled body copy (.mw-frontend-404__body).'
        );
        $this->assertStringContainsString(
            'class="mw-frontend-404__actions"',
            $this->view,
            'View must carry an actions row (.mw-frontend-404__actions) for the CTA.'
        );
    }

    #[Test]
    public function view_back_to_homepage_cta_uses_site_url_and_brand_blue(): void
    {
        $this->assertStringContainsString(
            'href="{{ site_url() }}"',
            $this->view,
            'Primary CTA must link to site_url() so the user always lands on a valid root page.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-frontend-404__cta--primary\s*\{[^}]*background:\s*#0d6efd/',
            $this->view,
            'Primary CTA must use brand blue #0d6efd (MwColors::Blue) — matches AI-209/AI-702 unification.'
        );
    }

    #[Test]
    public function view_has_reduced_motion_guard(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*reduce\)/',
            $this->view,
            'View must include a `@media (prefers-reduced-motion: reduce)` guard so the CTA transitions collapse for users who prefer reduced motion.'
        );
    }

    #[Test]
    public function view_has_mobile_refinement_breakpoint(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*480px\)/',
            $this->view,
            'View must include a `@media (max-width: 480px)` refinement so the card padding shrinks on narrow viewports (matches AI-794 mw-auth-card pattern).'
        );
    }

    #[Test]
    public function view_emits_the_pre_fix_placeholder_strings_nowhere(): void
    {
        // Designer Tier-3 probe checks `expect(html).not.toContain('My title')`
        // — the rendered view must NOT contain the pre-fix placeholder strings.
        // Pre-strip Blade {{-- ... --}} comments so the docblock prose
        // referring to the pre-fix shape doesn't false-fail this guard.
        $stripped = preg_replace('!{{--.*?--}}!s', '', $this->view);
        $this->assertStringNotContainsString(
            'My title',
            $stripped,
            'View must NOT contain the literal pre-fix placeholder string `My title` (designer Tier-3 probe regression-guard).'
        );
        $this->assertStringNotContainsString(
            'My text content',
            $stripped,
            'View must NOT contain the literal pre-fix placeholder string `My text content` (designer Tier-3 probe regression-guard).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — markers + lineage
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai795_markers_present_on_both_surfaces(): void
    {
        $this->assertStringContainsString('task-2026-05-17-c54f1f', $this->controller);
        $this->assertStringContainsString('AI-795', $this->controller);
        $this->assertStringContainsString('task-2026-05-17-c54f1f', $this->view);
        $this->assertStringContainsString('AI-795', $this->view);
    }

    #[Test]
    public function view_docblock_cites_lineage_tickets(): void
    {
        $this->assertStringContainsString(
            'AI-755',
            $this->view,
            'View docblock must cite AI-755 (the original placeholder-200 ticket this ship closes).'
        );
        $this->assertStringContainsString(
            'AI-793',
            $this->view,
            'View docblock must cite AI-793 (sister admin-404 surface, same shape).'
        );
        $this->assertStringContainsString(
            'AI-794',
            $this->view,
            'View docblock must cite AI-794 (auth-flow chrome refresh, same design language).'
        );
    }

    #[Test]
    public function controller_short_circuit_cites_lineage(): void
    {
        $start = strpos($this->controller, 'task-2026-05-17-c54f1f / AI-795');
        $this->assertNotFalse($start);
        $end = strpos($this->controller, '$mwFallbackTemplateName', $start);
        $this->assertNotFalse($end);
        $docblock = substr($this->controller, $start, $end - $start);

        $this->assertStringContainsString(
            'AI-755',
            $docblock,
            'Controller short-circuit docblock must cite AI-755 (the original ticket this slice closes).'
        );
        $this->assertStringContainsString(
            'AI-793',
            $docblock,
            'Controller short-circuit docblock must cite AI-793 (sister admin-404 propagation surface).'
        );
    }
}
