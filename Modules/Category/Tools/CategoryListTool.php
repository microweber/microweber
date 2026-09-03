<?php

declare(strict_types=1);

namespace Modules\Category\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Category\Models\Category;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the site's content categories.
 *
 * Exposes the Category module over MCP — lists categories (title, url, parent,
 * active state), optionally filtered by a search term or parent. Read-only.
 */
class CategoryListTool extends BaseTool
{
    protected string $domain = 'category';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'category_list',
            'List the site content categories (title, url, parent, active state). '
            . 'Optionally filter by a search term or a parent category id.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional term to match against category title or url.',
                required: false,
            ),
            new ToolProperty(
                name: 'parent_id',
                type: PropertyType::INTEGER,
                description: 'Optional parent category id to list children of. Use 0 for top-level.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of categories to return (1-100). Default 30.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $term = trim((string) ($args['search_term'] ?? ''));
            $limit = (int) ($args['limit'] ?? 30);
            if ($limit < 1 || $limit > 100) {
                $limit = 30;
            }

            $query = Category::query()
                ->where('is_deleted', 0)
                ->when($term !== '', function ($q) use ($term) {
                    $q->where(function ($w) use ($term) {
                        $w->where('title', 'like', "%{$term}%")
                            ->orWhere('url', 'like', "%{$term}%");
                    });
                })
                ->when(array_key_exists('parent_id', $args) && $args['parent_id'] !== null && $args['parent_id'] !== '',
                    fn ($q) => $q->where('parent_id', (int) $args['parent_id']))
                ->orderBy('position')
                ->limit($limit);

            $rows = $query->get(['id', 'title', 'url', 'parent_id', 'rel_type', 'rel_id', 'is_active'])
                ->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'title' => $c->title,
                        'url' => $c->url,
                        'parent_id' => (int) $c->parent_id,
                        'active' => (int) $c->is_active === 1,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'categories' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read categories: ' . $e->getMessage());
        }
    }
}
