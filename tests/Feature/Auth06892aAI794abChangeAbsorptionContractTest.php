<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-06892a — AI-794a + AI-794b CHANGE absorption per
 * designer's [ACK with CHANGE] on the AI-794 chrome ship.
 *
 * Jira: https://microweber.atlassian.net/browse/AI-794
 *       (filed inline as CHANGEs by designer; treated as direct
 *        continuations of AI-794 scope)
 *
 * The AI-794 primary deliverable (auth chrome wrap for forgot-
 * password + reset-password) shipped at commit `4ecba69fd0` and
 * was ACK'd by designer's 3-tier probe (card 440×342, semantic
 * <header>, autocomplete tokens, "Back to login" + "Send reset
 * link" all present). Designer's runtime verification surfaced
 * two follow-up issues filed as inline CHANGEs:
 *
 * CHANGE 1 — AI-794a — Desktop CTA cascade-loss:
 *   Pre-CHANGE: ship report claimed brand-blue but runtime
 *   measurement at 1440 desktop showed rgb(244, 162, 97) (salmon
 *   #F4A261 — Big2 template default). Mobile (≤480px) overrode
 *   correctly. Third Stage-2 cascade-loss sibling this session
 *   (AI-697 v3 / AI-786 / AI-810).
 *   Post-CHANGE: compound `.mw-auth-card .btn-primary` selector
 *   with `!important` on background-color + color + border-color
 *   defeats the template's higher-specificity .btn-primary rule.
 *
 * CHANGE 2 — AI-794b — /reset-password error path bypasses chrome:
 *   Pre-CHANGE: `UserForgotPasswordController::showResetForm()`
 *   (and the POST `update()` expired-token branch) called
 *   `abort(response("Password reset link is expired", 401))`
 *   which emitted bare text on a blank page (3 occurrences). Same
 *   propagation-without-renderer-update family as AI-735→AI-793
 *   admin-404 (form path got the AI-794 wrap; error path bypassed
 *   user::layout entirely).
 *   Post-CHANGE: new chrome-wrapped view
 *   `user::auth.reset-password-expired` extends user::layout +
 *   carries heading "Reset link expired" + body about 60-min
 *   validity + "Request a new reset link" CTA →
 *   route('password.request'). All 3 controller call sites now
 *   route through `$this->expiredResetLinkResponse()` helper.
 *
 * Selector-self-match guard family (20+ session-recurrences):
 * the docblock above + per-edit inline source comments
 * legitimately mention legacy strings (`abort(response(...))`,
 * "Password reset link is expired", salmon hex). Absence
 * assertions pre-strip PHP/Blade comments before scanning.
 */
class Auth06892aAI794abChangeAbsorptionContractTest extends TestCase
{
    private string $layoutCss;
    private string $controllerSrc;
    private string $controllerExecutable;
    private string $expiredView;

    protected function setUp(): void
    {
        parent::setUp();
        $this->layoutCss = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/resources/views/layout.blade.php'
        ));
        $this->controllerSrc = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/Http/Controllers/UserForgotPasswordController.php'
        ));
        $this->expiredView = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/resources/views/auth/reset-password-expired.blade.php'
        ));

        // Strip PHP block + line comments + Blade {{-- --}} from the
        // controller so docblock prose mentioning legacy patterns
        // doesn't false-fail absence assertions.
        $this->controllerExecutable = preg_replace('~/\*[\s\S]*?\*/~', '', $this->controllerSrc);
        $this->controllerExecutable = preg_replace('~//[^\n]*~', '', $this->controllerExecutable);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  AI-794a CHANGE 1 — desktop CTA cascade-loss fix
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai794a_btn_primary_compound_selector_with_important(): void
    {
        // The fix recipe: compound `.mw-auth-card .btn-primary`
        // with `!important` on background-color + border-color
        // (color too as defence-in-depth).
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s+\.btn-primary\s*\{[^}]*background-color:\s*#0d6efd\s*!important/i',
            $this->layoutCss,
            'AI-794a: .mw-auth-card .btn-primary MUST declare `background-color: #0d6efd !important` (Stage-2 cascade-loss fix per LESSONS canonical recipe).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s+\.btn-primary\s*\{[^}]*border-color:\s*#0d6efd\s*!important/i',
            $this->layoutCss,
            'AI-794a: .mw-auth-card .btn-primary MUST declare `border-color: #0d6efd !important` (cascade-loss fix).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s+\.btn-primary\s*\{[^}]*color:\s*#fff\s*!important/i',
            $this->layoutCss,
            'AI-794a: .mw-auth-card .btn-primary MUST declare `color: #fff !important` so text stays white on the brand-blue surface regardless of template cascade.'
        );
    }

    #[Test]
    public function ai794a_btn_primary_hover_focus_state_also_uses_important(): void
    {
        // Hover + focus state MUST also use !important so the
        // template's :hover/:focus rule doesn't win the cascade
        // and revert to the template-default colour.
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s+\.btn-primary:hover[\s\S]*?\.mw-auth-card\s+\.btn-primary:focus\s*\{[^}]*background-color:\s*#0b5ed7\s*!important/i',
            $this->layoutCss,
            'AI-794a: .mw-auth-card .btn-primary :hover + :focus state MUST also use !important on background-color so the template cascade can\'t reset to default on interaction.'
        );
    }

    #[Test]
    public function ai794a_lessons_lineage_cited(): void
    {
        // The Stage-2 cascade-loss family lineage MUST be cited
        // in source comment so future audits find the family via
        // grep.
        $this->assertMatchesRegularExpression(
            '/Stage-2|cascade-loss|AI-697|AI-786|AI-810/',
            $this->layoutCss,
            'AI-794a: source-side comment MUST cite the Stage-2 cascade-loss family lineage (AI-697 v3 / AI-786 / AI-810).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  AI-794b CHANGE 2 — error path chrome wrap
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai794b_expired_reset_link_view_exists_and_extends_layout(): void
    {
        $this->assertNotEmpty(
            $this->expiredView,
            'AI-794b: src/MicroweberPackages/User/resources/views/auth/reset-password-expired.blade.php MUST exist.'
        );
        // MUST extend user::layout so the AI-794 chrome (active-
        // template master + .mw-auth-card + brand logo + footer +
        // AI-794a brand-blue CTA) renders consistently.
        $this->assertStringContainsString(
            "@extends('user::layout')",
            $this->expiredView,
            'AI-794b: reset-password-expired.blade.php MUST extend user::layout.'
        );
        // MUST use @section('auth_form') (the user::layout slot
        // contract).
        $this->assertStringContainsString(
            "@section('auth_form')",
            $this->expiredView,
            'AI-794b: reset-password-expired.blade.php MUST @section(\'auth_form\') per user::layout slot contract.'
        );
    }

    #[Test]
    public function ai794b_expired_view_carries_designer_specified_copy(): void
    {
        // Heading, body, CTA per designer spec.
        $this->assertStringContainsString(
            "__('Reset link expired')",
            $this->expiredView,
            "AI-794b: expired view heading MUST be __('Reset link expired') per designer spec."
        );
        // Body uses _e() with trailing-period helper (Microweber
        // idiom per LESSONS — Laravel __() returns empty on trailing
        // period).
        $this->assertMatchesRegularExpression(
            "/_e\\(\\s*'Your password reset link has expired or is invalid\\.\\s+Reset links are valid for 60 minutes — request a new one\\.'\\s*,\\s*true\\s*\\)/",
            $this->expiredView,
            "AI-794b: expired view body MUST mirror designer-specified copy via Microweber _e('...', true) helper (trailing-period idiom)."
        );
        // CTA label + href.
        $this->assertStringContainsString(
            "__('Request a new reset link')",
            $this->expiredView,
            "AI-794b: expired view CTA label MUST be __('Request a new reset link')."
        );
        $this->assertMatchesRegularExpression(
            "/route\\(\\s*'password\\.request'\\s*\\)/",
            $this->expiredView,
            "AI-794b: expired view CTA href MUST route('password.request')."
        );
    }

    #[Test]
    public function ai794b_expired_view_cta_uses_brand_blue_btn_primary_class(): void
    {
        // The CTA MUST be class="btn btn-primary" so the AI-794a
        // cascade-loss fix applies → brand-blue on desktop AND
        // mobile (the previous CTA was the bare-text "Password
        // reset link is expired" with no recovery affordance).
        $this->assertMatchesRegularExpression(
            '/class="btn\s+btn-primary"\s+href="\{\{\s*route/',
            $this->expiredView,
            'AI-794b: expired view CTA MUST carry class="btn btn-primary" so AI-794a cascade-loss fix applies the brand-blue styling.'
        );
    }

    #[Test]
    public function ai794b_controller_no_longer_aborts_with_bare_text(): void
    {
        // After comment-strip, NO `abort(response("Password reset
        // link is expired", 401))` should remain in executable PHP.
        $this->assertDoesNotMatchRegularExpression(
            '/abort\(\s*response\(\s*"Password reset link is expired"/',
            $this->controllerExecutable,
            'AI-794b: controller MUST NOT carry any abort(response("Password reset link is expired", ...)) bare-text call (3 occurrences pre-CHANGE).'
        );
        // Same with $expiredText variable shape.
        $this->assertDoesNotMatchRegularExpression(
            '/abort\(\s*response\(\s*\$expiredText/',
            $this->controllerExecutable,
            'AI-794b: controller MUST NOT carry any abort(response($expiredText, ...)) variable-bound shape either.'
        );
    }

    #[Test]
    public function ai794b_controller_has_expired_reset_link_response_helper(): void
    {
        // The helper method MUST be defined as a protected method
        // that returns response()->view('user::auth.reset-password-
        // expired', [], 401).
        $this->assertMatchesRegularExpression(
            '/protected\s+function\s+expiredResetLinkResponse\s*\(\s*\)/',
            $this->controllerExecutable,
            'AI-794b: UserForgotPasswordController MUST define protected expiredResetLinkResponse() helper method.'
        );
        $this->assertMatchesRegularExpression(
            "/response\\(\\)->view\\(\\s*'user::auth\\.reset-password-expired'\\s*,\\s*\\[\\s*\\]\\s*,\\s*401\\s*\\)/",
            $this->controllerExecutable,
            'AI-794b: expiredResetLinkResponse() MUST return response()->view(\'user::auth.reset-password-expired\', [], 401) — chrome-wrapped view + 401 status preserved.'
        );
    }

    #[Test]
    public function ai794b_controller_all_3_call_sites_use_helper(): void
    {
        // Count $this->expiredResetLinkResponse() invocations:
        // exactly 3 (showResetForm:2 + update:1).
        $count = preg_match_all(
            '/\$this->expiredResetLinkResponse\(\)/',
            $this->controllerExecutable
        );
        $this->assertSame(
            3,
            $count,
            'AI-794b: controller MUST invoke $this->expiredResetLinkResponse() exactly 3 times (2 from showResetForm + 1 from update).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  task-id markers + AI-794 lineage citation
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai794ab_markers_present(): void
    {
        // AI-794a marker in layout.blade.php
        $this->assertStringContainsString('task-2026-05-17-06892a', $this->layoutCss);
        $this->assertStringContainsString('AI-794a', $this->layoutCss);
        // AI-794b marker in controller + view
        $this->assertStringContainsString('task-2026-05-17-06892a', $this->controllerSrc);
        $this->assertStringContainsString('AI-794b', $this->controllerSrc);
        $this->assertStringContainsString('task-2026-05-17-06892a', $this->expiredView);
        $this->assertStringContainsString('AI-794b', $this->expiredView);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  back-compat — primary AI-794 chrome wrap preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function primary_ai794_chrome_wrap_still_intact(): void
    {
        // The AI-794a CSS edit only added !important to colour
        // declarations — the rest of .mw-auth-card / .mw-auth-
        // container / .mw-auth-header chrome MUST stay intact.
        foreach (['.mw-auth-container', '.mw-auth-header', '.mw-auth-card', '.mw-auth-actions'] as $cls) {
            $this->assertStringContainsString(
                $cls,
                $this->layoutCss,
                "AI-794: primary chrome class `{$cls}` MUST be preserved after AI-794a/b CHANGE absorption."
            );
        }
        // forgot-password.blade.php still extends user::layout
        $forgotView = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/resources/views/auth/forgot-password.blade.php'
        ));
        $this->assertStringContainsString(
            "@extends('user::layout')",
            $forgotView,
            'AI-794 chrome: forgot-password.blade.php MUST still extend user::layout.'
        );
    }
}
