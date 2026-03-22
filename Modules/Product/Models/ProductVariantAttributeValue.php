<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;

/**
 * Product Variant Attribute Value Model
 *
 * Represents specific values for variant attributes like "Small", "Red", "Cotton"
 *
 * @property int $id
 * @property int $attribute_id
 * @property string $value The display value
 * @property string $key The unique key per attribute
 * @property string|null $description
 * @property string|null $color_code Hex color code for color type
 * @property string|null $image Optional image URL
 * @property int $position
 * @property bool $is_active
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read ProductVariantAttribute $attribute
 */
class ProductVariantAttributeValue extends Model
{
    use HasFactory;
    use CacheableQueryBuilderTrait;
    use MaxPositionTrait;

    protected $table = 'product_variant_attribute_values';

    protected $fillable = [
        'attribute_id',
        'value',
        'key',
        'description',
        'color_code',
        'image',
        'position',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
        'metadata' => 'json',
    ];

    protected $attributes = [
        'is_active' => true,
        'position' => 0,
    ];

    public $timestamps = true;

    public $cacheTagsToClear = ['product_variants', 'repositories', 'content'];

    /**
     * Get the attribute this value belongs to.
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductVariantAttribute::class, 'attribute_id');
    }

    /**
     * Get variant combinations using this attribute value.
     */
    public function combinations(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariantCombination::class,
            'product_variant_combination_attributes',
            'attribute_value_id',
            'combination_id'
        );
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->key) && !empty($model->value)) {
                $model->key = \Illuminate\Support\Str::slug($model->value);
            }
        });

        static::saving(function ($model) {
            if (empty($model->key) && !empty($model->value)) {
                $model->key = \Illuminate\Support\Str::slug($model->value);
            }
        });
    }

    /**
     * Scope for active values.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering by position.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('value');
    }

    /**
     * Scope for a specific attribute.
     */
    public function scopeForAttribute($query, int $attributeId)
    {
        return $query->where('attribute_id', $attributeId);
    }

    /**
     * Get value by attribute key and value key.
     */
    public static function findByKeys(string $attributeKey, string $valueKey): ?self
    {
        return static::whereHas('attribute', function ($query) use ($attributeKey) {
            $query->where('key', $attributeKey);
        })->where('key', $valueKey)->first();
    }

    /**
     * Check if this value has a color code.
     */
    public function hasColor(): bool
    {
        return !empty($this->color_code);
    }

    /**
     * Get the display value with color indicator.
     */
    public function getDisplayValueAttribute(): string
    {
        if ($this->hasColor()) {
            return "{$this->value} ({$this->color_code})";
        }
        return $this->value;
    }

    /**
     * Get formatted value for dropdowns.
     */
    public function getFormattedOptionAttribute(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'key' => $this->key,
            'color_code' => $this->color_code,
            'image' => $this->image,
        ];
    }
}
