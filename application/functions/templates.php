<?

/**
 * @desc  Get the template layouts info under the layouts subdir on your active template
 * @param $options
 * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
 * @return array
 * @author	Microweber Dev Team
 * @since Version 1.0
 */
function templates_list($options = false) {

    $args = func_get_args();
    $function_cache_id = '';
    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_group = 'templates';

    $cache_content = cache_get_content($cache_id, $cache_group);

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
    cache_store_data($to_return, $function_cache_id, $cache_group);

    return $to_return;
}

function layouts_list($options = false) {
    if (!isset($options['no_cache'])) {
        $args = func_get_args();
        $function_cache_id = '';
        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

        $cache_group = 'templates';

        $cache_content = cache_get_content($cache_id, $cache_group);

        if (($cache_content) != false) {

            return $cache_content;
        }
    }
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
    $glob_patern = "*.php";

    $dir = rglob($glob_patern, 0, $path);
    $configs = array();
    if (!empty($dir)) {

        foreach ($dir as $filename) {

            $fin = file_get_contents($filename);

            if (preg_match('/type:.+/', $fin, $regs)) {

                $result = $regs[0];
                $result = str_ireplace('type:', '', $result);
                $to_return_temp['type'] = trim($result);

                if (strtolower($to_return_temp['type']) == 'layout') {
                    $to_return_temp = array();

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
					
					
					  if (preg_match('/content_type:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('content_type:', '', $result);
                        $to_return_temp['content_type'] = trim($result);
                    }
					
					
					
                    $layout_file = str_replace($path, '', $filename);
                    $to_return_temp['layout_file'] = $layout_file;

                    $screen = str_ireplace('.php', '.png', $filename);
                    if (is_file($screen)) {
                        $to_return_temp['screenshot'] = $screen;
                    }

                    $configs[] = $to_return_temp;
                }
            }
        }

        if (!empty($configs)) {
            if (!isset($options['no_cache'])) {
                cache_store_data($configs, $function_cache_id, $cache_group);
            }
            return $configs;
        } else {
            //cache_store_data(false, $function_cache_id, $cache_group);
        }
    } else {
        //cache_store_data(false, $function_cache_id, $cache_group);
    }
}

function template_var($key, $new_val = false) {
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

    //
}
