<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;

/**
 * Product Variant Attribute Model
 *
 * Represents product attributes like Size, Color, Material, etc.
 * These are the "types" of variants a product can have.
 *
 * @property int $id
 * @property string $name The display name (e.g., "Size", "Color")
 * @property string $key The unique key (e.g., "size", "color")
 * @property string|null $description
 * @property string $type The UI type (select, radio, color, button)
 * @property int $position Display order
 * @property bool $is_active
 * @property array|null $settings Additional settings
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProductVariantAttribute extends Model
{
    use HasFactory;
    use CacheableQueryBuilderTrait;
    use HasCreatedByFieldsTrait;

    protected $table = 'product_variant_attributes';

    protected $fillable = [
        'name',
        'key',
        'description',
        'type',
        'position',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
        'settings' => 'json',
    ];

    protected $attributes = [
        'is_active' => true,
        'position' => 0,
        'type' => 'select',
    ];

    public $timestamps = true;

    public $cacheTagsToClear = ['product_variants', 'repositories', 'content'];

    /**
     * Get the attribute values for this attribute.
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductVariantAttributeValue::class, 'attribute_id')
            ->orderBy('position');
    }

    /**
     * Get active values only.
     */
    public function activeValues(): HasMany
    {
        return $this->hasMany(ProductVariantAttributeValue::class, 'attribute_id')
            ->where('is_active', true)
            ->orderBy('position');
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->key) && !empty($model->name)) {
                $model->key = \Illuminate\Support\Str::slug($model->name);
            }
        });

        static::saving(function ($model) {
            if (empty($model->key) && !empty($model->name)) {
                $model->key = \Illuminate\Support\Str::slug($model->name);
            }
        });
    }

    /**
     * Scope for active attributes.
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
        return $query->orderBy('position')->orderBy('name');
    }

    /**
     * Get attribute by key.
     */
    public static function findByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    /**
     * Check if this attribute has color type.
     */
    public function isColorType(): bool
    {
        return $this->type === 'color';
    }

    /**
     * Get formatted name with value count.
     */
    public function getNameWithCountAttribute(): string
    {
        $count = $this->values()->count();
        return "{$this->name} ({$count} values)";
    }
}
