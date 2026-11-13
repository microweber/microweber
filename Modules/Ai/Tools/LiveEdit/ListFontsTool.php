<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

/**
 * List the fonts currently enabled on the site, so the model knows what is
 * already available before enabling another or switching the design font.
 *
 * Sourced from TemplateFontsManager::getEnabledFonts(). Read-only.
 */
class ListFontsTool extends BaseTool
{
    protected string $domain = 'fonts';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'list_fonts',
            'List the fonts currently enabled/available on the site. Use it to see '
            . 'which fonts are already installed before enabling a new one with '
            . 'set_font, or before removing one with disable_font.'
        );
    }

    protected function properties(): array
    {
        return [];
    }

    public function __invoke(...$args): string
    {
        $manager = $this->manager();
        if ($manager === null) {
            return $this->handleError('The font manager is not available on this site.');
        }

        try {
            $enabled = array_values($manager->getEnabledFonts());
        } catch (\Throwable $e) {
            return $this->handleError('Could not read enabled fonts: ' . $e->getMessage());
        }

        return json_encode([
            'count' => count($enabled),
            'fonts' => $enabled,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
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
