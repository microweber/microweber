<?php

declare(strict_types=1);

namespace Modules\Tag\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Tag\Models\Tag;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the site's content tags.
 *
 * Exposes the Tag module over MCP — lists tags (name, slug, usage count),
 * optionally filtered by a search term. Read-only.
 */
class TagListTool extends BaseTool
{
    protected string $domain = 'tag';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'tag_list',
            'List the site content tags (name, slug, usage count). Optionally filter '
            . 'by a search term matching the tag name or slug.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional term to match against the tag name or slug.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of tags to return (1-200). Default 50.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $term = trim((string) ($args['search_term'] ?? ''));
            $limit = (int) ($args['limit'] ?? 50);
            if ($limit < 1 || $limit > 200) {
                $limit = 50;
            }

            $rows = Tag::query()
                ->when($term !== '', function ($q) use ($term) {
                    $q->where(function ($w) use ($term) {
                        $w->where('name', 'like', "%{$term}%")
                            ->orWhere('slug', 'like', "%{$term}%");
                    });
                })
                ->orderByDesc('count')
                ->limit($limit)
                ->get(['id', 'name', 'slug', 'count'])
                ->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'name' => $t->name,
                        'slug' => $t->slug,
                        'used' => (int) $t->count,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'tags' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read tags: ' . $e->getMessage());
        }
    }
}
