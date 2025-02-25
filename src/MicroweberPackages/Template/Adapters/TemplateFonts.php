<?php

namespace MicroweberPackages\Template\Adapters;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class TemplateFonts
{
    public function getFonts(): array
    {
        // Initialize with default system fonts and additional default fonts
        $defaultFonts = [
            'Arial, Helvetica, sans-serif',
            'Georgia, serif',
            'Times New Roman, serif',
            'Courier New, monospace',
            'Verdana, sans-serif',
            'Tahoma, sans-serif',
            'Trebuchet MS, sans-serif',
            'Impact, Charcoal, sans-serif',
            // Add more default fonts here
        ];

        $enabledCustomFonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();

        if (!empty($enabledCustomFonts)) {
            return array_unique(array_merge($defaultFonts, $enabledCustomFonts));
        }

        return $defaultFonts;
    }

    public function getFontsStylesheetCss(): string
    {
        $googleFontDomain = \MicroweberPackages\Utils\Misc\GoogleFonts::getDomain();
        $enabledCustomFonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();
        $output = [];

        if (!empty($enabledCustomFonts)) {
            foreach ($enabledCustomFonts as $font) {
                if ($font) {
                    $font = str_replace('%2B', '+', $font);
                    $fontUrl = urlencode($font);

                    $checkLocalFont = userfiles_path() . 'fonts' . DS . str_slug($font) . DS . 'font.css';
                    if (is_file($checkLocalFont)) {
                        $output[] = "@import url('" . userfiles_url() . 'fonts/' . str_slug($font) . '/font.css' . "');";
                        continue;
                    }

                    $output[] = "@import url(//{$googleFontDomain}/css?family={$fontUrl}:300italic,400italic,600italic,700italic,800italic,400,600,800,700,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic);";
                }
            }
        }

        return implode("\n", $output);
    }

    public function clearCache(): void
    {
        $userfiles_dir = userfiles_path();
        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
        $userfiles_cache_filename = $userfiles_cache_dir . $this->getFontsStylesheetFilename();
        if (is_file($userfiles_cache_filename)) {
            @unlink($userfiles_cache_filename);
        }

    }

    public function getFontsStylesheetFilename(): string
    {
        if (mw_is_multisite()) {
            $environment = app()->environment();
            $cache_file_name = 'custom_css.fonts.' . $environment . '.' . MW_VERSION . '.css';
        } else {
            $cache_file_name = 'custom_css.fonts.default.' . MW_VERSION . '.css';
        }
        return $cache_file_name;
    }

    public function getFontsStylesheetCssUrl()
    {

        $url = api_url('template/print_custom_css_fonts');
        if (in_live_edit() and is_admin()) {
            return $url;
        }


        $l = $this->getFontsStylesheetCss();
        $compile_assets = Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {

            $userfiles_dir = userfiles_path();
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);

            $userfiles_cache_filename = $userfiles_cache_dir . $this->getFontsStylesheetFilename();
            if (!is_file($userfiles_cache_filename)) {
                if (!is_dir($userfiles_cache_dir)) {
                    mkdir_recursive($userfiles_cache_dir);
                }

                if (is_dir($userfiles_cache_dir)) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            }
            return userfiles_url() . 'cache/' . $this->getFontsStylesheetFilename();
        } else {
            return $url;
        }
    }

}
