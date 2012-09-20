<?php

function get_all_functions_files_for_modules($options = false) {
    $args = func_get_args();
    $function_cache_id = '';
    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_group = 'modules';

    $cache_content = cache_get_content($cache_id, $cache_group);

    if (($cache_content) != false) {

        return $cache_content;
    }



    if (isset($options ['glob'])) {
        $glob_patern = $options ['glob'];
    } else {
        $glob_patern = '*functions.php';
    }

    if (isset($options ['dir_name'])) {
        $dir_name = $options ['dir_name'];
    } else {
        $dir_name = normalize_path(MODULES_DIR);
    }


    $dir = rglob($glob_patern, 0, $dir_name);

    if (!empty($dir)) {
        $configs = array();
        foreach ($dir as $key => $value) {
            $configs[] = normalize_path($value, false);
        }



        cache_save($configs, $function_cache_id, $cache_group);

        return $configs;
    } else {
        return false;
    }
}

function get_elements_from_db($params = false) {
    $cms_db_tables = c('db_tables');

    $table = $cms_db_tables['table_elements'];
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $options = $params2;
    }
    $params['table'] = $table;
    $params['orderby'] = 'position,asc';

    $params['cache_group'] = 'elements/global';
    $params['limit'] = 10000;


    if (!isset($params['ui'])) {
        $params['ui'] = 1;
    }
//d($params);
    return get($params);
}

function get_modules_from_db($params = false) {
    $cms_db_tables = c('db_tables');

    $table = $cms_db_tables['table_modules'];
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $options = $params2;
    }
    $params['table'] = $table;


    $params['orderby'] = 'position,asc';
    $params['cache_group'] = 'modules/global';
    $params['limit'] = 10000;
    if (!isset($params['ui'])) {
        $params['ui'] = 1;
    }

    return get($params);
}

api_expose('save_settings_el');

function save_settings_el($data_to_save) {
    return save_element_to_db($data_to_save);
}

api_expose('save_settings_md');

function save_settings_md($data_to_save) {
    return save_module_to_db($data_to_save);
}

function save_element_to_db($data_to_save) {

    if (is_admin() == false) {
        return false;
    }
    if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {
        exit(d($data_to_save));
    }

    $cms_db_tables = c('db_tables');

    $table = $cms_db_tables['table_elements'];
    $save = false;
    // d($table);
    //d($data_to_save);

    if (!empty($data_to_save)) {
        $s = $data_to_save;
        // $s["module_name"] = $data_to_save["name"];
        // $s["module_name"] = $data_to_save["name"];
        if (!isset($s["parent_id"])) {
            $s["parent_id"] = 0;
        }
        if (!isset($s["id"]) and isset($s["module"])) {
            $s["module"] = $data_to_save["module"];
            if (!isset($s["module_id"])) {
                $save = get_elements_from_db('limit=1&module=' . $s["module"]);
                if ($save != false and isset($save[0]) and is_array($save[0])) {
                    $s["id"] = $save[0]["id"];
                } else {
                    $save = save_data($table, $s);
                }
            }
        } else {
            $save = save_data($table, $s);
        }



        //
        //d($s);
    }

    if ($save != false) {

        cache_clean_group('elements' . DIRECTORY_SEPARATOR . '');
    }
    return $save;
}

function delete_elements_from_db() {
    if (is_admin() == false) {
        return false;
    } else {
        $cms_db_tables = c('db_tables');

        $table = $cms_db_tables['table_elements'];


        $q = "delete from $table ";
        db_q($q);
        cache_clean_group('elements' . DIRECTORY_SEPARATOR . '');
    }
}

function delete_modules_from_db() {
    if (is_admin() == false) {
        return false;
    } else {
        $cms_db_tables = c('db_tables');

        $table = $cms_db_tables['table_modules'];


        $q = "delete from $table ";
        db_q($q);
        cache_clean_group('modules' . DIRECTORY_SEPARATOR . '');
    }
}

function save_module_to_db($data_to_save) {

    if (is_admin() == false) {
        return false;
    }
    if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {
        exit(d($data_to_save));
    }

    $cms_db_tables = c('db_tables');

    $table = $cms_db_tables['table_modules'];
    $save = false;
    // d($table);
    //d($data_to_save);

    if (!empty($data_to_save)) {
        $s = $data_to_save;
        // $s["module_name"] = $data_to_save["name"];
        // $s["module_name"] = $data_to_save["name"];
        if (!isset($s["parent_id"])) {
            $s["parent_id"] = 0;
        }
        if (!isset($s["id"]) and isset($s["module"])) {
            $s["module"] = $data_to_save["module"];
            if (!isset($s["module_id"])) {
                $save = get_modules_from_db('limit=1&module=' . $s["module"]);
                if ($save != false and isset($save[0]) and is_array($save[0])) {
                    $s["id"] = $save[0]["id"];
                } else {
                    $save = save_data($table, $s);
                }
            }
        } else {
            $save = save_data($table, $s);
        }



        //
        //d($s);
    }

    if ($save != false) {
        //   cache_clean_group('modules' . DIRECTORY_SEPARATOR . intval($save));
        // cache_clean_group('modules' . DIRECTORY_SEPARATOR . 'global');
        cache_clean_group('modules' . DIRECTORY_SEPARATOR . '');
    }
    return $save;
}

function modules_list($options = false) {

    return get_modules($options);
}

function get_modules($options = false) {


    $params = $options;
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $options = $params2;
    }


    $args = func_get_args();
    $function_cache_id = '';
    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
    if (isset($options ['dir_name'])) {
        $dir_name = $options ['dir_name'];
        $list_as_element = true;
        $cache_group = 'elements';
        //
    } else {
        $dir_name = normalize_path(MODULES_DIR);
        $list_as_element = false;
        $cache_group = 'modules';
    }


    if (isset($options['cleanup_db']) == true) {
        if ($cache_group == 'modules') {
            delete_modules_from_db();
        }

        if ($cache_group == 'elements') {
            delete_elements_from_db();
        }
    }

    if (isset($options['skip_cache']) == false) {




        $cache_content = cache_get_content($cache_id, $cache_group);
        if (($cache_content) != false) {

            //   return $cache_content;
        }
    }
    if (isset($options ['glob'])) {
        $glob_patern = $options ['glob'];
    } else {
        $glob_patern = '*config.php';
    }





    $dir = rglob($glob_patern, 0, $dir_name);

    if (!empty($dir)) {
        $configs = array();
        foreach ($dir as $key => $value) {
            $skip_module = false;
            if (isset($options ['skip_admin']) and $options ['skip_admin'] == true) {
                // p($value);
                if (strstr($value, 'admin')) {
                    $skip_module = true;
                }
            }

            if ($skip_module == false) {

                $config = array();
                $value = normalize_path($value, false);
                $value_fn = $mod_name = str_replace('_config.php', '', $value);
                $value_fn = $mod_name = str_replace('config.php', '', $value_fn);
                $value_fn = $mod_name = str_replace('index.php', '', $value_fn);
                $value_fn = str_replace($dir_name, '', $value_fn);

                $value_fn = reduce_double_slashes($value_fn);

                $try_icon = $mod_name . '.png';
                $def_icon = MODULES_DIR . 'default.png';

                ob_start();
                include ($value);

                $content = ob_get_contents();
                ob_end_clean();

                $value_fn = rtrim($value_fn, '\\');
                $value_fn = rtrim($value_fn, '/');


                $config ['module'] = $value_fn . '';
                $config ['module'] = rtrim($config ['module'], '\\');
                $config ['module'] = rtrim($config ['module'], '/');


                $config ['module_base'] = str_replace('admin/', '', $value_fn);

                if (is_file($try_icon)) {
                    // p($try_icon);
                    $config ['icon'] = pathToURL($try_icon);
                } else {
                    $config ['icon'] = pathToURL($def_icon);
                }

                $mmd5 = url_title($config ['module']);
                $check_if_uninstalled = MODULES_DIR . '_system/' . $mmd5 . '.php';
                if (is_file($check_if_uninstalled)) {
                    $config ['uninstalled'] = true;
                    $config ['installed'] = false;
                } else {
                    $config ['uninstalled'] = false;
                    $config ['installed'] = true;
                }

                if (isset($options ['ui']) and $options ['ui'] == true) {
                    if ($config ['ui'] == false) {
                        $skip_module = true;
                    }
                }

                if ($skip_module == false) {
                    $configs [] = $config;

                    if ($list_as_element == true) {

                        save_element_to_db($config);
                    } else {

                        save_module_to_db($config);
                    }
                }
            }

            // p ( $value );
        }
        $cfg_ordered = array();
        $cfg_ordered2 = array();
        $cfg = $configs;
        foreach ($cfg as $k => $item) {
            if (isset($item ['position'])) {
                $cfg_ordered2 [$item ['position']] [] = $item;
                unset($cfg [$k]);
            }
        }
        ksort($cfg_ordered2);
        foreach ($cfg_ordered2 as $k => $item) {
            foreach ($item as $ite) {
                $cfg_ordered [] = $ite;
            }
        }

        $c2 = array_merge($cfg_ordered, $cfg);

        cache_save($c2, $function_cache_id, $cache_group);

        return $c2;
    }
}

function get_elements($options = array()) {

    if (is_string($options)) {
        $params = parse_str($options, $params2);
        $options = $params2;
    }


    // $options ['glob'] = '*.php';
    $options ['dir_name'] = normalize_path(ELEMENTS_DIR);

    return get_modules($options);

    $args = func_get_args();
    $function_cache_id = '';
    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_group = 'elements';

    $cache_content = cache_get_content($cache_id, $cache_group);

    if (($cache_content) != false) {

        return $cache_content;
    }

    $dir_name = normalize_path(ELEMENTS_DIR);
    $a1 = array();
    $dirs = array_filter(glob($dir_name . '*'), 'is_dir');
    foreach ($dirs as $dir) {
        $a1 [] = basename($dir);
    }
    cache_save($a1, $function_cache_id, $cache_group);

    return $a1;
}

function load_module($module_name, $attrs = array()) {
    $function_cache_id = false;
    $args = func_get_args();
    foreach ($args as $k => $v) {
        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }
    $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
    $cache_content = 'CACHE_LOAD_MODULE_' . $function_cache_id;

    if (!defined($cache_content)) {
        
    } else {

        // p((constant($cache_content)));
        return (constant($cache_content));
    }


    $is_element = false;
    $custom_view = false;
    if (isset($attrs ['view'])) {

        $custom_view = $attrs ['view'];
        $custom_view = trim($custom_view);
        $custom_view = str_replace('\\', '/', $custom_view);
        $custom_view = str_replace('..', '', $custom_view);
    }

    if ($custom_view != false and strtolower($custom_view) == 'admin') {
        if (is_admin() == false) {
            error('Not logged in as admin');
        }
    }
    // $CI = get_instance();
    $module_name = trim($module_name);
    $module_name = str_replace('\\', '/', $module_name);
    $module_name = str_replace('..', '', $module_name);
    // prevent hack of the directory
    $module_name = reduce_double_slashes($module_name);

    $module_in_template_dir = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '';
    $module_in_template_dir = normalize_path($module_in_template_dir, 1);
    $module_in_template_file = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '.php';
    $module_in_template_file = normalize_path($module_in_template_file, false);

    $try_file1 = false;


    if (is_dir($module_in_template_dir)) {
        $mod_d = $module_in_template_dir;
        $mod_d1 = normalize_path($mod_d, 1);
        $try_file1 = $mod_d1 . 'index.php';
    } elseif (is_file($module_in_template_file)) {
        $try_file1 = $module_in_template_file;
    } else {

        $module_in_default_dir = MODULES_DIR . $module_name . '';
        $module_in_default_dir = normalize_path($module_in_default_dir, 1);
        // d($module_in_default_dir);
        $module_in_default_file = MODULES_DIR . $module_name . '.php';
        $module_in_default_file_custom_view = MODULES_DIR . $module_name . '_' . $custom_view . '.php';

        $element_in_default_file = ELEMENTS_DIR . $module_name . '.php';
        $element_in_default_file = normalize_path($element_in_default_file, false);

        //
        $module_in_default_file = normalize_path($module_in_default_file, false);


        if (is_file($module_in_default_file)) {

            if ($custom_view == true and is_file($module_in_default_file_custom_view)) {
                $try_file1 = $module_in_default_file_custom_view;
            } else {

                $try_file1 = $module_in_default_file;
            }
        } else {
            if (is_dir($module_in_default_dir)) {

                $mod_d1 = normalize_path($module_in_default_dir, 1);

                if ($custom_view == true) {



                    $try_file1 = $mod_d1 . trim($custom_view) . '.php';
                } else {
                    $try_file1 = $mod_d1 . 'index.php';
                }
            } elseif (is_file($element_in_default_file)) {

                $is_element = true;

                $try_file1 = $element_in_default_file;
            }
        }
    }
    //

    if (isset($try_file1) != false and $try_file1 != false and is_file($try_file1)) {

        if (isset($attrs) and is_array($attrs) and !empty($attrs)) {
            $attrs2 = array();
            foreach ($attrs as $attrs_k => $attrs_v) {
                $attrs_k2 = substr($attrs_k, 0, 5);
                //d($attrs_k2);
                if (strtolower($attrs_k2) == 'data-') {
                    $attrs_k21 = substr($attrs_k, 5);
                    $attrs2[$attrs_k21] = $attrs_v;
                    //d($attrs_k21);
                }

                $attrs2[$attrs_k] = $attrs_v;
            }
            $attrs = $attrs2;
        }


        $config ['path_to_module'] = normalize_path((dirname($try_file1)) . '/', true);
        $config ['the_module'] = $module_name;
        $config ['url_to_module'] = pathToURL($config ['path_to_module']) . '/  ';
        //print(file_get_contents($try_file1));
        $l1 = new View($try_file1);
        $l1->config = $config;
        $l1->params = $attrs;

        $module_file = $l1->__toString();



        if (!defined($cache_content)) {
            define($cache_content, $module_file);
        }

        return $module_file;
    } else {
        define($cache_content, FALSE);

        return false;
    }
}
