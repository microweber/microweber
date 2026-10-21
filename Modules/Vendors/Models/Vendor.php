<?php

namespace Modules\Vendors\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Modules\Product\Models\Product;

/**
 * A vendor storefront owned by a single user.
 *
 * Vendor is a thin state owner: it holds the public shop identity
 * (name/slug/logo/banner/bio) and the approval gate. It does NOT own
 * products — products stay Content/Product rows owned via
 * `content.created_by = vendor.user_id` (the FilterByAuthor convention).
 */
class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
        'user_id',
        'shop_name',
        'slug',
        'description',
        'logo',
        'banner',
        'contact_email',
        'website',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Keep the slug populated + unique. Derived from the shop name (or the
     * user id as a last resort) whenever it is blank.
     */
    protected static function booted(): void
    {
        static::saving(function (Vendor $vendor) {
            if (empty($vendor->slug)) {
                $base = $vendor->shop_name ?: ('vendor-' . $vendor->user_id);
                $vendor->slug = static::uniqueSlug(Str::slug($base) ?: ('vendor-' . $vendor->user_id), $vendor->id);
            }
        });
    }

    protected static function uniqueSlug(string $slug, $ignoreId = null): string
    {
        $original = $slug;
        $i = 2;
        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $i;
            $i++;
        }
        return $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\MicroweberPackages\User\Models\User::class, 'user_id');
    }

    /**
     * Query the products this vendor owns (created_by = user_id).
     * Uses the Product FilterByAuthor trait so the ownership rule lives
     * in exactly one place.
     */
    public function products()
    {
        return Product::query()->filter(['author' => $this->user_id]);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->shop_name
            ?: ($this->user?->username ?: $this->user?->email ?: ('Vendor #' . $this->user_id));
    }
}
