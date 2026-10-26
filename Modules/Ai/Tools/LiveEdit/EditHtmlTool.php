<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: surgical HTML edit (str_replace / diff style).
 *
 * Side-effect-free like apply_css — the agent-chat endpoint surfaces this tool
 * call to the Live-Edit front-end (frontendTools.edit_html), which does a
 * find/replace inside the named element's innerHTML on the live canvas and marks
 * it changed so the user's SAVE persists it. Lets the model change ONLY the part
 * that differs instead of re-emitting a whole section, saving tokens and avoiding
 * clobbering siblings.
 */
class EditHtmlTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'edit_html',
            'Make a SURGICAL edit to an element\'s HTML — a find/replace inside the '
            . 'element you name, changing ONLY the part that differs instead of '
            . 're-sending the whole section. Use it to tweak existing content/markup '
            . '(a heading, a list item, a button, an attribute) AFTER reading the '
            . 'current HTML with get_dom. Much cheaper than re-adding a section. For a '
            . 'brand-new section use add_section; for plain visible text use set_text.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'CSS selector of the element whose inner HTML to edit '
                    . '(e.g. "#hero", ".features", "section:nth-of-type(2)").',
                required: true,
            ),
            new ToolProperty(
                name: 'find',
                type: PropertyType::STRING,
                description: 'The EXACT current HTML snippet to replace — copy it '
                    . 'verbatim from get_dom (whitespace + attributes must match). '
                    . 'Every occurrence inside the element is replaced.',
                required: true,
            ),
            new ToolProperty(
                name: 'replace',
                type: PropertyType::STRING,
                description: 'The new HTML to put in its place (may be an empty string '
                    . 'to delete the snippet).',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $selector = trim((string) ($args['selector'] ?? ''));
        $find = (string) ($args['find'] ?? '');
        if ($selector === '' || $find === '') {
            return $this->handleError('edit_html needs a selector and the exact current "find" HTML — read it with get_dom first.');
        }

        // Side-effect-free: the front-end (frontendTools.edit_html) performs the
        // find/replace on the live canvas and registers the change for SAVE.
        return "OK — this HTML find/replace will be applied to {$selector} on the "
            . "canvas (the user saves it with the Live-Edit Save button).";
    }
}
