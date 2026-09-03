<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: insert a functional Microweber module into the page.
 *
 * Lets the agent add real, working modules — a contact form, image gallery,
 * shop, map, menu, etc. — not just static HTML. Frontend tool: the browser
 * calls mw.app.editor.insertModule(type, …) on the live canvas (see mw-ai.js
 * frontendTools.insert_module) and marks the region dirty for the Live-Edit
 * SAVE. Backend is a thin side-effect-free declaration.
 */
class InsertModuleTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'insert_module',
            'Insert a functional Microweber module into the current page — use this '
            . 'for interactive/dynamic features rather than plain HTML. Common module '
            . 'types: "contact_form" (a contact form), "pictures" (image gallery), '
            . '"shop" (products), "map", "menu", "video", "buttons". After inserting a '
            . 'module you can configure it with set_module_option. The module is added '
            . 'live; the user saves with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'type',
                type: PropertyType::STRING,
                description: 'The module type to insert, e.g. "contact_form", '
                    . '"pictures", "shop", "map", "menu", "video". Use the exact type '
                    . 'string.',
                required: true,
            ),
            new ToolProperty(
                name: 'position',
                type: PropertyType::STRING,
                description: 'Where to place it: "bottom" (default, end of page) or '
                    . '"top".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $type = trim((string) ($args['type'] ?? ''));
        if ($type === '') {
            return $this->handleError('No module type was provided.');
        }

        return "OK — inserting the \"{$type}\" module on the page (live). The user "
            . "saves it with the Live-Edit Save button.";
    }
}
