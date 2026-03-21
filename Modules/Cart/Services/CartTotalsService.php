<?php

namespace Modules\Cart\Services;

use Modules\Coupons\Services\CouponService;
use Modules\Tax\Services\TaxCalculator;

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

    /**
     * @var TaxCalculator
     */
    protected ?TaxCalculator $taxCalculator = null;

    public function __construct($app = null)
    {
        $this->app = $app ?: app();
        $this->cartRepository = $this->app->cart_repository;
        $this->couponService = $this->app->coupon_service ?? null;
        $this->taxCalculator = $this->app->tax_calculator ?? null;
    }

    /**
     * Calculate cart totals.
     *
     * @param string $return
     * @param array $location Optional location data for tax calculation
     * @return array|mixed
     */
    public function totals(string $return = 'all', array $location = []): array
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
            $tax = $this->calculateTax($sum, $location);
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
    public function sum(bool $returnAmount = true): float|int
    {
        if ($returnAmount) {
            return $this->cartRepository->getCartAmount();
        }
        return $this->cartRepository->getCartItemsCount();
    }

    /**
     * Get tax amount.
     *
     * @param array $location Optional location data for tax calculation
     * @return float
     */
    public function getTax(array $location = []): float
    {
        $sum = $this->sum();
        return $this->calculateTax($sum, $location);
    }

    /**
     * Calculate tax amount using the new tax calculator with location-based rules.
     *
     * @param float $amount The amount to calculate tax on
     * @param array $location Optional location data (country_code, state_code, city, zip_code)
     * @return float
     */
    protected function calculateTax(float $amount, array $location = []): float
    {
        if (!$this->taxCalculator || !$this->taxCalculator->isEnabled()) {
            // Fall back to legacy tax_manager if tax_calculator not available or disabled
            return (float) $this->app->tax_manager->calculate($amount);
        }

        // Get location from session/checkout if not provided
        if (empty($location)) {
            $location = $this->getLocationFromSession();
        }

        return $this->taxCalculator->calculateAmount($amount, $location);
    }

    /**
     * Get tax calculation breakdown with detailed information.
     *
     * @param array $location Optional location data
     * @return array
     */
    public function getTaxBreakdown(array $location = []): array
    {
        $sum = $this->sum();

        if (!$this->taxCalculator || !$this->taxCalculator->isEnabled()) {
            $amount = $this->calculateTax($sum, $location);
            return [
                'amount' => $amount,
                'breakdown' => [],
            ];
        }

        if (empty($location)) {
            $location = $this->getLocationFromSession();
        }

        $calculation = $this->taxCalculator->calculate($sum, $location);

        return [
            'amount' => $calculation['amount'],
            'breakdown' => $calculation['breakdown'],
        ];
    }

    /**
     * Get location data from session/checkout.
     *
     * @return array
     */
    protected function getLocationFromSession(): array
    {
        $location = [];

        // Try to get location from checkout session
        if (session()->has('checkout_address')) {
            $checkoutAddress = session('checkout_address');
            $location = [
                'country_code' => $checkoutAddress['country'] ?? null,
                'state_code' => $checkoutAddress['state'] ?? null,
                'city' => $checkoutAddress['city'] ?? null,
                'zip_code' => $checkoutAddress['zip'] ?? null,
            ];
        } elseif (session()->has('shipping_address')) {
            $shippingAddress = session('shipping_address');
            $location = [
                'country_code' => $shippingAddress['country'] ?? null,
                'state_code' => $shippingAddress['state'] ?? null,
                'city' => $shippingAddress['city'] ?? null,
                'zip_code' => $shippingAddress['zip'] ?? null,
            ];
        }

        return $location;
    }

    /**
     * Get discount value.
     *
     * @return float|false
     */
    public function getDiscount(): float|false
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
    public function getDiscountType(): string|false
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
