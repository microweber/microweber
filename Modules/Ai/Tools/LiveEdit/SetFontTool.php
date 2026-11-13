<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: set the site's design font.
 *
 * Two halves: this backend call ENABLES the font (TemplateFontsManager::enableFont
 * — downloads + registers it so its @font-face ships with the site, and mirrors
 * to S3 on remote installs), and the matching frontend handler (mw-ai.js
 * frontendTools.set_font) APPLIES it live on the canvas by loading the webfont
 * and setting the design font-family CSS variables
 * (--mw-heading-font-family / --mw-body-font-family), persisted globally like
 * any other design change. Pair with search_fonts to pick a valid family.
 */
class SetFontTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'set_font',
            'Set the website font. Enables the given font family and applies it to '
            . 'the design. scope="all" (default) changes both headings and body, '
            . '"headings" or "body" changes just one. Use search_fonts first to get '
            . 'a valid family. Example: set_font(family="Poppins", scope="all"). '
            . 'Applied live; the user saves with the Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'family',
                type: PropertyType::STRING,
                description: 'The font family to use, e.g. "Poppins", "Playfair Display". '
                    . 'Get a valid name from search_fonts.',
                required: true,
            ),
            new ToolProperty(
                name: 'scope',
                type: PropertyType::STRING,
                description: 'What to apply the font to: "all" (default), "headings", or "body".',
                required: false,
            ),
            new ToolProperty(
                name: 'fallback',
                type: PropertyType::STRING,
                description: 'Generic fallback family for the CSS stack: "sans-serif" (default), '
                    . '"serif", "monospace", "cursive". Omit to use sans-serif.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $family = trim((string) ($args['family'] ?? ''));
        if ($family === '') {
            return $this->handleError('A font family is required (e.g. "Poppins"). Use search_fonts to find one.');
        }
        $scope = strtolower(trim((string) ($args['scope'] ?? 'all')));
        if (!in_array($scope, ['all', 'headings', 'body'], true)) {
            $scope = 'all';
        }

        // Enable the font server-side so its @font-face ships with the published
        // site (and mirrors to S3 on remote installs). The frontend handler then
        // loads the webfont into the canvas and sets the design font vars.
        $manager = $this->manager();
        if ($manager !== null) {
            try {
                $manager->enableFont($family, 'google');
            } catch (\Throwable) {
                // Non-fatal: the frontend still loads the webfont from Google so
                // the change is visible; enabling can be retried on save.
            }
        }

        return "OK — setting the site font to \"{$family}\" ({$scope}) and enabling it (live). "
            . 'The user saves with the Live-Edit Save button.';
    }

    private function manager(): ?TemplateFontsManager
    {
        if (!class_exists(TemplateFontsManager::class) || !app()->bound(TemplateFontsManager::class)) {
            return null;
        }

        try {
            return app(TemplateFontsManager::class);
        } catch (\Throwable) {
            return null;
        }
    }
}
