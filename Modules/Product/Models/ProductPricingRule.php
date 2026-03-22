<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

/**
 * Product Pricing Rule Model
 *
 * Represents advanced pricing rules for bulk and customer-specific pricing.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property array|null $product_ids
 * @property array|null $excluded_product_ids
 * @property array|null $category_ids
 * @property array|null $excluded_category_ids
 * @property array|null $customer_group_ids
 * @property array|null $customer_ids
 * @property bool $is_public
 * @property string $rule_type
 * @property array|null $tiers
 * @property string $price_type
 * @property int $priority
 * @property bool $is_stackable
 * @property array|null $cannot_stack_with
 * @property \Carbon\Carbon|null $valid_from
 * @property \Carbon\Carbon|null $valid_to
 * @property int|null $max_usage_count
 * @property int $usage_count
 * @property int|null $max_usage_per_customer
 * @property bool $is_active
 * @property \Carbon\Carbon|null $disabled_at
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProductPricingRule extends Model
{
    use HasFactory;
    use CacheableQueryBuilderTrait;

    protected $table = 'product_pricing_rules';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'product_ids',
        'excluded_product_ids',
        'category_ids',
        'excluded_category_ids',
        'customer_group_ids',
        'customer_ids',
        'is_public',
        'rule_type',
        'tiers',
        'price_type',
        'priority',
        'is_stackable',
        'cannot_stack_with',
        'valid_from',
        'valid_to',
        'max_usage_count',
        'usage_count',
        'max_usage_per_customer',
        'is_active',
        'disabled_at',
        'metadata',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'excluded_product_ids' => 'array',
        'category_ids' => 'array',
        'excluded_category_ids' => 'array',
        'customer_group_ids' => 'array',
        'customer_ids' => 'array',
        'is_public' => 'boolean',
        'tiers' => 'array',
        'priority' => 'integer',
        'is_stackable' => 'boolean',
        'cannot_stack_with' => 'array',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'max_usage_count' => 'integer',
        'usage_count' => 'integer',
        'max_usage_per_customer' => 'integer',
        'is_active' => 'boolean',
        'disabled_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $attributes = [
        'usage_count' => 0,
        'priority' => 0,
        'is_active' => true,
        'is_public' => true,
        'is_stackable' => false,
        'rule_type' => 'bulk_quantity',
        'price_type' => 'percentage_discount',
    ];

    public $timestamps = true;

    public $cacheTagsToClear = ['product_pricing', 'product_prices', 'content', 'cart'];

    /**
     * Rule type constants
     */
    const RULE_TYPE_BULK_QUANTITY = 'bulk_quantity';
    const RULE_TYPE_BULK_AMOUNT = 'bulk_amount';
    const RULE_TYPE_CUSTOMER_SPECIFIC = 'customer_specific';
    const RULE_TYPE_CUSTOMER_GROUP = 'customer_group';
    const RULE_TYPE_VARIANT_OVERRIDE = 'variant_override';
    const RULE_TYPE_BUNDLE_DISCOUNT = 'bundle_discount';

    /**
     * Price type constants
     */
    const PRICE_TYPE_FIXED = 'fixed';
    const PRICE_TYPE_PERCENTAGE_DISCOUNT = 'percentage_discount';
    const PRICE_TYPE_FIXED_DISCOUNT = 'fixed_discount';

    /**
     * Scope for active rules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_to')
                    ->orWhere('valid_to', '>=', now());
            });
    }

    /**
     * Scope for bulk pricing rules.
     */
    public function scopeBulk($query)
    {
        return $query->whereIn('rule_type', [
            self::RULE_TYPE_BULK_QUANTITY,
            self::RULE_TYPE_BULK_AMOUNT,
            self::RULE_TYPE_BUNDLE_DISCOUNT,
        ]);
    }

    /**
     * Scope for customer-specific rules.
     */
    public function scopeCustomerSpecific($query)
    {
        return $query->whereIn('rule_type', [
            self::RULE_TYPE_CUSTOMER_SPECIFIC,
            self::RULE_TYPE_CUSTOMER_GROUP,
        ]);
    }

    /**
     * Scope ordered by priority (highest first).
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    /**
     * Check if rule is valid for a product.
     *
     * @param int $productId
     * @return bool
     */
    public function appliesToProduct(int $productId): bool
    {
        // Check excluded products
        if (!empty($this->excluded_product_ids) && in_array($productId, $this->excluded_product_ids)) {
            return false;
        }

        // If no product restrictions, applies to all
        if (empty($this->product_ids)) {
            return true;
        }

        // Check if "all" is specified
        if (in_array('all', $this->product_ids)) {
            return true;
        }

        return in_array($productId, $this->product_ids);
    }

    /**
     * Check if rule applies to a category.
     *
     * @param int $categoryId
     * @return bool
     */
    public function appliesToCategory(int $categoryId): bool
    {
        // Check excluded categories
        if (!empty($this->excluded_category_ids) && in_array($categoryId, $this->excluded_category_ids)) {
            return false;
        }

        // If no category restrictions, applies to all
        if (empty($this->category_ids)) {
            return true;
        }

        return in_array($categoryId, $this->category_ids);
    }

    /**
     * Check if rule is valid for a customer.
     *
     * @param int|null $customerId
     * @param int|null $customerGroupId
     * @return bool
     */
    public function appliesToCustomer(?int $customerId = null, ?int $customerGroupId = null): bool
    {
        // Public rules apply to everyone
        if ($this->is_public) {
            return true;
        }

        // Check specific customer IDs
        if (!empty($this->customer_ids)) {
            if ($customerId !== null && in_array($customerId, $this->customer_ids)) {
                return true;
            }
        }

        // Check customer groups
        if (!empty($this->customer_group_ids)) {
            if ($customerGroupId !== null && in_array($customerGroupId, $this->customer_group_ids)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if rule is currently valid (dates).
     *
     * @return bool
     */
    public function isCurrentlyValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

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
     * Check if rule has reached usage limit.
     *
     * @param int|null $customerId
     * @return bool
     */
    public function hasReachedLimit(?int $customerId = null): bool
    {
        // Check total usage limit
        if ($this->max_usage_count !== null && $this->usage_count >= $this->max_usage_count) {
            return true;
        }

        // Check per-customer limit (would need separate tracking table)
        // For now, this is a placeholder - actual per-customer usage tracking
        // would require a pivot table or separate model
        if ($this->max_usage_per_customer !== null && $customerId !== null) {
            // Implementation would track uses in a separate table
            // For now, return false (no limit reached)
        }

        return false;
    }

    /**
     * Calculate price based on quantity or amount.
     *
     * @param float $basePrice
     * @param int $quantity
     * @param float $totalAmount
     * @return array ['price' => float, 'discount' => float, 'tier' => array|null]
     */
    public function calculatePrice(float $basePrice, int $quantity = 1, float $totalAmount = 0): array
    {
        $applicableTier = null;
        $value = $this->rule_type === self::RULE_TYPE_BULK_AMOUNT ? $totalAmount : $quantity;

        // Find applicable tier
        if (!empty($this->tiers)) {
            foreach ($this->tiers as $tier) {
                $threshold = $tier['min'] ?? 0;
                $max = $tier['max'] ?? null;

                if ($value >= $threshold && ($max === null || $value <= $max)) {
                    $applicableTier = $tier;
                    break;
                }
            }
        }

        if (!$applicableTier) {
            return [
                'price' => $basePrice,
                'discount' => 0,
                'tier' => null,
            ];
        }

        $tierValue = $applicableTier['value'] ?? 0;
        $calculatedPrice = $basePrice;
        $discount = 0;

        switch ($this->price_type) {
            case self::PRICE_TYPE_FIXED:
                $calculatedPrice = $tierValue;
                $discount = $basePrice - $tierValue;
                break;

            case self::PRICE_TYPE_PERCENTAGE_DISCOUNT:
                $discount = $basePrice * ($tierValue / 100);
                $calculatedPrice = $basePrice - $discount;
                break;

        case self::PRICE_TYPE_FIXED_DISCOUNT:
            $discount = min($tierValue, $basePrice);
            $calculatedPrice = max(0, $basePrice - $tierValue);
            break;
        }

        return [
            'price' => max(0, round($calculatedPrice, 2)),
            'discount' => round($discount, 2),
            'tier' => $applicableTier,
        ];
    }

    /**
     * Increment usage count.
     *
     * @return void
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get applicable tier for quantity.
     *
     * @param int $quantity
     * @return array|null
     */
    public function getTierForQuantity(int $quantity): ?array
    {
        if (empty($this->tiers)) {
            return null;
        }

        foreach ($this->tiers as $tier) {
            $min = $tier['min'] ?? 0;
            $max = $tier['max'] ?? null;

            if ($quantity >= $min && ($max === null || $quantity <= $max)) {
                return $tier;
            }
        }

        return null;
    }

    /**
     * Check if this rule can stack with another rule.
     *
     * @param ProductPricingRule $otherRule
     * @return bool
     */
    public function canStackWith(self $otherRule): bool
    {
        // Both rules must be stackable
        if (!$this->is_stackable || !$otherRule->is_stackable) {
            return false;
        }

        // Check if this rule cannot stack with the other
        if (!empty($this->cannot_stack_with) && in_array($otherRule->id, $this->cannot_stack_with)) {
            return false;
        }

        // Check if the other rule cannot stack with this one
        if (!empty($otherRule->cannot_stack_with) && in_array($this->id, $otherRule->cannot_stack_with)) {
            return false;
        }

        return true;
    }

    /**
     * Generate a unique slug if not provided.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Illuminate\Support\Str::slug($model->name) . '-' . uniqid();
            }
        });
    }
}
