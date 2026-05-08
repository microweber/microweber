<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Cart\Services\CartCouponService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-88 / AI-76 / TICKET-CV — newsletter coupon admin-on-behalf-of
 * customer email resolution regression coverage.
 *
 * Pins:
 *   - CartCouponService exposes a public `resolveCustomerEmail()`
 *     helper that prefers the `checkout` session "email" over
 *     Auth::user()->email.
 *   - The CartApiController::applyCoupon() route threads through
 *     `resolveCustomerEmail()` (not `Auth::user()?->email`).
 *   - Functional: when the checkout session carries a customer
 *     email, the resolver returns it; when empty, falls back to
 *     auth; when both are empty, returns null (guest path).
 *
 * Style after the cycle-52..87 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class CouponCustomerEmailResolverContractTest extends TestCase
{
    private string $serviceSrc;
    private string $controllerSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceSrc = file_get_contents(base_path(
            'Modules/Cart/Services/CartCouponService.php'
        ));
        $this->controllerSrc = file_get_contents(base_path(
            'Modules/Cart/Http/Controllers/Api/CartApiController.php'
        ));
    }

    #[Test]
    public function service_exposes_public_resolve_customer_email_helper(): void
    {
        // The helper must be `public` (callable from controllers AND
        // the legacy coupon_apply() helper) and must declare a
        // nullable string return so guests cleanly resolve to null.
        $this->assertStringContainsString(
            'public function resolveCustomerEmail(): ?string',
            $this->serviceSrc,
            'CartCouponService: must expose public resolveCustomerEmail(): ?string'
        );
    }

    #[Test]
    public function resolver_prefers_checkout_session_email_over_auth(): void
    {
        // Pin the resolution order in source:
        //   1. session_get('checkout')['email']
        //   2. auth()->user()?->email
        //   3. null
        $this->assertStringContainsString(
            "session_get('checkout')",
            $this->serviceSrc,
            'CartCouponService: resolver must read session_get(\'checkout\')'
        );

        // The session-email lookup must come BEFORE the auth-email
        // lookup. Without this byte-offset pin, a future refactor
        // could silently flip the order and re-introduce the
        // admin-on-behalf-of bug.
        $sessionPos = strpos(
            $this->serviceSrc,
            "session_get('checkout')"
        );
        $authPos = strpos(
            $this->serviceSrc,
            'auth()->user()?->email'
        );
        $this->assertNotFalse($sessionPos, 'session_get(\'checkout\') lookup must exist');
        $this->assertNotFalse($authPos, 'auth()->user()?->email fallback must exist');
        $this->assertLessThan(
            $authPos,
            $sessionPos,
            'CartCouponService: checkout-session email lookup must come BEFORE auth fallback (admin-on-behalf-of fix depends on this order)'
        );
    }

    #[Test]
    public function resolver_returns_session_email_when_present(): void
    {
        // Functional pin: directly invoke the resolver with a
        // populated checkout session and assert it returns that
        // email. We mock session_get() via Laravel's Session
        // facade (the helper proxies to it).
        \Illuminate\Support\Facades\Session::put('checkout', [
            'email' => 'customer@example.com',
            'phone' => '+1-555-0100',
        ]);

        $service = app(CartCouponService::class);
        $resolved = $service->resolveCustomerEmail();

        $this->assertSame(
            'customer@example.com',
            $resolved,
            'resolveCustomerEmail must return checkout session email when present'
        );

        // Cleanup so other tests aren't polluted.
        \Illuminate\Support\Facades\Session::forget('checkout');
    }

    #[Test]
    public function resolver_returns_null_when_session_and_auth_both_empty(): void
    {
        // Guest path — no session email, no auth email. Must
        // return null cleanly so CouponService skips the
        // email-keyed rate limit check (see CouponService:204, 241).
        \Illuminate\Support\Facades\Session::forget('checkout');
        \Illuminate\Support\Facades\Auth::logout();

        $service = app(CartCouponService::class);
        $resolved = $service->resolveCustomerEmail();

        $this->assertNull(
            $resolved,
            'resolveCustomerEmail must return null on the guest path so CouponService skips email-keyed rate limiting'
        );
    }

    #[Test]
    public function resolver_trims_whitespace_in_session_email(): void
    {
        // session emails sourced from form input may carry
        // trailing whitespace; the resolver must trim before
        // returning so downstream rate-limit keys don't see
        // ` jane@example.com ` and ` jane@example.com` as
        // different identities.
        \Illuminate\Support\Facades\Session::put('checkout', [
            'email' => '  jane@example.com  ',
        ]);

        $service = app(CartCouponService::class);
        $this->assertSame(
            'jane@example.com',
            $service->resolveCustomerEmail(),
            'resolveCustomerEmail must trim whitespace from session email'
        );

        \Illuminate\Support\Facades\Session::forget('checkout');
    }

    #[Test]
    public function api_controller_routes_through_resolver_not_auth(): void
    {
        // The cycle-88 fix is upstream of CartCouponService::applyCoupon;
        // the API controller must call resolveCustomerEmail(), NOT
        // Auth::user()?->email directly.
        $this->assertStringContainsString(
            '$cartCouponService->resolveCustomerEmail()',
            $this->controllerSrc,
            'CartApiController::applyCoupon: must thread through $cartCouponService->resolveCustomerEmail()'
        );

        // Negative: the prior shape (`Auth::user()?->email` as the
        // 2nd arg to applyCoupon) must be gone — strip PHP // line
        // comments first so the audit-trail doc-comment that mentions
        // it doesn't trigger a false positive.
        $stripped = preg_replace('!//.*$!m', '', $this->controllerSrc);
        $this->assertDoesNotMatchRegularExpression(
            '/applyCoupon\\(\\s*\\$couponCode,\\s*Auth::user\\(\\)\\?->email,/',
            $stripped,
            'CartApiController: prior `applyCoupon($couponCode, Auth::user()?->email, ...)` shape must be gone'
        );
    }
}
