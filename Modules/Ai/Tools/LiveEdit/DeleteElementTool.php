<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: delete an element / section / module from the page.
 *
 * Removes the element matching a CSS selector from the live canvas and marks the
 * containing edit region changed so the removal persists through the Live-Edit
 * SAVE. This is the counterpart to add_section / insert_module — use it to remove
 * a duplicated section, an unwanted module, or any element, instead of hiding it
 * with CSS. Frontend tool: the browser does the removal (see mw-ai.js
 * frontendTools.delete_element); backend is a thin declaration.
 */
class DeleteElementTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'delete_element',
            'Delete (remove) an element, section or module from the page by CSS '
            . 'selector — the way to remove a duplicated section or an unwanted '
            . 'module, rather than hiding it with CSS. The removal is applied live; '
            . 'the user saves with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'CSS selector of the element to remove (the FIRST match is removed), '
                    . 'e.g. ".pixel-hero", "#mw-ai-sec-abc", ".module[data-type=\'video\']".',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $selector = trim((string) ($args['selector'] ?? ''));
        if ($selector === '') {
            return $this->handleError('A CSS selector is required.');
        }

        return "OK — removing the element matching \"{$selector}\" (live). The user "
            . "saves with the Live-Edit Save button.";
    }
}
