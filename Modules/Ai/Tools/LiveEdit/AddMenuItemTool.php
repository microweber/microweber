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
            'Add a link to the site\'s main navigation menu (the header menu). Use '
            . 'this to wire up navigation after creating pages. Provide the visible '
            . 'title and either a content_id (a page you created) or a url.'
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

        try {
            // The primary navigation group is the "header_menu" row (parent of the
            // top-level items). Resolve it, creating it if somehow absent.
            $group = Menu::whereNull('parent_id')->where('title', 'header_menu')->first();
            if (!$group) {
                $group = Menu::create(['title' => 'header_menu']);
            }

            // Next position at the end of the menu.
            $maxPos = (int) Menu::where('parent_id', $group->id)->max('position');

            $data = [
                'parent_id' => $group->id,
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

            $item = Menu::create($data);

            return "OK — added \"{$title}\" to the main navigation menu (item #{$item->id}).";
        } catch (\Throwable $e) {
            return $this->handleError('Could not add the menu item: ' . $e->getMessage());
        }
    }
}
