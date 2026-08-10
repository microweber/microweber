<?php

namespace Modules\Media\Services;

use Illuminate\Support\Facades\Log;
use MicroweberPackages\CdnSync\Services\CdnSyncService;
use Modules\Media\Models\Media;
use MicroweberPackages\CdnSync\Facades\CdnSync;

/**
 * @deprecated Use MicroweberPackages\CdnSync\Services\CdnSyncService (CdnSync) instead.
 *
 * This class is a backwards-compatibility shim that delegates to the
 * microweber-packages/cdn-sync package. It will be removed in a future release.
 */
class CdnIntegrationService
{
    protected CdnSyncService $cdnSync;

    public function __construct()
    {
        $this->cdnSync = CdnSync::getFacadeRoot();
    }

    /**
     * @deprecated Use CdnSync::sync($media) instead.
     */
    public function uploadToCdn(Media $media, bool $deleteLocal = false): bool
    {
        $result = $this->cdnSync->sync($media);
        return $result['success'];
    }

    /**
     * @deprecated Use CdnSync::delete($media) instead.
     */
    public function deleteFromCdn(Media $media): bool
    {
        return $this->cdnSync->delete($media);
    }

    /**
     * @deprecated Use CdnSync::sync($media) instead.
     */
    public function syncMedia(int $mediaId, bool $deleteLocal = false): array
    {
        $media = Media::find($mediaId);
        if (!$media) {
            return ['success' => false, 'error' => 'Media not found'];
        }
        $result = $this->cdnSync->sync($media);
        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'Synced' : 'Failed',
            'cdn_url' => $media->getCdnUrl(),
            'error' => $result['errors'][0] ?? null,
        ];
    }

    /**
     * @deprecated Use CdnSync::bulkSync($models) instead.
     */
    public function bulkSync(array $mediaIds, bool $deleteLocal = false): array
    {
        $models = Media::whereIn('id', $mediaIds)->get();
        $cdnResult = $this->cdnSync->bulkSync($models);
        return [
            'total' => $cdnResult['total'],
            'success' => range(1, $cdnResult['success']),
            'failed' => array_fill(0, $cdnResult['failed'], ['id' => 0, 'error' => 'failed']),
        ];
    }

    /**
     * @deprecated Use $media->getCdnUrl() instead.
     */
    public function getCdnUrl(Media $media): ?string
    {
        return $media->getCdnUrl();
    }

    /**
     * @deprecated Use CdnSync::isConfigured() instead.
     */
    public function isConfigured(): bool
    {
        return $this->cdnSync->isConfigured();
    }

    /**
     * @deprecated Use CdnSync::getStats() instead.
     */
    public function getStats(): array
    {
        return $this->cdnSync->getStats();
    }

    /**
     * @deprecated No longer supported, use CloudFront invalidation directly.
     */
    public function invalidateCache(array $paths): bool
    {
        Log::warning('CdnIntegrationService::invalidateCache() is deprecated.');
        return false;
    }
}
