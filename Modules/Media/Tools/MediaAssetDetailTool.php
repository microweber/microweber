<?php

declare(strict_types=1);

namespace Modules\Media\Tools;

use Illuminate\Support\Str;
use Modules\Media\Models\Media;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class MediaAssetDetailTool extends AbstractMediaTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'media_asset_detail',
            'Inspect a single uploaded media asset with safe path, folder, relation, and metadata summaries.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'media_id', type: PropertyType::INTEGER, description: 'The media ID to inspect.', required: true),
            new ToolProperty(name: 'include_metadata', type: PropertyType::STRING, description: 'Optional metadata summary flag: yes or no.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $mediaId = isset($args['media_id']) ? (int) $args['media_id'] : 0;
        $includeMetadata = $this->normalizeNullableBoolean($args['include_metadata'] ?? '') ?? false;

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view media.');
        }

        if ($mediaId <= 0) {
            return $this->handleError('A valid media_id is required.');
        }

        try {
            $media = Media::query()
                ->with('folder')
                ->find($mediaId);

            if (! $media instanceof Media) {
                return $this->handleError('Media asset #' . $mediaId . ' was not found.');
            }

            $rows = [[
                'asset' => $this->mediaTitle($media),
                'path' => $this->mediaPath($media),
                'file_name' => $this->mediaBasename($media),
                'type' => $this->mediaTypeLabel($media),
                'folder' => $this->folderLabel($media->folder),
                'relation' => $this->relationSummary($media),
                'size' => $this->formatFileSize($media->file_size),
                'cdn_synced' => $this->yesNoLabel((bool) $media->is_synced_to_cdn),
                'cdn_provider' => (string) ($media->cdn_provider ?: 'Not configured'),
                'description' => trim((string) ($media->description ?? '')) !== '' ? trim((string) $media->description) : 'No description',
                'created_by' => $media->created_by ? 'User #' . $media->created_by : 'Unknown',
                'uploaded' => (string) ($media->created_at?->format('M j, Y H:i') ?: 'Unknown'),
                'updated' => (string) ($media->updated_at?->format('M j, Y H:i') ?: 'Unknown'),
            ]];

            if ($includeMetadata) {
                $rows[0]['metadata_keys'] = $this->metadataKeySummary($media->metadata);
                $rows[0]['cdn_metadata_keys'] = $this->metadataKeySummary($media->cdn_metadata);
                $rows[0]['file_hash'] = trim((string) ($media->file_hash ?? '')) !== '' ? 'Stored' : 'Not stored';
            }

            return '<h4>Media asset detail</h4>' . $this->formatAsHtmlTable(
                $rows,
                array_combine(array_keys($rows[0]), array_map(static fn (string $key): string => Str::headline(str_replace('_', ' ', $key)), array_keys($rows[0]))),
                'Media asset not found.',
                'media-asset-detail-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading media asset detail: ' . $exception->getMessage());
        }
    }
}
