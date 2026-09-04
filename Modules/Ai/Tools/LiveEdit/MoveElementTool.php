<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: reorder an element / section on the page.
 *
 * Moves the element matching a CSS selector up or down among its siblings (or to
 * the top/bottom of its container) and marks the edit region changed so the new
 * order persists through the Live-Edit SAVE. Use it to fix section order without
 * rebuilding. Frontend tool (see mw-ai.js frontendTools.move_element).
 */
class MoveElementTool extends BaseTool
{
    protected string $domain = 'liveedit';

    private const DIRECTIONS = ['up', 'down', 'top', 'bottom'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'move_element',
            'Move an element or section up/down among its siblings (or to top/bottom '
            . 'of its container) by CSS selector — use it to reorder sections. '
            . 'Directions: ' . implode(', ', self::DIRECTIONS) . '. Applied live; the '
            . 'user saves with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'CSS selector of the element to move (first match).',
                required: true,
            ),
            new ToolProperty(
                name: 'direction',
                type: PropertyType::STRING,
                description: 'Where to move it: ' . implode(', ', self::DIRECTIONS) . '. Default "up".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $selector = trim((string) ($args['selector'] ?? ''));
        $direction = strtolower(trim((string) ($args['direction'] ?? 'up')));
        if ($selector === '') {
            return $this->handleError('A CSS selector is required.');
        }
        if (!in_array($direction, self::DIRECTIONS, true)) {
            $direction = 'up';
        }

        return "OK — moving \"{$selector}\" {$direction} (live). The user saves with "
            . "the Live-Edit Save button.";
    }
}
