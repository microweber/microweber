<?php

namespace Modules\Cart\Services;

use Modules\Coupons\Services\CouponService;

class CartTotalsService
{
    /**
     * @var \MicroweberPackages\App\LaravelApplication
     */
    protected $app;

    /**
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * @var CouponService
     */
    protected $couponService;

    public function __construct($app = null)
    {
        $this->app = $app ?: app();
        $this->cartRepository = $this->app->cart_repository;
        $this->couponService = $this->app->coupon_service ?? null;
    }

    /**
     * Calculate cart totals.
     *
     * @param string $return
     * @return array|mixed
     */
    public function totals(string $return = 'all')
    {
        $allTotals = ['subtotal', 'shipping', 'tax', 'discount', 'total'];

        $shippingCost = $this->app->checkout_manager->getShippingCost();
        $shippingModules = $this->app->checkout_manager->getShippingModules();

        // Coupon code discount
        $discountValue = $this->getDiscountValue();
        $discountType = $this->getDiscountType();

        $sum = $subtotal = $this->sum();
        $discountSum = $this->calculateDiscount($sum, $discountType, $discountValue);
        $sum = $sum - $discountSum;

        $total = $sum + $shippingCost;

        if ($total > 0) {
            $tax = $this->app->tax_manager->calculate($sum);
            $total = $total + $tax;
        } else {
            $tax = 0;
        }

        $totals = [];
        foreach ($allTotals as $totalKey) {
            $totals[$totalKey] = $this->buildTotalItem($totalKey, $subtotal, $tax, $discountSum, $shippingCost, $shippingModules, $total);
        }

        if ($return != 'all' && isset($totals[$return])) {
            return $totals[$return];
        }

        return $totals;
    }

    /**
     * Get total value.
     *
     * @return float|int
     */
    public function total(): float
    {
        $total = $this->totals('total');
        return $total['value'] ?? 0;
    }

    /**
     * Sum cart items.
     *
     * @param bool $returnAmount
     * @return float|int
     */
    public function sum(bool $returnAmount = true)
    {
        if ($returnAmount) {
            return $this->cartRepository->getCartAmount();
        }
        return $this->cartRepository->getCartItemsCount();
    }

    /**
     * Get tax amount.
     *
     * @return float
     */
    public function getTax(): float
    {
        $sum = $this->sum();
        return $this->app->tax_manager->calculate($sum);
    }

    /**
     * Get discount value.
     *
     * @return float|false
     */
    public function getDiscount()
    {
        return $this->getDiscountValue();
    }

    /**
     * Get discount text representation.
     *
     * @return string
     */
    public function getDiscountText(): string
    {
        $discountType = $this->getDiscountType();

        if ($discountType === 'percentage') {
            return $this->getDiscountValue() . '%';
        }

        $discountValue = $this->getDiscountValue();
        if ($discountValue !== false) {
            return currency_format($discountValue);
        }

        return '';
    }

    /**
     * Get discount type from session.
     *
     * @return string|false
     */
    public function getDiscountType()
    {
        $data = $this->couponService ? $this->couponService->getCouponSession() : [];
        if (empty($data) || !isset($data['coupon_data'])) {
            return false;
        }
        return $data['coupon_data']['discount_type'] ?? false;
    }

    /**
     * Get discount value from session.
     *
     * @return float|false
     */
    public function getDiscountValue()
    {
        if (!$this->couponService) {
            return false;
        }

        $data = $this->couponService->getCouponSession();
        if (empty($data) || !isset($data['coupon_data'])) {
            return false;
        }

        $couponData = $data['coupon_data'];
        if (!isset($couponData['discount_value']) || !isset($couponData['total_amount'])) {
            return false;
        }

        $cartTotal = $this->sum();
        if ($cartTotal >= $couponData['total_amount']) {
            return floatval($couponData['discount_value']);
        }

        return false;
    }

    /**
     * Calculate discount amount.
     *
     * @param float $sum
     * @param string|false $discountType
     * @param float|false $discountValue
     * @return float
     */
    protected function calculateDiscount(float $sum, $discountType, $discountValue): float
    {
        $discountSum = 0;

        if ($discountValue === false) {
            return $discountSum;
        }

        if ($discountType === 'percentage') {
            $discountSum = $sum * ($discountValue / 100);
            if ($sum - $discountSum < 0) {
                $discountSum = $sum;
            }
        } elseif ($discountType === 'fixed_amount') {
            $discountSum = $discountValue;
            if ($sum - $discountSum < 0) {
                $discountSum = $sum;
            }
        }

        return $discountSum;
    }

    /**
     * Build total item array.
     *
     * @param string $key
     * @param float $subtotal
     * @param float $tax
     * @param float $discountSum
     * @param float $shippingCost
     * @param mixed $shippingModules
     * @param float $total
     * @return array|null
     */
    protected function buildTotalItem(string $key, float $subtotal, float $tax, float $discountSum, float $shippingCost, $shippingModules, float $total): ?array
    {
        switch ($key) {
            case 'subtotal':
                return [
                    'label' => _e('Subtotal', true),
                    'value' => $subtotal,
                    'amount' => currency_format($subtotal),
                ];

            case 'tax':
                if ($tax) {
                    return [
                        'label' => _e('Tax', true),
                        'value' => $tax,
                        'amount' => currency_format($tax),
                    ];
                }
                break;

            case 'discount':
                if ($discountSum > 0) {
                    return [
                        'label' => _e('Discount', true),
                        'value' => $discountSum,
                        'amount' => currency_format($discountSum),
                    ];
                }
                break;

            case 'shipping':
                if ($shippingModules && $shippingCost > 0) {
                    return [
                        'label' => _e('Shipping', true),
                        'value' => $shippingCost,
                        'amount' => currency_format($shippingCost),
                    ];
                }
                break;

            case 'total':
                return [
                    'label' => _e('Total', true),
                    'value' => $total,
                    'amount' => currency_format($total),
                ];
        }

        return null;
    }
}
