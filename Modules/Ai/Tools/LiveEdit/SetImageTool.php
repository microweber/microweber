<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: point an image element at a new source URL.
 *
 * Frontend tool — the swap happens in the browser on the live canvas (see
 * mw-ai.js frontendTools.set_image); this backend class is only the declaration
 * NeuronAI/Kimi calls. Pair it with generate_image (which returns a URL) to
 * replace an image with an AI-generated one. Side-effect-free here.
 */
class SetImageTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'set_image',
            'Change the source (src) of an image on the current page — e.g. to '
            . 'replace it with a newly generated image URL. The change is applied '
            . 'live in the editor; the user saves it with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'A CSS selector for the <img> to change, e.g. "img", '
                    . '".hero img", "#logo". Prefer a selector matching one element.',
                required: true,
            ),
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'The new image URL to set as the src (for example a URL '
                    . 'returned by generate_image).',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $selector = trim((string) ($args['selector'] ?? ''));
        $url = trim((string) ($args['url'] ?? ''));
        if ($selector === '' || $url === '') {
            return $this->handleError('Both selector and url are required.');
        }

        return "OK — set the image `{$selector}` to the new source on the live page "
            . "(the user saves it with the Live-Edit Save button).";
    }
}
