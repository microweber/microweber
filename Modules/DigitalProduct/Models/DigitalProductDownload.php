<?php

namespace Modules\DigitalProduct\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * A download grant: a buyer's tokenized right to download a specific file from a
 * paid order. download_limit NULL = unlimited; expires_at NULL = never expires.
 */
class DigitalProductDownload extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\DigitalProduct\Events\DigitalProductDownloadWasCreated::class,
        'updated' => \Modules\DigitalProduct\Events\DigitalProductDownloadWasUpdated::class,
        'deleted' => \Modules\DigitalProduct\Events\DigitalProductDownloadWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    protected $table = 'digital_product_downloads';

    protected $fillable = [
        'order_id',
        'product_id',
        'downloadable_file_id',
        'customer_id',
        'customer_email',
        'token',
        'downloads_count',
        'download_limit',
        'expires_at',
        'last_downloaded_at',
        'notified_at',
    ];

    protected $casts = [
        'downloads_count' => 'integer',
        'download_limit' => 'integer',
        'expires_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    /**
     * The secure, tokenized delivery URL for this grant — the only link ever
     * shared with the buyer (never a direct file URL).
     */
    public function downloadUrl(): string
    {
        return route('digital-product.download', ['token' => $this->token]);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(ProductDownloadableFile::class, 'downloadable_file_id');
    }

    public static function generateToken(): string
    {
        return Str::random(64);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function hasReachedLimit(): bool
    {
        return $this->download_limit !== null && $this->downloads_count >= $this->download_limit;
    }

    public function canDownload(): bool
    {
        return !$this->isExpired() && !$this->hasReachedLimit();
    }
}
