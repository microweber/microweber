<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-093946 / AI-757 + AI-735 — Purposeful background + scope guard.
 *
 * AI-757 remaining item: "flat grey background" → purposeful brand-tinted gradient.
 *   - .auth-container made full-viewport flex (min-height: 100vh; max-width: 100%)
 *   - Brand gradient: linear-gradient(160deg, #e8f0fe 0%, #f0f6ff 60%, #ffffff 100%)
 *   - Dark mode: linear-gradient(160deg, #0f172a 0%, #1e293b 60%, #0f172a 100%)
 *   - .auth-card gains explicit max-width: 480px so card stays constrained
 *
 * AI-735 scope expansion: "/admin/modules" and "/admin/shop/orders" flagged.
 *   - Both fall through to Route::fallback() which returns the AI-793 admin-styled
 *     404 view (resources/views/admin/errors/404.blade.php) — NOT the generic
 *     "My title" placeholder. No additional route registrations needed.
 *   - The actual orders route is /admin/orders (not /admin/shop/orders).
 *   - Regression guards confirm the Route::fallback() still carries the
 *     admin-prefix branch that renders admin.errors.404.
 */
class Auth093946AI757BackgroundAI735ScopeContractTest extends TestCase
{
    private string $authSrc;
    private string $authStripped;
    private string $routesSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $authRaw = (string) file_get_contents(
            base_path('src/MicroweberPackages/User/resources/views/admin/auth/index.blade.php')
        );
        $this->authSrc = $authRaw;
        $this->authStripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', $authRaw, '') ?? $authRaw;
        // Strip CSS comments for CSS-only assertions
        $this->authStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $authRaw) ?? $authRaw;

        $this->routesSrc = (string) file_get_contents(
            base_path('src/MicroweberPackages/Frontend/routes/web.php')
        );
    }

    // ─── AI-757: Branded background ──────────────────────────────────────────

    #[Test]
    public function auth_container_is_full_viewport(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.auth-container\s*\{[^}]*min-height:\s*100vh~s',
            $this->authStripped,
            '.auth-container must have min-height: 100vh for full-viewport coverage.'
        );
    }

    #[Test]
    public function auth_container_has_brand_gradient_background(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.auth-container\s*\{[^}]*background:\s*linear-gradient~s',
            $this->authStripped,
            '.auth-container must have a linear-gradient background.'
        );
        // Gradient uses brand-blue tint (#e8f0fe)
        $this->assertStringContainsString('#e8f0fe', $this->authSrc,
            'Brand gradient must use the brand-blue tint #e8f0fe.'
        );
    }

    #[Test]
    public function auth_container_is_flex_centered(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.auth-container\s*\{[^}]*display:\s*flex~s',
            $this->authStripped,
            '.auth-container must use display: flex for centering.'
        );
    }

    #[Test]
    public function auth_card_has_explicit_max_width(): void
    {
        // .auth-card must have its own max-width since container is now full-width
        $this->assertMatchesRegularExpression(
            '~\.auth-card\s*\{[^}]*max-width:\s*480px~s',
            $this->authStripped,
            '.auth-card must have explicit max-width: 480px since container is full-width.'
        );
    }

    #[Test]
    public function dark_mode_background_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark\s+\.auth-container[^{]*\{[^}]*background~s',
            $this->authStripped,
            'Dark mode background override must be present on .dark .auth-container.'
        );
        // Dark mode uses navy tone
        $this->assertStringContainsString('#0f172a', $this->authSrc,
            'Dark mode gradient must use dark navy #0f172a.'
        );
    }

    #[Test]
    public function dark_mode_card_styles_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.dark\s+\.auth-card[^{]*\{[^}]*background~s',
            $this->authStripped,
            'Dark mode .auth-card background override must be present.'
        );
    }

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-093946', $this->authSrc);
    }

    // ─── AI-735 scope expansion: /admin/modules + /admin/shop/orders ─────────

    #[Test]
    public function route_fallback_handles_admin_modules_and_shop_orders(): void
    {
        // The admin-prefix branch in Route::fallback() handles all /admin/*
        // unmatched URLs including /admin/modules and /admin/shop/orders.
        // Verify the branch still renders admin.errors.404.
        $this->assertStringContainsString(
            'admin.errors.404',
            $this->routesSrc,
            'Route::fallback must reference admin.errors.404 for unmatched admin URLs.'
        );
    }

    #[Test]
    public function admin_404_view_exists_for_unmatched_admin_routes(): void
    {
        $this->assertFileExists(
            base_path('resources/views/admin/errors/404.blade.php'),
            'Admin-styled 404 view must exist to handle /admin/modules, /admin/shop/orders etc.'
        );
    }

    #[Test]
    public function admin_404_view_has_back_to_dashboard_navigation(): void
    {
        $view = (string) file_get_contents(
            base_path('resources/views/admin/errors/404.blade.php')
        );
        $this->assertStringContainsString('Back to dashboard', $view,
            'Admin 404 must have "Back to dashboard" navigation — not a generic placeholder.'
        );
        $this->assertStringContainsString('mw-admin-empty-state', $view,
            'Admin 404 must use the branded admin-empty-state chrome.'
        );
    }

    // ─── AI-757 regression: prior fixes still intact ─────────────────────────

    #[Test]
    public function ai863_forgot_password_link_still_present(): void
    {
        $this->assertStringContainsString(
            "route('password.request')",
            $this->authSrc
        );
    }

    #[Test]
    public function ai757_brand_logo_still_present(): void
    {
        $this->assertStringContainsString(
            'admin_logo_login()',
            $this->authSrc
        );
    }
}
