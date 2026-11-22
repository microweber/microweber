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

    /**
     * The icon-set keys enabled by default when a template declares none.
     * Matches the historical hardcoded list that the templates' master.blade
     * used to load via mw.iconLoader().addIconSet(...).
     */
    public const DEFAULT_ENABLED = [
        'iconsMindLine',
        'iconsMindSolid',
        'fontAwesome',
        'materialDesignIcons',
    ];

    /**
     * The known icon sets, keyed by font_family. Each entry carries the CSS
     * url + the picker class/prefix. Mirror of the JS `common` registry in
     * frontend-assets/.../components/icon_selector.js — keep the two in sync.
     *
     * @return array<string, array<string, string>>
     */
    public function registry(): array
    {
        $basePath = site_url() . 'vendor/microweber-packages/frontend-assets-libs';

        return [
            'materialIcons' => [
                'name' => 'Material Icons',
                'font_family' => 'materialIcons',
                'url' => $basePath . '/material-icons/material-icons.css',
                'icon_class' => 'material-icons',
            ],
            'materialDesignIcons' => [
                'name' => 'Material Design Icons',
                'font_family' => 'materialDesignIcons',
                'url' => $basePath . '/mdi/css/materialdesignicons.min.css',
                'icon_class' => 'material-design-icons',
            ],
            'fontAwesome' => [
                'name' => 'Font Awesome',
                'font_family' => 'fontAwesome',
                'url' => $basePath . '/font-awesome/font-awesome.css',
                'icon_class' => 'fa',
                'icon_class_prefix' => 'fa-',
            ],
            'iconsMindLine' => [
                'name' => 'IconsMind Line',
                'font_family' => 'iconsMindLine',
                'url' => $basePath . '/mw-icons-mind/line/style.css',
                'icon_class' => 'mw-micon',
                'icon_class_prefix' => 'mw-micon-',
            ],
            'iconsMindSolid' => [
                'name' => 'IconsMind Solid',
                'font_family' => 'iconsMindSolid',
                'url' => $basePath . '/mw-icons-mind/solid/style.css',
                'icon_class' => 'mw-micon-solid',
                'icon_class_prefix' => 'mw-micon-solid-',
            ],
        ];
    }

    /**
     * Resolve the enabled icon-set keys from, in priority order:
     *   1. the `icon_sets` website option (set via set_option — lets a user
     *      enable extra sets at runtime),
     *   2. the active template's config.php `icon_sets` key,
     *   3. the DEFAULT_ENABLED list.
     * Each item may be a known font_family string, OR a full set definition
     * array (name/font_family/url/icon_class[/icon_class_prefix]) so a user can
     * load a custom icon set that is not in the registry.
     *
     * @return array<int, string|array<string, string>>
     */
    public function getEnabledIconSets(): array
    {
        $fromOption = $this->normalizeSetList(function_exists('get_option') ? get_option('icon_sets', 'template') : null);
        if ($fromOption) {
            return $fromOption;
        }

        if (function_exists('app') && app()->bound('template_manager')) {
            try {
                $config = app()->template_manager->get_config();
                if (is_array($config) && !empty($config['icon_sets'])) {
                    $fromConfig = $this->normalizeSetList($config['icon_sets']);
                    if ($fromConfig) {
                        return $fromConfig;
                    }
                }
            } catch (\Throwable $e) {
                // Fall through to defaults if the template config cannot be read.
            }
        }

        return self::DEFAULT_ENABLED;
    }

    /**
     * Coerce an option/config value into a list of set keys / defs.
     * Accepts an array, a JSON array string, or a comma-separated string.
     *
     * @return array<int, string|array<string, string>>
     */
    private function normalizeSetList($value): array
    {
        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return [];
            }
            $decoded = json_decode($trimmed, true);
            if (is_array($decoded)) {
                $value = $decoded;
            } else {
                $value = array_map('trim', explode(',', $trimmed));
            }
        }
        if (!is_array($value)) {
            return [];
        }
        $out = [];
        foreach ($value as $item) {
            if (is_array($item) && isset($item['url'])) {
                $out[] = $item;
            } elseif (is_string($item) && $item !== '') {
                $out[] = $item;
            }
        }
        return $out;
    }

    /**
     * The resolved, fully-expanded icon set definitions (url + classes) for the
     * active template. String keys are looked up in the registry; unknown keys
     * are skipped; inline array defs pass through. Used to build the cached CSS
     * stylesheet and to seed the icon picker.
     *
     * @return array<int, array<string, string>>
     */
    public function getIconSets(): array
    {
        $registry = $this->registry();
        $resolved = [];
        foreach ($this->getEnabledIconSets() as $item) {
            if (is_array($item)) {
                $resolved[] = $item;
            } elseif (isset($registry[$item])) {
                $resolved[] = $registry[$item];
            }
        }

        return $resolved;
    }

    /**
     * The enabled set keys (font_family names) for the icon picker to load.
     *
     * @return array<int, string>
     */
    public function getIconSetKeys(): array
    {
        $keys = [];
        foreach ($this->getIconSets() as $set) {
            if (isset($set['font_family'])) {
                $keys[] = $set['font_family'];
            }
        }

        return $keys;
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
