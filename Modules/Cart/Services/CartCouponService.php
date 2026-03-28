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
     * @param string $couponCode
     * @param string|null $customerEmail
     * @param string|null $customerIp
     * @return array
     */
    public function applyCoupon(string $couponCode, ?string $customerEmail = null, ?string $customerIp = null): array
    {
        if (!$this->couponService) {
            return [
                'error' => true,
                'message' => 'Coupon service not available',
            ];
        }

        $cartTotal = $this->cartRepository->getCartAmount();

        return $this->couponService->applyCoupon($couponCode, $cartTotal, $customerEmail, $customerIp);
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
