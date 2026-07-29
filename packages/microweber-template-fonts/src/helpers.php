<?php

declare(strict_types=1);

use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

if (!function_exists('template_fonts')) {
    /**
     * Resolve the TemplateFontsManager singleton.
     */
    function template_fonts(): TemplateFontsManager
    {
        return app(TemplateFontsManager::class);
    }
}

if (!function_exists('template_fonts_enabled')) {
    /**
     * @return list<string>
     */
    function template_fonts_enabled(): array
    {
        return template_fonts()->getEnabledFonts();
    }
}

if (!function_exists('template_fonts_stylesheet_css')) {
    function template_fonts_stylesheet_css(): string
    {
        return template_fonts()->getFontsStylesheetCss();
    }
}

if (!function_exists('template_fonts_stylesheet_url')) {
    function template_fonts_stylesheet_url(): string
    {
        return template_fonts()->getFontsStylesheetCssUrl();
    }
}
