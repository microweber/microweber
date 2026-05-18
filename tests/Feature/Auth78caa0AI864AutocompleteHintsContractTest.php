<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-18-78caa0 / AI-864 — /login Register tab autocomplete leak.
 * Jira: https://microweber.atlassian.net/browse/AI-864
 *
 * Pre-fix `src/MicroweberPackages/User/resources/views/admin/auth/index.blade.php`
 * Register tab Password (L645) + Confirm Password (L651) both carried
 * `autocomplete="current-password"` — the WHATWG HTML autocomplete spec
 * §4.10.18.7 hint reserved for *signing in with an existing account*.
 * Browsers saw the hint and silently pre-filled BOTH Register fields
 * with the user's saved /login password from prior sessions. Privacy
 * leak on shared/public devices + UX failure on registration happy path
 * + password-manager mismatch (offered FILL instead of GENERATE).
 *
 * Fix shape (2 mandatory attribute changes + adjacent audit):
 *   L645 + L651: `autocomplete="current-password"` → `autocomplete="new-password"`
 *
 * Adjacent autocomplete audit (in-scope per designer dispatch "while in the
 * same file, audit Email/Username inputs"):
 *   L506 Login username/email input → autocomplete="username"
 *   L628 Register first_name → autocomplete="name"
 *   L634 Register email → autocomplete="email"
 *   L640 Register username → autocomplete="username"
 *   L697 Forgot Password email → autocomplete="email"
 *   L512 Login Password — UNCHANGED (correct `current-password` for sign-in)
 *
 * Acceptance gates (verified at HEAD):
 *   - Tier-1 source-pin: counts match dispatch spec
 *   - Tier-2 served-page: curl counts match expected per attribute hint
 *   - Tier-3 runtime: browser autofill on Register tab is empty (Playwright)
 *
 * 4-group structure: A = mandatory password fix (current-password = 1, new-password = 2);
 * B = adjacent autocomplete audit (email/username/name hints on all relevant inputs);
 * C = AI-863 no-regression sentinel (route URLs + zero href="#" + JS intercept removal preserved);
 * D = back-compat regression sentinels (form-control class + name attributes preserved).
 */
class Auth78caa0AI864AutocompleteHintsContractTest extends TestCase
{
    private function authSource(): string
    {
        return (string) file_get_contents(base_path('src/MicroweberPackages/User/resources/views/admin/auth/index.blade.php'));
    }

    private function stripBladeAndPhpComments(string $source): string
    {
        $source = preg_replace('~\{\{--.*?--\}\}~s', '', $source);
        $source = preg_replace('~/\*.*?\*/~s', '', (string) $source);
        $source = preg_replace('~//[^\n]*~', '', (string) $source);
        return (string) $source;
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — mandatory dispatch fix: password autocomplete counts
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function current_password_appears_exactly_once_login_password_only(): void
    {
        // Pre-strip Blade comments so the docblock + inline AI-864 prose
        // (which mentions the literal `current-password` legacy value)
        // does NOT self-match the count (LESSONS selector-self-match
        // UNIFORMITY rule, 20+ session-recurrences).
        $source = $this->stripBladeAndPhpComments($this->authSource());
        $count = substr_count($source, 'autocomplete="current-password"');
        $this->assertSame(
            1,
            $count,
            'Exactly 1 input must carry autocomplete="current-password" — the Login Password (L512). Register Password + Confirm must NOT.'
        );
    }

    #[Test]
    public function new_password_appears_exactly_twice_register_password_and_confirm(): void
    {
        $source = $this->stripBladeAndPhpComments($this->authSource());
        $count = substr_count($source, 'autocomplete="new-password"');
        $this->assertSame(
            2,
            $count,
            'Exactly 2 inputs must carry autocomplete="new-password" — Register Password + Confirm Password. WHATWG spec §4.10.18.7: new-password tells browsers to NOT autofill + offer password-manager GENERATE.'
        );
    }

    #[Test]
    public function register_password_input_carries_new_password_autocomplete(): void
    {
        $source = $this->authSource();
        // Pin the specific Register Password input: must have name="password" + autocomplete="new-password".
        // The Login Password also has name="password" but carries the form-action /login (existing-account
        // sign-in). To disambiguate, slice the Register tab section (between
        // <!-- Register Tab --> and the next <!-- ... Tab --> marker) and assert
        // both Register password inputs inside.
        $registerTabPos = strpos($source, '<!-- Register Tab -->');
        $this->assertNotFalse($registerTabPos, 'Register Tab marker must exist.');
        $forgotTabPos = strpos($source, '<!-- Forgot Password Tab -->', $registerTabPos);
        $this->assertNotFalse($forgotTabPos, 'Forgot Password Tab marker must follow Register Tab.');
        $registerSlice = substr($source, $registerTabPos, $forgotTabPos - $registerTabPos);
        $this->assertMatchesRegularExpression(
            '/<input\s+type="password"[^>]*autocomplete="new-password"[^>]*name="password"[^>]*>/',
            $registerSlice,
            'Register Password input MUST carry autocomplete="new-password".'
        );
        $this->assertMatchesRegularExpression(
            '/<input\s+type="password"[^>]*autocomplete="new-password"[^>]*name="password_confirmation"[^>]*>/',
            $registerSlice,
            'Register Confirm Password input MUST carry autocomplete="new-password".'
        );
    }

    #[Test]
    public function source_carries_ai_864_task_marker(): void
    {
        $source = $this->authSource();
        $this->assertStringContainsString('task-2026-05-18-78caa0', $source, 'AI-864 task-id marker required for cross-surface grep.');
        $this->assertStringContainsString('AI-864', $source);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — adjacent autocomplete audit (Email/Username/Name hints)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function username_autocomplete_present_twice_login_and_register(): void
    {
        $source = $this->stripBladeAndPhpComments($this->authSource());
        $count = substr_count($source, 'autocomplete="username"');
        $this->assertSame(
            2,
            $count,
            'autocomplete="username" must appear on Login email/username input + Register username input — both are primary account identifiers per WHATWG spec.'
        );
    }

    #[Test]
    public function email_autocomplete_present_twice_register_and_forgot(): void
    {
        $source = $this->stripBladeAndPhpComments($this->authSource());
        $count = substr_count($source, 'autocomplete="email"');
        $this->assertSame(
            2,
            $count,
            'autocomplete="email" must appear on Register Email input + Forgot Password Email input.'
        );
    }

    #[Test]
    public function name_autocomplete_present_once_register_first_name(): void
    {
        $source = $this->stripBladeAndPhpComments($this->authSource());
        $count = substr_count($source, 'autocomplete="name"');
        $this->assertSame(
            1,
            $count,
            'autocomplete="name" must appear on Register Full Name input.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — AI-863 no-regression sentinels
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai863_route_urls_preserved_post_ai864(): void
    {
        $source = $this->authSource();
        // AI-863 (commit 6746d59b9a) put real route URLs on the 3 named anchors;
        // AI-864 must NOT regress those.
        $this->assertMatchesRegularExpression(
            '/<a\s+href="\{\{\s*route\(\'password\.request\'\)\s*\}\}"\s+class="forgot-password-link">/',
            $source,
            'AI-863 forgot-password-link route URL must stay intact.'
        );
        $count = preg_match_all('/<a\s+href="\{\{\s*route\(\'login\'\)\s*\}\}"\s+class="login-link">/', $source);
        $this->assertSame(2, $count, 'AI-863 login-link route URLs (2 instances) must stay intact.');
    }

    #[Test]
    public function ai863_js_intercept_removal_preserved(): void
    {
        $source = $this->stripBladeAndPhpComments($this->authSource());
        $this->assertStringNotContainsString(
            "querySelectorAll('.forgot-password-link')",
            $source,
            'AI-863 JS intercept removal must stay intact post-AI-864.'
        );
        $this->assertStringNotContainsString(
            "querySelectorAll('.login-link')",
            $source,
            'AI-863 JS intercept removal must stay intact post-AI-864.'
        );
    }

    #[Test]
    public function ai863_task_marker_preserved(): void
    {
        $source = $this->authSource();
        $this->assertStringContainsString('task-2026-05-18-77da7a', $source, 'AI-863 task-id marker must stay intact.');
        $this->assertStringContainsString('AI-863', $source);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — back-compat regression sentinels
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function form_control_classes_preserved_on_all_inputs(): void
    {
        $source = $this->authSource();
        // Every input that gained an autocomplete attr must STILL carry
        // the `form-control` class (style integration with the rest of
        // the auth chrome).
        $this->assertMatchesRegularExpression(
            '/<input[^>]*class="form-control"[^>]*autocomplete="username"/',
            $source
        );
        $this->assertMatchesRegularExpression(
            '/<input[^>]*class="form-control"[^>]*autocomplete="email"/',
            $source
        );
        $this->assertMatchesRegularExpression(
            '/<input[^>]*class="form-control"[^>]*autocomplete="new-password"/',
            $source
        );
        $this->assertMatchesRegularExpression(
            '/<input[^>]*class="form-control"[^>]*autocomplete="current-password"/',
            $source
        );
    }

    #[Test]
    public function input_name_attributes_preserved(): void
    {
        $source = $this->authSource();
        // The name attributes drive form submission — AI-864 must NOT
        // touch them.
        $this->assertStringContainsString('name="username"', $source, 'name="username" must stay intact.');
        $this->assertStringContainsString('name="email"', $source, 'name="email" must stay intact.');
        $this->assertStringContainsString('name="first_name"', $source, 'name="first_name" must stay intact.');
        $this->assertStringContainsString('name="password"', $source, 'name="password" must stay intact.');
        $this->assertStringContainsString('name="password_confirmation"', $source, 'name="password_confirmation" must stay intact.');
    }
}
