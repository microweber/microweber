<?php

namespace MicroweberPackages\Template\Adapters;

class TemplateCustomCss
{

    public function getCustomCssUrl()
    {
        $content = $this->getCustomCssContent();

        if (trim($content) == '') {
            return false;
        }

        $url = api_nosession_url('template/print_custom_css');
        if (in_live_edit() and is_admin()) {
            return $url;
        }

        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
            $userfiles_cache_filename = $userfiles_cache_dir . 'custom_css.' . crc32(site_url()) . '.' . MW_VERSION . '.css';
            if (is_file($userfiles_cache_filename)) {
                $custom_live_editmtime = filemtime($userfiles_cache_filename);
                $url = userfiles_url() . 'cache/' . 'custom_css.' . crc32(site_url()) . '.' . MW_VERSION . '.css?ver=' . $custom_live_editmtime;
            }
        }

        return $url;
    }
    public function clearCache()
    {
        $userfiles_dir = userfiles_path();
        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
        $userfiles_cache_filename = $userfiles_cache_dir . 'custom_css.' . crc32(site_url()) . '.' . MW_VERSION . '.css';
        if (is_file($userfiles_cache_filename)) {
            @unlink($userfiles_cache_filename);
        }

    }
    public function getCustomCss()
    {


        $l = $this->getCustomCssContent();
        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
            $userfiles_cache_filename = $userfiles_cache_dir . 'custom_css.' . crc32(site_url()) . '.' . MW_VERSION . '.css';
            if (!is_file($userfiles_cache_filename)) {
                if (!is_dir($userfiles_cache_dir)) {
                    mkdir_recursive($userfiles_cache_dir);
                }

                if (is_dir($userfiles_cache_dir)) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            }
        }

        return $l;
    }

    public function getCustomCssContent()
    {
        ob_start();

        event_trigger('mw.template.print_custom_css_includes');
//  moved to class  TemplateFonts 
//        $fonts_file = modules_path() . 'editor' . DS . 'fonts' . DS . 'stylesheet.php';
//        if (is_file($fonts_file)) {
//            include $fonts_file;
//        }
        $custom_css = get_option('custom_css', 'template');
        if (is_string($custom_css)) {
            echo $custom_css;
        }

        event_trigger('mw.template.print_custom_css');

        $output = ob_get_contents();
        ob_end_clean();


        return $output;
    }
}
