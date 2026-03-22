<?php

namespace Modules\Media\Services;

use Aws\S3\S3Client;
use Aws\CloudFront\CloudFrontClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Models\Media;

class CdnIntegrationService
{
    /**
     * @var array Supported CDN providers
     */
    protected $supportedProviders = ['s3', 'cloudfront', 'rackspace'];

    /**
     * @var string Current provider
     */
    protected $provider;

    /**
     * @var array Provider configuration
     */
    protected $config;

    /**
     * @var S3Client|null
     */
    protected $s3Client;

    /**
     * @var CloudFrontClient|null
     */
    protected $cloudFrontClient;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->provider = config('media.cdn_provider', 's3');
        $this->config = config('media.cdn_config', []);
    }

    /**
     * Initialize S3 client.
     *
     * @return S3Client
     */
    protected function getS3Client(): S3Client
    {
        if (!$this->s3Client) {
            $this->s3Client = new S3Client([
                'version' => 'latest',
                'region' => $this->config['region'] ?? 'us-east-1',
                'credentials' => [
                    'key' => $this->config['key'] ?? '',
                    'secret' => $this->config['secret'] ?? '',
                ],
            ]);
        }

        return $this->s3Client;
    }

    /**
     * Upload media to CDN.
     *
     * @param Media $media
     * @param bool $deleteLocal Whether to delete local file after successful upload
     * @return bool
     */
    public function uploadToCdn(Media $media, bool $deleteLocal = false): bool
    {
        try {
            if (!$this->isConfigured()) {
                Log::warning('CDN not configured, skipping upload', ['media_id' => $media->id]);
                return false;
            }

            $filePath = $this->getLocalPath($media->filename);

            if (!file_exists($filePath)) {
                Log::error('File not found for CDN upload', ['path' => $filePath]);
                return false;
            }

            $cdnPath = $this->generateCdnPath($media);

            switch ($this->provider) {
                case 's3':
                case 'cloudfront':
                    return $this->uploadToS3($media, $filePath, $cdnPath, $deleteLocal);

                case 'rackspace':
                    return $this->uploadToRackspace($media, $filePath, $cdnPath, $deleteLocal);

                default:
                    Log::error('Unsupported CDN provider', ['provider' => $this->provider]);
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('CDN upload failed', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Upload file to S3.
     *
     * @param Media $media
     * @param string $localPath
     * @param string $cdnPath
     * @param bool $deleteLocal
     * @return bool
     */
    protected function uploadToS3(Media $media, string $localPath, string $cdnPath, bool $deleteLocal): bool
    {
        $client = $this->getS3Client();
        $bucket = $this->config['bucket'] ?? '';

        if (empty($bucket)) {
            Log::error('S3 bucket not configured');
            return false;
        }

        // Determine content type
        $contentType = $this->getContentType($localPath);

        // Upload to S3
        $result = $client->putObject([
            'Bucket' => $bucket,
            'Key' => $cdnPath,
            'SourceFile' => $localPath,
            'ContentType' => $contentType,
            'ACL' => $this->config['acl'] ?? 'public-read',
            'Metadata' => [
                'media-id' => (string) $media->id,
                'original-filename' => basename($media->filename),
            ],
        ]);

        // Update media record
        $cdnUrl = $this->buildCdnUrl($cdnPath);
        $media->update([
            'cdn_url' => $cdnUrl,
            'cdn_provider' => $this->provider,
            'cdn_metadata' => [
                'bucket' => $bucket,
                'key' => $cdnPath,
                'etag' => $result['ETag'] ?? null,
                'version_id' => $result['VersionId'] ?? null,
                'uploaded_at' => now()->toIso8601String(),
            ],
            'is_synced_to_cdn' => true,
        ]);

        // Delete local file if requested
        if ($deleteLocal && file_exists($localPath)) {
            unlink($localPath);
        }

        Log::info('File uploaded to S3', [
            'media_id' => $media->id,
            'cdn_url' => $cdnUrl,
        ]);

        return true;
    }

    /**
     * Upload file to Rackspace Cloud Files.
     *
     * @param Media $media
     * @param string $localPath
     * @param string $cdnPath
     * @param bool $deleteLocal
     * @return bool
     */
    protected function uploadToRackspace(Media $media, string $localPath, string $cdnPath, bool $deleteLocal): bool
    {
        // Rackspace implementation
        // This would require the rackspace/php-opencloud package
        Log::warning('Rackspace CDN integration not yet implemented');
        return false;
    }

    /**
     * Delete media from CDN.
     *
     * @param Media $media
     * @return bool
     */
    public function deleteFromCdn(Media $media): bool
    {
        try {
            if (!$media->is_synced_to_cdn || empty($media->cdn_metadata)) {
                return true;
            }

            $metadata = $media->cdn_metadata;

            switch ($media->cdn_provider) {
                case 's3':
                case 'cloudfront':
                    return $this->deleteFromS3($metadata);

                case 'rackspace':
                    return $this->deleteFromRackspace($metadata);

                default:
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('CDN deletion failed', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Delete file from S3.
     *
     * @param array $metadata
     * @return bool
     */
    protected function deleteFromS3(array $metadata): bool
    {
        $client = $this->getS3Client();
        $bucket = $metadata['bucket'] ?? '';
        $key = $metadata['key'] ?? '';

        if (empty($bucket) || empty($key)) {
            return false;
        }

        $client->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key,
        ]);

        return true;
    }

    /**
     * Delete file from Rackspace.
     *
     * @param array $metadata
     * @return bool
     */
    protected function deleteFromRackspace(array $metadata): bool
    {
        // Rackspace implementation
        Log::warning('Rackspace CDN deletion not yet implemented');
        return false;
    }

    /**
     * Sync media to CDN.
     *
     * @param int $mediaId
     * @param bool $deleteLocal
     * @return array
     */
    public function syncMedia(int $mediaId, bool $deleteLocal = false): array
    {
        $media = Media::find($mediaId);

        if (!$media) {
            return [
                'success' => false,
                'error' => 'Media not found',
            ];
        }

        if ($media->is_synced_to_cdn) {
            return [
                'success' => true,
                'message' => 'Media already synced to CDN',
                'cdn_url' => $media->cdn_url,
            ];
        }

        $success = $this->uploadToCdn($media, $deleteLocal);

        if ($success) {
            return [
                'success' => true,
                'message' => 'Media synced to CDN successfully',
                'cdn_url' => $media->cdn_url,
            ];
        }

        return [
            'success' => false,
            'error' => 'Failed to sync media to CDN',
        ];
    }

    /**
     * Bulk sync media to CDN.
     *
     * @param array $mediaIds
     * @param bool $deleteLocal
     * @return array
     */
    public function bulkSync(array $mediaIds, bool $deleteLocal = false): array
    {
        $results = [
            'total' => count($mediaIds),
            'success' => [],
            'failed' => [],
        ];

        foreach ($mediaIds as $mediaId) {
            $result = $this->syncMedia($mediaId, $deleteLocal);

            if ($result['success']) {
                $results['success'][] = $mediaId;
            } else {
                $results['failed'][] = [
                    'id' => $mediaId,
                    'error' => $result['error'] ?? 'Unknown error',
                ];
            }
        }

        return $results;
    }

    /**
     * Get CDN URL for media.
     *
     * @param Media $media
     * @return string|null
     */
    public function getCdnUrl(Media $media): ?string
    {
        if ($media->is_synced_to_cdn && $media->cdn_url) {
            return $media->cdn_url;
        }

        return null;
    }

    /**
     * Check if CDN is configured.
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        if (!in_array($this->provider, $this->supportedProviders)) {
            return false;
        }

        switch ($this->provider) {
            case 's3':
            case 'cloudfront':
                return !empty($this->config['key']) &&
                       !empty($this->config['secret']) &&
                       !empty($this->config['bucket']);

            case 'rackspace':
                return !empty($this->config['username']) &&
                       !empty($this->config['key']) &&
                       !empty($this->config['container']);

            default:
                return false;
        }
    }

    /**
     * Get local file path from media filename.
     *
     * @param string $filename
     * @return string
     */
    protected function getLocalPath(string $filename): string
    {
        // Handle {SITE_URL} placeholder
        $filename = str_replace('{SITE_URL}', '', $filename);
        $filename = str_replace(site_url(), '', $filename);

        // Try different paths
        $paths = [
            public_path($filename),
            storage_path('app/public/' . $filename),
            media_uploads_path() . '/' . $filename,
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return $paths[0]; // Return first path even if not found
    }

    /**
     * Generate CDN path for media.
     *
     * @param Media $media
     * @return string
     */
    protected function generateCdnPath(Media $media): string
    {
        $folder = $media->folder ? $media->folder->slug : 'uploads';
        $datePath = date('Y/m');
        $filename = basename($media->filename);

        return "media/{$folder}/{$datePath}/{$filename}";
    }

    /**
     * Build CDN URL.
     *
     * @param string $cdnPath
     * @return string
     */
    protected function buildCdnUrl(string $cdnPath): string
    {
        if ($this->provider === 'cloudfront' && !empty($this->config['cloudfront_url'])) {
            return rtrim($this->config['cloudfront_url'], '/') . '/' . $cdnPath;
        }

        $bucket = $this->config['bucket'] ?? '';
        $region = $this->config['region'] ?? 'us-east-1';

        if ($region === 'us-east-1') {
            return "https://{$bucket}.s3.amazonaws.com/{$cdnPath}";
        }

        return "https://{$bucket}.s3.{$region}.amazonaws.com/{$cdnPath}";
    }

    /**
     * Get content type for file.
     *
     * @param string $filePath
     * @return string
     */
    protected function getContentType(string $filePath): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        return $mimeType ?: 'application/octet-stream';
    }

    /**
     * Invalidate CDN cache.
     *
     * @param array $paths
     * @return bool
     */
    public function invalidateCache(array $paths): bool
    {
        if ($this->provider === 'cloudfront' && !empty($this->config['cloudfront_distribution_id'])) {
            try {
                $cloudFront = new CloudFrontClient([
                    'version' => 'latest',
                    'region' => 'us-east-1',
                    'credentials' => [
                        'key' => $this->config['key'] ?? '',
                        'secret' => $this->config['secret'] ?? '',
                    ],
                ]);

                $cloudFront->createInvalidation([
                    'DistributionId' => $this->config['cloudfront_distribution_id'],
                    'InvalidationBatch' => [
                        'CallerReference' => uniqid(),
                        'Paths' => [
                            'Quantity' => count($paths),
                            'Items' => $paths,
                        ],
                    ],
                ]);

                return true;
            } catch (\Exception $e) {
                Log::error('CloudFront cache invalidation failed', [
                    'error' => $e->getMessage(),
                ]);
                return false;
            }
        }

        return false;
    }

    /**
     * Get CDN statistics.
     *
     * @return array
     */
    public function getStats(): array
    {
        $totalMedia = Media::count();
        $syncedMedia = Media::where('is_synced_to_cdn', true)->count();

        return [
            'provider' => $this->provider,
            'configured' => $this->isConfigured(),
            'total_media' => $totalMedia,
            'synced_media' => $syncedMedia,
            'pending_sync' => $totalMedia - $syncedMedia,
            'sync_percentage' => $totalMedia > 0 ? round(($syncedMedia / $totalMedia) * 100, 2) : 0,
        ];
    }
}
