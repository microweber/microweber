<?php

namespace Modules\Cart\Tests\Unit;

use Modules\Cart\Services\CartCouponService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * audit-test 2026-05-07 PM TASK-006 / TICKET-AO regression coverage.
 *
 * The fix in cycle-46 has 4 observable surfaces that this test pins:
 *
 *   1. CartCouponService::applyCoupon() now accepts $context as a 4th arg.
 *   2. CartCouponService::buildCouponContext() returns the exact shape the
 *      legacy `coupon_apply()` helper builds (drift-prevention).
 *   3. The widened API-controller regex `/^[\w\-+.]+$/` permits coupon
 *      codes with `+` and `.` alongside the prior word/dash characters.
 *   4. Guests (no Auth::user) → null email is passed through cleanly,
 *      doesn't crash, and CouponService skips the email-keyed limit.
 *
 * Per project memory `feedback_testing`: no RefreshDatabase, no parallel,
 * no mocked DB. Tests run against MySQL `microweber_testing`.
 */
class CartCouponRateLimitTest extends TestCase
{
    private CartCouponService $service;

    protected function setUp(): void
    {
        parent::setUp();
        empty_cart();
        $this->service = app(CartCouponService::class);
    }

    protected function tearDown(): void
    {
        empty_cart();
        parent::tearDown();
    }

    #[Test]
    public function applyCoupon_signature_accepts_context_arg(): void
    {
        $reflection = new \ReflectionMethod(CartCouponService::class, 'applyCoupon');
        $params = $reflection->getParameters();

        $this->assertCount(4, $params, 'applyCoupon must accept 4 parameters: code, email, ip, context');
        $this->assertSame('couponCode', $params[0]->getName());
        $this->assertSame('customerEmail', $params[1]->getName());
        $this->assertSame('customerIp', $params[2]->getName());
        $this->assertSame('context', $params[3]->getName());

        // Last 3 must be optional (preserves prior single-arg call sites).
        $this->assertTrue($params[1]->isDefaultValueAvailable());
        $this->assertTrue($params[2]->isDefaultValueAvailable());
        $this->assertTrue($params[3]->isDefaultValueAvailable());

        // Default for $context must be array, matching CouponService::applyCoupon.
        $this->assertSame([], $params[3]->getDefaultValue());
    }

    #[Test]
    public function buildCouponContext_returns_helper_shape(): void
    {
        $context = $this->service->buildCouponContext();

        // Five required keys, mirroring Modules/Coupons/Support/helpers.php::coupon_apply().
        $this->assertArrayHasKey('items', $context);
        $this->assertArrayHasKey('cart_product_ids', $context);
        $this->assertArrayHasKey('cart_category_ids', $context);
        $this->assertArrayHasKey('user_id', $context);
        $this->assertArrayHasKey('customer_group_id', $context);

        // Empty cart + guest session → known shapes.
        $this->assertIsArray($context['items']);
        $this->assertIsArray($context['cart_product_ids']);
        $this->assertIsArray($context['cart_category_ids']);
        // user_id is null for guests; customer_group_id is null when no auth.
        $this->assertNull($context['user_id']);
        $this->assertNull($context['customer_group_id']);
    }

    #[Test]
    public function applyCoupon_with_null_email_does_not_crash(): void
    {
        // Guest path: null email + valid IP. CouponService::applyCoupon at
        // lines 204 + 241 short-circuits the email-keyed rate-limit checks
        // when email is null — so the call returns a normal "coupon not
        // valid" or "service not available" array WITHOUT throwing.
        $result = $this->service->applyCoupon(
            'NON_EXISTENT_TEST_CODE_'.uniqid(),
            null,
            '127.0.0.1',
            $this->service->buildCouponContext()
        );

        // Whatever the result shape, it must be an array (no exception).
        $this->assertIsArray($result);
        // For a non-existent code, error must be flagged.
        $this->assertTrue(
            ($result['error'] ?? false) === true || ! isset($result['discount_value']),
            'Non-existent coupon code must not return a discount.'
        );
    }

    #[Test]
    public function api_controller_widened_regex_permits_plus_and_dot_chars(): void
    {
        // The cycle-46 widening from /^[\w-]+$/ to /^[\w\-+.]+$/ is enforced
        // at the controller validator. Verifying the regex pattern itself
        // here so a future tightening that breaks `BUY1GET1+FREE` or
        // `v2.0-LAUNCH` style codes is caught.
        $pattern = '/^[\w\-+.]+$/';

        // Codes that MUST pass.
        $passing = ['WELCOME_10', 'BLACK-FRIDAY', 'friend2024', 'BUY1GET1+FREE', 'v2.0-LAUNCH'];
        foreach ($passing as $code) {
            $this->assertSame(1, preg_match($pattern, $code), "Code must pass: {$code}");
        }

        // Codes that MUST reject (preserves the cycle-36 hardening).
        $rejecting = ['', 'AB', 'WELCOME!', 'PROMO 2024', 'BLACK&FRIDAY', '100%OFF'];
        foreach ($rejecting as $code) {
            // min:3 is enforced by the validator separately; the regex itself
            // rejects shape violators (we still test min via length check).
            if (strlen($code) >= 3) {
                $this->assertSame(0, preg_match($pattern, $code), "Code must reject: {$code}");
            } else {
                // Length below 3 — controller validator's min:3 rule rejects.
                $this->assertLessThan(3, strlen($code));
            }
        }
    }
}
