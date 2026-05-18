<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-18-77da7a / AI-863 — /login Forgot Password link orphans
 * AI-794 standalone chrome (href="#" + JS in-place panel).
 * Jira: https://microweber.atlassian.net/browse/AI-863
 *
 * Pre-fix `src/MicroweberPackages/User/resources/views/admin/auth/index.blade.php`
 * rendered 3 anchors with `href="#"` (L535 forgot-password-link, L672 + L704
 * login-link), with a JS click-intercept block at L735+/L742+ that called
 * `e.preventDefault()` + swapped to an in-place forgot panel. The standalone
 * `/forgot-password` chrome shipped by AI-794 (commit `4ecba69fd0`) was an
 * orphan work product — users never discovered it from the public site
 * navigation.
 *
 * Three defect classes in one file: (1) AI-794 orphaning; (2) `href="#"`
 * breaks back/forward, middle-click, shareable URLs, no-JS fallback, screen
 * reader semantics; (3) no URL state on tab swap.
 *
 * Fix shape — Option A per designer (preferred over B): real route URLs +
 * delete JS intercept entirely. Single canonical flow per state.
 *
 *   L535: <a href="{{ route('password.request') }}" class="forgot-password-link">
 *   L672: <a href="{{ route('login') }}" class="login-link">
 *   L704: <a href="{{ route('login') }}" class="login-link">
 *   L735-747 JS intercept block: removed (replaced with explanatory comment).
 *
 * Tab-switching JS for the visible `.auth-tab` buttons above is preserved
 * (separate UI concern; not in dispatch scope).
 *
 * Acceptance gates (verified at HEAD):
 *   - Tier-1 source-pin: 3 anchors carry real route URLs (zero `href="#"`)
 *   - Tier-2 served-page: `curl /login | grep 'href="#"' | wc -l` = 0
 *   - Tier-3 routes resolve: /login = HTTP 200, /forgot-password = HTTP 200
 *
 * 4-group structure: A = source-presence of new route URLs + zero `href="#"`;
 * B = JS intercept block removed + replaced with explanatory comment;
 * C = back-compat regression sentinels (tab-switching JS preserved + form
 * action route('password.email') preserved + auth-card chrome from AI-794
 * still reachable + register tab structure intact); D = route resolution
 * sanity checks (route('login') + route('password.request') exist).
 */
class Auth77da7aAI863ForgotPasswordLinkContractTest extends TestCase
{
    private function authSource(): string
    {
        return (string) file_get_contents(base_path('src/MicroweberPackages/User/resources/views/admin/auth/index.blade.php'));
    }

    private function stripBladeAndPhpComments(string $source): string
    {
        $source = preg_replace('~\{\{--.*?--\}\}~s', '', $source);
        $source = preg_replace('~/\*.*?\*/~s', '', (string) $source);
        // For Blade <script> JS context, strip JS `//` line comments too.
        $source = preg_replace('~//[^\n]*~', '', (string) $source);
        return (string) $source;
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — 3 anchors carry real route URLs (zero `href="#"`)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function forgot_password_link_carries_route_password_request(): void
    {
        $source = $this->authSource();
        $this->assertMatchesRegularExpression(
            '/<a\s+href="\{\{\s*route\(\'password\.request\'\)\s*\}\}"\s+class="forgot-password-link">/',
            $source,
            'forgot-password-link MUST carry route(\'password.request\') so AI-794 standalone chrome (/forgot-password) is reachable via natural navigation.'
        );
    }

    #[Test]
    public function login_link_carries_route_login_twice(): void
    {
        $source = $this->authSource();
        $count = preg_match_all(
            '/<a\s+href="\{\{\s*route\(\'login\'\)\s*\}\}"\s+class="login-link">/',
            $source
        );
        $this->assertSame(
            2,
            $count,
            'login-link MUST appear exactly 2x (Register tab back-to-login + Forgot panel back-to-login), both wired to route(\'login\').'
        );
    }

    #[Test]
    public function no_href_hash_in_anchors_with_named_classes(): void
    {
        $source = $this->stripBladeAndPhpComments($this->authSource());
        // Pre-strip comments so the docblock + inline AI-863 comments (which
        // mention the literal `href="#"` legacy shape) don't self-match the
        // assertion. LESSONS selector-self-match UNIFORMITY rule, 20+ session
        // recurrences.
        $this->assertDoesNotMatchRegularExpression(
            '/<a\s+href="#"\s+class="forgot-password-link"/',
            $source,
            'forgot-password-link MUST NOT carry href="#" — AI-794 standalone chrome must be reachable via real URL.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<a\s+href="#"\s+class="login-link"/',
            $source,
            'login-link MUST NOT carry href="#" — back-to-login must navigate via real URL.'
        );
    }

    #[Test]
    public function source_carries_ai_863_task_marker(): void
    {
        $source = $this->authSource();
        $this->assertStringContainsString('task-2026-05-18-77da7a', $source, 'AI-863 task-id marker required for cross-surface grep.');
        $this->assertStringContainsString('AI-863', $source);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — JS intercept block removed
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function js_intercept_for_forgot_password_link_is_removed(): void
    {
        $source = $this->stripBladeAndPhpComments($this->authSource());
        // The block-removal contract: there must be ZERO addEventListener
        // calls hung off the `.forgot-password-link` querySelectorAll OR the
        // `.login-link` querySelectorAll. The block at L735-747 was:
        //   document.querySelectorAll('.forgot-password-link').forEach(link => {
        //       link.addEventListener('click', function (e) {
        //           e.preventDefault();
        //           document.querySelector('[data-tab="forgot"]').click();
        //       });
        //   });
        // Both querySelectorAll selectors must be absent post-fix.
        $this->assertStringNotContainsString(
            "querySelectorAll('.forgot-password-link')",
            $source,
            'JS handler querying .forgot-password-link must be removed — natural anchor navigation handles the click.'
        );
        $this->assertStringNotContainsString(
            "querySelectorAll('.login-link')",
            $source,
            'JS handler querying .login-link must be removed — natural anchor navigation handles the click.'
        );
    }

    #[Test]
    public function js_intercept_removal_carries_explanatory_comment(): void
    {
        $source = $this->authSource();
        // The removal site must carry an AI-863 explanatory comment so future
        // agents reading the JS block don't add the intercept back.
        $this->assertStringContainsString(
            '// task-2026-05-18-77da7a / AI-863',
            $source,
            'JS intercept removal site MUST carry an AI-863 explanatory task-id marker.'
        );
        $this->assertStringContainsString(
            'JS click-intercept handlers for',
            $source,
            'JS intercept removal comment MUST document the removal rationale.'
        );
        $this->assertStringContainsString(
            '.forgot-password-link + .login-link REMOVED',
            $source,
            'JS intercept removal comment MUST explicitly name the 2 selectors that were intercepted + that the handlers were REMOVED.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — back-compat regression sentinels
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function tab_switching_js_is_preserved(): void
    {
        $source = $this->authSource();
        // The visible .auth-tab button click-handler must STAY — it's a
        // separate UI concern (the tab bar at the top of the auth panel).
        // Only the link-intercept JS at L735+ was removed.
        $this->assertStringContainsString(
            "document.querySelectorAll('.auth-tab')",
            $source,
            'Tab-switching JS (visible .auth-tab buttons) MUST stay intact — AI-863 only removed the link-intercept JS, not the tab-button JS.'
        );
    }

    #[Test]
    public function forgot_password_form_action_route_preserved(): void
    {
        $source = $this->authSource();
        $this->assertStringContainsString(
            "action=\"{{ route('password.email') }}\"",
            $source,
            'Forgot Password form action route(\'password.email\') MUST stay intact — AI-863 only changes the anchor href, not the form submission endpoint.'
        );
    }

    #[Test]
    public function register_tab_structure_preserved(): void
    {
        $source = $this->authSource();
        $this->assertStringContainsString(
            '@if($showRegisterTab)',
            $source,
            'Register tab @if($showRegisterTab) gate MUST stay intact.'
        );
        $this->assertStringContainsString(
            'Create Account',
            $source,
            'Register form submit copy MUST stay intact.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — route resolution sanity (AI-794 chrome reachable)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function login_route_is_registered(): void
    {
        $url = route('login');
        $this->assertIsString($url);
        $this->assertStringContainsString('/login', $url, "route('login') must resolve to a URL ending in /login.");
    }

    #[Test]
    public function password_request_route_is_registered(): void
    {
        $url = route('password.request');
        $this->assertIsString($url);
        $this->assertStringContainsString('/forgot-password', $url, "route('password.request') must resolve to /forgot-password — the AI-794 standalone chrome URL.");
    }

    #[Test]
    public function password_email_route_is_registered_for_form_action(): void
    {
        // Forgot Password form POSTs to route('password.email'). AI-863 does
        // not touch this; sentinel that the route still exists so the in-page
        // forgot form (still rendered when its tab is visible) keeps working.
        $url = route('password.email');
        $this->assertIsString($url);
    }
}
