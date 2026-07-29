<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

/**
 * @property int $id
 * @property string $family
 * @property string $provider
 * @property string|null $category
 * @property bool $is_enabled
 * @property string|null $file_path
 * @property string|null $file_url
 * @property string|null $css_path
 * @property string|null $css_url
 * @property array<string, mixed>|null $meta
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder<static> enabled()
 * @method static Builder<static> provider(string $provider)
 * @method static Builder<static> query()
 */
class TemplateFont extends Model
{
    // Cache SELECTs (fonts are read on nearly every page render) and flush the
    // 'fonts' tag on any write, mirroring the Option models — avoids a DB query
    // per load.
    use CacheableQueryBuilderTrait;

    /** @var list<string> */
    public $cacheTagsToClear = ['fonts'];

    public const PROVIDER_GOOGLE = 'google';

    public const PROVIDER_CUSTOM = 'custom';

    public const PROVIDER_SYSTEM = 'system';

    protected $table = 'template_fonts';

    /** @var list<string> */
    protected $fillable = [
        'family',
        'provider',
        'category',
        'is_enabled',
        'file_path',
        'file_url',
        'css_path',
        'css_url',
        'meta',
        'sort_order',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_enabled' => 'boolean',
        'meta' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeProvider(Builder $query, string $provider): Builder
    {
        return $query->where('provider', $provider);
    }

    public function isGoogle(): bool
    {
        return $this->provider === self::PROVIDER_GOOGLE;
    }

    public function isCustom(): bool
    {
        return $this->provider === self::PROVIDER_CUSTOM;
    }

    public function isSystem(): bool
    {
        return $this->provider === self::PROVIDER_SYSTEM;
    }
}
