<?php




/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */

namespace Microweber\Providers;

use Microweber\Utils\Adapters\Template\MicroweberTemplate;


/**
 * Content class is used to get and save content in the database.
 *
 * @package Content
 * @category Content
 * @desc  These functions will allow you to get and save content in the database.
 *
 */
class Template
{

    /**
     * An instance of the Microweber Application class
     *
     * @var $app
     */
    public $app;
    public $head = array();
    public $head_callable = array();
    public $foot = array();
    public $foot_callable = array();


    public $adapter_current = null;
    public $adapter_default = null;


    function __construct($app = null)
    {

        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }


        $this->adapter_current = $this->adapter_default = new MicroweberTemplate($app);

    }

    public function dir($add = false)
    {
        if (!defined('TEMPLATE_DIR')) {
            $this->app->content_manager->define_constants();
        }
        if (defined('TEMPLATE_DIR')) {
            $val = TEMPLATE_DIR;
        }
        if ($add != false) {
            $val = $val . $add;
        }
        return $val;
    }


    public function url($add = false)
    {
        if (!defined('TEMPLATE_URL')) {
            $this->app->content_manager->define_constants();
        }
        if (defined('TEMPLATE_URL')) {
            $val = TEMPLATE_URL;
        }

        if ($add != false) {
            $val = $val . $add;
        }

        return $val;
    }

    public function adapter($method, $params)
    {
        if (method_exists($this->adapter_current, $method)) {
            return $this->adapter_current->$method($params);
        } else if (method_exists($this->adapter_default, $method)) {
            return $this->adapter_default->$method($params);
        }
    }

    public function render($params = array())
    {
        return $this->adapter('render', $params);
    }

    public function get_custom_css()
    {
        ob_start();


        event_trigger('mw.template.print_custom_css_includes');


        $fonts_file = modules_path() . 'editor' . DS . 'fonts' . DS . 'stylesheet.php';
        if (is_file($fonts_file)) {
            include($fonts_file);
        }
        $custom_css = get_option("custom_css", "template");
        if (is_string($custom_css)) {
            print $custom_css;
        }


        event_trigger('mw.template.print_custom_css');

        $output = ob_get_contents();
        ob_end_clean();
        return $output;

    }


    public function name()
    {

        if (!defined('TEMPLATE_NAME')) {
            $this->app->content_manager->define_constants();
        }
        if (defined('TEMPLATE_NAME')) {
            return TEMPLATE_NAME;
        }
    }

    public function admin_head($script_src)
    {
        static $mw_template_headers;
        if ($mw_template_headers == null) {
            $mw_template_headers = array();
        }

        if (is_string($script_src)) {
            if (!in_array($script_src, $mw_template_headers)) {
                $mw_template_headers[] = $script_src;
                return $mw_template_headers;
            }
        } else if (is_bool($script_src)) {
            //   return $mw_template_headers;
            $src = '';
            if (is_array($mw_template_headers)) {
                foreach ($mw_template_headers as $header) {
                    $ext = get_file_extension($header);
                    switch (strtolower($ext)) {


                        case 'css':
                            $src .= '<link rel="stylesheet" href="' . $header . '" type="text/css" media="all">' . "\n";
                            break;

                        case 'js':
                            $src .= '<script type="text/javascript" src="' . $header . '"></script>' . "\n";
                            break;


                        default:
                            $src .= $header . "\n";
                            break;
                    }
                }
            }
            return $src;
        }
    }


    public function head($script_src)
    {


        if ($this->head_callable == null) {
            $this->head_callable = array();
        }

        if (is_string($script_src)) {
            if (!in_array($script_src, $this->head)) {
                $this->head[] = $script_src;
                return $this->head;
            }
        } else if (is_bool($script_src)) {
            //   return $this->head;
            $src = '';

            if (is_array($this->head)) {
                foreach ($this->head as $header) {
                    $ext = get_file_extension($header);
                    switch (strtolower($ext)) {


                        case 'css':
                            $src .= '<link rel="stylesheet" href="' . $header . '" type="text/css" media="all">' . "\n";
                            break;

                        case 'js':
                            $src .= '<script type="text/javascript" src="' . $header . '"></script>' . "\n";
                            break;


                        default:
                            $src .= $header . "\n";
                            break;
                    }
                }
            }

            return $src;
        } elseif (is_callable($script_src)) {
            if (!in_array($script_src, $this->head_callable)) {
                $this->head_callable[] = $script_src;
                return $this->head_callable;
            }
        }
    }


    public function head_callback($data = false)
    {
        $data = array();
        if (!empty($this->head_callable)) {
            foreach ($this->head_callable as $callback) {
                $data[] = call_user_func($callback, $data);
            }
        }
        return $data;
    }


    public function foot($script_src)
    {


        if ($this->foot_callable == null) {
            $this->foot_callable = array();
        }

        if (is_string($script_src)) {
            if (!in_array($script_src, $this->foot)) {
                $this->foot[] = $script_src;
                return $this->foot;
            }
        } else if (is_bool($script_src)) {
            //   return $this->foot;
            $src = '';

            if (is_array($this->foot)) {
                foreach ($this->foot as $footer) {
                    $ext = get_file_extension($footer);
                    switch (strtolower($ext)) {


                        case 'css':
                            $src .= '<link rel="stylesheet" href="' . $footer . '" type="text/css" media="all">' . "\n";
                            break;

                        case 'js':
                            $src .= '<script type="text/javascript" src="' . $footer . '"></script>' . "\n";
                            break;


                        default:
                            $src .= $footer . "\n";
                            break;
                    }
                }
            }

            return $src;
        } elseif (is_callable($script_src)) {
            if (!in_array($script_src, $this->foot_callable)) {
                $this->foot_callable[] = $script_src;
                return $this->foot_callable;
            }
        }
    }

    /**
     * @desc  Get the template layouts info under the layouts subdir on your active template
     * @param $options
     * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
     * @return array
     * @author    Microweber Dev Team
     * @since Version 1.0
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
        $map = $this->directory_map($path, TRUE, TRUE);
        $to_return = array();
        if (!is_array($map) or empty($map)) {
            return false;
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
                $fn1 = normalize_path($filename, true) . 'config.php';
                $fn2 = normalize_path($filename);
                if (is_file($fn1)) {
                    $config = false;
                    include($fn1);
                    if (!empty($config)) {
                        $c = $config;
                        $c['dir_name'] = $dir;
                        $screensshot_file = $fn2 . '/screenshot.png';
                        $screensshot_file = normalize_path($screensshot_file, false);
                        if (is_file($screensshot_file)) {
                            $c['screenshot'] = $this->app->url_manager->link_to_file($screensshot_file);
                        }
                        $to_return[] = $c;
                    }
                } else {
                    $filename_dir = false;
                }
                //	$path = $filename;
            }

        }
        $this->app->cache_manager->save($to_return, $cache_id, $cache_group, 'files');
        return $to_return;
    }

    /**
     * Create a Directory Map
     *
     *
     * Reads the specified directory and builds an array
     * representation of it.  Sub-folders contained with the
     * directory will be mapped as well.
     *
     * @author        ExpressionEngine Dev Team
     * @link        http://codeigniter.com/user_guide/helpers/directory_helper.html
     * @access    public
     * @param    string    path to source
     * @param    int        depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
     * @return    array
     */
    function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE, $full_path = false)
    {
        if ($fp = @opendir($source_dir)) {
            $filedata = array();
            $new_depth = $directory_depth - 1;
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp))) {
                // Remove '.', '..', and hidden files [optional]
                if (!trim($file, '.') OR ($hidden == FALSE && $file[0] == '.')) {
                    continue;
                }

                if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir . $file)) {
                    $filedata[$file] = $this->directory_map($source_dir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden, $full_path);
                } else {
                    if ($full_path == false) {
                        $filedata[] = $file;
                    } else {
                        $filedata[] = $source_dir . $file;
                    }

                }
            }

            closedir($fp);
            return $filedata;
        }

        return FALSE;
    }


    /**
     * Return the path to the layout file that will render the page
     *
     */
    public function get_layout($params = array())
    {
        return $this->adapter('get_layout', $params);
    }
}