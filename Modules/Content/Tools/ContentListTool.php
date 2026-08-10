<?php

declare(strict_types=1);

namespace Modules\Content\Tools;

use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class ContentListTool extends AbstractContentTool
{
    protected string $domain = 'content';
    protected string $contentType = 'content';
    protected array $requiredPermissions = ['view content'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'content_list',
            'List and filter content items including pages, posts, products and other content types in Microweber CMS with advanced filtering by custom fields, categories, and content data.'
        );
    }

    protected function properties(): array
    {
        $baseProperties = $this->getBaseProperties();
        
        // Add content-specific properties
        $contentProperties = [
            new ToolProperty(
                name: 'content_type',
                type: PropertyType::STRING,
                description: 'Type of content to list. Options: "page", "post", "product", or "all" for all types.',
                required: false,
            ),
        ];
        
        return array_merge($contentProperties, $baseProperties);
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $content_type = $args['content_type'] ?? 'all';
        $search_term = $args['search_term'] ?? '';
        $is_active = $args['is_active'] ?? 'all';
        $parent_id = $args['parent_id'] ?? null;
        $category_id = $args['category_id'] ?? null;
        $custom_fields = $args['custom_fields'] ?? '';
        $limit = $args['limit'] ?? 20;
        $sort_by = $args['sort_by'] ?? 'position';

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to list content.');
        }

        // Validate limit
        $limit = max(1, min(100, $limit));

        try {
            // Build base query
            if ($content_type === 'all') {
                $query = \Modules\Content\Models\Content::query()->where('is_deleted', 0);
            } else {
                $this->contentType = $content_type;
                $query = $this->buildContentQuery();
            }

            // Apply filters
            $params = [
                'search_term' => $search_term,
                'is_active' => $is_active,
                'parent_id' => $parent_id,
                'category_id' => $category_id,
                'custom_fields' => $custom_fields,
                'sort_by' => $sort_by,
            ];

            $query = $this->applyFilters($query, $params);
            $content = $query->limit($limit)->get();

            if ($content->isEmpty()) {
                $typeInfo = $content_type !== 'all' ? " of type '{$content_type}'" : '';
                $statusInfo = $is_active !== 'all' ? " with status '" . ($is_active ? 'published' : 'unpublished') . "'" : '';
                $searchInfo = !empty($search_term) ? " matching '{$search_term}'" : '';
                
                return $this->formatAsHtmlTable(
                    [],
                    ['title' => 'Title', 'type' => 'Type', 'status' => 'Status'],
                    "No content found{$typeInfo}{$statusInfo}{$searchInfo}.",
                    'content-list-empty'
                );
            }

            return $this->formatContentAsHtml($content, $content_type, $params, $limit);

        } catch (\Exception $e) {
            return $this->handleError('Error listing content: ' . $e->getMessage());
        }
    }
}
