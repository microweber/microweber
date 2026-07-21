<?php

namespace MicroweberPackages\CdnSync\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\CdnSync\Contracts\CdnSyncable;
use MicroweberPackages\CdnSync\Models\CdnSyncLog;

class CdnSyncService
{
    /**
     * Check if CDN sync is configured and enabled.
     */
    public function isConfigured(): bool
    {
        if (!config('cdn-sync.enabled', false)) {
            return false;
        }

        $key = $this->getConfigValue('key');
        $secret = $this->getConfigValue('secret');
        $bucket = $this->getConfigValue('bucket');

        return !empty($key) && !empty($secret) && !empty($bucket);
    }

    /**
     * Get a config value, checking options table first (for Microweber),
     * then falling back to config/env.
     */
    public function getConfigValue(string $key): string
    {
        // Try Microweber option if function exists
        if (function_exists('get_option')) {
            $optionValue = get_option('cdn_sync_' . $key, 'cdn_sync');
            if (!empty($optionValue)) {
                return (string) $optionValue;
            }
        }

        return (string) config('cdn-sync.' . $key, '');
    }

    /**
     * Get the configured S3 disk, creating it dynamically if needed.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function getDisk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        $diskName = config('cdn-sync.disk', 'cdn');

        // If the disk is already configured in filesystems.php, use it
        if (config("filesystems.disks.{$diskName}")) {
            return Storage::disk($diskName);
        }

        // Otherwise, configure it dynamically from our settings
        $endpoint = $this->getConfigValue('endpoint');
        $diskConfig = [
            'driver' => 's3',
            'key' => $this->getConfigValue('key'),
            'secret' => $this->getConfigValue('secret'),
            'region' => $this->getConfigValue('region') ?: 'us-east-1',
            'bucket' => $this->getConfigValue('bucket'),
            'url' => $this->getConfigValue('url') ?: null,
            'use_path_style_endpoint' => (bool) ($this->getConfigValue('use_path_style_endpoint') ?: config('cdn-sync.use_path_style_endpoint', false)),
        ];

        if (!empty($endpoint)) {
            $diskConfig['endpoint'] = $endpoint;
        }

        config(["filesystems.disks.{$diskName}" => $diskConfig]);

        return Storage::disk($diskName);
    }

    /**
     * Sync a CdnSyncable model's files to CDN.
     *
     * @return array{success: bool, synced: array<string>, failed: array<string>, errors: array<string>}
     */
    public function sync(CdnSyncable $model): array
    {
        $results = [
            'success' => true,
            'synced' => [],
            'failed' => [],
            'errors' => [],
        ];

        if (!$this->isConfigured()) {
            $results['success'] = false;
            $results['errors'][] = 'CDN sync is not configured.';
            return $results;
        }

        $files = $model->getCdnSyncFiles();

        if (empty($files)) {
            $results['errors'][] = 'No files to sync.';
            return $results;
        }

        foreach ($files as $localPath) {
            try {
                $result = $this->syncFile($model, $localPath);
                if ($result) {
                    $results['synced'][] = $localPath;
                } else {
                    $results['failed'][] = $localPath;
                    $results['success'] = false;
                }
            } catch (\Throwable $e) {
                $results['failed'][] = $localPath;
                $results['errors'][] = $e->getMessage();
                $results['success'] = false;
                Log::error('CDN sync failed', [
                    'rel_type' => $model->getCdnRelType(),
                    'rel_id' => $model->getCdnRelId(),
                    'file' => $localPath,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    /**
     * Sync a single file for a model.
     */
    public function syncFile(CdnSyncable $model, string $localPath): bool
    {
        $absolutePath = $this->resolveLocalPath($localPath);

        if (!file_exists($absolutePath)) {
            Log::warning('CDN sync: local file not found', ['path' => $absolutePath]);
            return false;
        }

        $relType = $model->getCdnRelType();
        $relId = $model->getCdnRelId();

        // Check if already synced
        $existing = CdnSyncLog::where('rel_type', $relType)
            ->where('rel_id', $relId)
            ->where('local_path', $localPath)
            ->where('is_synced', true)
            ->first();

        if ($existing) {
            // Check if file has changed by hash
            $currentHash = md5_file($absolutePath);
            if ($existing->file_hash === $currentHash) {
                return true; // Already synced and unchanged
            }
        }

        $cdnPath = $this->generateCdnPath($relType, $localPath);
        $disk = $this->getDisk();
        $bucket = $this->getConfigValue('bucket');

        // Read file contents and upload
        $contents = file_get_contents($absolutePath);
        $contentType = $this->getContentType($absolutePath);

        $disk->put($cdnPath, $contents, [
            'visibility' => 'public',
            'ContentType' => $contentType,
        ]);

        // Build CDN URL
        $cdnUrl = $this->buildCdnUrl($cdnPath);

        // Create or update sync log
        CdnSyncLog::updateOrCreate(
            [
                'rel_type' => $relType,
                'rel_id' => $relId,
                'local_path' => $localPath,
            ],
            [
                'cdn_path' => $cdnPath,
                'cdn_url' => $cdnUrl,
                'disk' => config('cdn-sync.disk', 'cdn'),
                'bucket' => $bucket,
                'content_type' => $contentType,
                'file_size' => filesize($absolutePath),
                'file_hash' => md5_file($absolutePath),
                'is_synced' => true,
                'synced_at' => now(),
            ]
        );

        // Delete local if configured
        if (config('cdn-sync.delete_local', false)) {
            @unlink($absolutePath);
        }

        Log::info('CDN sync: file uploaded', [
            'rel_type' => $relType,
            'rel_id' => $relId,
            'cdn_url' => $cdnUrl,
        ]);

        return true;
    }

    /**
     * Delete a model's synced files from CDN.
     */
    public function delete(CdnSyncable $model): bool
    {
        $relType = $model->getCdnRelType();
        $relId = $model->getCdnRelId();

        $logs = CdnSyncLog::where('rel_type', $relType)
            ->where('rel_id', $relId)
            ->where('is_synced', true)
            ->get();

        if ($logs->isEmpty()) {
            return true;
        }

        try {
            $disk = $this->getDisk();

            foreach ($logs as $log) {
                try {
                    $disk->delete($log->cdn_path);
                } catch (\Throwable $e) {
                    Log::warning('CDN sync: failed to delete remote file', [
                        'cdn_path' => $log->cdn_path,
                        'error' => $e->getMessage(),
                    ]);
                }

                $log->update([
                    'is_synced' => false,
                    'synced_at' => null,
                ]);
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('CDN sync: delete failed', [
                'rel_type' => $relType,
                'rel_id' => $relId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Bulk sync multiple models.
     *
     * @param iterable<CdnSyncable> $models
     * @return array{total: int, success: int, failed: int, errors: array<string>}
     */
    public function bulkSync(iterable $models): array
    {
        $results = [
            'total' => 0,
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($models as $model) {
            $results['total']++;
            $result = $this->sync($model);

            if ($result['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
                $results['errors'] = array_merge($results['errors'], $result['errors']);
            }
        }

        return $results;
    }

    /**
     * Get sync statistics.
     *
     * @return array{total_synced: int, configured: bool, by_type: array<string, array{synced: int|mixed}>}
     */
    public function getStats(): array
    {
        $synced = CdnSyncLog::where('is_synced', true)->count();

        $byType = CdnSyncLog::where('is_synced', true)
            ->selectRaw('rel_type, count(*) as synced_count')
            ->groupBy('rel_type')
            ->pluck('synced_count', 'rel_type')
            ->toArray();

        $typeStats = [];
        foreach ($byType as $type => $count) {
            $typeStats[$type] = ['synced' => $count];
        }

        return [
            'total_synced' => $synced,
            'configured' => $this->isConfigured(),
            'by_type' => $typeStats,
        ];
    }

    /**
     * Test the connection to the CDN/S3 endpoint.
     *
     * @return array{success: bool, message: string}
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'CDN sync is not configured. Please provide key, secret, and bucket.',
            ];
        }

        try {
            $disk = $this->getDisk();

            // Try to list files (limited) to verify connectivity
            $testKey = '.cdn-sync-test-' . uniqid();
            $disk->put($testKey, 'connection-test');
            $exists = $disk->exists($testKey);
            $disk->delete($testKey);

            if ($exists) {
                return [
                    'success' => true,
                    'message' => 'Connection successful. Bucket is accessible and writable.',
                ];
            }

            return [
                'success' => false,
                'message' => 'Connection test file could not be verified.',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Resolve a potentially relative local path to absolute.
     */
    protected function resolveLocalPath(string $path): string
    {
        // Handle {SITE_URL} placeholder (Microweber convention)
        $path = str_replace('{SITE_URL}', '', $path);

        if (function_exists('site_url')) {
            $path = str_replace(site_url(), '', $path);
        }

        // If already absolute, use as-is
        if (str_starts_with($path, '/')) {
            return $path;
        }

        // Try common locations
        $candidates = [
            public_path($path),
            storage_path('app/public/' . $path),
            base_path($path),
        ];

        if (function_exists('media_uploads_path')) {
            $candidates[] = media_uploads_path() . '/' . $path;
        }

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return public_path($path);
    }

    /**
     * Generate the CDN path for a file.
     */
    protected function generateCdnPath(string $relType, string $localPath): string
    {
        $prefix = config('cdn-sync.path_prefix', 'cdn-sync');
        $typeSlug = str_replace(['\\', '::'], '/', strtolower(class_basename($relType)));
        $datePath = date('Y/m');
        $filename = basename($localPath);

        return "{$prefix}/{$typeSlug}/{$datePath}/{$filename}";
    }

    /**
     * Build the public CDN URL for a file.
     */
    protected function buildCdnUrl(string $cdnPath): string
    {
        $cdnBaseUrl = $this->getConfigValue('cdn_url');

        if (!empty($cdnBaseUrl)) {
            return rtrim($cdnBaseUrl, '/') . '/' . $cdnPath;
        }

        $endpoint = $this->getConfigValue('endpoint');
        $bucket = $this->getConfigValue('bucket');
        $usePathStyle = (bool) ($this->getConfigValue('use_path_style_endpoint') ?: config('cdn-sync.use_path_style_endpoint', false));

        // For Minio / path-style endpoints
        if (!empty($endpoint) && $usePathStyle) {
            return rtrim($endpoint, '/') . '/' . $bucket . '/' . $cdnPath;
        }

        // For Minio / non-path-style with custom endpoint
        if (!empty($endpoint)) {
            $parsed = parse_url($endpoint);
            $scheme = $parsed['scheme'] ?? 'https';
            $host = $parsed['host'] ?? $endpoint;
            $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
            return "{$scheme}://{$bucket}.{$host}{$port}/{$cdnPath}";
        }

        // Standard AWS S3
        $region = $this->getConfigValue('region') ?: 'us-east-1';

        if ($region === 'us-east-1') {
            return "https://{$bucket}.s3.amazonaws.com/{$cdnPath}";
        }

        return "https://{$bucket}.s3.{$region}.amazonaws.com/{$cdnPath}";
    }

    /**
     * Get the MIME content type for a file.
     */
    protected function getContentType(string $filePath): string
    {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $type = finfo_file($finfo, $filePath);
                finfo_close($finfo);
                if ($type !== false) {
                    return $type;
                }
            }
        }

        // Fallback based on extension
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $map = [
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
            'gif' => 'image/gif', 'webp' => 'image/webp', 'svg' => 'image/svg+xml',
            'mp4' => 'video/mp4', 'webm' => 'video/webm', 'pdf' => 'application/pdf',
            'css' => 'text/css', 'js' => 'application/javascript',
        ];

        return $map[$ext] ?? 'application/octet-stream';
    }
}