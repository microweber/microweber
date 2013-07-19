<?php
namespace mw;
class ModuleUtils
{

    static function reorder_modules($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = MW_TABLE_PREFIX . 'modules';
        foreach ($data as $value) {
            if (is_arr($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    $i++;
                }

                db_update_position($table, $indx);
                return true;
                // d($indx);
            }
        }
    }


    static function save($data_to_save)
    {

        if (is_admin() == false) {
            return false;
        }
        if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {
            exit(d($data_to_save));
        }

        $table = MW_TABLE_PREFIX . 'modules';
        $save = false;
        // d($table);

        //d($data_to_save);

        if (!empty($data_to_save)) {
            $s = $data_to_save;
            // $s["module_name"] = $data_to_save["name"];
            // $s["debug"] = 1;
            if (!isset($s["parent_id"])) {
                $s["parent_id"] = 0;
            }
            if (!isset($s["id"]) and isset($s["module"])) {
                $s["module"] = $data_to_save["module"];
                if (!isset($s["module_id"])) {
                    $save = get_modules_from_db('no_cache=1&ui=any&limit=1&module=' . $s["module"]);
                    //  d($s);
                    //
                    if ($save != false and isset($save[0]) and is_array($save[0])) {
                        $s["id"] = intval($save[0]["id"]);

                        $save = save_data($table, $s);
                        $mname_clen = str_replace('\\', '/', $s["module"]);
                        $mname_clen = db_escape_string($mname_clen);
                        if ($s["id"] > 0) {
                            $delid = $s["id"];
                            $del = "DELETE FROM {$table} WHERE module='{$mname_clen}' AND id!={$delid} ";
                            db_q($del);
                        }
                    } else {

                        $save = save_data($table, $s);
                    }
                } else {

                }

            } else {

                $save = save_data($table, $s);
            }

            //
            //d($s);
        }
        cache_clean_group('modules' . DIRECTORY_SEPARATOR . 'functions');
        if (!isset($data_to_save['keep_cache'])) {
            if ($save != false) {
                //   cache_clean_group('modules' . DIRECTORY_SEPARATOR . intval($save));
                // cache_clean_group('modules' . DIRECTORY_SEPARATOR . 'global');
                //cache_clean_group('modules' . DIRECTORY_SEPARATOR . '');
            }
        }
        return $save;
    }

    static function delete_module($id)
    {
        if (is_admin() == false) {
            return false;
        }
        $id = intval($id);

        $table = MW_TABLE_PREFIX . 'modules';
        $db_categories = MW_TABLE_PREFIX . 'categories';
        $db_categories_items = MW_TABLE_PREFIX . 'categories_items';

        $q = "DELETE FROM $table WHERE id={$id}";
        db_q($q);

        $q = "DELETE FROM $db_categories_items WHERE rel='modules' AND data_type='category_item' AND rel_id={$id}";
        db_q($q);
        cache_clean_group('categories' . DIRECTORY_SEPARATOR . '');
        // cache_clean_group('categories_items' . DIRECTORY_SEPARATOR . '');

        cache_clean_group('modules' . DIRECTORY_SEPARATOR . '');
    }


    static function delete_all()
    {
        if (is_admin() == false) {
            return false;
        } else {

            $table = MW_TABLE_PREFIX . 'modules';
            $db_categories = MW_TABLE_PREFIX . 'categories';
            $db_categories_items = MW_TABLE_PREFIX . 'categories_items';

            $q = "DELETE FROM $table ";
            db_q($q);

            $q = "DELETE FROM $db_categories WHERE rel='modules' AND data_type='category' ";
            db_q($q);

            $q = "DELETE FROM $db_categories_items WHERE rel='modules' AND data_type='category_item' ";
            db_q($q);
            cache_clean_group('categories' . DIRECTORY_SEPARATOR . '');
            cache_clean_group('categories_items' . DIRECTORY_SEPARATOR . '');

            cache_clean_group('modules' . DIRECTORY_SEPARATOR . '');
        }
    }


    static function icon_with_title($module_name, $link = true)
    {
        $params = array();
        $to_print = '';
        $params['module'] = $module_name;
        $params['ui'] = 'any';
        $params['limit'] = 1;
        $data = \mw\Module::get($params);
        $info = false;
        if (isset($data[0])) {
            $info = $data[0];
        }
        if ($link == true and $info != false) {
            $href = admin_url() . 'view:modules/load_module:' . module_name_encode($info['module']);
        } else {
            $href = '#';
        }

        if (isset($data[0])) {
            $info = $data[0];
            $tn_ico = thumbnail($info['icon'], 32, 32);
            $to_print = '<a style="background-image:url(' . $tn_ico . ')" class="module-icon-title" href="' . $href . '">' . $info['name'] . '</a>';
        }
        print $to_print;
    }


    static function uninstall($params)
    {

        if (is_admin() == false) {
            return false;
        }
        if (isset($params['id'])) {
            $id = intval($params['id']);
            $this_module = get_modules_from_db('ui=any&one=1&id=' . $id);
            if ($this_module != false and is_array($this_module) and isset($this_module['id'])) {
                $module_name = $this_module['module'];
                if (is_admin() == false) {
                    return false;
                }

                if (trim($module_name) == '') {
                    return false;
                }
                //            $uninstall_lock = DBPATH_FULL . 'disabled_modules' . DS;
                //            if (!is_dir($uninstall_lock)) {
                //                mkdir_recursive($uninstall_lock);
                //            }
                //            $unistall_file = url_title($module_name);
                //            $unistall_file = $uninstall_lock . $unistall_file . '.php';
                //            touch($unistall_file);
                //  d($unistall_file);
                $loc_of_config = locate_module($module_name, 'config');
                $res = array();
                $loc_of_functions = locate_module($module_name, 'functions');
                $cfg = false;
                if (is_file($loc_of_config)) {
                    include ($loc_of_config);
                    if (isset($config)) {
                        $cfg = $config;
                    }

                    if (is_array($cfg) and !empty($cfg)) {

                        if (isset($cfg['on_uninstall'])) {

                            $func = $cfg['on_uninstall'];

                            if (!function_exists($func)) {
                                if (is_file($loc_of_functions)) {
                                    include_once ($loc_of_functions);
                                }
                            }

                            if (function_exists($func)) {

                                $res = $func();
                                // return $res;
                            }
                        } else {
                            //  return true;
                        }
                    }

                    // d($loc_of_config);
                }
                $to_save = array();
                $to_save['id'] = $id;
                $to_save['installed'] = '0';
                //  $to_save['keep_cache'] = '1';
                //   $to_save['module'] = $module_name;

                //d($to_save);
                save_module_to_db($to_save);
                // delete_module_by_id($id);
            }
        }
        cache_clean_group('modules' . DIRECTORY_SEPARATOR . '');

        // d($params);
    }


    static function install($params)
    {


        if (defined('MW_FORCE_MOD_INSTALLED')) {

        } else {
            if (is_admin() == false) {
                return false;
            }
        }


        if (isset($params['for_module'])) {
            $module_name = $params['for_module'];

            if (trim($module_name) == '') {
                return false;
            }
        }

        if (isset($params['module'])) {
            $module_name = $params['module'];

            if (trim($module_name) == '') {
                return false;
            }
        }

        $loc_of_config = locate_module($module_name, 'config', 1);
        //d($loc_of_config);
        $res = array();
        $loc_of_functions = locate_module($module_name, 'functions', 1);
        $cfg = false;
        if ($loc_of_config != false and is_file($loc_of_config)) {
            include ($loc_of_config);
            if (isset($config)) {
                $cfg = $config;
            }

        }

        //    $uninstall_lock = DBPATH_FULL . 'disabled_modules' . DS;
        //    if (!is_dir($uninstall_lock)) {
        //        mkdir_recursive($uninstall_lock);
        //    }
        //    $unistall_file = url_title($module_name);
        //    $unistall_file = $uninstall_lock . $unistall_file . '.php';
        //    // d($unistall_file);
        //    if (is_file($unistall_file)) {
        //        unlink($unistall_file);
        //    }

        $this_module = get_modules_from_db('no_cache=1&ui=any&one=1&module=' . $module_name);

        if ($this_module != false and is_array($this_module) and isset($this_module['id'])) {
            $to_save = array();
            $to_save['id'] = $this_module['id'];
            if (isset($params['installed']) and $params['installed'] == 'auto') {
                if (isset($this_module['installed']) and $this_module['installed'] == '') {
                    $to_save['installed'] = '1';
                } else if (isset($this_module['installed']) and $this_module['installed'] != '') {
                    $to_save['installed'] = $this_module['installed'];
                } else {
                    $to_save['installed'] = '1';
                }

            } else {
                $to_save['installed'] = '1';

            }

            if ($to_save['installed'] == '1') {
                if (isset($config)) {
                    if (isset($config['tables']) and is_arr($config['tables'])) {
                        $tabl = $config['tables'];
                        foreach ($tabl as $key1 => $fields_to_add) {
                            $table = db_get_real_table_name($key1);
                            set_db_table($table, $fields_to_add);
                        }
                    }
                    if (is_array($config) and !empty($config)) {

                        if (isset($config['on_install'])) {

                            $func = $config['on_install'];

                            if (!function_exists($func)) {
                                if (is_file($loc_of_functions)) {
                                    include_once ($loc_of_functions);
                                }
                            }

                            if (function_exists($func)) {

                                $res = $func();
                                //	return $res;
                            }
                        } else {
                            //return true;
                        }
                    }
                    if (isset($config['options']) and is_arr($config['options'])) {
                        $changes = false;
                        $tabl = $config['options'];
                        foreach ($tabl as $key => $value) {
                            //$table = db_get_real_table_name($key);
                            //d($value);
                            $value['module'] = $module_name;
                            $ch = set_default_option($value);
                            //	d($ch);
                            if ($ch == true) {
                                $changes = true;
                            }
                        }

                        if ($changes == true) {

                            cache_clean_group('options/global');
                        }
                    }

                    //
                }
            }
            $to_save['keep_cache'] = '1';
            //   $to_save['module'] = $module_name;

            save_module_to_db($to_save);
        }

        // d($loc_of_functions);
    }


    static function update_db()
    {

        if (isset($options['glob'])) {
            $glob_patern = $options['glob'];
        } else {
            $glob_patern = 'config.php';
        }

        //clearcache();
        //clearstatcache();
        $dir_name_mods = MODULES_DIR;
        $modules_remove_old = false;
        $dir = rglob($glob_patern, 0, $dir_name_mods);

        if (!empty($dir)) {
            $configs = array();
            foreach ($dir as $value) {
                $loc_of_config = $value;
                if ($loc_of_config != false and is_file($loc_of_config)) {
                    include ($loc_of_config);
                    if (isset($config)) {
                        $cfg = $config;
                        if (isset($config['tables']) and is_arr($config['tables'])) {
                            $tabl = $config['tables'];
                            foreach ($tabl as $key1 => $fields_to_add) {
                                $table = db_get_real_table_name($key1);
                                set_db_table($table, $fields_to_add);
                            }
                        }
                    }

                }
                //d($value);
            }
        }


    }

    static function get_saved_modules_as_template($params)
    {
        $params = parse_params($params);

        if (is_admin() == false) {
            return false;
        }

        $table = MW_DB_TABLE_MODULE_TEMPLATES;

        $params['table'] = $table;

        $data = get($params);
        return $data;
    }


    static function delete_module_as_template($data)
    {

        if (is_admin() == false) {
            return false;
        }

        $table = 'module_templates';
        $save = false;
        // d($table);

        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            db_delete_by_id($table, $c_id);
        }

        if (isset($data['ids']) and isarr($data['ids'])) {
            foreach ($data['ids'] as $value) {
                $c_id = intval($value);
                db_delete_by_id($table, $c_id);
            }

        }


    }
    static function save_module_as_template($data_to_save)
    {

        if (is_admin() == false) {
            return false;
        }

        $table = MW_DB_TABLE_MODULE_TEMPLATES;
        $save = false;
        // d($table);

        if (!empty($data_to_save)) {
            $s = $data_to_save;
            $save = save_data($table, $s);
        }

        return $save;
    }

   static function get_layouts($options = array())
    {

        if (is_string($options)) {
            $params = parse_str($options, $params2);
            $options = $params2;
        }

        $options['is_elements'] = 1;
        $options['dir_name'] = normalize_path(ELEMENTS_DIR);

        return scan_for_modules($options);


    }

   static function scan_for_modules($options = false)
    {

        $params = $options;
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $options = $params2;
        }

        $args = func_get_args();
        $function_cache_id = '';
        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v) . serialize($params);
        }
        $list_as_element = false;
        $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
        if (isset($options['dir_name'])) {
            $dir_name = $options['dir_name'];
            //$list_as_element = true;
            $cache_group = 'elements/global';
            //
        } else {
            $dir_name = normalize_path(MODULES_DIR);
            $list_as_element = false;
            $cache_group = 'modules/global';
        }

        if (isset($options['is_elements']) and $options['is_elements'] != false) {
            $list_as_element = true;

        } else {
            $list_as_element = false;
        }

        $skip_save = false;
        if (isset($options['skip_save']) and $options['skip_save'] != false) {
            $skip_save = true;

        }

        if (isset($options['cache_group'])) {
            $cache_group = $cache_group;
        }

        if (isset($options['reload_modules']) == true) {
            //
        }

        if (isset($options['cleanup_db']) == true) {

            if (is_admin() == true) {
                if ($cache_group == 'modules') {
                    //	delete_modules_from_db();
                }

                if ($cache_group == 'elements') {
                    //	delete_elements_from_db();
                }

                cache_clean_group('categories');
                cache_clean_group('categories_items');
            }
        }

        if (isset($options['skip_cache']) == false) {

            $cache_content = cache_get_content($cache_id, $cache_group, 'files');

            if (($cache_content) != false) {

                return $cache_content;
            }
        }
        if (isset($options['glob'])) {
            $glob_patern = $options['glob'];
        } else {
            $glob_patern = '*config.php';
        }


        if (defined("INI_SYSTEM_CHECK_DISABLED") == false) {
            define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
        }


        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
            ini_set("memory_limit", "160M");
            ini_set("set_time_limit", 0);
        }
        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
            set_time_limit(0);
        }
        //clearcache();
        //clearstatcache();

        $modules_remove_old = false;
        $dir = rglob($glob_patern, 0, $dir_name);
        $dir_name_mods = MODULES_DIR;
        $dir_name_mods2 = ELEMENTS_DIR;

        if (!empty($dir)) {
            $configs = array();

            foreach ($dir as $key => $value) {
                $skip_module = false;
                if (isset($options['skip_admin']) and $options['skip_admin'] == true) {
                    // p($value);
                    if (strstr($value, 'admin')) {
                        $skip_module = true;
                    }
                }

                if ($skip_module == false) {

                    $config = array();
                    $value = normalize_path($value, false);
                    //
                    $value_fn = $mod_name = str_replace('_config.php', '', $value);
                    $value_fn = $mod_name = str_replace('config.php', '', $value_fn);
                    $value_fn = $mod_name = str_replace('index.php', '', $value_fn);

                    //  d( $value_fn);

                    $value_fn = $mod_name_dir = str_replace($dir_name_mods, '', $value_fn);
                    $value_fn = $mod_name_dir = str_replace($dir_name_mods2, '', $value_fn);

                    //d( $value_fn);
                    //  $value_fn = reduce_double_slashes($value_fn);

                    $def_icon = MODULES_DIR . 'default.png';

                    ob_start();

                    include ($value);

                    $content = ob_get_contents();
                    ob_end_clean();

                    $value_fn = rtrim($value_fn, '\\');
                    $value_fn = rtrim($value_fn, '/');
                    $value_fn = str_replace('\\', '/', $value_fn);
                    $config['module'] = $value_fn . '';
                    $config['module'] = rtrim($config['module'], '\\');
                    $config['module'] = rtrim($config['module'], '/');

                    $config['module_base'] = str_replace('admin/', '', $value_fn);
                    if (is_dir($mod_name)) {
                        $bname = basename($mod_name);
                        $t1 = MODULES_DIR . $config['module'] . DS . $bname;

                        $try_icon = $t1 . '.png';

                    } else {
                        $try_icon = $mod_name . '.png';
                    }

                    $try_icon = normalize_path($try_icon, false);

                    if (is_file($try_icon)) {

                        $config['icon'] = pathToURL($try_icon);
                    } else {
                        $config['icon'] = pathToURL($def_icon);
                    }
                    //   $config ['installed'] = install_module($config ['module']);
                    // $mmd5 = url_title($config ['module']);
                    //   $check_if_uninstalled = MODULES_DIR . '_system/' . $mmd5 . '.php';
                    //                if (is_file($check_if_uninstalled)) {
                    //                    $config ['uninstalled'] = true;
                    //                    $config ['installed'] = false;
                    //                } else {
                    //                    $config ['uninstalled'] = false;
                    //                    $config ['installed'] = true;
                    //                }
                    //                if (isset($options ['ui']) and $options ['ui'] == true) {
                    //                    if ($config ['ui'] == false) {
                    //                       // $skip_module = true;
                    //                        $config ['ui'] = 0;
                    //                    }
                    //                }
                    //d( $config);
                    $configs[] = $config;

                    if ($skip_save !== true and $skip_module == false) {
                        if (trim($config['module']) != '') {

                            if ($list_as_element == true) {

                                save_element_to_db($config);
                            } else {
                                //d($config);
                                //if (isset($options['dir_name'])) {
                                save_module_to_db($config);
                                $modules_remove_old = true;
                                $config['installed'] = 'auto';


                                if (!defined('MW_FORCE_MOD_INSTALLED')) {
                                    define('MW_FORCE_MOD_INSTALLED', 1);
                                }
                                install_module($config);

                                //}
                            }
                        }
                    }

                }
            }

            if ($skip_save == true) {
                return $configs;
            }

            $cfg_ordered = array();
            $cfg_ordered2 = array();
            $cfg = $configs;
            foreach ($cfg as $k => $item) {
                if (isset($item['position'])) {
                    $cfg_ordered2[$item['position']][] = $item;
                    unset($cfg[$k]);
                }
            }
            ksort($cfg_ordered2);
            foreach ($cfg_ordered2 as $k => $item) {
                foreach ($item as $ite) {
                    $cfg_ordered[] = $ite;
                }
            }

            if ($modules_remove_old == true) {

                $table = MW_DB_TABLE_OPTIONS;
                $uninstall_lock = get_modules_from_db('ui=any');
                if (!empty($uninstall_lock)) {
                    foreach ($uninstall_lock as $value) {
                        $ism = is_module($value['module']);
                        if ($ism == false) {
                            delete_module_by_id($value['id']);
                            $mn = $value['module'];
                            $q = "DELETE FROM $table WHERE option_group='{$mn}'  ";

                            db_q($q);
                        }
                        //	d($ism);
                    }
                }
            }

            $c2 = array_merge($cfg_ordered, $cfg);

            cache_save($c2, $cache_id, $cache_group, 'files');

            return $c2;
        }
    }

}
