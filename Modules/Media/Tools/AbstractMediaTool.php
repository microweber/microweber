<?php

declare(strict_types=1);

namespace Modules\Media\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Illuminate\Support\Str;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaFolder;

abstract class AbstractMediaTool extends BaseTool
{
    protected string $domain = 'media';

    protected array $requiredPermissions = ['view media'];

    protected function safeLimit(mixed $limit, int $default = 10, int $max = 50): int
    {
        return max(1, min($max, (int) ($limit ?? $default)));
    }

    protected function normalizeNullableBoolean(mixed $value): ?bool
    {
        $normalized = strtolower(trim((string) $value));

        if ($normalized === '') {
            return null;
        }

        if (in_array($normalized, ['1', 'true', 'yes', 'y'], true)) {
            return true;
        }

        if (in_array($normalized, ['0', 'false', 'no', 'n'], true)) {
            return false;
        }

        return null;
    }

    protected function normalizeFileType(mixed $value): string
    {
        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['all', 'image', 'video', 'audio', 'document', 'file'], true)
            ? $normalized
            : 'all';
    }

    protected function formatFileSize(mixed $size): string
    {
        $bytes = (int) ($size ?? 0);

        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);

        return number_format($bytes / (1024 ** $power), $power === 0 ? 0 : 1) . ' ' . $units[$power];
    }

    protected function mediaPath(Media $media): string
    {
        $filename = trim((string) ($media->filename ?? ''));

        if ($filename === '') {
            return 'Unknown';
        }

        $path = parse_url($filename, PHP_URL_PATH);
        $path = is_string($path) && $path !== '' ? $path : $filename;
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#/+#', '/', $path) ?: $path;
        $path = ltrim($path, '/');

        return $path !== '' ? $path : 'Unknown';
    }

    protected function mediaBasename(Media $media): string
    {
        $path = $this->mediaPath($media);

        return $path === 'Unknown' ? 'unknown-file' : basename($path);
    }

    protected function mediaType(Media $media): string
    {
        $fileType = strtolower(trim((string) ($media->file_type ?? '')));

        if ($fileType !== '') {
            return $fileType;
        }

        $mediaType = strtolower(trim((string) ($media->media_type ?? '')));

        return match (true) {
            str_starts_with($mediaType, 'image/'), $mediaType === 'picture' => 'image',
            str_starts_with($mediaType, 'video/') => 'video',
            str_starts_with($mediaType, 'audio/') => 'audio',
            str_starts_with($mediaType, 'application/') => 'document',
            default => 'file',
        };
    }

    protected function mediaTypeLabel(Media $media): string
    {
        return match ($this->mediaType($media)) {
            'image' => 'Image',
            'video' => 'Video',
            'audio' => 'Audio',
            'document' => 'Document',
            default => 'File',
        };
    }

    protected function folderLabel(?MediaFolder $folder): string
    {
        return $folder?->full_path ?: 'Root';
    }

    protected function mediaTitle(Media $media): string
    {
        $title = trim((string) ($media->title ?? ''));

        if ($title === '') {
            $title = Str::headline(pathinfo($this->mediaBasename($media), PATHINFO_FILENAME));
        }

        return '#' . $media->id . ' ' . $title;
    }

    protected function relationSummary(Media $media): string
    {
        $relType = trim((string) ($media->rel_type ?? ''));
        $relId = trim((string) ($media->rel_id ?? ''));

        if ($relType === '' && $relId === '') {
            return 'Unassigned';
        }

        if ($relType === '') {
            return '#' . $relId;
        }

        return $relType . ($relId !== '' ? ' #' . $relId : '');
    }

    protected function metadataKeySummary(mixed $value): string
    {
        $decoded = $this->decodeMetadataArray($value);

        if ($decoded === []) {
            return 'No metadata';
        }

        $keys = array_values(array_filter(array_map(static fn ($key): string => (string) $key, array_keys($decoded))));

        if ($keys === []) {
            return 'Metadata present';
        }

        $summary = implode(', ', array_slice($keys, 0, 8));

        if (count($keys) > 8) {
            $summary .= ', +' . (count($keys) - 8) . ' more';
        }

        return $summary;
    }

    /**
     * @return array<string, mixed>
     */
    protected function decodeMetadataArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && trim($value) !== '') {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    protected function normalizeStoragePrefix(mixed $value, string $default = 'uploads'): string
    {
        $prefix = trim((string) $value);

        if ($prefix === '') {
            return $default;
        }

        $prefix = str_replace('\\', '/', $prefix);
        $prefix = preg_replace('#/+#', '/', $prefix) ?: $prefix;
        $prefix = trim($prefix, '/');

        $segments = array_values(array_filter(explode('/', $prefix), static fn (string $segment): bool => $segment !== '' && $segment !== '.'));

        if (in_array('..', $segments, true)) {
            return $default;
        }

        $normalized = implode('/', $segments);

        return $normalized !== '' ? $normalized : $default;
    }

    protected function yesNoLabel(bool $value): string
    {
        return $value ? 'Yes' : 'No';
    }
}
