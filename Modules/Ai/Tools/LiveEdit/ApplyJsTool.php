<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\TemplateCustomCss\Facades\TemplateCustomCss;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit design tool: add custom JavaScript to the current site.
 *
 * ARCHITECTURE: unlike apply_css (which the front-end applies live to the canvas),
 * custom JS must NOT run inside the Live-Edit editor canvas — a looping or broken
 * script would break editing. So there is no live-canvas apply path for JS; this
 * tool PERSISTS the JS server-side to the active template's custom-JS layer
 * (userfiles/css/<Template>/live_edit.js via
 * TemplateCustomCss::liveEdit()->saveLiveEditJsContent). LiveEditJsFooterTags then
 * serves it, deferred, in the footer of the PUBLISHED page only. Calls ACCUMULATE:
 * each block is appended to the existing site custom JS.
 */
class ApplyJsTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'apply_js',
            'Add custom JavaScript to the current website to make it interactive or '
            . 'animated: scroll-reveal animations (IntersectionObserver), hover/click '
            . 'behaviour, counters, simple carousels, menu toggles, etc. The JS runs '
            . 'on the PUBLISHED page only, never inside the editor, so you will not '
            . 'see it act in the canvas — it is saved immediately and runs when the '
            . 'page is viewed/reloaded. Provide complete, self-contained vanilla JS '
            . '(no <script> tags); wrap it in an IIFE, guard DOM access with '
            . 'DOMContentLoaded, and respect prefers-reduced-motion. Use this to '
            . 'deliver the motion the design rules call for.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'js',
                type: PropertyType::STRING,
                description: 'Complete, self-contained JavaScript to add (no <script> '
                    . 'tags), e.g. an IIFE that sets up an IntersectionObserver adding '
                    . 'a "is-revealed" class to sections as they enter the viewport. '
                    . 'Appended to the existing site custom JS and saved immediately.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $js = trim((string) ($args['js'] ?? ''));
        if ($js === '') {
            return $this->handleError('No JavaScript was provided to add.');
        }
        // The model sometimes wraps the snippet in <script> — strip any such tags;
        // the footer loader provides the real <script> element.
        $js = (string) preg_replace('~</?script[^>]*>~i', '', $js);
        $js = trim($js);
        if ($js === '') {
            return $this->handleError('No JavaScript was provided to add.');
        }

        try {
            $template = (string) (function_exists('template_name')
                ? template_name()
                : (function_exists('get_option') ? get_option('current_template', 'template') : ''));

            $manager = TemplateCustomCss::liveEdit();
            $existing = (string) $manager->getLiveEditJsContent($template);

            // Accumulate inside a marker block so the custom-JS file stays tidy
            // across multiple calls (mirrors the AI custom-CSS accumulation).
            $block = "/* MW-AI-JS */\n" . $js . "\n/* /MW-AI-JS */";
            $combined = $existing !== '' ? rtrim($existing) . "\n\n" . $block . "\n" : $block . "\n";

            $manager->saveLiveEditJsContent($combined, $template === '' ? null : $template);
        } catch (\Throwable $e) {
            return $this->handleError('Could not save the custom JS: ' . $e->getMessage());
        }

        return "OK — added custom JavaScript to the site. It is saved and will run on "
            . "the published page (not in the editor canvas); reload the public page to see it.";
    }
}
