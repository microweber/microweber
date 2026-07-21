<?php

namespace MicroweberPackages\CdnSync\Traits;

use MicroweberPackages\CdnSync\Models\CdnSyncLog;

/**
 * Trait HasCdnSync
 *
 * Add to any Eloquent model to enable CDN sync capabilities.
 * The model should also implement CdnSyncable interface.
 */
trait HasCdnSync
{
    /**
     * Get all CDN sync log entries for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function cdnSyncLogs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(CdnSyncLog::class, 'syncable', 'rel_type', 'rel_id');
    }

    /**
     * Check if all files are synced to CDN.
     */
    public function isFullySyncedToCdn(): bool
    {
        $files = $this->getCdnSyncFiles();

        if (empty($files)) {
            return false;
        }

        $syncedCount = $this->cdnSyncLogs()->where('is_synced', true)->count();

        return $syncedCount >= count($files);
    }

    /**
     * Check if any file is synced to CDN.
     */
    public function hasAnyCdnSync(): bool
    {
        return $this->cdnSyncLogs()->where('is_synced', true)->exists();
    }

    /**
     * Get the CDN URL for a specific local path, or the first synced file.
     */
    public function getCdnUrl(?string $localPath = null): ?string
    {
        $query = $this->cdnSyncLogs()->where('is_synced', true);

        if ($localPath !== null) {
            $query->where('local_path', $localPath);
        }

        $log = $query->first();

        return $log?->cdn_url;
    }

    /**
     * Default implementation of getCdnRelType.
     */
    public function getCdnRelType(): string
    {
        return static::class;
    }

    /**
     * Default implementation of getCdnRelId.
     */
    public function getCdnRelId(): int|string
    {
        return $this->getKey();
    }

    /**
     * Default implementation: return the model's main file attribute.
     * Override in your model to specify which files to sync.
     *
     * @return array<int, string>
     */
    public function getCdnSyncFiles(): array
    {
        // Try common file attribute names
        foreach (['filename', 'file_path', 'path', 'image', 'file'] as $attr) {
            if (isset($this->attributes[$attr]) && !empty($this->attributes[$attr])) {
                return [$this->attributes[$attr]];
            }
        }

        return [];
    }

    /**
     * Scope to filter models that are synced to CDN.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSyncedToCdn($query)
    {
        return $query->whereHas('cdnSyncLogs', function ($q) {
            $q->where('is_synced', true);
        });
    }

    /**
     * Scope to filter models not synced to CDN.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotSyncedToCdn($query)
    {
        return $query->whereDoesntHave('cdnSyncLogs', function ($q) {
            $q->where('is_synced', true);
        });
    }
}