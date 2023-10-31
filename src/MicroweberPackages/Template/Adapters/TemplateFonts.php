<?php

namespace MicroweberPackages\Template\Adapters;

class TemplateFonts
{
    public function getFonts() : array
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

    public function getFontsStylesheetCss() : string
    {
        $google_font_domain = \MicroweberPackages\Utils\Misc\GoogleFonts::getDomain();
        $enabled_custom_fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();
        $output = [];
        if (!empty($enabled_custom_fonts)) {
            foreach ($enabled_custom_fonts as $font) {
                if ($font) {
                    $font = str_replace('%2B', '+', $font);
                    $font_url = urlencode($font);
                    $output[] = "@import url(//{$google_font_domain}/css?family={$font_url}:300italic,400italic,600italic,700italic,800italic,400,600,800,700,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic);";
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
        $cache_file_name = 'custom_css.fonts.' . crc32(site_url()) . '.' . MW_VERSION . '.css';
        return $cache_file_name;
    }

    public function getFontsStylesheetCssUrl()
    {

        $url = api_nosession_url('template/print_custom_css_fonts');
        if (in_live_edit() and is_admin()) {
            return $url;
        }


        $l = $this->getFontsStylesheetCss();
        $compile_assets = \Config::get('microweber.compile_assets');
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
