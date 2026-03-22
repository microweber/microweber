<?php

namespace Modules\Coupons\Services;

use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Models\CouponLog;
use Modules\Coupons\Models\CartCouponLog;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    /**
     * @var array Currently applied coupons for stacking
     */
    protected array $appliedCoupons = [];

    public function generateCouponCode(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }

    /**
     * Generate a unique coupon code with custom prefix and length.
     *
     * @param string|null $prefix Optional prefix for the code
     * @param int $length Code length (default 8)
     * @return string
     */
    public function generateUniqueCouponCode(?string $prefix = null, int $length = 8): string
    {
        do {
            $code = $prefix ? strtoupper($prefix) . '-' : '';
            $code .= strtoupper(substr(md5(uniqid()), 0, $length));
        } while (Coupon::where('coupon_code', $code)->exists());

        return $code;
    }

    public function getCouponSession(): array
    {
        return [
            'coupon_code' => Session::get('applied_coupon'),
            'discount_value' => Session::get('coupon_discount'),
            'coupon_data' => Session::get('coupon_data')
        ];
    }

    /**
     * Get all stacked coupons from session.
     *
     * @return array
     */
    public function getStackedCoupons(): array
    {
        return Session::get('stacked_coupons', []);
    }

    public function clearCouponSession(): void
    {
        Session::forget([
            'applied_coupon',
            'coupon_discount',
            'coupon_data',
            'stacked_coupons'
        ]);
        $this->appliedCoupons = [];
    }

    /**
     * Check if a coupon can be applied (considering stacking rules).
     *
     * @param string $code Coupon code
     * @param float $cartTotal Current cart total
     * @param array $context Validation context
     * @return array Validation result
     */
    public function canApplyCoupon(string $code, float $cartTotal, array $context = []): array
    {
        $coupon = Coupon::where('coupon_code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$coupon) {
            return [
                'can_apply' => false,
                'error' => true,
                'message' => lang('The coupon code is not valid.')
            ];
        }

        // Check stacking rules - only for different coupons
        $existingCoupon = Session::get('coupon_data');
        $existingCouponCode = $existingCoupon['coupon_code'] ?? null;

        // Only check stacking if trying to apply a different coupon
        if ($existingCouponCode && $existingCouponCode !== $code) {
            // If there's already a coupon applied and current coupon is not stackable
            if (!$coupon->isStackable()) {
                return [
                    'can_apply' => false,
                    'error' => true,
                    'message' => lang('This coupon cannot be combined with other coupons.')
                ];
            }

            // If there's an existing non-stackable coupon already applied
            if (!($existingCoupon['is_stackable'] ?? false)) {
                return [
                    'can_apply' => false,
                    'error' => true,
                    'message' => lang('This coupon cannot be combined with other coupons.')
                ];
            }
        }

        // Check customer group restrictions
        $customerGroupId = $context['customer_group_id'] ?? null;
        if (!$coupon->isValidForCustomerGroup($customerGroupId)) {
            return [
                'can_apply' => false,
                'error' => true,
                'message' => lang('This coupon is not valid for your customer group.')
            ];
        }

        // Check first-time customer restriction
        $userId = $context['user_id'] ?? null;
        $customerEmail = $context['customer_email'] ?? null;
        if (!$coupon->isValidForFirstTimeCustomer($userId, $customerEmail)) {
            return [
                'can_apply' => false,
                'error' => true,
                'message' => lang('This coupon is only valid for first-time customers.')
            ];
        }

        // Check product exclusions
        $cartProductIds = $context['cart_product_ids'] ?? [];
        $excludedProducts = $coupon->getExcludedProducts($cartProductIds);
        if (!empty($excludedProducts) && count($excludedProducts) === count($cartProductIds)) {
            return [
                'can_apply' => false,
                'error' => true,
                'message' => lang('This coupon cannot be applied to products in your cart.')
            ];
        }

        // Check category restrictions
        $cartCategoryIds = $context['cart_category_ids'] ?? [];
        if (!$coupon->appliesToCategories($cartCategoryIds)) {
            return [
                'can_apply' => false,
                'error' => true,
                'message' => lang('This coupon is not valid for the categories in your cart.')
            ];
        }

        return [
            'can_apply' => true,
            'coupon' => $coupon
        ];
    }

    /**
     * Apply a coupon code with full validation and context.
     *
     * @param string $code Coupon code
     * @param float $cartTotal Current cart total
     * @param string|null $customerEmail Customer email
     * @param string|null $customerIp Customer IP address
     * @param array $context Additional context (user_id, customer_group_id, cart_items, etc.)
     * @return array Application result
     */
    public function applyCoupon(string $code, float $cartTotal, ?string $customerEmail = null, ?string $customerIp = null, array $context = []): array
    {
        $coupon = Coupon::where('coupon_code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$coupon) {
            return [
                'error' => true,
                'message' => lang('The coupon code is not valid.')
            ];
        }

        // Build validation context
        $validationContext = array_merge([
            'customer_email' => $customerEmail,
            'customer_ip' => $customerIp,
        ], $context);

        // Validate using canApplyCoupon for consistency
        $validation = $this->canApplyCoupon($code, $cartTotal, $validationContext);
        if (!$validation['can_apply']) {
            return [
                'error' => true,
                'message' => $validation['message']
            ];
        }

        // Check per-customer usage limit
        if ($coupon->uses_per_customer > 0 && $customerEmail) {
            $usageCount = CouponLog::where('coupon_code', $code)
                ->where('customer_email', $customerEmail)
                ->count();

            if ($usageCount >= $coupon->uses_per_customer) {
                return [
                    'error' => true,
                    'message' => lang('The coupon has reached its maximum usage limit for this customer.')
                ];
            }
        }

        // Check date validity
        $now = now();
        if ($coupon->valid_from && $now->lt($coupon->valid_from)) {
            return [
                'error' => true,
                'message' => lang('The coupon is not valid at this time.')
            ];
        }
        if ($coupon->valid_to && $now->gt($coupon->valid_to)) {
            return [
                'error' => true,
                'message' => lang('The coupon has expired.')
            ];
        }

        // Check minimum order amount
        if ($coupon->total_amount && $cartTotal < $coupon->total_amount) {
            return [
                'error' => true,
                'message' => lang('The coupon can\'t be applied because the minimum total amount is ') . currency_format($coupon->total_amount)
            ];
        }

        // Check per-customer limit (by email and IP)
        if ($customerEmail && $customerIp && !$coupon->isValidForCustomer($customerEmail, $customerIp)) {
            return [
                'error' => true,
                'message' => lang('The coupon cannot be applied cause maximum uses exceeded.')
            ];
        }

        // Check product restrictions
        if ($coupon->product_ids) {
            $requiredProducts = array_map('trim', explode(',', $coupon->product_ids));
            $cartItems = app('cart_manager')->get_cart([]);
            $cartProductIds = array_map('strval', array_column($cartItems, 'rel_id'));

            $hasRequiredProduct = !empty(array_intersect(
                $requiredProducts,
                $cartProductIds
            ));

            if (!$hasRequiredProduct) {
                return [
                    'error' => true,
                    'message' => lang('This coupon is not applicable to products in your cart.')
                ];
            }
        }

        // Check advanced discount rules first
        $cartItems = $context['items'] ?? [];
        $itemCount = count($cartItems);

        // Validate item count requirements
        if (!$coupon->meetsMinItemsRequirement($itemCount)) {
            return [
                'error' => true,
                'message' => lang('Minimum of :count items required', ['count' => $coupon->min_items_count])
            ];
        }

        if ($coupon->exceedsMaxItemsLimit($itemCount)) {
            return [
                'error' => true,
                'message' => lang('Maximum of :count items allowed', ['count' => $coupon->max_items_count])
            ];
        }

        // Calculate discount using advanced rules
        $advancedContext = [
            'cart_total' => $cartTotal,
            'item_count' => $itemCount,
            'items' => $cartItems,
            'product_ids' => $context['cart_product_ids'] ?? [],
            'category_ids' => $context['cart_category_ids'] ?? [],
        ];

        $advancedResult = $coupon->validateAdvancedRules($advancedContext);

        if (!$advancedResult['valid']) {
            return [
                'error' => true,
                'message' => $advancedResult['message']
            ];
        }

        // Calculate base discount
        $baseDiscountAmount = $coupon->calculateDiscount($cartTotal);

        // Add advanced rule discounts
        $discountAmount = $baseDiscountAmount + $advancedResult['discount'];

        // Apply maximum discount per order cap
        if ($coupon->max_discount_per_order && $discountAmount > $coupon->max_discount_per_order) {
            $discountAmount = $coupon->max_discount_per_order;
        }

        // Handle coupon stacking
        $stackedCoupons = $this->getStackedCoupons();
        if ($coupon->isStackable() && !empty($stackedCoupons)) {
            // Add to stacked coupons
            $stackedCoupons[] = [
                'coupon_id' => $coupon->id,
                'coupon_code' => $coupon->coupon_code,
                'discount_amount' => $discountAmount,
            ];
            Session::put('stacked_coupons', $stackedCoupons);
        } else {
            // Replace existing coupon
            Session::put([
                'applied_coupon' => $coupon->coupon_code,
                'coupon_discount' => $discountAmount,
                'coupon_data' => $coupon->toArray(),
                'stacked_coupons' => []
            ]);
        }

        // Log coupon application
        CartCouponLog::create([
            'coupon_code' => $coupon->coupon_code,
            'coupon_id' => $coupon->id,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'customer_email' => $customerEmail,
            'customer_ip' => $customerIp,
            'cart_total' => $cartTotal,
            'discount_amount' => $discountAmount
        ]);

        return [
            'success' => true,
            'message' => lang('Coupon code applied.'),
            'discount_amount' => $discountAmount,
            'coupon' => $coupon,
            'stacked' => $coupon->isStackable() && !empty($stackedCoupons)
        ];
    }

    /**
     * Calculate total discount from all applied coupons.
     *
     * @param float $cartTotal Current cart total
     * @return float Total discount amount
     */
    public function calculateTotalDiscount(float $cartTotal): float
    {
        $totalDiscount = 0;

        // Get primary coupon
        $primaryCoupon = Session::get('coupon_data');
        if ($primaryCoupon) {
            $coupon = new Coupon($primaryCoupon);
            $totalDiscount += $coupon->calculateDiscount($cartTotal);
        }

        // Get stacked coupons
        $stackedCoupons = $this->getStackedCoupons();
        foreach ($stackedCoupons as $stackedCoupon) {
            $coupon = Coupon::find($stackedCoupon['coupon_id'] ?? 0);
            if ($coupon && $coupon->isStackable()) {
                $remainingTotal = $cartTotal - $totalDiscount;
                $totalDiscount += $coupon->calculateDiscount($remainingTotal);
            }
        }

        return min($totalDiscount, $cartTotal);
    }

    /**
     * Get auto-apply coupons for current cart.
     *
     * @param float $cartTotal Current cart total
     * @param array $context Validation context
     * @return array Array of applicable auto-apply coupons
     */
    public function getAutoApplyCoupons(float $cartTotal, array $context = []): array
    {
        return Coupon::where('is_active', 1)
            ->where('auto_apply', 1)
            ->get()
            ->filter(function ($coupon) use ($cartTotal, $context) {
                $validation = $this->canApplyCoupon($coupon->coupon_code, $cartTotal, $context);
                return $validation['can_apply'];
            })
            ->values()
            ->toArray();
    }

    /**
     * Consume a coupon after successful order placement.
     *
     * @param string $code Coupon code
     * @param string $customerEmail Customer email
     * @param string $customerIp Customer IP address
     * @return void
     */
    public function consumeCoupon(string $code, string $customerEmail, string $customerIp): void
    {
        $coupon = Coupon::where('coupon_code', $code)->first();
        if (!$coupon) {
            return;
        }

        CouponLog::logUsage($coupon, $customerEmail, $customerIp);
        $coupon->incrementUsage($this->calculateTotalDiscount($coupon->total_amount ?? 0));
        $this->clearCouponSession();
    }

    /**
     * Get the currently applied coupon data.
     *
     * @return array|null
     */
    public function getAppliedCoupon(): ?array
    {
        return Session::get('coupon_data');
    }

    /**
     * Calculate discount from currently applied coupon.
     *
     * @param float $cartTotal Current cart total
     * @return float Calculated discount amount
     */
    public function getAppliedDiscount(float $cartTotal): float
    {
        $appliedCoupon = $this->getAppliedCoupon();
        if (!$appliedCoupon) {
            return 0;
        }

        $coupon = new Coupon($appliedCoupon);
        return $coupon->calculateDiscount($cartTotal);
    }

    /**
     * Get usage statistics for a coupon.
     *
     * @param string $code Coupon code
     * @return array|null
     */
    public function getCouponStats(string $code): ?array
    {
        $coupon = Coupon::where('coupon_code', $code)->first();
        if (!$coupon) {
            return null;
        }

        return $coupon->getUsageStats();
    }
}
