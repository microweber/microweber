<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-f141ff / AI-794 — /forgot-password unstyled raw HTML fix.
 * Jira: https://microweber.atlassian.net/browse/AI-794
 *
 * Lineage:
 *   - AI-757 — login parity surface (admin/auth/index.blade.php uses templates.X::layouts.master)
 *   - AI-789 — reusable admin-empty-state chrome (this slice ships the public-auth analogue)
 *   - AI-793 — admin 404 styled card (sister surface in the admin-shell chrome arc)
 *
 * Pre-fix shape: `auth/forgot-password.blade.php` extended `user::layout`, which emitted
 * a bare DOCTYPE via `app::public.partials.header` (only viewport + scripts + favicon — NO
 * public template CSS, NO header navigation, NO footer). `auth/reset-password.blade.php`
 * was even worse — a standalone bare-HTML file that didn't extend any layout at all.
 * Result: brand mark rendered ~200x200 px floating top-left + browser-default `<input>` +
 * 3D-beveled `<button>` + no card framing + no template chrome. Designer's r11 screenshot
 * flagged it as a phishing-page-grade trust collapse for locked-out users.
 *
 * Fix shape:
 *   - `user::layout` rewritten to extend the active template's master (Bootstrap fallback
 *     via the same conditional shape AI-757 uses in admin/auth/index.blade.php) so the
 *     template's CSS, header navigation, and footer load on every auth page.
 *   - Auth content wrapped in `.mw-auth-card` chrome with a semantic `<header class="mw-auth-header">`
 *     emitting the brand logo capped at 64x64 (was unconstrained → ~200x200 floating).
 *   - Front-end form tokens (.form-control 12px radius / 1px #d1d5db border / #0d6efd focus).
 *   - Primary CTA at brand blue #0d6efd (MwColors::Blue) — matches AI-209 unification.
 *   - Children now use `@section('auth_form') ... @endsection` (the layout owns the master's
 *     `content` slot and yields `auth_form` inside `.mw-auth-card`).
 *   - `auth/reset-password.blade.php` migrated from standalone bare HTML to `@extends('user::layout')`.
 *
 * Tier-3 probe per designer dispatch:
 *   const r = await fetch('/forgot-password');
 *   const html = await r.text();
 *   expect(html).toContain('<header');
 *   expect(html).toContain('mw-auth-card');
 */
class AuthF141ffAI794ForgotPasswordChromeContractTest extends TestCase
{
    private string $layout;
    private string $forgot;
    private string $reset;

    protected function setUp(): void
    {
        parent::setUp();
        $this->layout = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/resources/views/layout.blade.php'
        ));
        $this->forgot = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/resources/views/auth/forgot-password.blade.php'
        ));
        $this->reset = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/resources/views/auth/reset-password.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — layout extends the template master + wraps in chrome
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function layout_extends_active_template_master_with_bootstrap_fallback(): void
    {
        // Pre-strip Blade {{-- ... --}} comments so docblock prose referring to the legacy
        // shape (app::public.partials.header) doesn't false-pass — selector-self-match
        // guard pattern per the recurring LESSONS family.
        $stripped = preg_replace('!{{--.*?--}}!s', '', $this->layout);
        $stripped = preg_replace('!//.*$!m', '', $stripped);

        $this->assertStringContainsString(
            "\$mwAuthExtends = 'templates.bootstrap::layouts.master';",
            $stripped,
            'Layout must default to the Bootstrap template master (matches AI-757 admin login pattern).'
        );

        $this->assertStringContainsString(
            "\$extendsCheckView = 'templates.' . \$templateViewsName . '::layouts.master';",
            $stripped,
            'Layout must check for an active-template master and prefer it over Bootstrap when present.'
        );

        $this->assertMatchesRegularExpression(
            '/@extends\(\s*\$mwAuthExtends\s*\)/',
            $stripped,
            'Layout must @extends($mwAuthExtends) so the template chrome wraps the auth surface.'
        );

        $this->assertStringNotContainsString(
            "@include('app::public.partials.header')",
            $stripped,
            'Legacy bare app::public.partials.header include must be gone — it was the root cause of the unstyled-raw-HTML rendering.'
        );
        $this->assertStringNotContainsString(
            "@include('app::public.partials.footer')",
            $stripped,
            'Legacy bare app::public.partials.footer include must be gone.'
        );
    }

    #[Test]
    public function layout_emits_semantic_header_satisfying_tier3_probe(): void
    {
        // Designer's Tier-3 probe explicitly checks `expect(html).toContain('<header')`
        // — emit a semantic <header> element for the brand-logo block.
        $this->assertMatchesRegularExpression(
            '/<header\s+class="mw-auth-header"/',
            $this->layout,
            'Layout must emit semantic <header class="mw-auth-header"> so designer Tier-3 probe `expect(html).toContain("<header")` passes.'
        );
        $this->assertStringContainsString('</header>', $this->layout);
    }

    #[Test]
    public function layout_wraps_content_in_mw_auth_card_satisfying_tier3_probe(): void
    {
        // Designer's Tier-3 probe explicitly checks `expect(html).toContain('mw-auth-card')`.
        $this->assertStringContainsString(
            'class="mw-auth-card"',
            $this->layout,
            'Layout must wrap rendered auth form in a `.mw-auth-card` div so designer Tier-3 probe `expect(html).toContain("mw-auth-card")` passes.'
        );
        $this->assertStringContainsString(
            'class="mw-auth-container"',
            $this->layout,
            'Layout must wrap the card in a `.mw-auth-container` outer (max-width: 480px constraint).'
        );
    }

    #[Test]
    public function layout_yields_auth_form_section_not_content(): void
    {
        // Children should use `@section('auth_form')` (the layout owns the master's
        // `content` slot). Yielding `auth_form` keeps the section names disambiguated.
        $this->assertMatchesRegularExpression(
            '/@yield\(\s*[\'"]auth_form[\'"]\s*\)/',
            $this->layout,
            'Layout must @yield("auth_form") inside the .mw-auth-card so child sections render in the right scope.'
        );
    }

    #[Test]
    public function layout_brand_logo_capped_at_64x64_via_mw_auth_logo_class(): void
    {
        // Pre-fix the brand mark rendered at ~200x200 (Tailwind `max-width: 70%` on a
        // free-floating <img>). Spec: max 64x64 per designer dispatch.
        //
        // Pin-evolved 2026-05-17 / task-6305c9 / AI-848: original AI-794 spec
        // capped via parent .mw-auth-logo { max-width:64px; max-height:64px; },
        // but parent max-width on an inline-block anchor with no defined width
        // created a shrink-to-fit cycle that settled at 0×0 against the img's
        // width:auto + max-width:100%. AI-848 Slice A moves the design cap from
        // parent max-width to the img's explicit height:64px (and bounds wide
        // brand marks via img max-width:280px), breaking the cycle. The 64x64
        // design intent is preserved — relocated, not removed.
        $this->assertStringContainsString(
            'class="mw-auth-logo"',
            $this->layout,
            'Brand logo anchor must carry `.mw-auth-logo` so CSS reaches the size-cap rules.'
        );
        // Post-AI-848: cap lives on the img element (height:64px) NOT on the parent.
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-header\s+\.mw-auth-logo\s+img\s*\{[^}]*height:\s*64px/',
            $this->layout,
            '`.mw-auth-logo img` must declare `height: 64px` (AI-848 design-cap relocation).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-header\s+\.mw-auth-logo\s+img\s*\{[^}]*max-width:\s*280px/',
            $this->layout,
            '`.mw-auth-logo img` must declare `max-width: 280px` (AI-848 bound for wide brand marks).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — mw-auth-card CSS chrome
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function layout_defines_mw_auth_card_geometry(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s*\{[^}]*border-radius:\s*12px/',
            $this->layout,
            '.mw-auth-card must declare `border-radius: 12px` (consistent with AI-757 auth-card).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s*\{[^}]*box-shadow:/',
            $this->layout,
            '.mw-auth-card must declare a box-shadow for elevation.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-container\s*\{[^}]*max-width:\s*480px/',
            $this->layout,
            '.mw-auth-container must declare `max-width: 480px` (designer spec for auth form width).'
        );
    }

    #[Test]
    public function layout_primary_cta_uses_brand_blue(): void
    {
        // Per designer spec: "Primary CTA brand blue" — MwColors::Blue = #0d6efd.
        // Also matches AI-209 / AI-702 admin shell unification.
        //
        // Pin-evolved 2026-05-17 / task-06892a / AI-794a CHANGE absorption:
        // original AI-794 pin used the `background:` shorthand which lost the
        // cascade fight to the active template's higher-specificity
        // .btn-primary rule (e.g. Big2 ships salmon #F4A261). AI-794a moved
        // to longhand `background-color:` + `!important` on a compound
        // .mw-auth-card .btn-primary selector that defeats the template default.
        // The brand-blue contract is preserved — escalated, not removed.
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s+\.btn-primary\s*\{[^}]*background-color:\s*#0d6efd\s*!important/',
            $this->layout,
            '.mw-auth-card .btn-primary must use brand blue #0d6efd with !important (AI-794a Stage-2 cascade-loss fix).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s+\.form-control:focus\s*\{[^}]*border-color:\s*#0d6efd/',
            $this->layout,
            '.mw-auth-card .form-control:focus must use brand blue #0d6efd for the focus border.'
        );
    }

    #[Test]
    public function layout_has_reduced_motion_guard(): void
    {
        // Per ESE token contract + AI-697/AI-699/AI-700 pattern.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*reduce\)/',
            $this->layout,
            'Layout must include a `@media (prefers-reduced-motion: reduce)` guard so the form-control + btn transitions collapse for users who prefer reduced motion.'
        );
    }

    #[Test]
    public function layout_has_mobile_refinement_breakpoint(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*480px\)/',
            $this->layout,
            'Layout must include a `@media (max-width: 480px)` refinement so the card padding shrinks on narrow viewports.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — form pages wire into the new chrome
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function forgot_password_extends_user_layout_with_auth_form_section(): void
    {
        $this->assertMatchesRegularExpression(
            '/@extends\(\s*[\'"]user::layout[\'"]\s*\)/',
            $this->forgot,
            'forgot-password.blade.php must @extends("user::layout").'
        );
        $this->assertMatchesRegularExpression(
            '/@section\(\s*[\'"]auth_form[\'"]\s*\)/',
            $this->forgot,
            'forgot-password.blade.php must use @section("auth_form") — NOT @section("content"), which is owned by user::layout itself.'
        );
        $this->assertStringContainsString('@endsection', $this->forgot);
    }

    #[Test]
    public function forgot_password_renders_brand_primary_submit_and_back_to_login(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button[^>]*class="btn btn-primary[^"]*"[^>]*type="submit"|<button[^>]*type="submit"[^>]*class="btn btn-primary/',
            $this->forgot,
            'forgot-password must render the submit as <button type="submit" class="btn btn-primary ...">.'
        );
        $this->assertMatchesRegularExpression(
            '/<a[^>]*class="btn btn-link"[^>]*href="\{\{\s*route\([\'"]login[\'"]\)\s*\}\}"/',
            $this->forgot,
            'forgot-password must offer a "Back to login" link via route("login").'
        );
        $this->assertStringContainsString(
            "route('password.email')",
            $this->forgot,
            'forgot-password form action must point to route("password.email").'
        );
    }

    #[Test]
    public function reset_password_migrated_off_standalone_bare_html(): void
    {
        // Pre-fix reset-password.blade.php was a standalone <!DOCTYPE html><html><head>...
        // file with no template chrome. The migration removes those bare-HTML elements.
        $this->assertStringNotContainsString(
            '<!DOCTYPE html>',
            $this->reset,
            'reset-password must no longer declare its own DOCTYPE — extends user::layout instead.'
        );
        $this->assertStringNotContainsString(
            '<html ',
            $this->reset,
            'reset-password must no longer declare its own <html> root.'
        );
        $this->assertStringNotContainsString(
            '<body>',
            $this->reset,
            'reset-password must no longer declare its own <body>.'
        );
        $this->assertMatchesRegularExpression(
            '/@extends\(\s*[\'"]user::layout[\'"]\s*\)/',
            $this->reset,
            'reset-password.blade.php must @extends("user::layout").'
        );
        $this->assertMatchesRegularExpression(
            '/@section\(\s*[\'"]auth_form[\'"]\s*\)/',
            $this->reset,
            'reset-password.blade.php must use @section("auth_form").'
        );
    }

    #[Test]
    public function reset_password_preserves_token_email_and_submit_path(): void
    {
        // Functional regression-guard — the controller logic at
        // UserForgotPasswordController::update() relies on these hidden inputs.
        $this->assertStringContainsString(
            'name="token" value="{{ $token }}"',
            $this->reset,
            'reset-password must preserve the hidden token input (controller relies on it).'
        );
        $this->assertStringContainsString(
            'name="email" value="{{ $email }}"',
            $this->reset,
            'reset-password must preserve the hidden email input.'
        );
        $this->assertStringContainsString(
            "route('password.update')",
            $this->reset,
            'reset-password form action must point to route("password.update").'
        );
        $this->assertStringContainsString(
            'js-submit-change-password',
            $this->reset,
            'reset-password submit must keep the legacy `js-submit-change-password` class hook (external JS may reference it).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers + lineage
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai794_markers_present_across_three_surfaces(): void
    {
        $this->assertStringContainsString('task-2026-05-17-f141ff', $this->layout);
        $this->assertStringContainsString('AI-794', $this->layout);
        $this->assertStringContainsString('task-2026-05-17-f141ff', $this->forgot);
        $this->assertStringContainsString('AI-794', $this->forgot);
        $this->assertStringContainsString('task-2026-05-17-f141ff', $this->reset);
        $this->assertStringContainsString('AI-794', $this->reset);
    }

    #[Test]
    public function layout_docblock_cites_lineage_tickets(): void
    {
        $this->assertStringContainsString(
            'AI-757',
            $this->layout,
            'Layout docblock should cite AI-757 (login parity) as the sibling auth-surface precedent.'
        );
        $this->assertStringContainsString(
            'AI-789',
            $this->layout,
            'Layout docblock should cite AI-789 (reusable empty-state chrome) as the chrome-pattern precedent.'
        );
        $this->assertStringContainsString(
            'AI-793',
            $this->layout,
            'Layout docblock should cite AI-793 (admin 404 styled card) as the sister styled-error surface.'
        );
    }
}
