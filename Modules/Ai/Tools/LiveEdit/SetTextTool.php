<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: change the text of an element on the page.
 *
 * Like every Live-Edit tool this is a frontend tool — the real work runs in the
 * browser on the live canvas (see mw-ai.js frontendTools.set_text). This backend
 * class is only the declaration NeuronAI/Kimi needs to be able to call it; the
 * agent-chat-stream endpoint streams the call to the canvas, which edits the DOM
 * exactly as a user typing in Live Edit would and marks it dirty for SAVE. So it
 * is side-effect-free here: it just validates and echoes the intent back.
 */
class SetTextTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'set_text',
            'Change the visible text of an element on the current page (e.g. a '
            . 'heading, paragraph, button or title). Use this when the user asks to '
            . 'rewrite, replace or reword text. The change is applied live in the '
            . 'editor; the user saves it with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'A CSS selector for the element whose text to change, '
                    . 'e.g. "h1", ".hero h2", "#title", ".btn". Prefer a selector '
                    . 'that matches exactly one element. If the user refers to "the '
                    . 'first heading" use "h1".',
                required: true,
            ),
            new ToolProperty(
                name: 'text',
                type: PropertyType::STRING,
                description: 'The new text content to set on that element.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $selector = trim((string) ($args['selector'] ?? ''));
        $text = (string) ($args['text'] ?? '');
        if ($selector === '') {
            return $this->handleError('No selector was provided.');
        }

        return "OK — set the text of `{$selector}` on the live page (the user saves "
            . "it with the Live-Edit Save button).";
    }
}
