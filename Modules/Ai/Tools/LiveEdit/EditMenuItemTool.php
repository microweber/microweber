<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Menu\Models\Menu;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Edit an existing navigation menu item: rename, relink, reorder or remove it.
 *
 * A real server-side change (updates/deletes a menu row), so the agent can fully
 * manage the site navigation. Get item ids from get_menu first.
 */
class EditMenuItemTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'edit_menu_item',
            'Edit an existing navigation menu item by id: rename it, point it at a '
            . 'different page/url, change its position, or remove it. Use get_menu '
            . 'first to find the item id.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'id',
                type: PropertyType::INTEGER,
                description: 'The id of the menu item to edit (from get_menu).',
                required: true,
            ),
            new ToolProperty(
                name: 'remove',
                type: PropertyType::BOOLEAN,
                description: 'Set true to delete this menu item. When true, the other '
                    . 'fields are ignored.',
                required: false,
            ),
            new ToolProperty(
                name: 'title',
                type: PropertyType::STRING,
                description: 'New link text.',
                required: false,
            ),
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'Point the item at a different page id.',
                required: false,
            ),
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'Point the item at a raw URL instead of a page.',
                required: false,
            ),
            new ToolProperty(
                name: 'position',
                type: PropertyType::INTEGER,
                description: 'New order position (lower = earlier).',
                required: false,
            ),
            new ToolProperty(
                name: 'icon',
                type: PropertyType::STRING,
                description: 'Icon shown before the label, e.g. "fa-shield", '
                    . '"fa-cloud", "fa-rocket" (Font Awesome 4 token). Pass an empty '
                    . 'string to remove the icon.',
                required: false,
            ),
            new ToolProperty(
                name: 'description',
                type: PropertyType::STRING,
                description: 'Short description for this item — shown under the title '
                    . 'in a mega-menu entry.',
                required: false,
            ),
            new ToolProperty(
                name: 'enable_mega_menu',
                type: PropertyType::BOOLEAN,
                description: 'Turn this top-level item into a MEGA MENU: its child '
                    . 'items render as a styled dropdown grid (icon + title + '
                    . 'description), and their children become tag chips. Add the '
                    . 'child entries with add_menu_item using this item\'s id as '
                    . 'parent_id.',
                required: false,
            ),
            new ToolProperty(
                name: 'mega_menu_skin',
                type: PropertyType::STRING,
                description: 'Mega-menu layout skin: "default" (2-column grid), '
                    . '"columns" (3-column grid) or "simple" (single-column list, no '
                    . 'chips). Only used when enable_mega_menu is on.',
                required: false,
            ),
        ];
    }

    /** The mega-menu skins shipped by the Menu module (view names). */
    private const MEGA_SKINS = ['default', 'columns', 'simple'];

    public function __invoke(...$args): string
    {
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->handleError('A menu item id is required.');
        }

        try {
            $item = Menu::whereNotNull('parent_id')->find($id);
            if (!$item) {
                return $this->handleError("No menu item #{$id} was found.");
            }

            if (!empty($args['remove'])) {
                $item->delete();
                return "OK — removed menu item #{$id}.";
            }

            if (array_key_exists('title', $args) && trim((string) $args['title']) !== '') {
                $item->title = (string) $args['title'];
            }
            if (!empty($args['content_id'])) {
                $item->content_id = (int) $args['content_id'];
                $item->item_type = 'content';
                $item->url = null;
            } elseif (array_key_exists('url', $args) && trim((string) $args['url']) !== '') {
                $item->url = (string) $args['url'];
                $item->item_type = 'url';
                $item->content_id = null;
            }
            if (array_key_exists('position', $args) && $args['position'] !== null && $args['position'] !== '') {
                $item->position = (int) $args['position'];
            }
            if (array_key_exists('icon', $args)) {
                // Accept "fa-shield" or "fa fa-shield"; empty string clears it.
                $item->icon = trim((string) $args['icon']);
            }
            if (array_key_exists('description', $args)) {
                $item->description = (string) $args['description'];
            }
            if (array_key_exists('enable_mega_menu', $args) && $args['enable_mega_menu'] !== null && $args['enable_mega_menu'] !== '') {
                $item->enable_mega_menu = filter_var($args['enable_mega_menu'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }
            if (array_key_exists('mega_menu_skin', $args) && trim((string) $args['mega_menu_skin']) !== '') {
                $skin = strtolower(trim((string) $args['mega_menu_skin']));
                if (!in_array($skin, self::MEGA_SKINS, true)) {
                    $skin = 'default';
                }
                $item->menu_item_template = $skin;
            }
            $item->save();

            // The header/footer caches the rendered menu — clear it so the mega
            // menu / icon / description change shows on the next render.
            foreach (['menus', 'menus/global'] as $cacheGroup) {
                try { app()->cache_manager->delete($cacheGroup); } catch (\Throwable $e) {}
            }

            $extra = '';
            if (!empty($item->enable_mega_menu)) {
                $extra = ' Mega menu is ON (skin: ' . ($item->menu_item_template ?: 'default')
                    . ') — add its entries with add_menu_item using parent_id=' . $id . '.';
            }

            return "OK — updated menu item #{$id}.{$extra}";
        } catch (\Throwable $e) {
            return $this->handleError('Could not edit the menu item: ' . $e->getMessage());
        }
    }
}
