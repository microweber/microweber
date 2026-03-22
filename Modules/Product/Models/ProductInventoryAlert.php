<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Content\Models\Content;
use MicroweberPackages\User\Models\User;

/**
 * Product Inventory Alert Model
 *
 * Tracks low stock and out of stock alerts for products and variants.
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $variant_id
 * @property string $alert_type
 * @property int $current_quantity
 * @property int $threshold_quantity
 * @property bool $is_resolved
 * @property \Carbon\Carbon|null $resolved_at
 * @property int|null $resolved_by
 * @property string|null $resolution_notes
 * @property array|null $notification_sent_to
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProductInventoryAlert extends Model
{
    use HasFactory;
    use CacheableQueryBuilderTrait;

    protected $table = 'product_inventory_alerts';

    protected $fillable = [
        'product_id',
        'variant_id',
        'alert_type',
        'current_quantity',
        'threshold_quantity',
        'is_resolved',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
        'notification_sent_to',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'current_quantity' => 'integer',
        'threshold_quantity' => 'integer',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'resolved_by' => 'integer',
        'notification_sent_to' => 'json',
    ];

    /**
     * Alert types
     */
    public const TYPE_LOW_STOCK = 'low_stock';
    public const TYPE_OUT_OF_STOCK = 'out_of_stock';
    public const TYPE_CRITICAL = 'critical';

    public $timestamps = true;

    public $cacheTagsToClear = ['product_inventory', 'repositories', 'content'];

    /**
     * Get the product this alert belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'product_id');
    }

    /**
     * Get the variant this alert belongs to (if applicable).
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariantCombination::class, 'variant_id');
    }

    /**
     * Get the user who resolved this alert.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Scope for unresolved alerts.
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope for resolved alerts.
     */
    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    /**
     * Scope for alerts by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('alert_type', $type);
    }

    /**
     * Scope for low stock alerts.
     */
    public function scopeLowStock($query)
    {
        return $query->where('alert_type', self::TYPE_LOW_STOCK);
    }

    /**
     * Scope for out of stock alerts.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('alert_type', self::TYPE_OUT_OF_STOCK);
    }

    /**
     * Scope for critical alerts.
     */
    public function scopeCritical($query)
    {
        return $query->where('alert_type', self::TYPE_CRITICAL);
    }

    /**
     * Scope for alerts by product.
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope for alerts by variant.
     */
    public function scopeForVariant($query, int $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    /**
     * Mark alert as resolved.
     */
    public function resolve(?int $userId = null, ?string $notes = null): bool
    {
        $this->is_resolved = true;
        $this->resolved_at = now();
        $this->resolved_by = $userId;
        $this->resolution_notes = $notes;

        return $this->save();
    }

    /**
     * Check if this is a low stock alert.
     */
    public function isLowStock(): bool
    {
        return $this->alert_type === self::TYPE_LOW_STOCK;
    }

    /**
     * Check if this is an out of stock alert.
     */
    public function isOutOfStock(): bool
    {
        return $this->alert_type === self::TYPE_OUT_OF_STOCK;
    }

    /**
     * Check if this is a critical alert.
     */
    public function isCritical(): bool
    {
        return $this->alert_type === self::TYPE_CRITICAL;
    }

    /**
     * Get severity level for display.
     */
    public function getSeverityAttribute(): string
    {
        return match ($this->alert_type) {
            self::TYPE_CRITICAL => 'critical',
            self::TYPE_OUT_OF_STOCK => 'high',
            self::TYPE_LOW_STOCK => 'medium',
            default => 'low',
        };
    }

    /**
     * Get severity color for Filament.
     */
    public function getSeverityColorAttribute(): string
    {
        return match ($this->alert_type) {
            self::TYPE_CRITICAL => 'danger',
            self::TYPE_OUT_OF_STOCK => 'warning',
            self::TYPE_LOW_STOCK => 'info',
            default => 'gray',
        };
    }
}
