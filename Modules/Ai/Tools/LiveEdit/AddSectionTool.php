<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: add a new content section to the page.
 *
 * This is the structural build tool — it lets the agent create a page from
 * scratch (hero, features, testimonials, footer …), one section at a time, so
 * "recreate this site" is possible with Live-Edit tools alone. Like every
 * Live-Edit tool it is a FRONTEND tool: the real work runs in the browser
 * (mw-ai.js frontendTools.add_section), which sanitises the HTML, appends it
 * into the page's editable content region and marks it dirty so the normal
 * Live-Edit SAVE persists it. Side-effect-free on the backend.
 */
class AddSectionTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'add_section',
            'Add a new section of content to the current page (use this to BUILD a '
            . 'page: hero, features, pricing, testimonials, footer, etc.). Provide '
            . 'plain semantic HTML for the section body — headings (h1-h3), '
            . 'paragraphs, lists, links/buttons (<a class="btn">), images '
            . '(<img src="…">), and wrapper <div>s with classes you then style '
            . 'with apply_css. Call it once per section, in order. The section is '
            . 'added live; the user saves with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'html',
                type: PropertyType::STRING,
                description: 'The inner HTML of the section — semantic, plain HTML '
                    . 'only. Do NOT include <script>, <style>, <iframe> or Microweber '
                    . '<module> tags. Give elements class names (e.g. '
                    . '"hero", "hero-title", "features", "feature-card") so you can '
                    . 'target them with apply_css afterwards.',
                required: true,
            ),
            new ToolProperty(
                name: 'css',
                type: PropertyType::STRING,
                description: 'STRONGLY RECOMMENDED: the CSS that styles this section, '
                    . 'applied together with it so the section looks right immediately. '
                    . 'Target the class names you used in html (e.g. ".hero { … } '
                    . '.hero-title { … }"). Include layout, colors, spacing and '
                    . 'typography. Always pass this when you add a section.',
                required: false,
            ),
            new ToolProperty(
                name: 'position',
                type: PropertyType::STRING,
                description: 'Where to place the section: "append" (default, at the '
                    . 'end of the page) or "prepend" (at the top).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $html = trim((string) ($args['html'] ?? ''));
        if ($html === '') {
            return $this->handleError('No section HTML was provided.');
        }
        if (stripos($html, '<module') !== false) {
            return $this->handleError('Do not include <module> tags in a section; use plain HTML.');
        }

        $position = strtolower(trim((string) ($args['position'] ?? 'append')));
        $where = $position === 'prepend' ? 'at the top of' : 'to';

        return "OK — added a new section {$where} the page (live). The user saves it "
            . "with the Live-Edit Save button.";
    }
}
