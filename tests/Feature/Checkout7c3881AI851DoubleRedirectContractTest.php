<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-7c3881 / AI-851 [P3] — collapse the bare /checkout +
 * empty-cart double-redirect chain into a single 302 + add notice banner.
 *
 * Jira: https://microweber.atlassian.net/browse/AI-851
 *
 * Pre-fix chain (user types /checkout directly with empty cart):
 *   GET /checkout            → 302 → /checkout/checkout  (Filament panel default)
 *   GET /checkout/checkout   → 302 → /cart               (CheckoutPage::mount)
 *   GET /cart                → 200 (empty-state per AI-796)
 *
 * Post-fix:
 *   GET /checkout            → 302 → /cart?notice=empty-cart-no-checkout
 *   GET /cart?notice=...     → 200 (empty-state + banner)
 *
 * Selector-self-match guard UNIFORMITY (post-task-7aa48a default-on
 * protocol): docblock + inline source comments legitimately mention
 * the legacy double-redirect chain shape. Absence assertions pre-strip
 * PHP/Blade comments before grepping.
 */
class Checkout7c3881AI851DoubleRedirectContractTest extends TestCase
{
    private const MIDDLEWARE = 'Modules/Checkout/Http/Middleware/RedirectEmptyCheckoutToCart.php';
    private const PANEL_PROVIDER = 'Modules/Checkout/Providers/FilamentCheckoutPanelProvider.php';
    private const CHECKOUT_PAGE = 'Modules/Checkout/Filament/Resources/Pages/CheckoutPage.php';
    private const CART_VIEW = 'Modules/Checkout/resources/views/livewire/cart-items.blade.php';

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — RedirectEmptyCheckoutToCart middleware exists + correct shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function middleware_file_exists(): void
    {
        $this->assertFileExists(
            base_path(self::MIDDLEWARE),
            'AI-851: Modules/Checkout/Http/Middleware/RedirectEmptyCheckoutToCart.php must exist.'
        );
    }

    #[Test]
    public function middleware_class_has_canonical_constants(): void
    {
        $source = $this->read(self::MIDDLEWARE);
        $this->assertMatchesRegularExpression(
            "/public const NOTICE_PARAM\s*=\s*'notice'/",
            $source,
            'AI-851: middleware must define `NOTICE_PARAM = \'notice\'` constant (shared with CheckoutPage::mount + the cart-items view notice-banner conditional).'
        );
        $this->assertMatchesRegularExpression(
            "/public const NOTICE_VALUE\s*=\s*'empty-cart-no-checkout'/",
            $source,
            'AI-851: middleware must define `NOTICE_VALUE = \'empty-cart-no-checkout\'` constant.'
        );
    }

    #[Test]
    public function middleware_scope_guards_to_bare_checkout_path(): void
    {
        $source = $this->read(self::MIDDLEWARE);
        // Scope guard: only the BARE /checkout path (`$request->path()
        // === 'checkout'`) triggers the short-circuit. Subroutes are
        // handled by their own controllers / Filament pages.
        $this->assertMatchesRegularExpression(
            "/\\\$request->path\(\)\s*===\s*'checkout'/",
            $source,
            "AI-851: middleware must scope-guard to the BARE /checkout path via `\$request->path() === 'checkout'`. Subroutes (/checkout/checkout etc.) must fall through to their own handlers."
        );
    }

    #[Test]
    public function middleware_redirects_to_cart_with_notice(): void
    {
        $source = $this->read(self::MIDDLEWARE);
        $this->assertMatchesRegularExpression(
            '/redirect\(\)->to\(\s*\$cartUrl\s*\.\s*\'\?\'\s*\.\s*self::NOTICE_PARAM\s*\.\s*\'=\'\s*\.\s*self::NOTICE_VALUE\s*,\s*302\s*\)/',
            $source,
            'AI-851: middleware must redirect to /cart with `?notice=empty-cart-no-checkout` query param at 302 status.'
        );
    }

    #[Test]
    public function middleware_cart_is_empty_check_mirrors_checkout_page(): void
    {
        $source = $this->read(self::MIDDLEWARE);
        // The empty-cart detection must mirror the existing CheckoutPage::
        // mount() logic (get_cart() + session order_id recovery +
        // cart_manager->get()) so both code paths agree on what "empty"
        // means.
        $this->assertStringContainsString(
            'get_cart()',
            $source,
            'AI-851: middleware cartIsEmpty() must call get_cart() (mirror of CheckoutPage::mount line 27).'
        );
        $this->assertStringContainsString(
            "session_get('order_id')",
            $source,
            'AI-851: middleware must check session order_id (mirror of CheckoutPage::mount line 28).'
        );
        $this->assertStringContainsString(
            'app()->cart_manager->get()',
            $source,
            'AI-851: middleware must finalize with `app()->cart_manager->get()` (mirror of CheckoutPage::mount line 35).'
        );
    }

    #[Test]
    public function middleware_falls_back_to_literal_cart_url_when_route_helper_unavailable(): void
    {
        $source = $this->read(self::MIDDLEWARE);
        // The resolveCartUrl() helper mirrors CheckoutPage::mount line 36:
        // prefer `route('shop.cart')` when registered, fall back to `/cart`
        // literal when not.
        $this->assertStringContainsString(
            "Route::has('shop.cart')",
            $source,
            'AI-851: middleware must check Route::has(\'shop.cart\') before using route() helper.'
        );
        $this->assertStringContainsString(
            "return '/cart'",
            $source,
            'AI-851: middleware must fall back to literal `/cart` when the named route is not registered.'
        );
    }

    #[Test]
    public function middleware_wraps_db_calls_in_try_catch(): void
    {
        $source = $this->read(self::MIDDLEWARE);
        // First-boot / missing-migrations / cart-manager-not-bound edge
        // cases must not crash the middleware — treat as "not empty"
        // (let the normal flow proceed).
        $this->assertMatchesRegularExpression(
            '/try\s*\{[\s\S]*?\}\s*catch\s*\(\s*\\\\?Throwable\s+\$e\s*\)/',
            $source,
            'AI-851: middleware cartIsEmpty() must wrap DB calls in try/catch so first-boot / cart-manager-not-bound states default to "not empty" instead of crashing.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Middleware registered in FilamentCheckoutPanelProvider
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function panel_provider_imports_middleware(): void
    {
        $source = $this->read(self::PANEL_PROVIDER);
        $this->assertStringContainsString(
            'use Modules\\Checkout\\Http\\Middleware\\RedirectEmptyCheckoutToCart;',
            $source,
            'AI-851: FilamentCheckoutPanelProvider must `use` the RedirectEmptyCheckoutToCart middleware class.'
        );
    }

    #[Test]
    public function panel_provider_registers_middleware_in_chain(): void
    {
        $source = $this->read(self::PANEL_PROVIDER);
        // Strip PHP block + line comments — the docblock prose mentions
        // the middleware class name; absence-by-rule assertions need the
        // comment-strip per selector-self-match guard UNIFORMITY.
        $stripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $source);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertStringContainsString(
            'RedirectEmptyCheckoutToCart::class',
            $stripped,
            'AI-851: FilamentCheckoutPanelProvider middleware chain must include RedirectEmptyCheckoutToCart::class.'
        );
    }

    #[Test]
    public function panel_provider_middleware_ordering_before_filament_chain(): void
    {
        $source = $this->read(self::PANEL_PROVIDER);
        // The middleware must fire BEFORE SubstituteBindings + Filament's
        // dispatchers so the bare /checkout short-circuit triggers
        // BEFORE Filament's panel-default home redirect.
        $middlewarePos = strpos($source, 'RedirectEmptyCheckoutToCart::class');
        $bindingsPos = strpos($source, 'SubstituteBindings::class');
        $dispatchPos = strpos($source, 'DispatchServingFilamentEvent::class');

        $this->assertNotFalse($middlewarePos, 'AI-851: middleware class reference must be present.');
        $this->assertNotFalse($bindingsPos, 'AI-851: SubstituteBindings reference must be present.');
        $this->assertNotFalse($dispatchPos, 'AI-851: DispatchServingFilamentEvent reference must be present.');
        $this->assertLessThan(
            $bindingsPos,
            $middlewarePos,
            'AI-851: RedirectEmptyCheckoutToCart middleware must be listed BEFORE SubstituteBindings in the middleware chain so it fires earlier.'
        );
        $this->assertLessThan(
            $dispatchPos,
            $middlewarePos,
            'AI-851: RedirectEmptyCheckoutToCart middleware must be listed BEFORE DispatchServingFilamentEvent so it fires before Filament panel default home redirect.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — CheckoutPage::mount() destination updated to carry notice
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function checkout_page_mount_appends_notice_query_param(): void
    {
        $source = $this->read(self::CHECKOUT_PAGE);
        // CheckoutPage::mount must append the notice query param to its
        // cart-redirect destination so users reaching /checkout/checkout
        // via bookmarks land on the same surface with the same notice
        // as users typing /checkout (intercepted by middleware).
        $this->assertStringContainsString(
            'NOTICE_PARAM',
            $source,
            'AI-851: CheckoutPage::mount() must reference RedirectEmptyCheckoutToCart::NOTICE_PARAM for the redirect query string (consistency with middleware).'
        );
        $this->assertStringContainsString(
            'NOTICE_VALUE',
            $source,
            'AI-851: CheckoutPage::mount() must reference RedirectEmptyCheckoutToCart::NOTICE_VALUE for the redirect query string.'
        );
    }

    #[Test]
    public function checkout_page_mount_preserves_existing_cart_url_resolution(): void
    {
        $source = $this->read(self::CHECKOUT_PAGE);
        // Pre-fix code resolved the cart URL via Route::has('shop.cart')
        // with /cart literal fallback. AI-851 must preserve that
        // resolution shape — only the QUERY STRING is appended.
        $this->assertStringContainsString(
            "Route::has('shop.cart')",
            $source,
            'AI-851 regression-guard: CheckoutPage::mount() must keep the Route::has(\'shop.cart\') guard for cart URL resolution.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Cart-items view shows the notice banner conditionally
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function cart_items_view_emits_notice_banner_when_query_param_matches(): void
    {
        $source = $this->read(self::CART_VIEW);
        // The banner branch must be guarded by a query-param check
        // matching the canonical 'empty-cart-no-checkout' value.
        $this->assertMatchesRegularExpression(
            "/@if\s*\(\s*request\(\)->query\(\s*'notice'\s*\)\s*===\s*'empty-cart-no-checkout'\s*\)/",
            $source,
            "AI-851: cart-items view must check `request()->query('notice') === 'empty-cart-no-checkout'` to gate the notice banner."
        );
    }

    #[Test]
    public function cart_items_view_notice_banner_has_aria_attributes(): void
    {
        $source = $this->read(self::CART_VIEW);
        // The banner is a `role="status"` + `aria-live="polite"` region
        // so screen readers announce the notice without preempting other
        // focus changes.
        $this->assertMatchesRegularExpression(
            '/class="mw-cart-empty__notice"\s+role="status"\s+aria-live="polite"/',
            $source,
            'AI-851: cart-items view notice banner must carry `role="status"` + `aria-live="polite"` for AT accessibility.'
        );
    }

    #[Test]
    public function cart_items_view_notice_banner_copy_uses_underscore_e_helper(): void
    {
        $source = $this->read(self::CART_VIEW);
        // Per SUMMARY.md gotcha + AI-796 lesson: Microweber's _e($str,
        // true) is the right helper for trailing-period translatable
        // strings. Laravel __() returns empty for trailing-period keys.
        $this->assertMatchesRegularExpression(
            "/_e\(\s*'You tried to check out but your cart is empty — add a product first\.'\s*,\s*true\s*\)/",
            $source,
            "AI-851: cart-items view notice banner copy must use `_e('...', true)` helper (per AI-796 / SUMMARY.md gotcha on Laravel __() trailing-period behaviour)."
        );
    }

    #[Test]
    public function cart_items_view_preserves_ai796_empty_state_baseline(): void
    {
        $source = $this->read(self::CART_VIEW);
        // AI-796 empty-state contract: .mw-cart-empty heading + body +
        // single Continue-shopping CTA. AI-851 ADDS the notice banner;
        // must NOT touch the AI-796 baseline.
        $this->assertStringContainsString(
            '<h2 class="mw-cart-empty__heading">{{ __(\'Your cart is empty\') }}</h2>',
            $source,
            'AI-851 regression-guard: AI-796 empty-state heading "Your cart is empty" must remain.'
        );
        $this->assertStringContainsString(
            "_e('Browse our products and add items.', true)",
            $source,
            'AI-851 regression-guard: AI-796 empty-state body copy must remain.'
        );
        $this->assertStringContainsString(
            'mw-cart-empty-cta',
            $source,
            'AI-851 regression-guard: AI-796 empty-state Continue-shopping CTA class must remain.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Task-id markers + audit-trail discoverability
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_middleware(): void
    {
        $source = $this->read(self::MIDDLEWARE);
        $this->assertStringContainsString(
            'task-2026-05-17-7c3881',
            $source,
            'AI-851: middleware must carry the task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-851',
            $source,
            'AI-851: middleware must carry the AI-851 ticket marker.'
        );
    }

    #[Test]
    public function task_id_marker_present_in_panel_provider(): void
    {
        $source = $this->read(self::PANEL_PROVIDER);
        $this->assertStringContainsString(
            'task-2026-05-17-7c3881',
            $source,
            'AI-851: panel provider must carry the task-id marker near the middleware registration.'
        );
    }

    #[Test]
    public function task_id_marker_present_in_checkout_page(): void
    {
        $source = $this->read(self::CHECKOUT_PAGE);
        $this->assertStringContainsString(
            'task-2026-05-17-7c3881',
            $source,
            'AI-851: CheckoutPage::mount() must carry the task-id marker near the destination-URL update.'
        );
    }

    #[Test]
    public function task_id_marker_present_in_cart_items_view(): void
    {
        $source = $this->read(self::CART_VIEW);
        $this->assertStringContainsString(
            'task-2026-05-17-7c3881',
            $source,
            'AI-851: cart-items view must carry the task-id marker near the notice-banner block.'
        );
    }
}
