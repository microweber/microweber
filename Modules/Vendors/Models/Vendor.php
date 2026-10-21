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

    /** Channels a vendor can receive order notifications on. */
    public const CHANNEL_MAIL = 'mail';
    public const CHANNEL_DATABASE = 'database';
    public const CHANNELS = [self::CHANNEL_MAIL, self::CHANNEL_DATABASE];

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
        'notify_on_order',
        'notify_channels',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'notify_on_order' => 'boolean',
        'notify_channels' => 'array',
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
        // Use the app's configured auth model (App\Models\User in a standard
        // install) rather than the base class, so $vendor->user has the SAME
        // morph type as the authenticated user. Database notifications are
        // keyed by notifiable morph type, so a mismatch would hide a vendor's
        // in-app order notifications from their own notification bell.
        $model = config('auth.providers.users.model') ?: \MicroweberPackages\User\Models\User::class;
        return $this->belongsTo($model, 'user_id');
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

    /** Should this vendor be told when one of their products is ordered? */
    public function wantsOrderNotifications(): bool
    {
        return (bool) $this->notify_on_order && ! empty($this->orderNotificationChannels());
    }

    /**
     * The channels this vendor receives order notifications on. Falls back to
     * both mail + in-app when unset, and always drops anything unknown.
     */
    public function orderNotificationChannels(): array
    {
        $channels = $this->notify_channels;
        if (! is_array($channels) || empty($channels)) {
            $channels = self::CHANNELS;
        }
        return array_values(array_intersect($channels, self::CHANNELS));
    }

    /** Where order-notification email should go for this vendor. */
    public function notificationEmail(): ?string
    {
        return $this->contact_email ?: $this->user?->email;
    }
}
