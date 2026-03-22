<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Content\Models\Content;

/**
 * Product Variant Combination Model
 *
 * Represents a specific combination of variant attributes for a product.
 * Example: Small + Red + Cotton = one specific variant of a product.
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $sku
 * @property string|null $barcode
 * @property float|null $price Override price for this variant
 * @property float|null $compare_price
 * @property float|null $cost_price
 * @property int $quantity
 * @property string $quantity_type
 * @property bool $track_quantity
 * @property bool $allow_backorders
 * @property float|null $weight
 * @property string|null $image
 * @property bool $is_default
 * @property bool $is_active
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProductVariantCombination extends Model
{
    use HasFactory;
    use CacheableQueryBuilderTrait;

    protected $table = 'product_variants_combinations';

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'price',
        'compare_price',
        'cost_price',
        'quantity',
        'quantity_type',
        'track_quantity',
        'allow_backorders',
        'weight',
        'image',
        'is_default',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'quantity' => 'integer',
        'track_quantity' => 'boolean',
        'allow_backorders' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'json',
    ];

    protected $attributes = [
        'quantity' => 0,
        'quantity_type' => 'limited',
        'track_quantity' => true,
        'allow_backorders' => false,
        'is_default' => false,
        'is_active' => true,
    ];

    public $timestamps = true;

    public $cacheTagsToClear = ['product_variants', 'repositories', 'content', 'cart'];

    /**
     * Get the product this variant belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'product_id');
    }

    /**
     * Get the attribute values for this combination.
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariantAttributeValue::class,
            'product_variant_combination_attributes',
            'combination_id',
            'attribute_value_id'
        )->with('attribute');
    }

    /**
     * Scope for active combinations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default variant.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for product.
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Check if variant is in stock.
     */
    public function isInStock(): bool
    {
        if (!$this->track_quantity) {
            return true;
        }

        if ($this->quantity_type === 'unlimited') {
            return true;
        }

        return $this->quantity > 0 || $this->allow_backorders;
    }

    /**
     * Get available quantity.
     */
    public function getAvailableQuantity(): int
    {
        if (!$this->track_quantity || $this->quantity_type === 'unlimited') {
            return PHP_INT_MAX;
        }

        return $this->quantity;
    }

    /**
     * Get final price for this variant.
     */
    public function getFinalPrice(): float
    {
        if ($this->price !== null && $this->price > 0) {
            return (float) $this->price;
        }

        $product = $this->product;
        if ($product) {
            return (float) ($product->price ?? 0);
        }

        return 0.0;
    }

    /**
     * Get variant attributes as key-value array.
     */
    public function getAttributesArray(): array
    {
        return $this->attributeValues->mapWithKeys(function ($value) {
            return [$value->attribute->key => $value->key];
        })->toArray();
    }

    /**
     * Check if variant matches given attributes.
     */
    public function matchesAttributes(array $attributes): bool
    {
        $variantAttributes = $this->getAttributesArray();
        return $variantAttributes == $attributes;
    }

    /**
     * Find combination by product and attributes.
     */
    public static function findByAttributes(int $productId, array $attributes): ?self
    {
        $combinations = static::where('product_id', $productId)
            ->with('attributeValues.attribute')
            ->get();

        foreach ($combinations as $combination) {
            if ($combination->matchesAttributes($attributes)) {
                return $combination;
            }
        }

        return null;
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->is_default) {
                static::where('product_id', $model->product_id)
                    ->where('id', '!=', $model->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
