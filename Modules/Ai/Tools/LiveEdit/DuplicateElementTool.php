<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: duplicate (clone) an element / section on the page.
 *
 * Clones the element matching a CSS selector and inserts the copy right after it,
 * marking the edit region changed so it persists through the Live-Edit SAVE — the
 * quick way to repeat a card/section. Frontend tool (see mw-ai.js
 * frontendTools.duplicate_element).
 */
class DuplicateElementTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'duplicate_element',
            'Duplicate (clone) an element or section by CSS selector — the copy is '
            . 'inserted right after the original. Use it to repeat a card or section. '
            . 'Applied live; the user saves with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'CSS selector of the element to duplicate (first match).',
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

        return "OK — duplicating \"{$selector}\" (live). The user saves with the "
            . "Live-Edit Save button.";
    }
}
