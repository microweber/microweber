<?php

namespace Modules\Product\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\Content\Models\Content;
use Modules\Product\Models\ProductCustomerPricing;
use Modules\Product\Models\ProductPricingRule;
use MicroweberPackages\User\Models\User;

/**
 * Advanced Pricing Service
 *
 * Handles bulk pricing, customer-specific pricing, and tiered discounts.
 */
class AdvancedPricingService
{
    /**
     * Cache key prefix for pricing rules.
     */
    protected const CACHE_PREFIX = 'product_pricing_';

    /**
     * Cache TTL in seconds.
     */
    protected const CACHE_TTL = 3600; // 1 hour

    /**
     * Calculate final price for a product with all applicable pricing rules.
     *
     * @param int $productId
     * @param int $quantity
     * @param float $basePrice
     * @param int|null $customerId
     * @param int|null $customerGroupId
     * @return array
     */
    public function calculatePrice(
        int $productId,
        int $quantity = 1,
        float $basePrice = 0,
        ?int $customerId = null,
        ?int $customerGroupId = null
    ): array {
        $product = Content::find($productId);
        if (!$product) {
            return [
                'base_price' => $basePrice,
                'final_price' => $basePrice,
                'discount' => 0,
                'discount_percentage' => 0,
                'rules_applied' => [],
            ];
        }

        // Get base price if not provided
        if ($basePrice <= 0) {
            $basePrice = $this->getBasePrice($productId);
        }

        // Get category IDs for this product
        $categoryIds = [];
        if (method_exists($product, 'categories')) {
            $categoryIds = $product->categories()->pluck('id')->toArray();
        }

        // Collect applicable rules
        $applicableRules = $this->getApplicableRules(
            $productId,
            $categoryIds,
            $quantity,
            $basePrice * $quantity,
            $customerId,
            $customerGroupId
        );

        // Calculate price with rules
        $result = $this->applyRules($basePrice, $quantity, $applicableRules, $productId, $customerId);

        return [
            'base_price' => $basePrice,
            'final_price' => $result['price'],
            'discount' => $result['discount'],
            'discount_percentage' => $basePrice > 0 ? round(($result['discount'] / $basePrice) * 100, 2) : 0,
            'rules_applied' => $result['rules'],
        ];
    }

    /**
     * Get base price for a product.
     *
     * @param int $productId
     * @return float
     */
    public function getBasePrice(int $productId): float
    {
        $product = Content::find($productId);
        if (!$product) {
            return 0;
        }

        // Use the product's price attribute
        return (float) ($product->price ?? 0);
    }

    /**
     * Get customer-specific pricing for a product.
     *
     * @param int $productId
     * @param int|null $customerId
     * @return ProductCustomerPricing|null
     */
    public function getCustomerPricing(int $productId, ?int $customerId = null): ?ProductCustomerPricing
    {
        if ($customerId === null) {
            return null;
        }

        $cacheKey = self::CACHE_PREFIX . "customer_{$customerId}_{$productId}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($productId, $customerId) {
            return ProductCustomerPricing::active()
                ->forCustomer($customerId)
                ->forProduct($productId)
                ->orderBy('price', 'asc')
                ->first();
        });
    }

    /**
     * Get applicable pricing rules for a context.
     *
     * @param int $productId
     * @param array $categoryIds
     * @param int $quantity
     * @param float $totalAmount
     * @param int|null $customerId
     * @param int|null $customerGroupId
     * @return array
     */
    protected function getApplicableRules(
        int $productId,
        array $categoryIds,
        int $quantity,
        float $totalAmount,
        ?int $customerId = null,
        ?int $customerGroupId = null
    ): array {
        $cacheKey = self::CACHE_PREFIX . 'rules_' . md5(serialize([
            $productId, $categoryIds, $quantity, $totalAmount, $customerId, $customerGroupId
        ]));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use (
            $productId,
            $categoryIds,
            $quantity,
            $totalAmount,
            $customerId,
            $customerGroupId
        ) {
            $rules = ProductPricingRule::active()
                ->byPriority()
                ->get();

            $applicableRules = [];

            foreach ($rules as $rule) {
                // Check if rule applies to this product
                if (!$rule->appliesToProduct($productId)) {
                    continue;
                }

                // Check category restrictions
                $appliesToCategory = false;
                if (!empty($categoryIds)) {
                    foreach ($categoryIds as $categoryId) {
                        if ($rule->appliesToCategory($categoryId)) {
                            $appliesToCategory = true;
                            break;
                        }
                    }
                } else {
                    $appliesToCategory = empty($rule->category_ids);
                }

                if (!$appliesToCategory) {
                    continue;
                }

                // Check customer eligibility
                if (!$rule->appliesToCustomer($customerId, $customerGroupId)) {
                    continue;
                }

                // Check usage limits
                if ($rule->hasReachedLimit($customerId)) {
                    continue;
                }

                // Check quantity/amount requirements
                if (!$this->meetsRuleRequirements($rule, $quantity, $totalAmount)) {
                    continue;
                }

                $applicableRules[] = $rule;
            }

            return $applicableRules;
        });
    }

    /**
     * Check if context meets rule requirements.
     *
     * @param ProductPricingRule $rule
     * @param int $quantity
     * @param float $totalAmount
     * @return bool
     */
    protected function meetsRuleRequirements(ProductPricingRule $rule, int $quantity, float $totalAmount): bool
    {
        $tiers = $rule->tiers ?? [];

        if (empty($tiers)) {
            return true;
        }

        $value = $rule->rule_type === ProductPricingRule::RULE_TYPE_BULK_AMOUNT ? $totalAmount : $quantity;

        foreach ($tiers as $tier) {
            $min = $tier['min'] ?? 0;
            $max = $tier['max'] ?? null;

            if ($value >= $min && ($max === null || $value <= $max)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Apply pricing rules to calculate final price.
     *
     * @param float $basePrice
     * @param int $quantity
     * @param array $rules
     * @param int $productId
     * @param int|null $customerId
     * @return array
     */
    protected function applyRules(
        float $basePrice,
        int $quantity,
        array $rules,
        int $productId,
        ?int $customerId = null
    ): array {
        $finalPrice = $basePrice;
        $totalDiscount = 0;
        $appliedRules = [];

        // First, check for customer-specific pricing
        $customerPricing = $this->getCustomerPricing($productId, $customerId);
        if ($customerPricing && $customerPricing->appliesToQuantity($quantity)) {
            $finalPrice = $customerPricing->price ?? $finalPrice;
            $appliedRules[] = [
                'id' => 'customer_' . $customerPricing->id,
                'name' => 'Customer-specific pricing',
                'type' => 'customer_specific',
                'discount' => $basePrice - $finalPrice,
            ];

        // If customer pricing is fixed, don't apply other rules unless stackable
        if (!($customerPricing->metadata['stackable'] ?? false)) {
                return [
                    'price' => $finalPrice,
                    'discount' => $totalDiscount + ($basePrice - $finalPrice),
                    'rules' => $appliedRules,
                ];
            }
        }

        // Sort rules by priority (highest first)
        usort($rules, function ($a, $b) {
            return $b->priority <=> $a->priority;
        });

        $stackedRules = [];
        $nonStackableApplied = false;

        foreach ($rules as $rule) {
            // Skip if a non-stackable rule has been applied and this one can't stack
            if ($nonStackableApplied && !$rule->is_stackable) {
                continue;
            }

            // Check if this rule can stack with already applied rules
            foreach ($stackedRules as $stackedRule) {
                if (!$rule->canStackWith($stackedRule)) {
                    continue 2;
                }
            }

            $result = $rule->calculatePrice($finalPrice, $quantity, $finalPrice * $quantity);

            if ($result['discount'] > 0) {
                $appliedRules[] = [
                    'id' => $rule->id,
                    'name' => $rule->name,
                    'type' => $rule->rule_type,
                    'price_type' => $rule->price_type,
                    'tier' => $result['tier'],
                    'discount' => $result['discount'],
                ];

                $finalPrice = $result['price'];
                $totalDiscount += $result['discount'];
                $stackedRules[] = $rule;

                if (!$rule->is_stackable) {
                    $nonStackableApplied = true;
                }
            }
        }

        return [
            'price' => max(0, round($finalPrice, 2)),
            'discount' => round($totalDiscount, 2),
            'rules' => $appliedRules,
        ];
    }

    /**
     * Create a bulk pricing rule.
     *
     * @param array $data
     * @return ProductPricingRule
     */
    public function createBulkPricingRule(array $data): ProductPricingRule
    {
        $rule = ProductPricingRule::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? null,
            'description' => $data['description'] ?? null,
            'rule_type' => $data['rule_type'] ?? ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => $data['price_type'] ?? ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => $data['product_ids'] ?? null,
            'category_ids' => $data['category_ids'] ?? null,
            'tiers' => $data['tiers'] ?? [],
            'priority' => $data['priority'] ?? 0,
            'is_stackable' => $data['is_stackable'] ?? false,
            'is_active' => $data['is_active'] ?? true,
            'valid_from' => $data['valid_from'] ?? null,
            'valid_to' => $data['valid_to'] ?? null,
        ]);

        $this->clearPricingCache();

        return $rule;
    }

    /**
     * Create customer-specific pricing.
     *
     * @param int $productId
     * @param int $customerId
     * @param float $price
     * @param array $data
     * @return ProductCustomerPricing
     */
    public function createCustomerPricing(
        int $productId,
        int $customerId,
        float $price,
        array $data = []
    ): ProductCustomerPricing {
        $customerPricing = ProductCustomerPricing::create([
            'product_id' => $productId,
            'user_id' => $customerId,
            'price' => $price,
            'compare_price' => $data['compare_price'] ?? null,
            'minimum_quantity' => $data['minimum_quantity'] ?? 1,
            'valid_from' => $data['valid_from'] ?? null,
            'valid_to' => $data['valid_to'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'metadata' => $data['metadata'] ?? [],
        ]);

        $this->clearPricingCache($customerId);

        return $customerPricing;
    }

    /**
     * Clear pricing cache.
     *
     * @param int|null $customerId
     * @return void
     */
    public function clearPricingCache(?int $customerId = null): void
    {
        if ($customerId) {
            Cache::forget(self::CACHE_PREFIX . 'customer_' . $customerId . '_*');
        }

        // Clear all pricing rules cache
        Cache::forget(self::CACHE_PREFIX . 'rules_*');

        // Clear general pricing cache
        Cache::tags(['product_pricing', 'product_prices'])->flush();
    }

    /**
     * Get pricing tiers for display.
     *
     * @param int $productId
     * @return array
     */
    public function getPricingTiers(int $productId): array
    {
        $rules = ProductPricingRule::active()
            ->whereJsonContains('product_ids', $productId)
            ->orWhereNull('product_ids')
            ->orderBy('priority', 'desc')
            ->get();

        $tiers = [];

        foreach ($rules as $rule) {
            if (in_array($rule->rule_type, [
                ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
                ProductPricingRule::RULE_TYPE_BULK_AMOUNT,
            ])) {
                $tiers[] = [
                    'rule_id' => $rule->id,
                    'rule_name' => $rule->name,
                    'tiers' => $rule->tiers,
                    'price_type' => $rule->price_type,
                ];
            }
        }

        return $tiers;
    }

    /**
     * Validate pricing tier structure.
     *
     * @param array $tiers
     * @return bool
     */
    public function validateTiers(array $tiers): bool
    {
        if (empty($tiers)) {
            return true;
        }

        $previousMax = null;

        foreach ($tiers as $tier) {
            // Check required fields
            if (!isset($tier['min']) || !isset($tier['value'])) {
                return false;
            }

            // Validate numeric values
            if (!is_numeric($tier['min']) || !is_numeric($tier['value'])) {
                return false;
            }

            // Check for overlapping ranges
            if ($previousMax !== null && $tier['min'] <= $previousMax) {
                return false;
            }

            if (isset($tier['max'])) {
                if ($tier['max'] < $tier['min']) {
                    return false;
                }
                $previousMax = $tier['max'];
            }
        }

        return true;
    }

    /**
     * Apply pricing to cart items.
     *
     * @param array $items
     * @param int|null $customerId
     * @param int|null $customerGroupId
     * @return array
     */
    public function applyPricingToCart(array $items, ?int $customerId = null, ?int $customerGroupId = null): array
    {
        $pricedItems = [];
        $totalDiscount = 0;

        foreach ($items as $item) {
            $productId = $item['product_id'] ?? null;
            $quantity = $item['qty'] ?? 1;
            $basePrice = $item['price'] ?? 0;

            if (!$productId) {
                $pricedItems[] = $item;
                continue;
            }

            $pricing = $this->calculatePrice(
                $productId,
                $quantity,
                $basePrice,
                $customerId,
                $customerGroupId
            );

            $item['original_price'] = $basePrice;
            $item['price'] = $pricing['final_price'];
            $item['discount'] = $pricing['discount'];
            $item['pricing_rules'] = $pricing['rules_applied'];

            $totalDiscount += $pricing['discount'] * $quantity;
            $pricedItems[] = $item;
        }

        return [
            'items' => $pricedItems,
            'total_discount' => $totalDiscount,
        ];
    }

    /**
     * Check if product has any active pricing rules.
     *
     * @param int $productId
     * @param int|null $customerId
     * @return bool
     */
    public function hasActivePricingRules(int $productId, ?int $customerId = null): bool
    {
        // Check for customer-specific pricing
        if ($customerId) {
            $customerPricing = ProductCustomerPricing::active()
                ->forCustomer($customerId)
                ->forProduct($productId)
                ->exists();

            if ($customerPricing) {
                return true;
            }
        }

        // Check for general pricing rules
        $hasRules = ProductPricingRule::active()
            ->where(function ($q) use ($productId) {
                $q->whereJsonContains('product_ids', $productId)
                    ->orWhereJsonContains('product_ids', 'all')
                    ->orWhereNull('product_ids');
            })
            ->exists();

        return $hasRules;
    }
}
