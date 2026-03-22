<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Content\Models\Content;
use MicroweberPackages\User\Models\User;

/**
 * Product Inventory Movement Model
 *
 * Tracks all stock changes including sales, restocks, adjustments, and returns.
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $variant_id
 * @property int $quantity_change
 * @property int $quantity_before
 * @property int $quantity_after
 * @property string $type
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property string|null $notes
 * @property int|null $user_id
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProductInventoryMovement extends Model
{
    use HasFactory;
    use CacheableQueryBuilderTrait;

    protected $table = 'product_inventory_movements';

    protected $fillable = [
        'product_id',
        'variant_id',
        'quantity_change',
        'quantity_before',
        'quantity_after',
        'type',
        'reference_type',
        'reference_id',
        'notes',
        'user_id',
        'metadata',
    ];

    protected $casts = [
        'quantity_change' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'reference_id' => 'integer',
        'user_id' => 'integer',
        'variant_id' => 'integer',
        'metadata' => 'json',
    ];

    /**
     * Movement types
     */
    public const TYPE_SALE = 'sale';
    public const TYPE_RESTOCK = 'restock';
    public const TYPE_ADJUSTMENT = 'adjustment';
    public const TYPE_RESERVATION = 'reservation';
    public const TYPE_RESERVATION_RELEASED = 'reservation_released';
    public const TYPE_RETURN = 'return';
    public const TYPE_CANCELLED = 'cancelled';
    public const TYPE_DAMAGED = 'damaged';
    public const TYPE_LOST = 'lost';
    public const TYPE_TRANSFER_IN = 'transfer_in';
    public const TYPE_TRANSFER_OUT = 'transfer_out';
    public const TYPE_INITIAL = 'initial';

    public $timestamps = true;

    public $cacheTagsToClear = ['product_inventory', 'repositories', 'content'];

    /**
     * Get the product this movement belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'product_id');
    }

    /**
     * Get the variant this movement belongs to (if applicable).
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariantCombination::class, 'variant_id');
    }

    /**
     * Get the user who made this movement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for movements by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for movements by product.
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope for movements by variant.
     */
    public function scopeForVariant($query, int $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    /**
     * Scope for sales (outgoing stock).
     */
    public function scopeSales($query)
    {
        return $query->where('quantity_change', '<', 0);
    }

    /**
     * Scope for restocks (incoming stock).
     */
    public function scopeRestocks($query)
    {
        return $query->where('quantity_change', '>', 0);
    }

    /**
     * Scope for date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get the reference model (polymorphic).
     */
    public function reference()
    {
        if ($this->reference_type && $this->reference_id) {
            $class = $this->getReferenceClass();
            if ($class) {
                return $class::find($this->reference_id);
            }
        }
        return null;
    }

    /**
     * Get the class for reference type.
     */
    protected function getReferenceClass(): ?string
    {
        $mapping = [
            'order' => 'Modules\\Order\\Models\\Order',
            'cart' => 'Modules\\Cart\\Models\\Cart',
            'adjustment' => self::class,
        ];

        return $mapping[$this->reference_type] ?? null;
    }

    /**
     * Check if this is a sale movement.
     */
    public function isSale(): bool
    {
        return $this->type === self::TYPE_SALE;
    }

    /**
     * Check if this is a restock movement.
     */
    public function isRestock(): bool
    {
        return $this->type === self::TYPE_RESTOCK;
    }

    /**
     * Check if this is an adjustment movement.
     */
    public function isAdjustment(): bool
    {
        return $this->type === self::TYPE_ADJUSTMENT;
    }
}
