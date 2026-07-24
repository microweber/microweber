<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaFolder;
use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class MediaStorageHealthTool extends AbstractMediaTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'media_storage_health',
            'Summarize media-library storage health, public disk usage, folder distribution, and optional WebP cache statistics.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'path', type: PropertyType::STRING, description: 'Optional public disk prefix to inspect. Default is uploads.', required: false),
            new ToolProperty(name: 'include_webp_cache', type: PropertyType::STRING, description: 'Optional WebP cache stats flag: yes or no. Default is yes.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum top folders/directories to summarize (1-20). Default is 5.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $path = $this->normalizeStoragePrefix($args['path'] ?? 'uploads', 'uploads');
        $includeWebpCache = $this->normalizeNullableBoolean($args['include_webp_cache'] ?? '') ?? true;
        $limit = $this->safeLimit($args['limit'] ?? 5, 5, 20);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view media.');
        }

        try {
            $assets = Media::query()
                ->with('folder')
                ->orderByDesc('created_at')
                ->get();

            $folderCount = MediaFolder::query()->count();
            $cdnSyncedCount = $assets->filter(fn (Media $media): bool => (bool) $media->is_synced_to_cdn)->count();
            $typeBreakdown = collect(['image', 'document', 'video', 'audio', 'file'])
                ->map(function (string $type) use ($assets): string {
                    $count = $assets->filter(fn (Media $media): bool => $this->mediaType($media) === $type)->count();

                    return ucfirst($type) . ': ' . $count;
                })
                ->implode(' | ');

            $disk = Storage::disk('public');
            $files = $disk->allFiles($path);
            $sizesByFile = [];

            foreach ($files as $file) {
                $sizesByFile[$file] = (int) $disk->size($file);
            }

            $summaryRow = [[
                'path' => $path,
                'media_assets' => (string) $assets->count(),
                'folders' => (string) $folderCount,
                'root_assets' => (string) $assets->whereNull('folder_id')->count(),
                'cdn_synced' => (string) $cdnSyncedCount,
                'database_size' => $this->formatFileSize($assets->sum(fn (Media $media): int => (int) ($media->file_size ?? 0))),
                'disk_files' => (string) count($files),
                'disk_size' => $this->formatFileSize(array_sum($sizesByFile)),
                'types' => $typeBreakdown,
            ]];

            if ($includeWebpCache) {
                $webpStats = app(ImageOptimizationService::class)->getStatistics();
                $summaryRow[0]['webp_cache'] = (int) ($webpStats['total_files'] ?? 0) . ' file(s), ' . (string) ($webpStats['total_size_human'] ?? '0 B');
            }

            $topFolderRows = $assets
                ->groupBy(fn (Media $media): string => (string) ($media->folder_id ?? 0))
                ->map(function ($items, string $folderId): array {
                    /** @var \Illuminate\Support\Collection<int, Media> $items */
                    $folder = $items->first()?->folder;

                    return [
                        'folder' => $folderId !== '0' ? $this->folderLabel($folder) : 'Root / unassigned',
                        'asset_count' => (string) $items->count(),
                        'total_size' => $this->formatFileSize($items->sum(fn (Media $media): int => (int) ($media->file_size ?? 0))),
                        'latest_upload' => (string) ($items->max('created_at') ?: 'Unknown'),
                    ];
                })
                ->sortByDesc(fn (array $row): int => (int) $row['asset_count'])
                ->take($limit)
                ->values()
                ->all();

            $directoryRows = collect($sizesByFile)
                ->map(function (int $size, string $file) use ($path): array {
                    $normalizedFile = ltrim(str_replace('\\', '/', $file), '/');
                    $relative = $normalizedFile;

                    if ($path !== '' && str_starts_with($normalizedFile, $path . '/')) {
                        $relative = substr($normalizedFile, strlen($path . '/'));
                    } elseif ($normalizedFile === $path) {
                        $relative = '';
                    }

                    $firstSegment = strtok($relative, '/');
                    $directory = $firstSegment !== false && $firstSegment !== '' ? $firstSegment : '(root)';

                    return [
                        'directory' => $directory,
                        'size' => $size,
                    ];
                })
                ->groupBy('directory')
                ->map(function ($items, string $directory): array {
                    return [
                        'directory' => $directory,
                        'file_count' => (string) $items->count(),
                        'total_size' => $this->formatFileSize($items->sum('size')),
                    ];
                })
                ->sortByDesc(fn (array $row): int => (int) $row['file_count'])
                ->take($limit)
                ->values()
                ->all();

            $output = '<h4>Media storage health</h4>' . $this->formatAsHtmlTable(
                $summaryRow,
                array_combine(array_keys($summaryRow[0]), array_map(static fn (string $key): string => Str::headline(str_replace('_', ' ', $key)), array_keys($summaryRow[0]))),
                'No media storage data is available.',
                'media-storage-health-summary'
            );

            if ($topFolderRows !== []) {
                $output .= '<h5 class="mt-3">Top media folders</h5>' . $this->formatAsHtmlTable(
                    $topFolderRows,
                    [
                        'folder' => 'Folder',
                        'asset_count' => 'Assets',
                        'total_size' => 'Total size',
                        'latest_upload' => 'Latest upload',
                    ],
                    'No media folders contain assets.',
                    'media-storage-health-folders'
                );
            }

            if ($directoryRows !== []) {
                $output .= '<h5 class="mt-3">Top public disk directories</h5>' . $this->formatAsHtmlTable(
                    $directoryRows,
                    [
                        'directory' => 'Directory',
                        'file_count' => 'Files',
                        'total_size' => 'Total size',
                    ],
                    'No public disk files were found for the requested path.',
                    'media-storage-health-directories'
                );
            }

            return $output;
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading media storage health: ' . $exception->getMessage());
        }
    }
}
