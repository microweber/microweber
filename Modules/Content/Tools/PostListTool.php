<?php

declare(strict_types=1);

namespace Modules\Content\Tools;

class PostListTool extends AbstractContentTool
{
    protected string $domain = 'content';
    protected string $contentType = 'post';
    protected array $requiredPermissions = ['view content'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'post_list',
            'List and filter blog posts in Microweber CMS with advanced filtering by custom fields, categories, tags, and publication status.'
        );
    }

    protected function properties(): array
    {
        return $this->getBaseProperties();
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $search_term = $args['search_term'] ?? '';
        $is_active = $args['is_active'] ?? 'all';
        $parent_id = $args['parent_id'] ?? null;
        $category_id = $args['category_id'] ?? null;
        $custom_fields = $args['custom_fields'] ?? '';
        $limit = $args['limit'] ?? 20;
        $sort_by = $args['sort_by'] ?? 'created_at';

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to list posts.');
        }

        // Validate limit
        $limit = max(1, min(100, $limit));

        try {
            $query = $this->buildContentQuery();

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
            $posts = $query->limit($limit)->get();

            if ($posts->isEmpty()) {
                $statusInfo = $is_active !== 'all' ? " with status '" . ($is_active ? 'published' : 'unpublished') . "'" : '';
                $searchInfo = !empty($search_term) ? " matching '{$search_term}'" : '';
                
                return $this->formatAsHtmlTable(
                    [],
                    ['title' => 'Title', 'status' => 'Status', 'created' => 'Created'],
                    "No posts found{$statusInfo}{$searchInfo}.",
                    'post-list-empty'
                );
            }

            return $this->formatContentAsHtml($posts, 'post', $params, $limit);

        } catch (\Exception $e) {
            return $this->handleError('Error listing posts: ' . $e->getMessage());
        }
    }
}
