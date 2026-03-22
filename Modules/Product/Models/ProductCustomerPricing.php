<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\User\Models\User;

/**
 * Product Customer Pricing Model
 *
 * Represents customer-specific pricing for individual products.
 *
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property float|null $price
 * @property float|null $compare_price
 * @property float $minimum_quantity
 * @property \Carbon\Carbon|null $valid_from
 * @property \Carbon\Carbon|null $valid_to
 * @property bool $is_active
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProductCustomerPricing extends Model
{
    use HasFactory;
    use CacheableQueryBuilderTrait;

    protected $table = 'product_customer_pricing';

    protected $fillable = [
        'product_id',
        'user_id',
        'price',
        'compare_price',
        'minimum_quantity',
        'valid_from',
        'valid_to',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'minimum_quantity' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    protected $attributes = [
        'minimum_quantity' => 1,
        'is_active' => true,
    ];

    public $timestamps = true;

    public $cacheTagsToClear = ['product_pricing', 'product_prices', 'content', 'cart'];

    /**
     * Get the product this pricing applies to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(\Modules\Content\Models\Content::class, 'product_id');
    }

    /**
     * Get the customer this pricing applies to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for active customer pricing.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
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
     * Scope for a specific customer.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $customerId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('user_id', $customerId);
    }

    /**
     * Scope for a specific product.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $productId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope for minimum quantity.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $quantity
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForQuantity($query, float $quantity)
    {
        return $query->where('minimum_quantity', '<=', $quantity);
    }

    /**
     * Check if this pricing is currently valid.
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
     * Check if pricing applies to quantity.
     *
     * @param float $quantity
     * @return bool
     */
    public function appliesToQuantity(float $quantity): bool
    {
        return $quantity >= $this->minimum_quantity;
    }

    /**
     * Get formatted price for display.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        return currency_format($this->price);
    }

    /**
     * Get formatted compare price for display.
     *
     * @return string|null
     */
    public function getFormattedComparePriceAttribute(): ?string
    {
        if ($this->compare_price === null) {
            return null;
        }

        return currency_format($this->compare_price);
    }

    /**
     * Calculate discount percentage.
     *
     * @return float|null
     */
    public function getDiscountPercentageAttribute(): ?float
    {
        if ($this->price === null || $this->compare_price === null || $this->compare_price <= 0) {
            return null;
        }

        return round((($this->compare_price - $this->price) / $this->compare_price) * 100, 2);
    }
}
