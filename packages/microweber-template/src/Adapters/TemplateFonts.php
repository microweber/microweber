<?php

namespace MicroweberPackages\Template\Adapters;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

/**
 * CMS adapter bridging TemplateManager to the microweber-template-fonts package.
 */
class TemplateFonts
{
    protected function manager(): TemplateFontsManager
    {
        return app(TemplateFontsManager::class);
    }

    public function getFonts(): array
    {
        return $this->manager()->getFonts();
    }

    public function getFontsStylesheetCss(): string
    {
        return $this->manager()->getFontsStylesheetCss();
    }

    public function clearCache(): void
    {
        $this->manager()->clearCssCache();
    }

    public function getFontsStylesheetFilename(): string
    {
        return $this->manager()->getFontsStylesheetFilename();
    }

    public function getFontsStylesheetCssUrl()
    {
        // Prefer package compile_assets from microweber config
        $this->manager()->setConfigValue(
            'compile_assets',
            (bool) Config::get('microweber.compile_assets')
        );

        return $this->manager()->getFontsStylesheetCssUrl();
    }
}
