<?php

declare(strict_types=1);

namespace Modules\Menu\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Menu\Models\Menu;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the site's navigation menus and their items.
 *
 * Exposes the Menu module over MCP — lists each menu group (e.g. header_menu,
 * footer_menu) and the items under it (title, what it links to, order), so an
 * MCP client / agent can inspect the site navigation. Read-only.
 */
class MenuListTool extends BaseTool
{
    protected string $domain = 'menu';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'menu_list',
            'List the site navigation menus and their items (menu group, item title, '
            . 'what each item links to, and order). Optionally filter to one menu group.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'menu',
                type: PropertyType::STRING,
                description: 'Optional menu group title to filter by, e.g. "header_menu" '
                    . 'or "footer_menu". Omit to list all menus.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of items per menu (1-100). Default 50.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $filter = trim((string) ($args['menu'] ?? ''));
            $limit = (int) ($args['limit'] ?? 50);
            if ($limit < 1 || $limit > 100) {
                $limit = 50;
            }

            $groups = Menu::whereNull('parent_id')
                ->when($filter !== '', fn ($q) => $q->where('title', $filter))
                ->orderBy('position')
                ->get(['id', 'title']);

            $out = [];
            foreach ($groups as $group) {
                $items = Menu::where('parent_id', $group->id)
                    ->orderBy('position')
                    ->limit($limit)
                    ->get(['id', 'title', 'content_id', 'url', 'position', 'item_type'])
                    ->map(function ($m) {
                        return [
                            'id' => $m->id,
                            'title' => $m->title,
                            'links_to' => $m->content_id ? ('page #' . $m->content_id) : ($m->url ?: '(none)'),
                            'type' => $m->item_type,
                            'position' => $m->position,
                        ];
                    })->all();

                $out[] = [
                    'menu' => $group->title,
                    'menu_id' => $group->id,
                    'item_count' => count($items),
                    'items' => $items,
                ];
            }

            if (empty($out)) {
                return json_encode(['menus' => [], 'note' => 'No menus found' . ($filter !== '' ? " for \"{$filter}\"." : '.')]);
            }

            return json_encode(['menus' => $out], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read menus: ' . $e->getMessage());
        }
    }
}
