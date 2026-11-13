<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: set or change the icon of an element.
 *
 * Swaps the Font Awesome glyph on an existing <i> icon matched by selector (or
 * inserts one if the target has none), and optionally sets its colour and size.
 * This is the icon counterpart to set_text / set_image / set_link — the way the
 * model edits icons without hand-writing `<i class="fa ...">` markup and
 * without guessing names. Pair with search_icons to pick a valid name. Frontend
 * tool (see mw-ai.js frontendTools.set_icon); the user saves with the Live-Edit
 * Save button.
 */
class SetIconTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'set_icon',
            'Set or change the icon of an element by CSS selector. Swaps the Font '
            . 'Awesome glyph of an existing icon (or adds one if the element has '
            . 'none), and can also set its colour and size. Use search_icons first '
            . 'to find a valid icon name. Examples: set_icon(".hero .cta i", '
            . 'icon="rocket", color="#0d6efd"); set_icon(".feature-1", '
            . 'icon="shield-halved", color="var(--mw-primary-color)", size="2x"). '
            . 'Applied live; the user saves with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'CSS selector (first match) of the icon <i> to change, or of a '
                    . 'button/heading/box that should GET an icon (one is inserted at its start).',
                required: true,
            ),
            new ToolProperty(
                name: 'icon',
                type: PropertyType::STRING,
                description: 'The Font Awesome icon name, with or without the "fa-" prefix, '
                    . 'e.g. "rocket", "cart-shopping", "tripadvisor". Get a valid name from '
                    . 'search_icons.',
                required: true,
            ),
            new ToolProperty(
                name: 'color',
                type: PropertyType::STRING,
                description: 'Optional icon colour — a hex ("#0d6efd"), rgb()/named colour, or '
                    . 'a CSS variable ("var(--mw-primary-color)" or "--mw-primary-color"). '
                    . 'Omit to leave the colour unchanged.',
                required: false,
            ),
            new ToolProperty(
                name: 'size',
                type: PropertyType::STRING,
                description: 'Optional icon size — a Font Awesome keyword ("lg", "2x".."5x") or '
                    . 'a CSS length ("48px", "2rem"). Omit to leave the size unchanged.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $selector = trim((string) ($args['selector'] ?? ''));
        if ($selector === '') {
            return $this->handleError('A CSS selector is required.');
        }
        $icon = trim((string) ($args['icon'] ?? ''));
        if ($icon === '') {
            return $this->handleError('An icon name is required (e.g. "rocket"). Use search_icons to find one.');
        }
        // Normalise to the bare glyph name for the confirmation message; the
        // frontend handler does the authoritative normalisation + DOM work.
        $name = ltrim(preg_replace('/^fa[sbrl]?[-\s]?/', '', strtolower($icon)) ?? $icon, '-');

        $color = trim((string) ($args['color'] ?? ''));
        $size = trim((string) ($args['size'] ?? ''));

        $extra = [];
        if ($color !== '') {
            $extra[] = "colour {$color}";
        }
        if ($size !== '') {
            $extra[] = "size {$size}";
        }
        $suffix = $extra ? (' (' . implode(', ', $extra) . ')') : '';

        return "OK — setting the icon on \"{$selector}\" to fa-{$name}{$suffix} (live). "
            . 'The user saves with the Live-Edit Save button.';
    }
}
