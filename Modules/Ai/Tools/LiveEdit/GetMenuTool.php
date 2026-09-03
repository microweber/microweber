<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Menu\Models\Menu;

/**
 * Read the site's main navigation menu (the header_menu items).
 *
 * Gives the agent the current menu — item ids, titles and what each links to —
 * so it can edit navigation (rename, relink, reorder, remove) with edit_menu_item
 * and know which pages are already linked. Read-only.
 */
class GetMenuTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_menu',
            'List the items in the site\'s main navigation menu (title, id, and what '
            . 'each links to). Call this before editing the menu so you know the item '
            . 'ids to pass to edit_menu_item.'
        );
    }

    public function __invoke(...$args): string
    {
        try {
            $group = Menu::whereNull('parent_id')->where('title', 'header_menu')->first();
            if (!$group) {
                return json_encode(['menu' => [], 'note' => 'No header menu found.']);
            }

            $items = Menu::where('parent_id', $group->id)
                ->orderBy('position')
                ->get(['id', 'title', 'content_id', 'url', 'position'])
                ->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'title' => $m->title,
                        'links_to' => $m->content_id ? ('page #' . $m->content_id) : ($m->url ?: '(none)'),
                        'position' => $m->position,
                    ];
                })->all();

            return json_encode(['menu_group_id' => $group->id, 'items' => $items], JSON_UNESCAPED_SLASHES);
        } catch (\Throwable $e) {
            return $this->handleError('Could not read the menu: ' . $e->getMessage());
        }
    }
}
