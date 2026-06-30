<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-1dcb94 / AI-757 HIGH — Admin login page brand logo.
 *
 * Dispatch described the admin login page as "missing brand logo + brand expression".
 *
 * State assessment:
 *   - "Forgot password?" link: ALREADY present via AI-863 (task-2026-05-18-77da7a)
 *     as `<a href="{{ route('password.request') }}" class="forgot-password-link">`.
 *   - Brand logo on login form: MISSING — .auth-header had <h2>Welcome</h2> with
 *     no logo above it. This fix adds the brand logo using the same
 *     `app()->ui->admin_logo_login()` helper as AI-794 (layout.blade.php).
 *
 * AI-735 status note:
 *   AI-735 (404/search/missing routes) was addressed by prior work:
 *   - AI-793 (task-2026-05-17-a596d2): admin-prefix 404 via Route::fallback()
 *   - AI-795 (task-2026-05-17-c54f1f): frontend 404 via FrontendController
 *   - AI-837 (task-2026-05-17-3e91f4): /search route registration
 *   All three views exist: resources/views/admin/errors/404.blade.php,
 *   resources/views/frontend/errors/404.blade.php. No further work needed
 *   for AI-735.
 */
class Auth1dcb94AI757AdminLoginBrandLogoContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $raw = (string) file_get_contents(
            base_path('src/MicroweberPackages/User/resources/views/admin/auth/index.blade.php')
        );
        $this->src = $raw;
        // Strip Blade comments before absence assertions
        $this->srcStripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $raw) ?? $raw;
    }

    // ─── AI-757: Brand logo in auth header ───────────────────────────────────

    #[Test]
    public function admin_login_logo_php_block_present(): void
    {
        $this->assertStringContainsString(
            "admin_logo_login()",
            $this->src,
            'admin auth index must call app()->ui->admin_logo_login() for the brand logo.'
        );
    }

    #[Test]
    public function auth_brand_logo_wrapper_present(): void
    {
        $this->assertStringContainsString(
            'auth-brand-logo-wrapper',
            $this->src,
            '.auth-brand-logo-wrapper div must be present in the auth-header.'
        );
    }

    #[Test]
    public function auth_brand_logo_img_class_present(): void
    {
        $this->assertStringContainsString(
            'auth-brand-logo',
            $this->src,
            '.auth-brand-logo class must be applied to the brand logo img.'
        );
    }

    #[Test]
    public function auth_brand_logo_has_alt_text(): void
    {
        // The img element carrying class="auth-brand-logo" must also carry alt=
        // Note: Blade expressions use -> which contains literal > characters,
        // making [^>]* regexes fragile. Use a two-assertion approach instead:
        // confirm both class and alt appear in close proximity (within 500 chars).
        $pos = strrpos($this->src, 'class="auth-brand-logo"');
        $this->assertNotFalse($pos, 'class="auth-brand-logo" must be present in img tag.');
        // Search backward from the class= for the nearest <img
        $imgStart = strrpos(substr($this->src, 0, (int) $pos), '<img');
        $this->assertNotFalse($imgStart, '<img element must precede auth-brand-logo class.');
        // Slice from <img to 400 chars after the class= to capture all attributes
        $imgSlice = substr($this->src, (int) $imgStart, ((int) $pos - (int) $imgStart) + 400);
        $this->assertStringContainsString('alt=', $imgSlice,
            'Brand logo img must have an alt attribute for accessibility.'
        );
    }

    #[Test]
    public function auth_brand_name_fallback_present(): void
    {
        // When no logo URL is configured, the brand name text should be shown
        $this->assertStringContainsString(
            'auth-brand-name',
            $this->src,
            '.auth-brand-name text fallback must be present for installs without a configured logo.'
        );
    }

    #[Test]
    public function css_auth_brand_logo_styles_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.auth-brand-logo\s*\{[^}]*max-height[^}]*\}~s',
            $this->srcStripped,
            '.auth-brand-logo CSS rule must exist with max-height constraint.'
        );
    }

    #[Test]
    public function logo_wrapper_is_inside_auth_header(): void
    {
        // The logo wrapper must appear inside the .auth-header div, before <h2>Welcome</h2>
        $headerStart = strpos($this->srcStripped, 'class="auth-header"');
        $this->assertNotFalse($headerStart);
        $logoPos = strpos($this->srcStripped, 'auth-brand-logo-wrapper', (int) $headerStart);
        $welcomePos = strpos($this->srcStripped, '<h2>Welcome</h2>', (int) $headerStart);
        $this->assertNotFalse($logoPos, 'Logo wrapper must be inside .auth-header.');
        $this->assertNotFalse($welcomePos, '<h2>Welcome</h2> must be inside .auth-header.');
        $this->assertLessThan(
            $welcomePos,
            $logoPos,
            'Brand logo wrapper must appear BEFORE <h2>Welcome</h2>.'
        );
    }

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-1dcb94', $this->src);
    }

    // ─── AI-757 regression: forgot-password link (AI-863) still present ──────

    #[Test]
    public function ai863_forgot_password_route_still_present(): void
    {
        $this->assertStringContainsString(
            "route('password.request')",
            $this->src,
            'AI-863 forgot-password route anchor must still be present.'
        );
    }

    // ─── AI-735 regression guards ─────────────────────────────────────────────

    #[Test]
    public function ai793_admin_404_view_exists(): void
    {
        $this->assertFileExists(
            base_path('resources/views/admin/errors/404.blade.php'),
            'AI-793 admin-styled 404 view must exist.'
        );
    }

    #[Test]
    public function ai793_admin_404_view_has_back_to_dashboard(): void
    {
        $view = (string) file_get_contents(
            base_path('resources/views/admin/errors/404.blade.php')
        );
        $this->assertStringContainsString('Back to dashboard', $view,
            'AI-793 admin 404 view must have a Back to dashboard CTA.'
        );
    }

    #[Test]
    public function ai795_frontend_404_view_exists(): void
    {
        $this->assertFileExists(
            base_path('resources/views/frontend/errors/404.blade.php'),
            'AI-795 frontend 404 view must exist.'
        );
    }

    #[Test]
    public function ai793_route_fallback_has_admin_prefix_branch(): void
    {
        $routes = (string) file_get_contents(
            base_path('src/MicroweberPackages/Frontend/routes/web.php')
        );
        $this->assertStringContainsString('admin.errors.404', $routes,
            'AI-793 Route::fallback must reference the admin-styled 404 view.'
        );
    }
}
