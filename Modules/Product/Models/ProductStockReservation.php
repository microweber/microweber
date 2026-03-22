<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Content\Models\Content;
use Modules\Order\Models\Order;
use MicroweberPackages\User\Models\User;

/**
 * Product Stock Reservation Model
 *
 * Tracks reserved stock for carts, pending orders, and holds.
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $variant_id
 * @property int $quantity
 * @property string $reservation_type
 * @property string|null $session_id
 * @property int|null $user_id
 * @property int|null $order_id
 * @property \Carbon\Carbon $expires_at
 * @property bool $is_active
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProductStockReservation extends Model
{
    use HasFactory;
    use CacheableQueryBuilderTrait;

    protected $table = 'product_stock_reservations';

    protected $fillable = [
        'product_id',
        'variant_id',
        'quantity',
        'reservation_type',
        'session_id',
        'user_id',
        'order_id',
        'expires_at',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'quantity' => 'integer',
        'user_id' => 'integer',
        'order_id' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'json',
    ];

    /**
     * Reservation types
     */
    public const TYPE_CART = 'cart';
    public const TYPE_ORDER = 'order';
    public const TYPE_HOLD = 'hold';
    public const TYPE_PREORDER = 'preorder';

    public $timestamps = true;

    public $cacheTagsToClear = ['product_inventory', 'repositories', 'content', 'cart'];

    /**
     * Get the product this reservation belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'product_id');
    }

    /**
     * Get the variant this reservation belongs to (if applicable).
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariantCombination::class, 'variant_id');
    }

    /**
     * Get the user who made this reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the order this reservation belongs to (if applicable).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Scope for active reservations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope for expired reservations.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for reservations by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('reservation_type', $type);
    }

    /**
     * Scope for cart reservations.
     */
    public function scopeCart($query)
    {
        return $query->where('reservation_type', self::TYPE_CART);
    }

    /**
     * Scope for order reservations.
     */
    public function scopeOrder($query)
    {
        return $query->where('reservation_type', self::TYPE_ORDER);
    }

    /**
     * Scope for reservations by product.
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope for reservations by variant.
     */
    public function scopeForVariant($query, int $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    /**
     * Scope for reservations by session.
     */
    public function scopeForSession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope for reservations by user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if reservation is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if reservation is still valid.
     */
    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Release (deactivate) this reservation.
     */
    public function release(): bool
    {
        $this->is_active = false;
        return $this->save();
    }

    /**
     * Extend the expiration time.
     */
    public function extend(int $minutes): bool
    {
        $this->expires_at = $this->expires_at->addMinutes($minutes);
        return $this->save();
    }

    /**
     * Convert to a cart reservation.
     */
    public function convertToCart(string $sessionId): bool
    {
        $this->reservation_type = self::TYPE_CART;
        $this->session_id = $sessionId;
        $this->order_id = null;
        return $this->save();
    }

    /**
     * Convert to an order reservation.
     */
    public function convertToOrder(int $orderId): bool
    {
        $this->reservation_type = self::TYPE_ORDER;
        $this->order_id = $orderId;
        $this->session_id = null;
        return $this->save();
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Automatically deactivate expired reservations when retrieved
        static::retrieved(function ($model) {
            if ($model->is_active && $model->isExpired()) {
                $model->is_active = false;
                $model->saveQuietly();
            }
        });
    }
}
