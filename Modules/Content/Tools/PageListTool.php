<?php

declare(strict_types=1);

namespace Modules\Content\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Modules\Page\Models\Page;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class PageListTool extends BaseTool
{
    protected string $domain = 'content';
    protected array $requiredPermissions = ['view content'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'page_list',
            'List website pages with hierarchy and filtering options. Specialized tool for page content type.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'is_active',
                type: PropertyType::STRING,
                description: 'Filter by publication status. Options: "1" for published, "0" for unpublished, or "all" for both.',
                required: false,
            ),
            new ToolProperty(
                name: 'parent_id',
                type: PropertyType::INTEGER,
                description: 'Filter by parent page ID. Use 0 for top-level pages, or specific ID for children.',
                required: false,
            ),
            new ToolProperty(
                name: 'show_hierarchy',
                type: PropertyType::STRING,
                description: 'Show page hierarchy structure. Options: "yes" or "no". Default is "yes".',
                required: false,
            ),
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Search term to find in page title, content, or URL.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of results to return (1-100). Default is 30.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $is_active = $args['is_active'] ?? 'all';
        $parent_id = $args['parent_id'] ?? null;
        $show_hierarchy = $args['show_hierarchy'] ?? 'yes';
        $search_term = $args['search_term'] ?? '';
        $limit = $args['limit'] ?? 30;

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to list pages.');
        }

        // Validate limit
        $limit = max(1, min(100, $limit));

        try {
            $query = Page::query()
                ->where('is_deleted', 0);

            // Filter by active status
            if ($is_active !== 'all') {
                $query->where('is_active', (int)$is_active);
            }

            // Filter by parent ID
            if ($parent_id !== null) {
                $query->where('parent', $parent_id);
            }

            // Search in content
            if (!empty($search_term)) {
                $query->where(function ($q) use ($search_term) {
                    $q->where('title', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('url', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('description', 'LIKE', '%' . $search_term . '%');
                });
            }

            // Order by position and hierarchy
            $query->orderBy('position', 'asc')->orderBy('title', 'asc');

            $pages = $query->limit($limit)->get();

            if ($pages->isEmpty()) {
                $statusInfo = $is_active !== 'all' ? " with status '" . ($is_active ? 'published' : 'unpublished') . "'" : '';
                $searchInfo = !empty($search_term) ? " matching '{$search_term}'" : '';
                $parentInfo = $parent_id !== null ? " under parent ID {$parent_id}" : '';
                
                return $this->formatAsHtmlTable(
                    [],
                    ['title' => 'Title', 'status' => 'Status', 'url' => 'URL'],
                    "No pages found{$statusInfo}{$searchInfo}{$parentInfo}.",
                    'page-list-empty'
                );
            }

            return $this->formatPagesAsHtml($pages, $is_active, $show_hierarchy === 'yes', $limit);

        } catch (\Exception $e) {
            return $this->handleError('Error listing pages: ' . $e->getMessage());
        }
    }

    protected function formatPagesAsHtml($pages, string $is_active, bool $showHierarchy, int $limit): string
    {
        $totalFound = $pages->count();
        
        $statusInfo = $is_active !== 'all' ? "Status: " . ($is_active ? 'Published' : 'Unpublished') . " " : '';
        
        $header = "
        <div class='page-list-header mb-3'>
            <h4><i class='fas fa-file text-primary me-2'></i>Website Pages</h4>
            <p class='mb-2'>
                {$statusInfo}
                <strong>Found:</strong> {$totalFound} page(s)" . 
                ($totalFound >= $limit ? " (showing first {$limit})" : '') . "
            </p>
        </div>";

        if ($showHierarchy) {
            return $header . $this->formatHierarchicalPages($pages);
        } else {
            return $header . $this->formatFlatPageList($pages);
        }
    }

    protected function formatHierarchicalPages($pages): string
    {
        $pagesByParent = $pages->groupBy('parent');
        $topLevelPages = $pagesByParent->get(0, collect());
        
        $html = "<div class='page-hierarchy'>";
        
        foreach ($topLevelPages as $page) {
            $html .= $this->renderPageNode($page, $pagesByParent, 0);
        }
        
        // Handle orphaned pages (pages with parent not in result set)
        foreach ($pagesByParent as $parentId => $childPages) {
            if ($parentId != 0 && !$topLevelPages->contains('id', $parentId)) {
                foreach ($childPages as $page) {
                    $html .= $this->renderPageNode($page, $pagesByParent, 0, true);
                }
            }
        }
        
        $html .= "</div>";
        
        return $html;
    }

    protected function renderPageNode($page, $pagesByParent, int $level, bool $isOrphaned = false): string
    {
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
        $indentClass = $level > 0 ? " style='margin-left: " . ($level * 20) . "px;'" : '';
        
        $statusBadge = $page->is_active ? 
            "<span class='badge bg-success badge-sm'>Published</span>" : 
            "<span class='badge bg-warning badge-sm'>Unpublished</span>";

        $specialBadges = [];
        if ($page->is_home) {
            $specialBadges[] = "<span class='badge bg-danger badge-sm'>Homepage</span>";
        }
        if ($page->is_shop) {
            $specialBadges[] = "<span class='badge bg-info badge-sm'>Shop</span>";
        }

        $badges = $statusBadge . ($specialBadges ? ' ' . implode(' ', $specialBadges) : '');
        
        $orphanedLabel = $isOrphaned ? " <small class='text-warning'>(orphaned)</small>" : '';
        
        $url = $page->url ? 
            "<small class='text-muted'>/{$page->url}</small>" : 
            '<small class="text-muted">No URL</small>';

        $html = "
        <div class='page-node mb-2 p-2 border-start'{$indentClass}>
            <div class='d-flex justify-content-between align-items-center'>
                <div>
                    {$indent}<strong>{$page->title}</strong>{$orphanedLabel}<br>
                    {$indent}{$url}
                </div>
                <div class='text-end'>
                    {$badges}<br>
                    <small class='text-muted'>ID: {$page->id}</small>
                </div>
            </div>
        </div>";

        // Add children
        $children = $pagesByParent->get($page->id, collect());
        foreach ($children as $child) {
            $html .= $this->renderPageNode($child, $pagesByParent, $level + 1);
        }

        return $html;
    }

    protected function formatFlatPageList($pages): string
    {
        $tableData = [];
        
        foreach ($pages as $page) {
            $statusBadge = $page->is_active ? 
                "<span class='badge bg-success'>Published</span>" : 
                "<span class='badge bg-warning'>Unpublished</span>";

            $specialBadges = [];
            if ($page->is_home) {
                $specialBadges[] = "<span class='badge bg-danger'>Homepage</span>";
            }
            if ($page->is_shop) {
                $specialBadges[] = "<span class='badge bg-info'>Shop</span>";
            }

            $badges = $statusBadge . ($specialBadges ? '<br>' . implode(' ', $specialBadges) : '');
            
            $url = $page->url ? 
                "<code>/{$page->url}</code>" : 
                '<em class="text-muted">No URL</em>';

            $parent = '';
            if ($page->parent) {
                try {
                    $parentPage = \Modules\Content\Models\Content::find($page->parent);
                    $parent = $parentPage ? $parentPage->title : "ID: {$page->parent}";
                } catch (\Exception $e) {
                    $parent = "ID: {$page->parent}";
                }
            } else {
                $parent = '<em class="text-muted">Top level</em>';
            }

            $tableData[] = [
                'id' => "<strong>#{$page->id}</strong>",
                'title' => "<strong>{$page->title}</strong>",
                'url' => $url,
                'status' => $badges,
                'parent' => $parent,
                'position' => $page->position ?: 0,
            ];
        }

        return $this->formatAsHtmlTable(
            $tableData,
            [
                'id' => 'ID',
                'title' => 'Title',
                'url' => 'URL',
                'status' => 'Status',
                'parent' => 'Parent',
                'position' => 'Position',
            ],
            '',
            'page-list-results'
        );
    }
}
