<?php

namespace Modules\Cart\Services;

use Modules\Coupons\Services\CouponService;

class CartCouponService
{
    /**
     * @var \MicroweberPackages\App\LaravelApplication
     */
    protected $app;

    /**
     * @var CouponService|null
     */
    protected $couponService;

    /**
     * @var CartRepository
     */
    protected $cartRepository;

    public function __construct($app = null)
    {
        $this->app = $app ?: app();
        $this->couponService = $this->app->coupon_service ?? null;
        $this->cartRepository = $this->app->cart_repository;
    }

    /**
     * Get discount value from session coupon.
     *
     * @return float|false
     */
    public function getDiscountValue(): float|false
    {
        if (!$this->couponService) {
            return false;
        }

        $data = $this->couponService->getCouponSession();

        if (empty($data) || !isset($data['coupon_data'])) {
            return false;
        }

        $couponData = $data['coupon_data'];

        if (!isset($couponData['discount_value'])) {
            return false;
        }

        if (!isset($couponData['total_amount'])) {
            return false;
        }

        $cartTotal = $this->cartRepository->getCartAmount();

        if ($cartTotal >= $couponData['total_amount']) {
            return floatval($couponData['discount_value']);
        }

        return false;
    }

    /**
     * Get discount type from session coupon.
     *
     * @return string|false
     */
    public function getDiscountType(): string|false
    {
        if (!$this->couponService) {
            return false;
        }

        $data = $this->couponService->getCouponSession();

        if (empty($data) || !isset($data['coupon_data'])) {
            return false;
        }

        return $data['coupon_data']['discount_type'] ?? false;
    }

    /**
     * Get discount text representation.
     *
     * @return string
     */
    public function getDiscountText(): string
    {
        $discountType = $this->getDiscountType();

        if ($discountType === 'percentage' || $discountType === 'percentage') {
            return $this->getDiscountValue() . '%';
        }

        $discountValue = $this->getDiscountValue();
        if ($discountValue !== false) {
            return currency_format($discountValue);
        }

        return '';
    }

    /**
     * Check if coupon code is valid.
     *
     * @param string $couponCode
     * @return bool
     */
    public function isCouponValid(string $couponCode): bool
    {
        if (!$this->couponService) {
            return false;
        }

        $result = $this->couponService->applyCoupon(
            $couponCode,
            $this->cartRepository->getCartAmount(),
            null,
            null,
            ['check_only' => true]
        );

        return !isset($result['error']);
    }

    /**
     * Get coupon data from session.
     *
     * @return array|false
     */
    public function getCouponDataFromSession(): array|false
    {
        if (!$this->couponService) {
            return false;
        }

        $session = $this->couponService->getCouponSession();

        if (empty($session) || !isset($session['coupon_code'])) {
            return false;
        }

        $couponCode = $session['coupon_code'];

        // Check if coupon is still valid
        if (!$this->isCouponValid($couponCode)) {
            $this->clearCouponSession();
            return false;
        }

        return $session['coupon_data'] ?? false;
    }

    /**
     * Clear coupon session.
     *
     * @return void
     */
    public function clearCouponSession(): void
    {
        if ($this->couponService) {
            $this->couponService->clearCouponSession();
        }
    }

    /**
     * Apply coupon to cart.
     *
     * audit-test 2026-05-07 PM TASK-006 / TICKET-AO + agent-test Gotcha #1:
     * Now threads $context through to CouponService so context-driven coupon
     * rules (cart_product_ids, cart_category_ids, customer_group_id, user_id,
     * minimum cart total) actually apply on the API path. Previously the
     * controller's apply path called this with no email/IP/context, so:
     *   - per-IP/per-email rate limits silently didn't apply
     *   - per-product-id and per-category-id rules silently didn't apply
     *   - per-customer-group rules silently didn't apply
     * Callers that pass [] for $context get the prior behaviour (the underlying
     * CouponService treats the array as "no extra constraints").
     *
     * @param string $couponCode
     * @param string|null $customerEmail
     * @param string|null $customerIp
     * @param array $context Additional validation context (cart_items, ids, user_id, etc.)
     * @return array
     */
    public function applyCoupon(string $couponCode, ?string $customerEmail = null, ?string $customerIp = null, array $context = []): array
    {
        if (!$this->couponService) {
            return [
                'error' => true,
                'message' => 'Coupon service not available',
            ];
        }

        $cartTotal = $this->cartRepository->getCartAmount();

        return $this->couponService->applyCoupon($couponCode, $cartTotal, $customerEmail, $customerIp, $context);
    }

    /**
     * Build the coupon-validation context from the current cart + auth state.
     *
     * audit-test 2026-05-07 PM TASK-006 / TICKET-AO Gotcha #1:
     * Extracted from `Modules/Coupons/Support/helpers.php::coupon_apply()` so
     * the API controller path and the legacy helper path both build context
     * the same way. Drift-prevention: changing the context shape now happens
     * in ONE place.
     *
     * @return array
     */
    public function buildCouponContext(): array
    {
        $cartItems = [];
        if (function_exists('app') && app()->bound('cart_manager')) {
            $cartItems = app('cart_manager')->get_cart([]);
        }

        return [
            'items' => $cartItems,
            'cart_product_ids' => array_map('strval', array_column($cartItems, 'rel_id')),
            'cart_category_ids' => array_map('strval', array_column($cartItems, 'category_id')),
            'user_id' => auth()->id(),
            'customer_group_id' => auth()->user()?->customer_group_id ?? null,
        ];
    }

    /**
     * Consume coupon after order completion.
     *
     * @param string $couponCode
     * @param string $customerEmail
     * @param string $customerIp
     * @return void
     */
    public function consumeCoupon(string $couponCode, string $customerEmail, string $customerIp): void
    {
        if ($this->couponService) {
            $this->couponService->consumeCoupon($couponCode, $customerEmail, $customerIp);
        }
    }
}
