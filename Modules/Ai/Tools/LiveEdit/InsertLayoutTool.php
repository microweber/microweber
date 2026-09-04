<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: insert a ready-made layout (a responsive grid row)
 * into the page.
 *
 * Where add_section adds a freeform HTML block, insert_layout drops a structural
 * multi-column layout — a Bootstrap row with editable columns — so the agent can
 * lay out content in columns and then fill each column (with set_text, or an
 * insert_module such as pictures/contact_form). Frontend tool: the browser builds
 * the grid on the live canvas (see mw-ai.js frontendTools.insert_layout); backend
 * is a thin declaration.
 */
class InsertLayoutTool extends BaseTool
{
    protected string $domain = 'liveedit';

    /** Named layout presets the frontend understands. */
    private const LAYOUTS = [
        'one-column', 'two-column', 'three-column', 'four-column',
        'sidebar-left', 'sidebar-right', 'hero', 'grid',
    ];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'insert_layout',
            'Insert a ready-made layout (a responsive column grid) into the page — '
            . 'use it to structure content in columns before filling it. Presets: '
            . implode(', ', self::LAYOUTS) . '. After inserting a layout you can put '
            . 'text in a column with set_text or a module in it with insert_module. '
            . 'The layout is added live; the user saves with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'layout',
                type: PropertyType::STRING,
                description: 'The layout preset: ' . implode(', ', self::LAYOUTS)
                    . '. You may also pass a plain column count "2"/"3"/"4".',
                required: true,
            ),
            new ToolProperty(
                name: 'heading',
                type: PropertyType::STRING,
                description: 'Optional heading shown above the layout row.',
                required: false,
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
        $layout = trim((string) ($args['layout'] ?? ''));
        if ($layout === '') {
            return $this->handleError('No layout preset was provided.');
        }

        return "OK — inserting a \"{$layout}\" layout on the page (live). You can now "
            . "fill its columns with set_text or insert_module. The user saves with the "
            . "Live-Edit Save button.";
    }
}
