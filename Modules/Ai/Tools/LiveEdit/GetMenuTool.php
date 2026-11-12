<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Menu\Models\Menu;

/**
 * Read the site's navigation menus — BOTH the header and the footer menu.
 *
 * Gives the agent the current menus — item ids, titles and what each links to —
 * so it can edit navigation (rename, relink, reorder, remove) with edit_menu_item
 * and know which pages are already linked. Returns both the header_menu and the
 * footer_menu groups so footer links can be managed too. Read-only.
 */
class GetMenuTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_menu',
            'List the items in the site\'s navigation menus — BOTH the header menu and '
            . 'the footer menu (each item\'s title, id, group and what it links to). '
            . 'Call this before editing a menu so you know the item ids to pass to '
            . 'edit_menu_item and which group (header/footer) each item is in.'
        );
    }

    public function __invoke(...$args): string
    {
        try {
            $groups = Menu::whereNull('parent_id')
                ->whereIn('title', ['header_menu', 'footer_menu'])
                ->get(['id', 'title']);

            $out = [];
            foreach (['header_menu', 'footer_menu'] as $groupTitle) {
                $group = $groups->firstWhere('title', $groupTitle);
                $short = $groupTitle === 'footer_menu' ? 'footer' : 'header';
                if (!$group) {
                    $out[$short] = ['group_id' => null, 'items' => []];
                    continue;
                }
                $cols = ['id', 'title', 'content_id', 'url', 'position', 'icon', 'description', 'enable_mega_menu', 'menu_item_template'];

                // Map one item, recursing into its children (mega-menu entries ->
                // their chips) so the agent can see the full mega-menu structure.
                $mapItem = function ($m) use (&$mapItem, $short, $cols) {
                    $row = [
                        'id' => $m->id,
                        'title' => $m->title,
                        'group' => $short,
                        'links_to' => $m->content_id ? ('page #' . $m->content_id) : ($m->url ?: '(none)'),
                        'position' => $m->position,
                    ];
                    if (!empty($m->icon)) { $row['icon'] = $m->icon; }
                    if (!empty($m->description)) { $row['description'] = $m->description; }
                    if (!empty($m->enable_mega_menu)) {
                        $row['mega_menu'] = true;
                        $row['mega_menu_skin'] = $m->menu_item_template ?: 'default';
                    }
                    $children = Menu::where('parent_id', $m->id)->orderBy('position')->get($cols);
                    if ($children->isNotEmpty()) {
                        $row['children'] = $children->map($mapItem)->all();
                    }
                    return $row;
                };

                $items = Menu::where('parent_id', $group->id)
                    ->orderBy('position')
                    ->get($cols)
                    ->map($mapItem)->all();
                $out[$short] = ['group_id' => $group->id, 'items' => $items];
            }

            return json_encode($out, JSON_UNESCAPED_SLASHES);
        } catch (\Throwable $e) {
            return $this->handleError('Could not read the menu: ' . $e->getMessage());
        }
    }
}
