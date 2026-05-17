<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * task-2026-05-17-7c3881 / AI-851 [P3] — collapse the bare /checkout +
 * empty-cart double-redirect chain into a single 302.
 *
 * Pre-fix chain (user types /checkout directly with empty cart):
 *   GET /checkout            → 302 → /checkout/checkout
 *      (Filament panel default home-page redirect)
 *   GET /checkout/checkout   → 302 → /cart
 *      (CheckoutPage::mount() detects empty cart)
 *   GET /cart                → 200 (empty-state per AI-796)
 *
 * Two 302 hops to land where the user belongs + no notice explaining
 * why /checkout bounced to /cart. UX-polish defect, not a correctness
 * defect — final destination + body are right via AI-796.
 *
 * Post-fix chain:
 *   GET /checkout            → 302 → /cart?notice=empty-cart-no-checkout
 *      (this middleware fires on bare /checkout, detects empty cart,
 *       short-circuits the Filament panel home redirect)
 *   GET /cart?notice=...     → 200 (empty-state + notice banner)
 *
 * Single 302 to reach the right destination + a friendly notice
 * explaining the bounce.
 *
 * Wired in Modules/Checkout/Providers/FilamentCheckoutPanelProvider.php
 * `->middleware([...])` chain, placed BEFORE the Filament authentication
 * middleware so it fires on the bare /checkout request before the
 * panel-default home redirect kicks in.
 *
 * Scope guard — only fires on the BARE /checkout path (`$request->path()
 * === 'checkout'`). Subroutes (/checkout/checkout, /checkout/billing-
 * portal, /checkout/purchase-success, etc.) are untouched. CheckoutPage::
 * mount() still handles the /checkout/checkout empty-cart redirect for
 * users who reach that URL via bookmarks/direct-nav — its destination
 * is also updated to /cart?notice=empty-cart-no-checkout so both code
 * paths land users on the same surface with the same notice.
 *
 * Non-empty cart: falls through to the Filament panel default home
 * redirect (→ /checkout/checkout → CheckoutPage which renders the
 * checkout wizard with the cart items).
 */
class RedirectEmptyCheckoutToCart
{
    public const NOTICE_PARAM = 'notice';
    public const NOTICE_VALUE = 'empty-cart-no-checkout';

    public function handle(Request $request, Closure $next): Response
    {
        // Scope guard: only the BARE /checkout path triggers the short-
        // circuit. Subroutes (any path with a `/` after `checkout`) are
        // handled by their own controllers / Filament pages.
        if ($request->path() === 'checkout' && $this->cartIsEmpty()) {
            $cartUrl = $this->resolveCartUrl();
            return redirect()->to($cartUrl . '?' . self::NOTICE_PARAM . '=' . self::NOTICE_VALUE, 302);
        }

        return $next($request);
    }

    /**
     * Mirrors the cart-empty detection in CheckoutPage::mount():
     * if get_cart() is null/empty AND there's no session order_id to
     * recover from, the cart is empty.
     */
    protected function cartIsEmpty(): bool
    {
        try {
            $cart = function_exists('get_cart') ? get_cart() : null;
            if ($cart) {
                return false;
            }

            // Session order_id recovery path (mirrors CheckoutPage::mount()
            // lines 27-32). If there's a recoverable order, the cart isn't
            // truly empty — let the normal flow proceed.
            $orderId = app()->user_manager->session_get('order_id');
            if ($orderId) {
                return false;
            }

            return ! app()->cart_manager->get();
        } catch (\Throwable $e) {
            // First-boot / missing-migrations / cart-manager-not-bound
            // edge cases — treat as "not empty" so we don't redirect on
            // a defective state. Better to land on /checkout/checkout
            // (Filament panel default) than to redirect on a possibly
            // false-positive empty cart.
            return false;
        }
    }

    protected function resolveCartUrl(): string
    {
        try {
            if (\Illuminate\Support\Facades\Route::has('shop.cart')) {
                return route('shop.cart');
            }
        } catch (\Throwable $e) {
            // Route facade may not be available in early middleware
            // boot states — fall through to /cart literal.
        }
        return '/cart';
    }
}
