<?php

declare(strict_types=1);

namespace Modules\Content\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Modules\Content\Models\Content;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class ContentSearchTool extends BaseTool
{
    protected string $domain = 'content';
    protected array $requiredPermissions = ['view content'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'content_search',
            'Search for content like pages, posts, blog articles, and other content types in Microweber CMS.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Search term to find in content titles, descriptions, or content body. Use keywords related to the content you are looking for.',
                required: true,
            ),
            new ToolProperty(
                name: 'content_type',
                type: PropertyType::STRING,
                description: 'Type of content to search for. Options: "page", "post", "product", "category", or "all" for all types.',
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
        $content_type = $args['content_type'] ?? 'all';
        $limit = $args['limit'] ?? 10;

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to search content.');
        }

        if (empty($search_term)) {
            return $this->handleError('Search term cannot be empty. Please provide a keyword to search for.');
        }

        // Validate limit
        $limit = max(1, min(50, $limit));

        try {
            $query = Content::query();

            // Search by title, description, or content
            $query->where(function ($q) use ($search_term) {
                $q->where('title', 'LIKE', '%' . $search_term . '%')
                  ->orWhere('description', 'LIKE', '%' . $search_term . '%')
                  ->orWhere('content', 'LIKE', '%' . $search_term . '%');
            });

            // Filter by content type if specified
            if ($content_type !== 'all') {
                $query->where('content_type', $content_type);
            }

            // Order by relevance (title matches first, then updated_at)
            $query->orderByRaw("CASE WHEN title LIKE '%{$search_term}%' THEN 1 ELSE 2 END")
                  ->orderBy('updated_at', 'desc');

            $content = $query->limit($limit)->get();

            if ($content->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    ['title' => 'Title', 'type' => 'Type', 'status' => 'Status'],
                    "No content found matching '{$search_term}'" . 
                    ($content_type !== 'all' ? " in {$content_type} content" : '') . ".",
                    'content-search-empty'
                );
            }

            return $this->formatContentAsHtml($content, $search_term, $content_type, $limit);

        } catch (\Exception $e) {
            return $this->handleError('Error searching content: ' . $e->getMessage());
        }
    }

    protected function formatContentAsHtml($content, string $search_term, string $content_type, int $limit): string
    {
        $totalFound = $content->count();
        
        $header = "
        <div class='content-search-header mb-3'>
            <h4><i class='fas fa-search text-primary me-2'></i>Content Search Results</h4>
            <p class='mb-2'>
                <strong>Search:</strong> \"{$search_term}\" 
                " . ($content_type !== 'all' ? "<strong>Type:</strong> {$content_type} " : '') . "
                <strong>Found:</strong> {$totalFound} result(s)" . 
                ($totalFound >= $limit ? " (showing first {$limit})" : '') . "
            </p>
        </div>";

        $cards = "<div class='row'>";
        
        foreach ($content as $item) {
            $contentType = ucfirst($item->content_type ?? 'Content');
            $status = $item->is_active ? 
                "<span class='badge bg-success'>Active</span>" : 
                "<span class='badge bg-secondary'>Inactive</span>";
            
            $description = $item->description ? 
                "<p class='card-text'>" . \Str::limit($item->description, 120) . "</p>" : '';

            $lastUpdated = $item->updated_at ? 
                "<small class='text-muted'>Updated: " . $item->updated_at->format('M j, Y') . "</small>" : '';

            $cards .= "
            <div class='col-md-6 col-lg-4 mb-3'>
                <div class='card h-100 content-card'>
                    <div class='card-header d-flex justify-content-between align-items-center'>
                        <span class='badge bg-primary'>{$contentType}</span>
                        {$status}
                    </div>
                    <div class='card-body'>
                        <h6 class='card-title'>{$item->title}</h6>
                        {$description}
                        {$lastUpdated}
                    </div>
                    <div class='card-footer'>
                        <small>ID: {$item->id}</small>
                    </div>
                </div>
            </div>";
        }
        
        $cards .= "</div>";

        return $header . $cards;
    }
}
