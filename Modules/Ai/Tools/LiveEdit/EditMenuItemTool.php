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
        ];
    }

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
            $item->save();

            return "OK — updated menu item #{$id}.";
        } catch (\Throwable $e) {
            return $this->handleError('Could not edit the menu item: ' . $e->getMessage());
        }
    }
}
