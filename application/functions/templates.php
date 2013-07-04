<?php

$mw_template_headers = array();
function template_header($script_src)
{
    global $mw_template_headers;

    if (is_string($script_src)) {
        if (!in_array($script_src, $mw_template_headers)) {
            $mw_template_headers[] = $script_src;
            return $mw_template_headers;
        }
    } else if (is_bool($script_src)) {
        return $mw_template_headers;

    }
}

function template_headers_src()
{
    $src = '';
    $headers = template_header(true);
    if (isarr($headers)) {
        foreach ($headers as $header) {
            $ext = get_file_extension($header);
            switch (strtolower($ext)) {


                case 'css':
                    $src .= '<link rel="stylesheet" href="' . $header . '" type="text/css" media="all">' . "\n";
                    break;

                case 'js':
                    $src .= '<script type="text/javascript" src="' . $header . '"></script>' . "\n";
                    break;


                default:
                    $src .=   $header   . "\n";
                    break;
            }
        }
    }
    return $src;
}

/**
 * @desc  Get the template layouts info under the layouts subdir on your active template
 * @param $options
 * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
 * @return array
 * @author    Microweber Dev Team
 * @since Version 1.0
 */
function templates_list($options = false)
{

    $args = func_get_args();
    $function_cache_id = '';
    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_group = 'templates';

    $cache_content = cache_get_content($cache_id, $cache_group, 'files');

    if (($cache_content) != false) {

        return $cache_content;
    }

    $path = TEMPLATEFILES;
    $path_to_layouts = $path;
    $layout_path = $path;
    //	print $path;
    //exit;
    //$map = directory_map ( $path, TRUE );
    $map = directory_map($path, TRUE, TRUE);

    $to_return = array();

    foreach ($map as $dir) {

        //$filename = $path . $dir . DIRECTORY_SEPARATOR . 'layout.php';
        $filename = $path . DIRECTORY_SEPARATOR . $dir;
        $filename_location = false;
        $filename_dir = false;
        $filename = normalize_path($filename);
        $filename = rtrim($filename, '\\');
        //p ( $filename );
        if (is_dir($filename)) {
            //
            $fn1 = normalize_path($filename, true) . 'config.php';
            $fn2 = normalize_path($filename);

            //  p ( $fn1 );

            if (is_file($fn1)) {
                $config = false;

                include ($fn1);
                if (!empty($config)) {
                    $c = $config;
                    $c['dir_name'] = $dir;

                    $screensshot_file = $fn2 . '/screenshot.png';
                    $screensshot_file = normalize_path($screensshot_file, false);
                    //p($screensshot_file);
                    if (is_file($screensshot_file)) {
                        $c['screenshot'] = pathToURL($screensshot_file);
                    }

                    $to_return[] = $c;
                }
            } else {
                $filename_dir = false;
            }

            //	$path = $filename;
        }

        //p($filename);
    }
    cache_save($to_return, $function_cache_id, $cache_group, 'files');

    return $to_return;
}

function layout_link($options = false)
{
    $args = func_get_args();
    $function_cache_id = '';
    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $cache_id = __FUNCTION__ . crc32($function_cache_id);
    //get cache from memory
    $mem = mw_var($cache_id);
    if ($mem != false) {

        return $mem;
    }

    $options = parse_params($options);
    $fn = false;

    if (isset($options[0])) {
        $fn = $options[0];
    } elseif (isarr($options)) {
        $val = current($options);
        $fn = key($options);
    }

    $page_url_segment_1 = url_segment(0);
    $td = TEMPLATEFILES . $page_url_segment_1;
    $td_base = $td;

    $page_url_segment_2 = url_segment(1);
    $directly_to_file = false;
    $page_url_segment_3 = url_segment();

    if (!is_dir($td_base)) {
        array_shift($page_url_segment_3);
        //$page_url_segment_1 =	$the_active_site_template = get_option('curent_template');
        //$td_base = TEMPLATEFILES .  $the_active_site_template.DS;
    } else {

    }
    if (empty($page_url_segment_3)) {
        $page_url_segment_str = '';
    } else {
        $page_url_segment_str = $page_url_segment_3[0];
    }
    //$page_url_segment_str = implode('/', $page_url_segment_3);
    $fn = site_url($page_url_segment_str . '/' . $fn);
    //d($page_url_segment_3);

    //set cache in memory
    mw_var($cache_id, $fn);

    return $fn;
}


/**
 * Lists the layout files from a given directory
 *
 * You can use this function to get layouts from various folders in your web server.
 * It returns array of layouts with desctption, icon, etc
 *
 * This function caches the result in the 'templates' cache group
 *
 * @param bool|array|string $options
 * @return array|mixed
 *
 * @params $options['path'] if set i will look for layouts in this folder
 * @params $options['get_dynamic_layouts'] if set this function will scan for templates for the 'layout' module in all templates folders
 *
 *
 *
 *
 *
 */
function layouts_list($options = false)
{
    $options = parse_params($options);
    if (!isset($options['path'])) {
        if (isset($options['site_template']) and (strtolower($options['site_template']) != 'default') and (trim($options['site_template']) != '')) {
            $tmpl = trim($options['site_template']);
            $check_dir = TEMPLATEFILES . '' . $tmpl;
            if (is_dir($check_dir)) {
                $the_active_site_template = $tmpl;
            } else {
                $the_active_site_template = get_option('curent_template');
            }
        } else {
            $the_active_site_template = get_option('curent_template');
        }
        $path = normalize_path(TEMPLATEFILES . $the_active_site_template);
    } else {
        $path = $options['path'];
    }
    if (isset($the_active_site_template) and trim($the_active_site_template) != 'default') {
        if (!isset($path) or $path == false or (!strstr($path, DEFAULT_TEMPLATE_DIR))) {
            $use_default_layouts = $path . 'use_default_layouts.php';
            if (is_file($use_default_layouts)) {
                $path = DEFAULT_TEMPLATE_DIR;
            }
        }

    }

    if (!isset($options['no_cache'])) {
        $args = func_get_args();
        $function_cache_id = '';
        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $cache_id = $function_cache_id = __FUNCTION__ . crc32($path . $function_cache_id);

        $cache_group = 'templates';

        $cache_content = cache_get_content($cache_id, $cache_group, 'files');

        if (($cache_content) != false) {

            return $cache_content;
        }
    }

    $glob_patern = "*.php";
    $template_dirs = array();
    if (isset($options['get_dynamic_layouts'])) {

        $_dirs = glob(TEMPLATEFILES . '*', GLOB_ONLYDIR);
        $dir = array();
        foreach ($_dirs as $item) {
            $possible_dir = $item . DS . 'modules' . DS . 'layout' . DS;

            if (is_dir($possible_dir)) {
                $template_dirs[] = $item;
                $dir2 = rglob($possible_dir . '*.php', 0);
                // d($dir2);
                if (!empty($dir2)) {
                    foreach ($dir2 as $dir_glob) {
                        $dir[] = $dir_glob;
                    }
                }
            }
        }


        // d($dir);
        //  return $dir;
    }


    if (!isset($options['get_dynamic_layouts'])) {
        if (!isset($options['filename'])) {
            $dir = rglob($glob_patern, 0, $path);
        } else {
            $dir = array();
            $dir[] = $options['filename'];
        }
    } else {

    }


    $configs = array();
    if (!empty($dir)) {

        foreach ($dir as $filename) {
            $skip = false;
            if (!isset($options['get_dynamic_layouts'])) {
                if (!isset($options['for_modules'])) {
                    if (strstr($filename, 'modules' . DS)) {
                        $skip = true;
                    }
                } else {
                    if (!strstr($filename, 'modules' . DS)) {
                        $skip = true;
                    }
                }
            }
            if ($skip == false) {
                $fin = file_get_contents($filename);
                $here_dir = dirname($filename) . DS;
                $to_return_temp = array();
                if (preg_match('/type:.+/', $fin, $regs)) {

                    $result = $regs[0];
                    $result = str_ireplace('type:', '', $result);
                    $to_return_temp['type'] = trim($result);
                    $to_return_temp['directory'] = $here_dir;

                    $templ_dir = str_replace(TEMPLATEFILES, '', $here_dir);
                    if ($templ_dir != '') {
                        $templ_dir = explode(DS, $templ_dir);
                        //d($templ_dir);
                        if (isset($templ_dir[0])) {
                            $to_return_temp['template_dir'] = $templ_dir[0];

                        }

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


                        if (preg_match('/version:.+/', $fin, $regs)) {
                            $result = $regs[0];
                            $result = str_ireplace('version:', '', $result);
                            $to_return_temp['version'] = trim($result);
                        }


                        if (preg_match('/icon:.+/', $fin, $regs)) {
                            $result = $regs[0];
                            $result = str_ireplace('icon:', '', $result);
                            $to_return_temp['icon'] = trim($result);

                            $possible = $here_dir . $to_return_temp['icon'];
                            if (is_file($possible)) {
                                $to_return_temp['icon'] = dir2url($possible);
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
                                $to_return_temp['image'] = dir2url($possible);
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

                        $layout_file = str_replace($path, '', $filename);
                        $layout_file = str_replace(TEMPLATEFILES, '', $filename);


                      // d(  $layout_file);
                        $layout_file = str_replace(DS, '/', $layout_file);
                        $to_return_temp['layout_file'] = $layout_file;
                        $to_return_temp['filename'] = $filename;
                        $screen = str_ireplace('.php', '.png', $filename);
                        if (is_file($screen)) {
                            $to_return_temp['screenshot'] = $screen;
                        }

                        $configs[] = $to_return_temp;
                    }
                }
            }
        }

        if (!empty($configs)) {
            if (!isset($options['no_cache'])) {
                cache_save($configs, $function_cache_id, $cache_group, 'files');
            }
            return $configs;
        } else {
            //cache_save(false, $function_cache_id, $cache_group);
        }
    } else {
        //cache_save(false, $function_cache_id, $cache_group);
    }
}

function template_var($key, $new_val = false)
{
    static $defined = array();
    $contstant = ($key);
    if ($new_val == false) {
        if (isset($defined[$contstant]) != false) {
            return $defined[$contstant];
        } else {
            return false;
        }
    } else {
        if (isset($defined[$contstant]) == false) {
            $defined[$contstant] = $new_val;
            return $new_val;
        }
    }
    return false;
}
