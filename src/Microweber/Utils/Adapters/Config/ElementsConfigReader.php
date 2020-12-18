<?php

namespace Microweber\Utils\Adapters\Config;


class ElementsConfigReader
{
    /** @var \Microweber\Application */
    public $app;

    public function __construct($app = null)
    {

        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $this->app = $app;
    }

    public function scan($folder)
    {
        $glob_patern = '*.php';
        $dir = rglob($folder . '*.php', 0);
        if ($dir) {
            $configs = array();
            foreach ($dir as $filename) {
                $read = $this->read($filename);
                if ($read) {
                    $configs[] = $this->read($filename);
                }
            }
            return $configs;
        }
    }

    public function read($filename)
    {


        if (!is_file($filename)) {
            return;
        }

        $loc_of_config = str_ireplace('.php', '_config.php', $filename);

        $template_dirs = array();
        $template_dirs[] = modules_path();
        if (defined('TEMPLATE_DIR')) {
            $template_dirs[] = TEMPLATE_DIR;
        }
        $config_ready = array();

        $layout_file = $filename;

        if (isset($template_dirs) and !empty($template_dirs)) {
            foreach ($template_dirs as $template_dir) {
                $layout_file = str_replace($template_dir, '', $layout_file);

                $layout_file = str_replace(normalize_path($template_dir), '', $layout_file);
            }
        }

        if (defined('TEMPLATE_DIR')) {
            $layout_file = substr(normalize_path($layout_file , false), strpos(normalize_path($layout_file,false), TEMPLATE_DIR),strlen($layout_file) );

        }

        $layout_file = substr($layout_file, strpos($layout_file, "templates"),strlen($layout_file) );

        $layout_file = str_replace(DS, '/', $layout_file);

        if (is_file($loc_of_config)) {
            include $loc_of_config;
            if (isset($config) and is_array($config) and isset($config['as_element']) and $config['as_element'] == true) {


                $config_ready = $config;
                $config_ready['layout_file'] = $layout_file;
                $config_ready['module'] = 'element-from-template';
                $config_ready['template'] = $layout_file;
                $config_ready['filename'] = $filename;

                $config_ready['type'] = 'layout';
                $config_ready['as_element'] = true;


            }
        } else {
            $fin = file_get_contents($filename);
            $fin = preg_replace('/\r\n?/', "\n", $fin);

            $here_dir = dirname($filename) . DS;
            $to_return_temp = array();
            if (preg_match('/type:.+/', $fin, $regs)) {
                $result = $regs[0];
                $result = str_ireplace('type:', '', $result);
                $to_return_temp['type'] = trim($result);
                $to_return_temp['directory'] = $here_dir;
                if (strstr($here_dir, templates_path())) {
                    $templ_dir = str_replace(templates_path(), '', $here_dir);
                    if ($templ_dir != '') {
                        $templ_dir = explode(DS, $templ_dir);
                        if (isset($templ_dir[0])) {
                            $to_return_temp['template_dir'] = $templ_dir[0];
                        }
                    }
                }
                if (strstr($here_dir, modules_path())) {
                    $templ_dir1 = str_replace(modules_path(), '', $here_dir);

                    $templ_dir1 = str_ireplace('templates', '', $templ_dir1);
                    $templ_dir1 = str_ireplace('\\', '/', $templ_dir1);
                    $templ_dir1 = str_ireplace('//', '/', $templ_dir1);
                    $templ_dir1 = rtrim($templ_dir1, '/\\');

                    $to_return_temp['module_directory'] = $templ_dir1;
                }

                if (strtolower($to_return_temp['type']) == 'layout') {
                    $to_return_temp['directory'] = $here_dir;

                    if (preg_match('/is_shop:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('is_shop:', '', $result);
                        $to_return_temp['is_shop'] = trim($result);
                    }

                    if (preg_match('/name:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('name:', '', $result);
                        $to_return_temp['name'] = trim($result);
                    }

                    if (preg_match('/is_default:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('is_default:', '', $result);
                        $to_return_temp['is_default'] = trim($result);
                    }

                    if (preg_match('/position:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('position:', '', $result);
                        $to_return_temp['position'] = intval($result);
                    } else {
                        $to_return_temp['position'] = 99999;
                    }

                    if (preg_match('/version:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('version:', '', $result);
                        $to_return_temp['version'] = trim($result);
                    }
                    if (preg_match('/visible:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('visible:', '', $result);
                        $to_return_temp['visible'] = trim($result);
                    }

                    if (preg_match('/icon:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('icon:', '', $result);
                        $to_return_temp['icon'] = trim($result);

                        $possible = $here_dir . $to_return_temp['icon'];
                        if (is_file($possible)) {
                            $to_return_temp['icon'] = mw()->url_manager->link_to_file($possible);
                        } else {
                            unset($to_return_temp['icon']);
                        }
                    }

                    if (preg_match('/image:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('image:', '', $result);
                        $to_return_temp['image'] = trim($result);
                        $possible = $here_dir . $to_return_temp['image'];

                        if (is_file($possible)) {
                            $to_return_temp['image'] = mw()->url_manager->link_to_file($possible);
                        } else {
                            unset($to_return_temp['image']);
                        }
                    }

                    if (preg_match('/description:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('description:', '', $result);
                        $to_return_temp['description'] = trim($result);
                    }

                    if (preg_match('/content_type:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('content_type:', '', $result);
                        $to_return_temp['content_type'] = trim($result);
                    }

                    if (preg_match('/tag:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('tag:', '', $result);
                        $to_return_temp['tag'] = trim($result);
                    }
                    //    $layout_file = $filename;

                    //  $layout_file = str_replace(DS, '/', $layout_file);
                    $to_return_temp['layout_file'] = $layout_file;
                    $to_return_temp['filename'] = $filename;


                    $config_ready = $to_return_temp;
                }
            }
        }


        if (isset($config_ready['filename'])) {
            $screen = str_ireplace('.php', '.png', $config_ready['filename']);
            $screen_jpg = str_ireplace('.php', '.jpg', $config_ready['filename']);
            if (is_file($screen_jpg)) {
                $config_ready['screenshot_file'] = $screen_jpg;
            } elseif (is_file($screen)) {
                $config_ready['screenshot_file'] = $screen;
            }
            if (isset($config_ready['screenshot_file'])) {
                $config_ready['screenshot'] = mw()->url_manager->link_to_file($config_ready['screenshot_file']);
                $config_ready['icon'] = mw()->url_manager->link_to_file($config_ready['screenshot_file']);

            }
        }

        if ($config_ready) {
            return $config_ready;
        }

    }


}
