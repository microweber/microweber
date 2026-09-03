<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: navigate the editor canvas to another page.
 *
 * Lets the agent move between pages so the user can SEE the page it just built
 * or is about to edit. Frontend tool: the browser calls
 * mw.top().app.canvas.setUrl(...) to load that page into the live-edit canvas
 * (see mw-ai.js frontendTools.navigate_to_page). Side-effect-free on the backend.
 */
class NavigateToPageTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'navigate_to_page',
            'Open a different page of the site in the editor so the user can see it. '
            . 'Use this after creating a page to show it, or before editing a page\'s '
            . 'content. Pass the page URL/slug (e.g. "features", "about", or a full '
            . 'URL, or "/" for the home page).'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'The page URL or slug to open, e.g. "features", "tour", '
                    . '"/" for home, or a full http URL.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $url = trim((string) ($args['url'] ?? ''));
        if ($url === '') {
            return $this->handleError('No page URL was provided.');
        }

        return "OK — opening \"{$url}\" in the editor so the user can see it.";
    }
}
