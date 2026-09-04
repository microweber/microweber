<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: insert a ready-made template layout into the page.
 *
 * Inserts one of the active template's real layouts (the same ones the
 * "Insert layout" modal offers) — the agent should first call get_layouts to
 * see what the template provides, then pass the chosen layout's `template`
 * value here. Frontend tool: the browser calls
 * mw.app.editor.insertLayout({ template }, location, target) on the live canvas
 * (see mw-ai.js frontendTools.insert_layout), exactly like the modal. Backend
 * is a thin declaration; the layout list is NOT hardcoded — it comes from the
 * template via get_layouts.
 */
class InsertLayoutTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'insert_layout',
            'Insert a ready-made layout from the active template into the page. FIRST '
            . 'call get_layouts to see the template\'s layouts, then pass the chosen '
            . 'layout\'s `template` value here (e.g. "content/skin-1", "default", '
            . '"skin-1"). The layout is added live; the user saves with the Live-Edit '
            . 'Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'template',
                type: PropertyType::STRING,
                description: 'The layout\'s `template` value from get_layouts, e.g. '
                    . '"content/skin-1", "default", "skin-1".',
                required: true,
            ),
            new ToolProperty(
                name: 'position',
                type: PropertyType::STRING,
                description: 'Where to place it: "bottom" (default) or "top".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $template = trim((string) ($args['template'] ?? ''));
        if ($template === '') {
            return $this->handleError(
                'No layout was provided. Call get_layouts first, then pass a layout\'s `template` value.'
            );
        }

        return "OK — inserting the \"{$template}\" layout on the page (live). The user "
            . "saves it with the Live-Edit Save button.";
    }
}
