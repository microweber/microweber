<?php

declare(strict_types=1);

namespace Modules\Media\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use MicroweberPackages\Media\Models\Media;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class MediaSearchTool extends BaseTool
{
    protected string $domain = 'media';
    protected array $requiredPermissions = ['view media'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'media_search',
            'Search for media files, images, documents, and other uploaded files in Microweber CMS.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Search term to find in media file names, titles, or descriptions. Use keywords related to the media you are looking for.',
                required: false,
            ),
            new ToolProperty(
                name: 'media_type',
                type: PropertyType::STRING,
                description: 'Type of media to search for. Options: "image", "document", "video", "audio", or "all" for all types.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of results to return (1-50). Default is 10.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $search_term = $args['search_term'] ?? '';
        $media_type = $args['media_type'] ?? 'all';
        $limit = $args['limit'] ?? 10;

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to search media.');
        }

        // Validate limit
        $limit = max(1, min(50, $limit));

        try {
            $query = Media::query();

            // Search by filename or title if search term provided
            if (!empty($search_term)) {
                $query->where(function ($q) use ($search_term) {
                    $q->where('filename', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('title', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('description', 'LIKE', '%' . $search_term . '%');
                });
            }

            // Filter by media type if specified
            if ($media_type !== 'all') {
                switch ($media_type) {
                    case 'image':
                        $query->where('media_type', 'LIKE', 'image/%');
                        break;
                    case 'document':
                        $query->whereIn('media_type', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
                        break;
                    case 'video':
                        $query->where('media_type', 'LIKE', 'video/%');
                        break;
                    case 'audio':
                        $query->where('media_type', 'LIKE', 'audio/%');
                        break;
                }
            }

            // Order by upload date (newest first)
            $query->orderBy('created_at', 'desc');

            $media = $query->limit($limit)->get();

            if ($media->isEmpty()) {
                $searchInfo = !empty($search_term) ? " matching '{$search_term}'" : '';
                $typeInfo = $media_type !== 'all' ? " of type '{$media_type}'" : '';
                
                return $this->formatAsHtmlTable(
                    [],
                    ['filename' => 'File Name', 'type' => 'Type', 'size' => 'Size'],
                    "No media files found{$searchInfo}{$typeInfo}.",
                    'media-search-empty'
                );
            }

            return $this->formatMediaAsHtml($media, $search_term, $media_type, $limit);

        } catch (\Exception $e) {
            return $this->handleError('Error searching media: ' . $e->getMessage());
        }
    }

    protected function formatMediaAsHtml($media, string $search_term, string $media_type, int $limit): string
    {
        $totalFound = $media->count();
        
        $searchInfo = !empty($search_term) ? "Search: \"{$search_term}\" " : '';
        $typeInfo = $media_type !== 'all' ? "Type: {$media_type} " : '';
        
        $header = "
        <div class='media-search-header mb-3'>
            <h4><i class='fas fa-images text-primary me-2'></i>Media Search Results</h4>
            <p class='mb-2'>
                {$searchInfo}{$typeInfo}
                <strong>Found:</strong> {$totalFound} file(s)" . 
                ($totalFound >= $limit ? " (showing first {$limit})" : '') . "
            </p>
        </div>";

        $cards = "<div class='row'>";
        
        foreach ($media as $item) {
            $fileExtension = pathinfo($item->filename, PATHINFO_EXTENSION);
            $fileSize = $this->formatFileSize($item->file_size ?? 0);
            $mediaType = $this->getMediaTypeLabel($item->media_type ?? '');
            
            $isImage = strpos($item->media_type ?? '', 'image/') === 0;
            $thumbnail = $isImage ? 
                "<img src='{$item->filename}' class='card-img-top' style='height: 120px; object-fit: cover;' alt='{$item->title}'>" :
                "<div class='card-img-top d-flex align-items-center justify-content-center bg-light' style='height: 120px;'>
                    <i class='fas fa-file fa-3x text-muted'></i>
                </div>";

            $title = $item->title ?: pathinfo($item->filename, PATHINFO_FILENAME);
            $description = $item->description ? 
                "<p class='card-text small'>" . \Str::limit($item->description, 80) . "</p>" : '';

            $uploadDate = $item->created_at ? 
                "<small class='text-muted'>Uploaded: " . $item->created_at->format('M j, Y') . "</small>" : '';

            $cards .= "
            <div class='col-md-6 col-lg-3 mb-3'>
                <div class='card h-100 media-card'>
                    {$thumbnail}
                    <div class='card-body p-2'>
                        <h6 class='card-title small'>{$title}</h6>
                        {$description}
                        <div class='d-flex justify-content-between align-items-center'>
                            <span class='badge bg-info'>{$mediaType}</span>
                            <small class='text-muted'>{$fileSize}</small>
                        </div>
                        {$uploadDate}
                    </div>
                    <div class='card-footer p-2'>
                        <small class='text-muted'>{$item->filename}</small>
                    </div>
                </div>
            </div>";
        }
        
        $cards .= "</div>";

        return $header . $cards;
    }

    protected function formatFileSize(int $size): string
    {
        if ($size === 0) return 'Unknown';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = floor(log($size, 1024));
        $power = min($power, count($units) - 1);
        
        return number_format($size / (1024 ** $power), 1) . ' ' . $units[$power];
    }

    protected function getMediaTypeLabel(string $mediaType): string
    {
        if (strpos($mediaType, 'image/') === 0) return 'Image';
        if (strpos($mediaType, 'video/') === 0) return 'Video';
        if (strpos($mediaType, 'audio/') === 0) return 'Audio';
        if (strpos($mediaType, 'application/pdf') === 0) return 'PDF';
        if (strpos($mediaType, 'application/') === 0) return 'Document';
        
        return 'File';
    }
}
