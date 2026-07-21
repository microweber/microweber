<?php

namespace MicroweberPackages\CdnSync\Contracts;

/**
 * Contract for models that can sync files to CDN.
 *
 * Any Eloquent model implementing this interface declares which local files
 * should be uploaded to CDN/S3 storage.
 */
interface CdnSyncable
{
    /**
     * Return an array of local file paths that should be synced to CDN.
     *
     * Each entry should be an absolute path or a path relative to the
     * application's public/storage directory.
     *
     * @return array<int, string>
     */
    public function getCdnSyncFiles(): array;

    /**
     * Return the CDN rel_type identifier for this model.
     *
     * Used in the polymorphic cdn_sync_log table.
     *
     * @return string
     */
    public function getCdnRelType(): string;

    /**
     * Return the CDN rel_id for this model.
     *
     * @return int|string
     */
    public function getCdnRelId(): int|string;
}