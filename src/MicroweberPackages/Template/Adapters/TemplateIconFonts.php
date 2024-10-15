<?php

namespace MicroweberPackages\Template\Adapters;

use Illuminate\Support\Facades\App;

class TemplateIconFonts
{

    public array $icons = [

    ];

    public function addIconSet($data): array
    {
        $this->icons[] = $data;
        return $this->icons;
    }

    public function getIconSets(): array
    {
        $basePath = site_url() . '/vendor/microweber-packages/frontend-assets-libs';

        $defaultFonts = [
            [
                'name' => 'Material Icons',
                'font_family' => 'materialIcons',
                'url' => $basePath . '/material-icons/material-icons.css',
                'icon_class' => 'material-icons',
            ],
         [
                'name' => 'Material Design Icons',
                'font_family' => 'materialDesignIcons',
                'url' =>  $basePath . '/mdi/css/materialdesignicons.min.css',
                'icon_class' => 'material-design-icons',
            ],


            [
                'name' => 'Font Awesome',
                'font_family' => 'fontAwesome',
                'url' =>  $basePath . '/font-awesome/font-awesome.css',
                'icon_class' => 'fa',
                'icon_class_prefix' => 'fa-',
            ],

            [
                'name' => 'IconsMind Line',
                'font_family' => 'iconsMindLine',
                'url' =>  $basePath . '/mw-icons-mind/line/style.css',
                'icon_class' => 'mw-micon',
                'icon_class_prefix' => 'mw-micon-',
            ],

            [
                'name' => 'IconsMind Solid',
                'font_family' => 'iconsMindSolid',

                'url' =>  $basePath . '/mw-icons-mind/solid/style.css',
                'icon_class' => 'mw-micon-solid',
                'icon_class_prefix' => 'mw-micon-solid-',
            ],

        ];


        //$enabledCustomFonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();


        return $defaultFonts;
    }

    public function getIconFontsStylesheetUrl(): string
    {

        $icons = $this->getIconSets();
        if (!$icons) {
            return '';
        }

        $filename = $this->getFontsStylesheetFilename();
        $savePath = userfiles_path() . 'cache' . DS;

        if (!is_dir($savePath)) {
            mkdir_recursive($savePath);
        }

        if (!is_file($savePath . $filename)) {
            $content = $this->getIconFontsStylesheetContent();

            if ($content) {
                file_put_contents($savePath . $filename, $content);
            }
        }



        return  userfiles_url() . 'cache/' . $filename;
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
        $icons = $this->getIconSets();
        $hash = '';
        if ($icons) {
            $hash = 'iconFontsHash' . crc32(json_encode($icons)).is_https();
        }

        if (mw_is_multisite()) {
            $environment = app()->environment();
            $cache_file_name = 'icon_fonts_css.fonts.' . $environment . '.' . $hash . MW_VERSION . '.css';
        } else {
            $cache_file_name = 'icon_fonts_css.fonts.default.' . $hash . MW_VERSION . '.css';
        }
        return $cache_file_name;
    }

    public function getIconFontsStylesheetContent()
    {

        $icons = $this->getIconSets();
        if (!$icons) {
            return '';
        }
        $output = [];
        foreach ($icons as $icon) {
            if (isset($icon['url'])) {
                $output[] = "@import url('" . $icon['url'] . "');";
            }
        }

        return implode("\n", $output);

    }

}
