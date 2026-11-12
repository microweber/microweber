<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Menu\Models\Menu;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Add an item to the site's main navigation menu.
 *
 * Unlike the canvas tools this performs a real server-side change (a menu row),
 * so the AI can wire up navigation between the pages it creates. It links a menu
 * entry to a page (by content_id) or to a raw URL, under the site's primary
 * "header_menu" group.
 */
class AddMenuItemTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'add_menu_item',
            'Add a link to one of the site\'s navigation menus — the header menu '
            . '(default) or the footer menu. Use this to wire up navigation after '
            . 'creating pages. Provide the visible title and either a content_id (a '
            . 'page you created) or a url; set group to "footer" for a footer link.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'title',
                type: PropertyType::STRING,
                description: 'The menu link text, e.g. "Features".',
                required: true,
            ),
            new ToolProperty(
                name: 'group',
                type: PropertyType::STRING,
                description: 'Which menu to add to: "header" (default) or "footer".',
                required: false,
            ),
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'The id of the page this link points to (preferred). '
                    . 'Use the id returned when you created the page.',
                required: false,
            ),
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'A URL to link to instead of a page id (e.g. "features" '
                    . 'or a full http URL). Ignored if content_id is given.',
                required: false,
            ),
            new ToolProperty(
                name: 'parent_id',
                type: PropertyType::INTEGER,
                description: 'Add this item UNDER an existing menu item (a submenu / '
                    . 'mega-menu entry) instead of at the top level. Pass the id of a '
                    . 'top-level item that has enable_mega_menu on to add one of its '
                    . 'mega-menu entries; pass an entry\'s id to add a tag chip under '
                    . 'it. Omit for a normal top-level link. "group" is ignored when '
                    . 'parent_id is set.',
                required: false,
            ),
            new ToolProperty(
                name: 'icon',
                type: PropertyType::STRING,
                description: 'Icon shown before the label (Font Awesome 4 token, e.g. '
                    . '"fa-shield", "fa-cloud") — used for mega-menu entry icons.',
                required: false,
            ),
            new ToolProperty(
                name: 'description',
                type: PropertyType::STRING,
                description: 'Short description shown under the title in a mega-menu '
                    . 'entry.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $title = trim((string) ($args['title'] ?? ''));
        $contentId = (int) ($args['content_id'] ?? 0);
        $url = trim((string) ($args['url'] ?? ''));

        if ($title === '' && $contentId <= 0 && $url === '') {
            return $this->handleError('Provide a title and a content_id or url.');
        }

        $parentId = (int) ($args['parent_id'] ?? 0);

        try {
            // Nested add (mega-menu entry / chip / submenu): parent is an existing
            // menu item. Otherwise add at the top level under a navigation group.
            if ($parentId > 0) {
                $parentItem = Menu::whereNotNull('parent_id')->find($parentId);
                if (!$parentItem) {
                    return $this->handleError("No parent menu item #{$parentId} was found (use get_menu to find ids).");
                }
                $parentRowId = $parentItem->id;
                $groupLabel = 'submenu of #' . $parentId;
            } else {
                // Resolve the target group: "footer" -> footer_menu, else header.
                $groupArg = strtolower(trim((string) ($args['group'] ?? 'header')));
                $groupTitle = str_contains($groupArg, 'footer') ? 'footer_menu' : 'header_menu';
                $groupLabel = $groupTitle === 'footer_menu' ? 'footer' : 'header';

                // The navigation group row (parent of the top-level items). Resolve
                // it, creating it if somehow absent.
                $group = Menu::whereNull('parent_id')->where('title', $groupTitle)->first();
                if (!$group) {
                    $group = Menu::create(['title' => $groupTitle]);
                }
                $parentRowId = $group->id;
            }

            // Next position at the end of the (sub)menu.
            $maxPos = (int) Menu::where('parent_id', $parentRowId)->max('position');

            $data = [
                'parent_id' => $parentRowId,
                'title' => $title !== '' ? $title : null,
                'position' => $maxPos + 1,
                'is_active' => 1,
            ];
            if ($contentId > 0) {
                $data['content_id'] = $contentId;
                $data['item_type'] = 'content';
            } elseif ($url !== '') {
                $data['url'] = $url;
                $data['item_type'] = 'url';
            }
            // A nested item (submenu / mega-menu entry / chip) MUST be item_type
            // "menu_item" — that is what the mega-menu renderer (getMegaMenuChildren)
            // and the submenu renderer look for. The content_id/url above still hold
            // its link; only the type differs from a top-level item.
            if ($parentId > 0) {
                $data['item_type'] = 'menu_item';
            }
            if (array_key_exists('icon', $args) && trim((string) $args['icon']) !== '') {
                $data['icon'] = trim((string) $args['icon']);
            }
            if (array_key_exists('description', $args) && trim((string) $args['description']) !== '') {
                $data['description'] = (string) $args['description'];
            }

            $item = Menu::create($data);

            // Clear the menu cache so the header/footer re-renders with the new
            // item (otherwise the stale cached menu keeps showing).
            foreach (['menus', 'menus/global'] as $cacheGroup) {
                try { app()->cache_manager->delete($cacheGroup); } catch (\Throwable $e) {}
            }

            $where = $parentId > 0
                ? "as a child of menu item #{$parentId} (a mega-menu entry/chip)"
                : "to the {$groupLabel} navigation menu";
            return "OK — added \"{$title}\" {$where} (item #{$item->id}).";
        } catch (\Throwable $e) {
            return $this->handleError('Could not add the menu item: ' . $e->getMessage());
        }
    }
}
