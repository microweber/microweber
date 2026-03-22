<?php

namespace Modules\Coupons\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Coupons\Database\Factories\CouponFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'cart_coupons';

    protected $fillable = [
        'coupon_name',
        'coupon_code',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'total_amount',
        'uses_per_coupon',
        'uses_per_customer',
        'is_active',
        'is_stackable',
        'first_time_only',
        'auto_apply',
        'free_shipping',
        'valid_from',
        'valid_to',
        'product_ids',
        'excluded_product_ids',
        'category_ids',
        'customer_group_ids',
        'times_used',
        'total_discount_given',
        // Advanced discount rules
        'bogo_enabled',
        'bogo_buy_quantity',
        'bogo_get_quantity',
        'bogo_discount_percent',
        'bogo_apply_to',
        'tiered_enabled',
        'tiered_rules',
        'conditional_rules',
        'max_discount_per_order',
        'min_items_count',
        'max_items_count',
        'discount_shipping',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'uses_per_coupon' => 'integer',
        'uses_per_customer' => 'integer',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        // Advanced discount rules
        'bogo_enabled' => 'boolean',
        'bogo_buy_quantity' => 'integer',
        'bogo_get_quantity' => 'integer',
        'bogo_discount_percent' => 'decimal:2',
        'tiered_enabled' => 'boolean',
        'tiered_rules' => 'array',
        'conditional_rules' => 'array',
        'max_discount_per_order' => 'decimal:2',
        'min_items_count' => 'integer',
        'max_items_count' => 'integer',
        'discount_shipping' => 'boolean',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(CouponLog::class, 'coupon_id');
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check total usage limit
        if ($this->uses_per_coupon > 0 && $this->logs()->count() >= $this->uses_per_coupon) {
            return false;
        }

        // Check date validity
        $now = now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_to && $now->gt($this->valid_to)) {
            return false;
        }

        return true;
    }

    /**
     * Check if coupon applies to specific products.
     *
     * @param array $productIds Array of product IDs in cart
     * @return bool
     */
    public function appliesToProducts(array $productIds): bool
    {
        // If no product restrictions, applies to all
        if (empty($this->product_ids)) {
            return true;
        }

        $allowedProductIds = array_map('trim', explode(',', $this->product_ids));

        return !empty(array_intersect($allowedProductIds, array_map('strval', $productIds)));
    }

    /**
     * Check if any products are excluded from this coupon.
     *
     * @param array $productIds Array of product IDs in cart
     * @return array Array of excluded product IDs found in cart
     */
    public function getExcludedProducts(array $productIds): array
    {
        if (empty($this->excluded_product_ids)) {
            return [];
        }

        $excludedIds = array_map('trim', explode(',', $this->excluded_product_ids));

        return array_intersect($excludedIds, array_map('strval', $productIds));
    }

    /**
     * Check if coupon applies to specific categories.
     *
     * @param array $categoryIds Array of category IDs
     * @return bool
     */
    public function appliesToCategories(array $categoryIds): bool
    {
        // If no category restrictions, applies to all
        if (empty($this->category_ids)) {
            return true;
        }

        $allowedCategoryIds = array_map('trim', explode(',', $this->category_ids));

        return !empty(array_intersect($allowedCategoryIds, array_map('strval', $categoryIds)));
    }

    /**
     * Check if customer is in allowed group.
     *
     * @param int|null $customerGroupId Customer group ID
     * @return bool
     */
    public function isValidForCustomerGroup(?int $customerGroupId): bool
    {
        // If no group restrictions, allow all
        if (empty($this->customer_group_ids)) {
            return true;
        }

        if ($customerGroupId === null) {
            return false;
        }

        $allowedGroupIds = array_map('trim', explode(',', $this->customer_group_ids));

        return in_array((string) $customerGroupId, $allowedGroupIds, true);
    }

    /**
     * Check if this is a first-time customer coupon.
     *
     * @param int|null $userId User ID
     * @param string|null $customerEmail Customer email
     * @return bool
     */
    public function isValidForFirstTimeCustomer(?int $userId, ?string $customerEmail): bool
    {
        if (!$this->first_time_only) {
            return true;
        }

        // If no user ID or email, treat as guest (first time)
        if ($userId === null && empty($customerEmail)) {
            return true;
        }

        // Check if customer has previous orders
        $orderCount = 0;

        if ($userId !== null) {
            $orderCount = \DB::table('cart_orders')
                ->where('user_id', $userId)
                ->where('order_status', 'completed')
                ->count();
        }

        if ($orderCount === 0 && !empty($customerEmail)) {
            $orderCount = \DB::table('cart_orders')
                ->where('email', $customerEmail)
                ->where('order_status', 'completed')
                ->count();
        }

        return $orderCount === 0;
    }

    /**
     * Check if coupon can be stacked with other coupons.
     *
     * @return bool
     */
    public function isStackable(): bool
    {
        return (bool) $this->is_stackable;
    }

    public function isValidForCustomer(string $customerEmail, string $customerIp): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->uses_per_customer > 0) {
            $customerUses = $this->logs()
                ->where('customer_email', $customerEmail)
                ->where('customer_ip', $customerIp)
                ->sum('uses_count');

            if ($customerUses >= $this->uses_per_customer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount for given cart total.
     *
     * @param float $amount Cart total
     * @param bool $ignoreMinimum Ignore minimum order amount check
     * @return float Calculated discount amount
     */
    public function calculateDiscount(float $amount, bool $ignoreMinimum = false): float
    {
        // Check minimum order amount
        if (!$ignoreMinimum && $this->total_amount && $amount < $this->total_amount) {
            return 0;
        }

        // Free shipping - no monetary discount
        if ($this->free_shipping) {
            return 0;
        }

        $discount = 0;

        if ($this->discount_type === 'percentage') {
            $discount = $amount * ($this->discount_value / 100);

            // Apply maximum discount cap if set
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = $this->max_discount_amount;
            }
        } else {
            $discount = $this->discount_value;
        }

        // Cap at cart total
        return min($discount, $amount);
    }

    /**
     * Calculate discount for specific items only.
     *
     * @param array $items Cart items with 'price' and 'product_id' keys
     * @return float Calculated discount
     */
    public function calculateDiscountForItems(array $items): float
    {
        $applicableTotal = 0;

        foreach ($items as $item) {
            // Check if product is excluded
            if (!empty($this->excluded_product_ids)) {
                $excludedIds = array_map('trim', explode(',', $this->excluded_product_ids));
                if (in_array((string) $item['product_id'], $excludedIds, true)) {
                    continue;
                }
            }

            // Check if product is in allowed list
            if (!empty($this->product_ids)) {
                $allowedIds = array_map('trim', explode(',', $this->product_ids));
                if (!in_array((string) $item['product_id'], $allowedIds, true)) {
                    continue;
                }
            }

            $applicableTotal += $item['price'] * ($item['qty'] ?? 1);
        }

        if ($applicableTotal <= 0) {
            return 0;
        }

        return $this->calculateDiscount($applicableTotal);
    }

    /**
     * Increment usage statistics.
     *
     * @param float $discountAmount Amount of discount applied
     * @return void
     */
    public function incrementUsage(float $discountAmount): void
    {
        $this->increment('times_used');
        $this->increment('total_discount_given', $discountAmount);
    }

    /**
     * Get formatted discount value for display.
     *
     * @return string
     */
    public function getFormattedDiscountAttribute(): string
    {
        if ($this->free_shipping) {
            return 'Free Shipping';
        }

        if ($this->discount_type === 'percentage') {
            $value = number_format($this->discount_value, 0) . '%';
            if ($this->max_discount_amount) {
                $value .= ' (max ' . currency_format($this->max_discount_amount) . ')';
            }
            return $value;
        }

        return currency_format($this->discount_value);
    }

    /**
     * Get usage statistics summary.
     *
     * @return array
     */
    public function getUsageStats(): array
    {
        return [
            'total_uses' => $this->times_used,
            'total_discount_given' => $this->total_discount_given,
            'remaining_uses' => $this->uses_per_coupon > 0
                ? max(0, $this->uses_per_coupon - $this->logs()->count())
                : null,
            'usage_percentage' => $this->uses_per_coupon > 0
                ? min(100, ($this->logs()->count() / $this->uses_per_coupon) * 100)
                : null,
        ];
    }

    // ADVANCED DISCOUNT RULES METHODS

    /**
     * Check if coupon has BOGO (Buy X Get Y) rules enabled.
     *
     * @return bool
     */
    public function hasBogoRules(): bool
    {
        return (bool) $this->bogo_enabled;
    }

    /**
     * Calculate BOGO discount for cart items.
     *
     * @param array $items Cart items with 'product_id', 'price', and 'qty' keys
     * @return float Calculated BOGO discount
     */
    public function calculateBogoDiscount(array $items): float
    {
        if (!$this->hasBogoRules() || !$this->bogo_buy_quantity || !$this->bogo_get_quantity) {
            return 0;
        }

        $discount = 0;
        $buyQty = $this->bogo_buy_quantity;
        $getQty = $this->bogo_get_quantity;
        $bogoDiscountPercent = $this->bogo_discount_percent ?? 100;

        // Group items by product for BOGO calculations
        $productQuantities = [];
        foreach ($items as $item) {
            $productId = $item['product_id'];
            if (!isset($productQuantities[$productId])) {
                $productQuantities[$productId] = [
                    'qty' => 0,
                    'price' => $item['price'],
                    'items' => [],
                ];
            }
            $productQuantities[$productId]['qty'] += $item['qty'] ?? 1;
            $productQuantities[$productId]['items'][] = $item;
        }

        // Apply BOGO to each product group
        foreach ($productQuantities as $productId => $data) {
            $totalQty = $data['qty'];
            $price = $data['price'];

            // Calculate how many free items
            $bogoSets = intdiv($totalQty, $buyQty + $getQty);
            $remainingItems = $totalQty % ($buyQty + $getQty);

            // Additional partial sets (if remaining >= buy quantity)
            if ($remainingItems >= $buyQty) {
                $bogoSets++;
            }

            $freeItems = $bogoSets * $getQty;
            $itemDiscount = $freeItems * $price * ($bogoDiscountPercent / 100);
            $discount += $itemDiscount;
        }

        return $discount;
    }

    /**
     * Check if coupon has tiered/volume discount rules enabled.
     *
     * @return bool
     */
    public function hasTieredRules(): bool
    {
        return (bool) $this->tiered_enabled && !empty($this->tiered_rules);
    }

    /**
     * Get applicable tier for a given cart total or quantity.
     *
     * @param float $cartTotal Cart total amount
     * @param int $itemCount Number of items in cart
     * @return array|null Applicable tier or null if none
     */
    public function getApplicableTier(float $cartTotal, int $itemCount = 0): ?array
    {
        if (!$this->hasTieredRules()) {
            return null;
        }

        $tiers = $this->tiered_rules ?? [];
        $applicableTier = null;

        foreach ($tiers as $tier) {
            $threshold = $tier['threshold'] ?? 0;
            $type = $tier['type'] ?? 'amount'; // 'amount' or 'quantity'
            $value = $type === 'quantity' ? $itemCount : $cartTotal;

            if ($value >= $threshold) {
                // Keep the highest applicable tier
                if ($applicableTier === null || $threshold > ($applicableTier['threshold'] ?? 0)) {
                    $applicableTier = $tier;
                }
            }
        }

        return $applicableTier;
    }

    /**
     * Calculate tiered discount for cart.
     *
     * @param float $cartTotal Cart total amount
     * @param int $itemCount Number of items in cart
     * @return float Calculated tiered discount
     */
    public function calculateTieredDiscount(float $cartTotal, int $itemCount = 0): float
    {
        $tier = $this->getApplicableTier($cartTotal, $itemCount);

        if (!$tier) {
            return 0;
        }

        $discountValue = $tier['discount_value'] ?? 0;
        $discountType = $tier['discount_type'] ?? 'percentage';

        if ($discountType === 'percentage') {
            $discount = $cartTotal * ($discountValue / 100);

            // Apply maximum cap if set
            if (!empty($tier['max_discount'])) {
                $discount = min($discount, $tier['max_discount']);
            }

            return $discount;
        }

        // Fixed amount
        return min($discountValue, $cartTotal);
    }

    /**
     * Check if coupon has conditional discount rules.
     *
     * @return bool
     */
    public function hasConditionalRules(): bool
    {
        return !empty($this->conditional_rules);
    }

    /**
     * Evaluate conditional rules against cart context.
     *
     * @param array $context Cart context with keys: cart_total, item_count, products, categories, customer_group_id
     * @return array Result with 'applicable' (bool) and 'discount' (float)
     */
    public function evaluateConditionalRules(array $context): array
    {
        if (!$this->hasConditionalRules()) {
            return ['applicable' => true, 'discount' => 0, 'rules_matched' => []];
        }

        $rules = $this->conditional_rules ?? [];
        $cartTotal = $context['cart_total'] ?? 0;
        $itemCount = $context['item_count'] ?? 0;
        $totalDiscount = 0;
        $rulesMatched = [];

        foreach ($rules as $rule) {
            $condition = $rule['condition'] ?? '';
            $operator = $rule['operator'] ?? '>';
            $value = $rule['value'] ?? 0;
            $discountType = $rule['discount_type'] ?? 'percentage';
            $discountValue = $rule['discount_value'] ?? 0;

            $conditionMet = false;

            // Evaluate condition
            switch ($condition) {
                case 'cart_total':
                    $conditionMet = $this->compareValues($cartTotal, $operator, $value);
                    break;
                case 'item_count':
                    $conditionMet = $this->compareValues($itemCount, $operator, $value);
                    break;
                case 'specific_product':
                    $productIds = $context['product_ids'] ?? [];
                    $targetProducts = is_array($value) ? $value : [$value];
                    $hasProduct = !empty(array_intersect($productIds, $targetProducts));
                    $conditionMet = $operator === 'has' ? $hasProduct : !$hasProduct;
                    break;
                case 'specific_category':
                    $categoryIds = $context['category_ids'] ?? [];
                    $targetCategories = is_array($value) ? $value : [$value];
                    $hasCategory = !empty(array_intersect($categoryIds, $targetCategories));
                    $conditionMet = $operator === 'has' ? $hasCategory : !$hasCategory;
                    break;
            }

            if ($conditionMet) {
                // Calculate discount for this rule
                if ($discountType === 'percentage') {
                    $discount = $cartTotal * ($discountValue / 100);
                    if (!empty($rule['max_discount'])) {
                        $discount = min($discount, $rule['max_discount']);
                    }
                    $totalDiscount += $discount;
                } else {
                    $totalDiscount += $discountValue;
                }

                $rulesMatched[] = $rule;
            }
        }

        return [
            'applicable' => count($rulesMatched) > 0,
            'discount' => min($totalDiscount, $cartTotal),
            'rules_matched' => $rulesMatched,
        ];
    }

    /**
     * Compare values based on operator.
     *
     * @param float $left Left side value
     * @param string $operator Comparison operator
     * @param float $right Right side value
     * @return bool
     */
    protected function compareValues(float $left, string $operator, float $right): bool
    {
        return match ($operator) {
            '>' => $left > $right,
            '>=' => $left >= $right,
            '<' => $left < $right,
            '<=' => $left <= $right,
            '=' => $left == $right,
            '!=' => $left != $right,
            default => false,
        };
    }

    /**
     * Check if coupon meets minimum items requirement.
     *
     * @param int $itemCount Number of items in cart
     * @return bool
     */
    public function meetsMinItemsRequirement(int $itemCount): bool
    {
        if (!$this->min_items_count) {
            return true;
        }
        return $itemCount >= $this->min_items_count;
    }

    /**
     * Check if coupon exceeds maximum items limit.
     *
     * @param int $itemCount Number of items in cart
     * @return bool
     */
    public function exceedsMaxItemsLimit(int $itemCount): bool
    {
        if (!$this->max_items_count) {
            return false;
        }
        return $itemCount > $this->max_items_count;
    }

    /**
     * Check if coupon is within maximum items limit.
     *
     * @param int $itemCount Number of items in cart
     * @return bool
     */
    public function withinMaxItemsLimit(int $itemCount): bool
    {
        if (!$this->max_items_count) {
            return true;
        }
        return $itemCount <= $this->max_items_count;
    }

    /**
     * Check all advanced discount rules.
     *
     * @param array $context Cart context
     * @return array Result with 'valid' (bool), 'discount' (float), and 'message' (string)
     */
    public function validateAdvancedRules(array $context): array
    {
        $cartTotal = $context['cart_total'] ?? 0;
        $itemCount = $context['item_count'] ?? 0;

        // Check min/max items
        if (!$this->meetsMinItemsRequirement($itemCount)) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => lang('Minimum of :count items required', ['count' => $this->min_items_count]),
            ];
        }

        if ($this->exceedsMaxItemsLimit($itemCount)) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => lang('Maximum of :count items allowed', ['count' => $this->max_items_count]),
            ];
        }

        $totalDiscount = 0;

        // Calculate BOGO discount
        if ($this->hasBogoRules()) {
            $bogoItems = $context['items'] ?? [];
            $totalDiscount += $this->calculateBogoDiscount($bogoItems);
        }

        // Calculate tiered discount
        if ($this->hasTieredRules()) {
            $totalDiscount += $this->calculateTieredDiscount($cartTotal, $itemCount);
        }

        // Calculate conditional discount
        if ($this->hasConditionalRules()) {
            $conditionalResult = $this->evaluateConditionalRules($context);
            if (!$conditionalResult['applicable']) {
                return [
                    'valid' => false,
                    'discount' => 0,
                    'message' => lang('Coupon conditions not met'),
                ];
            }
            $totalDiscount += $conditionalResult['discount'];
        }

        // Apply maximum discount per order cap
        if ($this->max_discount_per_order && $totalDiscount > $this->max_discount_per_order) {
            $totalDiscount = $this->max_discount_per_order;
        }

        return [
            'valid' => true,
            'discount' => $totalDiscount,
            'message' => null,
        ];
    }

    protected static function newFactory(): CouponFactory
    {
        return new CouponFactory();
    }
}
