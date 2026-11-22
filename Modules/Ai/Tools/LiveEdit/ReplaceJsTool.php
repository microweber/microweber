<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\TemplateCustomCss\Facades\TemplateCustomCss;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: surgical JavaScript edit (find/replace / diff style) — the JS
 * sibling of replace_css.
 *
 * apply_js only APPENDS (each call accumulates a new block in live_edit.js), so
 * it can never change or remove JS the agent already wrote. This tool does a
 * find/replace INSIDE the persisted global custom JS
 * (userfiles/css/<Template>/live_edit.js) so the agent can fix one handler,
 * retune a value, or delete a snippet WITHOUT re-sending the whole script.
 *
 * Fully server-side, like apply_js: there is no live-canvas JS (a looping/broken
 * script would break editing), so the manager persists it and LiveEditJsFooterTags
 * serves it, deferred, in the PUBLISHED footer only. Read the current JS first
 * with get_page_context (it returns current_custom_js).
 */
class ReplaceJsTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'replace_js',
            'Make a SURGICAL edit to the custom JavaScript — a find/replace within '
            . 'the site JS you already added with apply_js, to CHANGE or REMOVE a '
            . 'snippet WITHOUT re-sending all of it. Read the current JS with '
            . 'get_page_context (current_custom_js), copy the EXACT snippet, and pass '
            . 'find + replace (replace may be empty to delete it). To ADD new '
            . 'behaviour, use apply_js instead (it appends).'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'find',
                type: PropertyType::STRING,
                description: 'The EXACT current JS snippet to replace, copied verbatim '
                    . 'from the site custom JS (read it with get_page_context). Every '
                    . 'occurrence is replaced.',
                required: true,
            ),
            new ToolProperty(
                name: 'replace',
                type: PropertyType::STRING,
                description: 'The new JS to put in its place (no <script> tags; may be '
                    . 'an empty string to delete the snippet).',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $find = (string) ($args['find'] ?? '');
        if (trim($find) === '') {
            return $this->handleError('replace_js needs the exact current "find" JS — read it with get_page_context (current_custom_js) first.');
        }
        $replace = (string) ($args['replace'] ?? '');
        // Never let a <script> wrapper leak into the stored JS file.
        $replace = (string) preg_replace('~</?script[^>]*>~i', '', $replace);

        try {
            $template = (string) (function_exists('template_name')
                ? template_name()
                : (function_exists('get_option') ? get_option('current_template', 'template') : ''));

            $manager = TemplateCustomCss::liveEdit();
            $existing = (string) $manager->getLiveEditJsContent($template);
            if ($existing === '' || strpos($existing, $find) === false) {
                return $this->handleError('That exact JS snippet was not found in the site custom JS. '
                    . 'Read the current JS with get_page_context (current_custom_js) and copy the snippet verbatim.');
            }

            $updated = str_replace($find, $replace, $existing);
            $manager->saveLiveEditJsContent($updated, $template === '' ? null : $template);
        } catch (\Throwable $e) {
            return $this->handleError('Could not edit the custom JS: ' . $e->getMessage());
        }

        return trim($replace) === ''
            ? 'OK — removed that JS snippet from the site custom JS. Reload the public page to see the effect.'
            : 'OK — updated that JS snippet in the site custom JS. Reload the public page to see the effect.';
    }
}
