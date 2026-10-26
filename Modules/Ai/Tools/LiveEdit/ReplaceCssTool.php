<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: surgical CSS edit (find/replace / diff style).
 *
 * Side-effect-free like apply_css — the front-end (frontendTools.replace_css) does
 * the find/replace within the accumulated AI design CSS on the live canvas and
 * persists it. Lets the model CHANGE or REMOVE a rule it already wrote without
 * re-sending all the CSS. To ADD a new rule, apply_css is still preferred (it
 * merges by selector).
 */
class ReplaceCssTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'replace_css',
            'Make a SURGICAL edit to the custom CSS — a find/replace within the design '
            . 'CSS you already applied, to CHANGE or REMOVE a rule WITHOUT re-sending '
            . 'all of it. Read the current CSS with get_page_context, copy the exact '
            . 'snippet, and pass find + replace (replace may be empty to delete it). '
            . 'To ADD a new rule, prefer apply_css — it merges by selector.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'find',
                type: PropertyType::STRING,
                description: 'The EXACT current CSS snippet to replace, copied from the '
                    . 'custom CSS (get_page_context). Note the stored AI CSS carries '
                    . '!important on each declaration. Every occurrence is replaced.',
                required: true,
            ),
            new ToolProperty(
                name: 'replace',
                type: PropertyType::STRING,
                description: 'The new CSS to put in its place (may be an empty string '
                    . 'to delete the snippet).',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $find = (string) ($args['find'] ?? '');
        if (trim($find) === '') {
            return $this->handleError('replace_css needs the exact current "find" CSS — read it with get_page_context first.');
        }

        // Side-effect-free: the front-end (frontendTools.replace_css) performs the
        // find/replace on the live AI CSS block and persists it.
        return "OK — this CSS find/replace will be applied to the site design "
            . "(the user saves it with the Live-Edit Save button).";
    }
}
