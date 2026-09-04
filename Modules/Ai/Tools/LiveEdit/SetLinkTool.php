<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: set (or clear) the link URL of an element.
 *
 * Sets the href of the anchor matching a CSS selector (wrapping a non-anchor
 * element's click target if needed) so buttons and links point where they should.
 * This is the link counterpart to set_text / set_image. Frontend tool (see
 * mw-ai.js frontendTools.set_link).
 */
class SetLinkTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'set_link',
            'Set the link URL (href) of a link/button by CSS selector — use it to '
            . 'point a nav link or CTA button at the right page/URL. Pass an empty '
            . 'url to remove the link. Applied live; the user saves with the '
            . 'Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'CSS selector of the link/anchor (first match), e.g. ".nav-cta", "a.btn".',
                required: true,
            ),
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'The URL to link to (absolute or site-relative). Empty removes the link.',
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
        $url = trim((string) ($args['url'] ?? ''));

        return $url === ''
            ? "OK — removing the link on \"{$selector}\" (live)."
            : "OK — linking \"{$selector}\" to {$url} (live). The user saves with the Live-Edit Save button.";
    }
}
