<?php


/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Template;

/**
 * Content class is used to get and save content in the database.
 *
 * @category Content
 * @desc     These functions will allow you to get and save content in the database.
 */
class TemplateManager
{
    /**
     * An instance of the Microweber Application class.
     *
     * @var
     */
    public $app;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = app();
            }
        }
    }


    /**
     * @desc      Get the template layouts info under the layouts subdir on your active template
     *
     * @param $options
     * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
     *
     * @return array
     *
     * @author    Microweber Dev Team
     *
     * @since     Version 1.0
     */
    public function site_templates($options = false)
    {
        $args = func_get_args();
        $function_cache_id = '';
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }
        $cache_id = __FUNCTION__ . crc32($function_cache_id);
        $cache_group = 'templates';
        $cache_content = false;
        //  $cache_content = $this->app->cache_manager->get($cache_id, $cache_group);
        if (($cache_content) != false) {
            // return $cache_content;
        }
        if (!isset($options['path'])) {
            $path = templates_path();
        } else {
            $path = $options['path'];
        }

        $path_to_layouts = $path;
        $layout_path = $path;
        $map = directory_map($path, true, true);
        $to_return = array();
        if (!is_array($map) or empty($map)) {
            return false;
        }

        $remove_hidden_from_install_screen = false;
        if (isset($options['remove_hidden_from_install_screen']) and $options['remove_hidden_from_install_screen']) {
            $remove_hidden_from_install_screen = true;

        }

        foreach ($map as $dir) {
            //$filename = $path . $dir . DIRECTORY_SEPARATOR . 'layout.php';
            $filename = $path . DIRECTORY_SEPARATOR . $dir;
            $filename_location = false;
            $filename_dir = false;
            $filename = normalize_path($filename);
            $filename = rtrim($filename, '\\');
            $filename = (substr($filename, 0, 1) === '.' ? substr($filename, 1) : $filename);
            if (!@is_file($filename) and @is_dir($filename)) {
                $skip = false;
                $fn1 = normalize_path($filename, true) . 'config.php';
                $fn2 = normalize_path($filename);
                if (is_file($fn1)) {
                    $config = false;
                    include $fn1;
                    if (!empty($config)) {
                        $c = $config;
                        $c['dir_name'] = $dir;
                        $screensshot_file = $fn2 . '/screenshot.jpg';
                        $screensshot_file = normalize_path($screensshot_file, false);

                        $screensshot_file_png = $fn2 . '/screenshot.png';
                        $screensshot_file_png = normalize_path($screensshot_file_png, false);

                        if (is_file($screensshot_file)) {
                            $c['screenshot'] = $this->app->url_manager->link_to_file($screensshot_file);
                        } elseif (is_file($screensshot_file_png)) {
                            $c['screenshot'] = $this->app->url_manager->link_to_file($screensshot_file_png);
                        }

                        if ($remove_hidden_from_install_screen) {
                            if (isset($c['is_hidden_from_install_screen']) and $c['is_hidden_from_install_screen']) {
                                $skip = true;
                            }
                        }

                        if (!$skip) {
                            $to_return[] = $c;
                        }
                    }
                } else {
                    $filename_dir = false;
                }
                //	$path = $filename;
            }
        }

        //$this->app->cache_manager->save($to_return, $cache_id, $cache_group, 'files');
        return $to_return;
    }




    public $isBooted = false;

    public function boot_template()
    {
        if ($this->isBooted) {
            return;
        }
        $this->isBooted = true;

        $load_template_functions = TEMPLATE_DIR . 'functions.php';
        if (is_file($load_template_functions)) {
            include_once $load_template_functions;
        }

        $module = app()->template->get_config();

        if (isset($module['settings']) and $module['settings'] and isset($module['settings']['service_provider']) and $module['settings']['service_provider']) {

            $loadProviders = [];
            if (is_array($module['settings']['service_provider'])) {
                foreach ($module['settings']['service_provider'] as $serviceProvider) {
                    $loadProviders[] = $serviceProvider;
                }
            } else {
                $loadProviders[] = $module['settings']['service_provider'];
            }
            foreach ($loadProviders as $loadProvider) {
                if (class_exists($loadProvider)) {
                    app()->register($loadProvider);
                }
            }
        }
    }
}
