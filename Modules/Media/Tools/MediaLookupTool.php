<?php

declare(strict_types=1);

namespace Modules\Media\Tools;

use Modules\Media\Models\Media;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class MediaLookupTool extends AbstractMediaTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'media_lookup',
            'Search uploaded media assets by title, filename, type, folder, or CDN sync status.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'media_id', type: PropertyType::INTEGER, description: 'Optional media ID for a direct lookup.', required: false),
            new ToolProperty(name: 'search_term', type: PropertyType::STRING, description: 'Optional search term for title, description, or filename.', required: false),
            new ToolProperty(name: 'file_type', type: PropertyType::STRING, description: 'Optional file type filter: image, document, video, audio, file, or all.', required: false),
            new ToolProperty(name: 'folder_id', type: PropertyType::INTEGER, description: 'Optional media folder ID filter.', required: false),
            new ToolProperty(name: 'is_synced_to_cdn', type: PropertyType::STRING, description: 'Optional CDN sync filter: yes or no.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum media rows to return (1-50). Default is 10.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $mediaId = isset($args['media_id']) ? (int) $args['media_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $fileType = $this->normalizeFileType($args['file_type'] ?? 'all');
        $folderId = isset($args['folder_id']) ? (int) $args['folder_id'] : null;
        $cdnSync = $this->normalizeNullableBoolean($args['is_synced_to_cdn'] ?? '');
        $limit = $this->safeLimit($args['limit'] ?? 10, 10, 50);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view media.');
        }

        try {
            $query = Media::query()->with('folder');

            if ($mediaId !== null && $mediaId > 0) {
                $query->where('id', $mediaId);
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('title', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%')
                        ->orWhere('filename', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($folderId !== null && $folderId > 0) {
                $query->where('folder_id', $folderId);
            }

            if ($cdnSync !== null) {
                $query->where('is_synced_to_cdn', $cdnSync);
            }

            $candidateLimit = $fileType === 'all' ? $limit : min($limit * 5, 200);

            $media = $query
                ->orderByDesc('created_at')
                ->limit($candidateLimit)
                ->get()
                ->filter(fn (Media $item): bool => $fileType === 'all' || $this->mediaType($item) === $fileType)
                ->take($limit)
                ->values();

            if ($media->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'asset' => 'Asset',
                        'type' => 'Type',
                        'path' => 'Path',
                    ],
                    'No media assets matched the requested filters.',
                    'media-lookup-empty'
                );
            }

            $summaryBits = [];
            if ($mediaId !== null && $mediaId > 0) {
                $summaryBits[] = 'Asset ID: #' . $mediaId;
            }
            if ($searchTerm !== '') {
                $summaryBits[] = 'Search: "' . e($searchTerm) . '"';
            }
            if ($fileType !== 'all') {
                $summaryBits[] = 'Type: ' . ucfirst($fileType);
            }
            if ($folderId !== null && $folderId > 0) {
                $summaryBits[] = 'Folder ID: #' . $folderId;
            }
            if ($cdnSync !== null) {
                $summaryBits[] = 'CDN synced: ' . $this->yesNoLabel($cdnSync);
            }

            $header = '<h4>Media lookup</h4><p>'
                . ($summaryBits !== [] ? implode(' | ', $summaryBits) . ' | ' : '')
                . '<strong>Found:</strong> ' . $media->count() . ' asset(s)</p>';

            $rows = $media->map(function (Media $item): array {
                return [
                    'asset' => $this->mediaTitle($item),
                    'type' => $this->mediaTypeLabel($item),
                    'path' => $this->mediaPath($item),
                    'folder' => $this->folderLabel($item->folder),
                    'relation' => $this->relationSummary($item),
                    'size' => $this->formatFileSize($item->file_size),
                    'cdn' => $this->yesNoLabel((bool) $item->is_synced_to_cdn),
                    'uploaded' => (string) ($item->created_at?->format('M j, Y H:i') ?: 'Unknown'),
                ];
            })->all();

            return $header . $this->formatAsHtmlTable(
                $rows,
                [
                    'asset' => 'Asset',
                    'type' => 'Type',
                    'path' => 'Path',
                    'folder' => 'Folder',
                    'relation' => 'Relation',
                    'size' => 'Size',
                    'cdn' => 'CDN synced',
                    'uploaded' => 'Uploaded',
                ],
                'No media assets matched the requested filters.',
                'media-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading media assets: ' . $exception->getMessage());
        }
    }
}
