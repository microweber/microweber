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

namespace MicroweberPackages\Module;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\App\Models\SystemLicenses;
use MicroweberPackages\Database\Utils as DbUtils;

class ModuleManager
{
    public $tables = array();
    public $app = null;
    public $ui = array();
    private $activeLicenses = array();
    public $table_prefix = false;
    public $current_module = false;
    public $current_module_params = false;
    protected $table = 'modules';
    private $_install_mode = false;

    public function __construct($app = null)
    {
        if (!defined('EMPTY_MOD_STR')) {
            define('EMPTY_MOD_STR', "<div class='mw-empty-module '>{module_title} {type}</div>");
        }

        /*  print '         1                  ';
          dump(debug_backtrace(1));*/

        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->set_table_names();
        if (mw_is_installed()) {
            $getSystemLicense = SystemLicenses::get();
            if ($getSystemLicense != null) {
                $this->activeLicenses = $getSystemLicense->toArray();
            }
        }


    }

    public function set_table_names($tables = false)
    {
        if (!is_array($tables)) {
            $tables = array();
        }
        if (!isset($tables['modules'])) {
            $tables['modules'] = 'modules';
        }
        if (!isset($tables['elements'])) {
            $tables['elements'] = 'elements';
        }
        if (!isset($tables['module_templates'])) {
            $tables['module_templates'] = 'module_templates';
        }
        if (!isset($tables['system_licenses'])) {
            $tables['system_licenses'] = 'system_licenses';
        }
        if (!isset($tables['options'])) {
            $tables['options'] = 'options';
        }
        $this->tables['options'] = $tables['options'];
        $this->tables['modules'] = $tables['modules'];
        $this->tables['elements'] = $tables['elements'];
        $this->tables['module_templates'] = $tables['module_templates'];
        $this->tables['system_licenses'] = $tables['system_licenses'];
    }



    // example:
    /*
     ['name' => 'User Roles',
            'icon' => 'icon.png',
            'author' => 'Microweber',
            'description' => 'User Roles',
            'website' => 'http://microweber.com/',
            'help' => 'http://microweber.info/modules',
            'version' => 0.19,
            'ui' => true,
            'ui_admin' => true,
            'position' => 30,
            'categories' => 'admin',
            'assets' => '['resources']',

            'type' => 'users/roles',
            'controllers' => [
                'index' => "MicroweberPackages\Role\Http\Controllers\IndexController@index",
                'admin' => "MicroweberPackages\Role\Http\Controllers\IndexController@admin",
            ],
        ]
    */


    private $modules_register = [];

    public function register($module_type, $controller_action)
    {
        $this->_register_module_callback_controller($module_type, $controller_action);
        $config = [];
        $config['module'] = $module_type;
        $this->modules_register[] = $config;

//        $config = [];
//
//        if (isset($config['type']) and $config['type']) {
//            $type = $config['type'];
//
//            $this->modules_register[] = $config;
//
//            //Register controllers
//            if (isset($config['controllers']) and $config['controllers'] and is_array($config['controllers'])) {
//                foreach ($config['controllers'] as $controller_key => $controller) {
//                    $this->_register_module_callback_controller($type . '/' . $controller_key, $controller);
//                }
//            }
//        }


    }

    public function _register_module_callback_controller($module_type, $controller)
    {
        $this->app->parser->module_registry[trim($module_type)] = trim($controller);
    }


    /* public function register_module($module)
     {

     }

     public function generate_module($module)
     {
         if (!isset($module['public_folder'])) {
             new Exception('Please set public folder for registering module');
         }

         $moduleName = trim($module['name']);
         $modulePublicFolder = trim($module['public_folder']);
         $modulePublicPath = normalize_path(modules_path() . $modulePublicFolder);

         $moduleIcon = '';
         if (is_file($module['icon'])) {
             file_put_contents($modulePublicPath . 'icon.png', file_get_contents($module['icon']));
             $moduleIcon = $modulePublicPath . 'icon.png';
             $moduleIcon = dir2url($moduleIcon);
             $moduleIcon = str_replace(site_url(), '{SITE_URL}', $moduleIcon);
         }

         if (isset($module['controller'])) {
             file_put_contents($modulePublicPath . 'index.php', '
 <?php
 return \App::call("' . $module['controller'] . '@index");
 ?>
         ');
         }

         if (isset($module['admin_controller'])) {
             file_put_contents($modulePublicPath . 'admin.php', '
 <?php
 return \App::call("' . $module['admin_controller'] . '@index");
 ?>
         ');
         }

         $moduleConfig = array();
         $moduleConfig['name'] = $module['name'];
         $moduleConfig['icon'] = $moduleIcon;
         $moduleConfig['author'] = "Microweber";
         $moduleConfig['description'] = $module['name'];
         $moduleConfig['website'] = "http://microweber.com/";
         $moduleConfig['help'] = "http://microweber.info/modules";
         $moduleConfig['version'] = 0.19;
         $moduleConfig['ui'] = true;
         $moduleConfig['ui_admin'] = true;
         $moduleConfig['position'] = 30;
         $moduleConfig['categories'] = "admin";

         file_put_contents($modulePublicPath . 'config.php', "<?php\n\$config = ".var_export($moduleConfig, true).";\n?>");

     }*/

    public function install()
    {
        $this->_install_mode = true;

        mw()->cache_manager->delete('db');
        mw()->cache_manager->clear();
        mw()->module_repository->clearCache();

        $this->scan();

        $this->_install_mode = false;
    }

    public function scan($options = false)
    {
        return $this->scan_for_modules($options);
    }

    public function scan_for_modules($options = false)
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
        } else {
            $dir_name = normalize_path(modules_path());
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
        $modules_remove_old = false;
        if (isset($options['cache_group'])) {
            $cache_group = $options['cache_group'];
        }

        if (isset($options['reload_modules']) == true) {
            $modules_remove_old = true;
            if (is_cli()) {
                $this->_install_mode = true;
            }
        }

        if ($modules_remove_old or isset($options['cleanup_db']) == true) {
            if ($this->app->user_manager->is_admin() == true) {
                $this->app->cache_manager->delete('categories');
                $this->app->cache_manager->delete('categories_items');
                $this->app->cache_manager->delete('db');
                $this->app->cache_manager->delete('modules');
            }
        }

        if (isset($options['skip_cache']) == false and isset($options['no_cache']) == false) {
            $cache_content = $this->app->cache_manager->get($cache_id, $cache_group);

            if (($cache_content) != false) {
                return $cache_content;
            }
        }
        if (isset($options['glob'])) {
            $glob_patern = $options['glob'];
        } else {
            $glob_patern = '*config.php';
        }


        if (php_can_use_func('ini_set')) {
            ini_set('memory_limit', '-1');
        }


        $dir = rglob($glob_patern, 0, $dir_name);

        //  var_dump($dir);

        $dir_name_mods = modules_path();
        $dir_name_mods2 = elements_path();
        $saved_ids = array();
        if (!empty($dir)) {
            $configs = array();
            foreach ($dir as $key => $value) {
                $skip_module = false;
                if (isset($options['skip_admin']) and $options['skip_admin'] == true) {
                    if (strstr($value, 'admin')) {
                        $skip_module = true;
                    }
                }

                if ($skip_module == false) {
                    $config = array();
                    $value = normalize_path($value, false);

                    $moduleDir = $mod_name = str_replace('_config.php', '', $value);
                    $moduleDir = $mod_name = str_replace('config.php', '', $moduleDir);
                    $moduleDir = $mod_name = str_replace('index.php', '', $moduleDir);

                    $moduleDir = $mod_name_dir = str_replace($dir_name_mods, '', $moduleDir);
                    $moduleDir = $mod_name_dir = str_replace($dir_name_mods2, '', $moduleDir);

                    $def_icon = modules_path() . 'default.svg';

                    ob_start();

                    $is_mw_ignore = dirname($value) . DS . '.mwignore';
                    if (!is_file($is_mw_ignore) and is_file($value)) {
                        include $value;
                    }

                    $content = ob_get_contents();
                    ob_end_clean();
                    if ($list_as_element == true) {
                        $moduleDir = str_replace(elements_path(), '', $moduleDir);
                    } else {
                        $moduleDir = str_replace(modules_path(), '', $moduleDir);
                    }

                    $replace_root = MW_ROOTPATH . DS . 'userfiles' . DS . 'modules' . DS;

                    $moduleDir = str_replace($replace_root, '', $moduleDir);

                    $replace_root = dirname(dirname(MW_PATH)) . DS . 'userfiles' . DS . 'modules' . DS;
                    $moduleDir = str_replace($replace_root, '', $moduleDir);

                    $moduleDir = rtrim($moduleDir, '\\');
                    $moduleDir = rtrim($moduleDir, '/');
                    $moduleDir = str_replace('\\', '/', $moduleDir);
                    $moduleDir = str_replace(modules_path(), '', $moduleDir);

                    $config['module'] = $moduleDir;
                    $config['module'] = rtrim($config['module'], '\\');
                    $config['module'] = rtrim($config['module'], '/');

                    $config['module_base'] = str_replace('admin/', '', $moduleDir);
                    $main_try_icon = false;

                    $config['is_symlink'] = false;
                    if (is_link(normalize_path($moduleDir, false))) {
                        $config['is_symlink'] = true;
                    }

                    if (is_dir($mod_name)) {
                        $bname = basename($mod_name);
                        $t1 = modules_path() . $config['module'] . DS . $bname;

                        if (is_file($t1 . '.svg')) {
                            $try_icon = $t1 . '.svg';
                        } elseif (is_file($t1 . '.png')) {
                            $try_icon = $t1 . '.png';
                        } else {
                            $try_icon = $t1 . '.jpg';
                        }
                        $main_try_icon = modules_path() . $config['module'] . DS . 'icon.svg';
                        $main_try_icon2 = modules_path() . $config['module'] . DS . 'icon.png';
                    } else {
                        if (is_file($mod_name . '.svg')) {
                            $try_icon = $mod_name . '.svg';
                        } elseif (is_file($mod_name . '.png')) {
                            $try_icon = $mod_name . '.png';
                        } else {
                            $try_icon = $mod_name . '.jpg';
                        }
                        $main_try_icon = modules_path() . $mod_name . DS . 'icon.svg';
                        $main_try_icon2 = modules_path() . $mod_name . DS . 'icon.png';

                    }

                    $try_icon = normalize_path($try_icon, false);

                    if ($main_try_icon and is_file($main_try_icon)) {
                        $config['icon'] = $this->app->url_manager->link_to_file($main_try_icon);
                    } else if ($main_try_icon2 and is_file($main_try_icon2)) {
                        $config['icon'] = $this->app->url_manager->link_to_file($main_try_icon2);
                    }elseif (is_file($try_icon)) {

                        $config['icon'] = $this->app->url_manager->link_to_file($try_icon);
                    } else {
                        $config['icon'] = $this->app->url_manager->link_to_file($def_icon);
                    }


                    if (isset($config['ui'])) {
                        $config['ui'] = intval($config['ui']);
                    } else {
                        $config['ui'] = 0;
                    }

                    if (isset($config['is_system'])) {
                        $config['is_system'] = intval($config['is_system']);
                    } else {
                        $config['is_system'] = 0;
                    }

                    if (isset($config['is_integration'])) {
                        $config['is_integration'] = intval($config['is_integration']);
                    } else {
                        $config['is_integration'] = 0;
                    }

                    if (isset($config['ui_admin'])) {
                        $config['ui_admin'] = intval($config['ui_admin']);
                    } else {
                        $config['ui_admin'] = 0;
                    }

                    if (isset($config['no_cache']) and $config['no_cache'] == true) {
                        $config['allow_caching'] = 0;
                    } else {
                        $config['allow_caching'] = 1;
                    }

                    if (isset($config['name']) and $skip_save !== true and $skip_module == false) {
                        if (trim($config['module']) != '') {
                            if ($list_as_element == true) {


                                $this->app->layouts_manager->save($config);
                            } else {
                                $this->log('Installing module: ' . $config['name']);
                                $config['installed'] = 'auto';
                                $tablesData = false;
                                $schemaFileName = modules_path() . $moduleDir . '/schema.json';
                                if (isset($config['tables']) && is_array($config['tables']) && !empty($config['tables'])) {
                                    $tablesData = $config['tables'];
                                } elseif (isset($config['tables']) && is_callable($config['tables'])) {
                                    call_user_func($config['tables']);
                                    unset($config['tables']);
                                } elseif (file_exists($schemaFileName)) {
                                    $json = file_get_contents($schemaFileName);
                                    $json = @json_decode($json, true);
                                    $tablesData = $json;
                                }
                                $saved_ids[] = $this->save($config);

                                if ($tablesData) {
                                    $this->log('Installing module DB: ' . $config['name']);
                                    (new DbUtils())->build_tables($tablesData);
                                }
                            }
                        }
                    }

                    $configs[] = $config;
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
                $table = 'options';
                $uninstall_lock = $this->get('ui=any');

                if (is_array($uninstall_lock) and !empty($uninstall_lock)) {
                    foreach ($uninstall_lock as $value) {
                        $ism = $this->exists($value['module']);
                        if ($ism == false) {
                            $this->delete_module($value['id']);
                            $mn = $value['module'];
                            $table_options = $this->tables['options'];
                            $this->app->database_manager->delete_by_id($table_options, $mn, 'option_group');
                        }
                    }
                }
            }

            $c2 = array_merge($cfg_ordered, $cfg);

            $this->app->cache_manager->save($c2, $cache_id, $cache_group);

            return $c2;
        }
    }

    public function save($data_to_save)
    {
        if (mw()->user_manager->is_admin() == false and $this->_install_mode == false) {
            return false;
        }
        if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {
        }

        $table = $this->tables['modules'];
        $save = false;

        if (!empty($data_to_save)) {
            $s = $data_to_save;

            if (!isset($s['parent_id'])) {
                $s['parent_id'] = 0;
            }

            if (!isset($s['installed']) or $s['installed'] == 'auto') {
                $s['installed'] = 1;
            }

            if (isset($s['settings']) and is_array($s['settings'])) {
                $s['settings'] = json_encode($s['settings']);
            }

            $s['allow_html'] = true;

            if (!isset($s['id']) and isset($s['module'])) {
                $s['module'] = $data_to_save['module'];

                if (!isset($s['module_id'])) {
                    //$save = $this->get_modules('ui=any&no_cache=1&module=' . $s['module']);
                    $save = db_get('table=modules&no_cache=1&module=' . $s['module']);

                    if ($save != false and isset($save[0]) and is_array($save[0]) and isset($save[0]['id'])) {
                        $s['id'] = intval($save[0]['id']);
                        //   $s['position'] = intval($save[0]['position']);
                        $s['installed'] = intval($save[0]['installed']);

                        $save = mw()->database_manager->save($table, $s);
                        // print_r($save);
                        $mname_clen = str_replace('\\', '/', $s['module']);
                        if ($s['id'] > 0) {

                            //$delid = $s["id"];
                            DB::table($table)->where('id', '!=', $s['id'])->where('module', $s['module'])->delete();
                            // $del = "DELETE FROM {$table} WHERE module='{$mname_clen}' AND id!={$delid} ";
                            //mw()->database_manager->q($del);
                        }
                    } else {

                        $save = mw()->database_manager->save($table, $s);
                    }
                }
            } else {
                $save = mw()->database_manager->save($table, $s);
            }
        }
        return $save;
    }

    public function get_modules($params)
    {
        return $this->get($params);
    }

    public function get($params = false)
    {
        if (!mw_is_installed()) {
            return false;
        }

        $table = $this->tables['modules'];
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $options = $params2;
        }
        $params['table'] = $table;
        if (!isset($params['group_by'])) {
            $params['group_by'] = 'module';
        }
        if (!isset($params['order_by'])) {
            $params['order_by'] = 'position asc';
        }
        $params['cache_group'] = 'modules/global';

        if (isset($params['id'])) {
            $params['limit'] = 1;
        } else {
            $params['limit'] = 1000;
        }
        if (isset($params['module'])) {
            $params['module'] = str_replace('/admin', '', $params['module']);
        }
        if (isset($params['keyword'])) {
            $params['search_in_fields'] = array('name', 'module', 'description', 'author', 'website', 'version', 'help');
        }

        if (!isset($params['ui'])) {
            //  $params['ui'] = 1;
            //
        }

        if (isset($params['ui']) and $params['ui'] == 'any') {
            unset($params['ui']);
        }

        $data = $this->app->database_manager->get($params);

        if (is_array($data) and !empty($data)) {
            if (isset($data['settings']) and !is_array($data['settings'])) {
                $data['settings'] = @json_decode($data['settings'], true);
            } else {
                foreach ($data as $k => $v) {
                    if (isset($v['settings']) and !is_array($v['settings'])) {
                        $v['settings'] = @json_decode($v['settings'], true);
                        $data[$k] = $v;
                    }
                }
            }
        }

        $return = [];
        if ($data) {
            $return = array_merge($data, $return);
        }

        if ($this->modules_register) {
            $return = array_merge($return, $this->modules_register);
        }

        return $return;
    }

    public function exists($module_name)
    {
        if (!is_string($module_name)) {
            return false;
        }

        if (!mw_is_installed()) {
            return false;
        }
        if (trim($module_name) == '') {
            return false;
        }

        if (isset($this->app->parser->module_registry[$module_name]) and $this->app->parser->module_registry[$module_name]) {
            return true;
        } else if (isset($this->app->parser->module_registry[$module_name . '/index']) and $this->app->parser->module_registry[$module_name . '/index']) {
            return true;

        }


        global $mw_loaded_mod_memory;

        if (!isset($mw_loaded_mod_memory[$module_name])) {
            $ch = $this->locate($module_name, $custom_view = false);
            if ($ch != false) {
                $mw_loaded_mod_memory[$module_name] = true;
            } else {
                $mw_loaded_mod_memory[$module_name] = false;
            }
        }

        return $mw_loaded_mod_memory[$module_name];
    }

    public function locate($module_name, $custom_view = false, $no_fallback_to_view = false)
    {

        $template_dir = templates_path() . 'default/';

        if (defined('ACTIVE_TEMPLATE_DIR')) {
            $template_dir = ACTIVE_TEMPLATE_DIR;
            //  $this->app->content_manager->define_constants();
        }

        //  dd(debug_backtrace(1));

        $module_name = trim($module_name);
        // prevent hack of the directory
        $module_name = str_replace('\\', '/', $module_name);
        $module_name = str_replace('..', '', $module_name);

        $module_name = reduce_double_slashes($module_name);
        $module_in_template_dir = $template_dir . 'modules/' . $module_name . '';
        $module_in_template_dir = normalize_path($module_in_template_dir, 1);
        $module_in_template_file = $template_dir . 'modules/' . $module_name . '.php';
        $module_in_template_file = normalize_path($module_in_template_file, false);
        $module_in_default_file12 = modules_path() . $module_name . '.php';

        $try_file1 = false;
        $mod_d = $module_in_template_dir;
        $mod_d1 = normalize_path($mod_d, 1);
        $try_file1x = $mod_d1 . 'index.php';

        if (is_file($try_file1x)) {
            $try_file1 = $try_file1x;
        } elseif (is_file($module_in_template_file)) {
            $try_file1 = $module_in_template_file;
        } elseif (is_file($module_in_default_file12) and $custom_view == false) {
            $try_file1 = $module_in_default_file12;
        } else {
            $module_in_default_dir = modules_path() . $module_name . '';
            $module_in_default_dir = normalize_path($module_in_default_dir, 1);
            $module_in_default_file = modules_path() . $module_name . '.php';
            $module_in_default_file_custom_view = modules_path() . $module_name . '_' . $custom_view . '.php';
            $element_in_default_file = elements_path() . $module_name . '.php';
            $element_in_default_file = normalize_path($element_in_default_file, false);

            $module_in_default_file = normalize_path($module_in_default_file, false);

            if (is_file($module_in_default_file)) {
                if ($custom_view == true and is_file($module_in_default_file_custom_view)) {
                    $try_file1 = $module_in_default_file_custom_view;
                    if ($no_fallback_to_view == true) {
                        return $try_file1;
                    }
                }
            } else {
                if (is_dir($module_in_default_dir)) {
                    $mod_d1 = normalize_path($module_in_default_dir, 1);

                    if ($custom_view == true) {
                        $try_file1 = $mod_d1 . trim($custom_view) . '.php';
                        if ($no_fallback_to_view == true) {
                            return $try_file1;
                        }
                    } else {
                        if ($no_fallback_to_view == true) {
                            return false;
                        }
                        $try_file1 = $mod_d1 . 'index.php';
                    }
                } elseif (is_file($element_in_default_file)) {
                    $is_element = true;
                    $try_file1 = $element_in_default_file;
                }
            }
        }

        $try_file1 = normalize_path($try_file1, false);

        return $try_file1;
    }

    public function delete_module($id)
    {
        if ($this->app->user_manager->is_admin() == false) {
            return false;
        }
        $id = intval($id);

        $table = $this->tables['modules'];

        $db_categories = get_table_prefix() . 'categories';
        $db_categories_items = get_table_prefix() . 'categories_items';

        $this->app->database_manager->delete_by_id($table, $id);

        $q = "DELETE FROM $db_categories_items WHERE rel_type='modules' AND rel_id={$id}";
        $this->app->database_manager->q($q);
        $this->app->cache_manager->delete('categories' . DIRECTORY_SEPARATOR . '');

        $this->app->cache_manager->delete('modules' . DIRECTORY_SEPARATOR . '');
    }

    public function info($module_name)
    {
        $module_name = preg_replace('/admin$/', '', $module_name);
        $module_name = rtrim($module_name, '/');

        $data = app()->module_repository->getModule($module_name);

        return $data;
    }

    public function ui($name, $arr = false)
    {
        return $this->app->ui->module($name, $arr);
    }

    public function load($module_name, $attrs = array())
    {
        return $this->app->parser->load($module_name, $attrs);

    }

    public function css_class($module_name)
    {
        global $mw_defined_module_classes;

        if (isset($mw_defined_module_classes[$module_name]) != false) {
            return $mw_defined_module_classes[$module_name];
        } else {
            $module_class = str_replace('/', '-', $module_name);
            $module_class = str_replace('\\', '-', $module_class);
            $module_class = str_replace(' ', '-', $module_class);
            $module_class = str_replace('%20', '-', $module_class);
            $module_class = str_replace('_', '-', $module_class);
            $module_class = 'module-' . $module_class;

            $mw_defined_module_classes[$module_name] = $module_class;

            return $module_class;
        }
    }

    public function license($module_name = false)
    {
     //   $module_name = str_replace('\\', '/', $module_name);
        $licenses = $this->activeLicenses;
        $lic = [];
        if ($licenses) {
            foreach ($licenses as $license) {
               /* if (isset($license["rel_type"]) and $license["rel_type"] == $module_name) {
                    $lic = $license;
                }*/
                $lic[] = $license;
            }
        }

        if (!empty($lic)) {
            return true;
        }

        return false;
    }

    /**
     * module_templates.
     *
     * Gets all templates for a module
     *
     * @category       modules api
     */
    public function templates($module_name, $template_name = false, $get_settings_file = false)
    {
        $module_name = str_replace('admin', '', $module_name);
        $module_name_l = $this->locate($module_name);
        $replace_paths = array();
        if ($module_name_l == false) {
            $module_name_l = modules_path() . DS . $module_name . DS;
            $module_name_l = normalize_path($module_name_l, 1);
            $replace_paths[] = $module_name_l;
        } else {
            $module_name_l = dirname($module_name_l) . DS . 'templates' . DS;
            $module_name_l = normalize_path($module_name_l, 1);
            $replace_paths[] = $module_name_l;
        }

        if (defined('ACTIVE_TEMPLATE_DIR')) {
            $module_name_l_theme = ACTIVE_TEMPLATE_DIR . 'modules' . DS . $module_name . DS . 'templates' . DS;
            $module_name_l_theme = normalize_path($module_name_l_theme, 1);
            $replace_paths[] = $module_name_l_theme;
        }
        $replace_paths[] = normalize_path('modules' . '/' . $module_name . '/' . 'templates' . '/', 1);

        $template_config = mw()->template->get_config();

        if (!is_dir($module_name_l) /*and !is_dir($module_name_l_theme)*/) {
            return false;
        } else {
            if ($template_name == false) {
                $options = array();
                $options['for_modules'] = 1;
                $options['no_cache'] = 1;
                $options['path'] = $module_name_l;
                $module_name_l = $this->app->layouts_manager->scan($options);

                //  $module_name_l  = array();

                if (is_dir($module_name_l_theme)) {
                    $options['path'] = $module_name_l_theme;
                    $module_skins_from_theme = $this->app->layouts_manager->scan($options);

                    if (is_array($module_skins_from_theme)) {
                        if (!is_array($module_name_l)) {
                            $module_name_l = array();
                        }
                        $file_names_found = array();
                        if (is_array($module_skins_from_theme)) {


                            if (isset($template_config['standalone_module_skins']) and $template_config['standalone_module_skins']) {
                                $comb = $module_skins_from_theme;

                            } else {
                                $comb = array_merge($module_skins_from_theme, $module_name_l);
                            }

                            // $comb = array_merge($module_skins_from_theme, $module_name_l);
                            if (is_array($comb) and !empty($comb)) {
                                foreach ($comb as $k1 => $itm) {
//                                    if (isset($itm['layout_file']) and $itm['layout_file']) {
//
//                                            foreach ($replace_paths as $replace_path) {
//                                                $replace_path2  = str_replace(DS, '/', $replace_path );
//
//                                                $itm['layout_file']  = str_replace(DS, '/', $itm['layout_file'] );
//
//                                                $itm['layout_file'] = str_ireplace($replace_path, '', $itm['layout_file']);
//                                                $itm['layout_file'] = str_ireplace($replace_path2, '', $itm['layout_file']);
//
//                                                $itm['layout_file'] = str_ireplace(normalize_path($replace_path), '', $itm['layout_file']);
//                                            }
//                                      //
//
//                                        $itm['layout_file'] = normalize_path($itm['layout_file'],false);
//
//                                    }
                                    if (!in_array($itm['layout_file'], $file_names_found)) {
                                        if (isset($itm['visible'])) {
                                            if ($itm['visible'] == 'false'
                                                or $itm['visible'] == 'no'
                                                or $itm['visible'] == 'n'
                                            ) {
                                                // skip
                                            } else {
                                                $file_names_found[] = $itm['layout_file'];
                                            }
                                        } else {
                                            $file_names_found[] = $itm['layout_file'];
                                        }
                                    } else {
                                        unset($comb[$k1]);
                                    }
                                }
                            }
                            $module_name_l = ($comb);
                        }
                    }
                }

                return $module_name_l;
            } else {
                $template_name = str_replace('..', '', $template_name);
                $template_name_orig = $template_name;

                if ($get_settings_file == true) {
                    $is_dot_php = get_file_extension($template_name);
                    if ($is_dot_php != false and $is_dot_php == 'php') {
                        $template_name = str_ireplace('.php', '', $template_name);
                    }
                    $template_name = $template_name . '_settings';
                }

                $is_dot_php = get_file_extension($template_name);
                if ($is_dot_php != false and $is_dot_php != 'php') {
                    $template_name = $template_name . '.php';
                }


                $tf_mw_default = $module_name_l . 'default.php';
                $tf = normalize_path($module_name_l . $template_name, false);
                $tf_theme = $module_name_l_theme . $template_name;
                $tf_from_other_theme = templates_path() . $template_name;
                $tf_from_other_theme = normalize_path($tf_from_other_theme, false);

                $tf_other_module = modules_path() . $template_name;
                $tf_other_module = normalize_path($tf_other_module, false);


                if ($template_name == 'mw_default.php' and is_file($tf)) {
                    return $tf;
                } else if ($template_name == 'mw_default.php' and is_file($tf_mw_default)) {
                    return normalize_path($tf_mw_default, false);
                } else if (strstr($tf_from_other_theme, 'modules') and is_file($tf_from_other_theme)) {
                    return normalize_path($tf_from_other_theme, false);
                } elseif (is_file($tf_theme)) {
                    return normalize_path($tf_theme, false);
                } elseif (is_file($tf)) {
                    return normalize_path($tf, false);
                } elseif (strtolower($template_name_orig) != 'default' and is_file($tf_other_module)) {
                    return normalize_path($tf_other_module, false);
                } else {
                    return false;
                }
            }
        }
    }

    public function url($module_name = false)
    {
        if ($module_name == false) {

            $mod_data = $this->app->parser->processor->current_module;
            if (isset($mod_data['url_to_module'])) {
                return $mod_data['url_to_module'];
            }

            if (isset($mod_data['url_to_module'])) {
                return $mod_data['url_to_module'];
            } else {
                $mod_data = $this->current_module;
                if (isset($mod_data['url_to_module'])) {
                    return $mod_data['url_to_module'];
                }
            }
        }

        if (!is_string($module_name)) {
            return false;
        }

        $secure_connection = false;
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'on') {
                $secure_connection = true;
            }
        }

        $args = func_get_args();
        $function_cache_id = '';
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

        $cache_group = 'modules/global';

        $cache_content = $this->app->cache_manager->get($cache_id, $cache_group);

        if (($cache_content) != false) {
            return $cache_content;
        }

        static $checked = array();

        if (!isset($checked[$module_name])) {
            $ch = $this->locate($module_name, $custom_view = false);

            if ($ch != false) {
                $ch = dirname($ch);
                $ch = $this->app->url_manager->link_to_file($ch);
                $ch = $ch . '/';
                $checked[$module_name] = $ch;
            } else {
                $checked[$module_name] = false;
            }
        }
        $this->app->cache_manager->save($checked[$module_name], $function_cache_id, $cache_group);
        if ($secure_connection == true) {
            $checked[$module_name] = str_ireplace('http://', 'https://', $checked[$module_name]);
        }

        return $checked[$module_name];
    }

    public function path($module_name)
    {
        return $this->dir($module_name);
    }

    public function dir($module_name)
    {
        if (!is_string($module_name)) {
            return false;
        }

        $args = func_get_args();
        $function_cache_id = '';
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
        $cache_group = 'modules/global';
        $cache_content = $this->app->cache_manager->get($cache_id, $cache_group);
        if (($cache_content) != false) {
            return $cache_content;
        }
        $checked = array();
        if (!isset($checked[$module_name])) {
            $ch = $this->locate($module_name, $custom_view = false);
            if ($ch != false) {
                $ch = dirname($ch);
                $ch = normalize_path($ch, 1);
                $checked[$module_name] = $ch;
            } else {
                $checked[$module_name] = false;
            }
        }
        $this->app->cache_manager->save($checked[$module_name], $function_cache_id, $cache_group);

        return $checked[$module_name];
    }


    public function is_installed($module_name)
    {

        if (!mw_is_installed()) {
            return true;
        }
        $module_name = trim($module_name);
        $module_namei = $module_name;
        if (strstr($module_name, 'admin')) {
            $module_namei = str_ireplace('\\admin', '', $module_namei);
            $module_namei = str_ireplace('/admin', '', $module_namei);
        }
        //$uninstall_lock = $this->get('one=1&ui=any&module=' . $module_namei);
        $uninstall_lock = app()->module_repository->getModule($module_namei);


        if (!$uninstall_lock or empty($uninstall_lock) or (isset($uninstall_lock['installed']) and $uninstall_lock['installed'] != '' and intval($uninstall_lock['installed']) != 1)) {
            $root_mod = $this->locate_root_module($module_name);
            if ($root_mod) {
                //$uninstall_lock = $this->get('one=1&ui=any&module=' . $root_mod);
                $uninstall_lock = app()->module_repository->getModule($root_mod);

                if (empty($uninstall_lock) or (isset($uninstall_lock['installed']) and $uninstall_lock['installed'] != '' and intval($uninstall_lock['installed']) != 1)) {
                    return false;
                } else {
                    return true;
                }
            }
            return false;
        } else {
            return true;
        }
    }

    public function reorder_modules($data)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = $this->tables['modules'];
        foreach ($data as $value) {
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    ++$i;
                }
                $this->app->database_manager->update_position_field($table, $indx);
                app()->module_repository->clearCache();

                return $indx;
            }
        }
        // $this->db_init();
    }

    public function delete_all()
    {
        if ($this->app->user_manager->is_admin() == false) {
            return false;
        } else {
            $table = $this->tables['modules'];
            $db_categories = $this->table_prefix . 'categories';
            $db_categories_items = $this->table_prefix . 'categories_items';

            $q = "DELETE FROM $table ";
            $this->app->database_manager->q($q);

            $q = "DELETE FROM $db_categories WHERE rel_type='modules' AND data_type='category' ";
            $this->app->database_manager->q($q);

            $q = "DELETE FROM $db_categories_items WHERE rel_type='modules' AND data_type='category_item' ";
            $this->app->database_manager->q($q);
            $this->app->cache_manager->delete('categories' . DIRECTORY_SEPARATOR . '');
            $this->app->cache_manager->delete('categories_items' . DIRECTORY_SEPARATOR . '');

            $this->app->cache_manager->delete('modules' . DIRECTORY_SEPARATOR . '');
        }
        app()->module_repository->clearCache();
    }

    public function icon_with_title($module_name, $link = true)
    {
        $params = array();
        $to_print = '';
        $params['module'] = $module_name;
        $params['ui'] = 'any';
        $params['limit'] = 1;

        //  $data = $this->get($params);

        $data = app()->module_repository->getModule($module_name);


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
        echo $to_print;
    }

    public function uninstall($params)
    {
        if (isset($params['for_module'])) {
            $this_module = $this->get('ui=any&one=1&module=' . $params['for_module']);
            if (isset($this_module['id'])) {
                $params['id'] = $this_module['id'];
            }
        }


        if (isset($params['id'])) {
            $id = intval($params['id']);
            $this_module = $this->get('ui=any&one=1&id=' . $id);
            if ($this_module != false and is_array($this_module) and isset($this_module['id'])) {
                $module_name = $this_module['module'];

                if (trim($module_name) == '') {
                    return false;
                }
                $loc_of_config = $this->locate($module_name, 'config');
                $res = array();
                $loc_of_functions = $this->locate($module_name, 'functions');
                $cfg = false;
                if (is_file($loc_of_config)) {
                    include $loc_of_config;
                    if (isset($config)) {
                        $cfg = $config;
                    }
                    if (is_array($cfg) and !empty($cfg)) {
                        if (isset($cfg['on_uninstall'])) {
                            $func = $cfg['on_uninstall'];
                            if (!function_exists($func)) {
                                if (is_file($loc_of_functions)) {
                                    include_once $loc_of_functions;
                                }
                            }
                            if (function_exists($func)) {
                                $res = $func();
                                // return $res;
                            }
                        }
                    }
                }
                $to_save = array();
                $this->_install_mode = true;
                $to_save['id'] = $id;
                $to_save['installed'] = '0';
                $this->save($to_save);
            }
        }
        $this->app->cache_manager->delete('modules' . DIRECTORY_SEPARATOR . '');
        $this->app->cache_manager->clear();
        app()->module_repository->clearCache();

//
//        $this_module = $this->get('ui=any&one=1&id=' . $id);
//dd($this_module);
    }

    public function set_installed($params)
    {

        if (isset($params['for_module'])) {
            $this_module = $this->get('ui=any&one=1&module=' . $params['for_module']);
            if (isset($this_module['id'])) {
                $params['id'] = $this_module['id'];
            }
        }

        if (isset($params['id'])) {
            $id = intval($params['id']);
            $this_module = $this->get('ui=any&one=1&id=' . $id);
            if ($this_module != false and is_array($this_module) and isset($this_module['id'])) {
                $module_name = $this_module['module'];

                if (trim($module_name) == '') {
                    return false;
                }
                $loc_of_config = $this->locate($module_name, 'config');
                $res = array();
                $loc_of_functions = $this->locate($module_name, 'functions');
                $cfg = false;
                if (is_file($loc_of_config)) {
                    include $loc_of_config;
                    if (isset($config)) {
                        $cfg = $config;
                    }
                    if (is_array($cfg) and !empty($cfg)) {
                        if (isset($cfg['on_install'])) {
                            $func = $cfg['on_install'];
                            if (!function_exists($func)) {
                                if (is_file($loc_of_functions)) {
                                    include_once $loc_of_functions;
                                }
                            }
                            if (function_exists($func)) {
                                $res = $func();
                            }
                        }
                    }
                }
                $to_save = array();
                $to_save['id'] = $id;
                $to_save['installed'] = 1;
                $this->_install_mode = true;
                $this->save($to_save);
            }
        }
        $this->app->cache_manager->delete('modules' . DIRECTORY_SEPARATOR . '');
        app()->module_repository->clearCache();

    }

    public function update_db()
    {
        if (isset($options['glob'])) {
            $glob_patern = $options['glob'];
        } else {
            $glob_patern = 'config.php';
        }

        //$this->app->cache_manager->clear();
        //clearstatcache();
        $dir_name_mods = modules_path();
        $modules_remove_old = false;
        $dir = rglob($glob_patern, 0, $dir_name_mods);

        if (!empty($dir)) {
            $configs = array();
            foreach ($dir as $value) {
                $loc_of_config = $value;
                if ($loc_of_config != false and is_file($loc_of_config)) {
                    include $loc_of_config;
                    if (isset($config)) {
                        $cfg = $config;
                        if (isset($config['tables']) and is_array($config['tables'])) {
                            $tabl = $config['tables'];
                            foreach ($tabl as $key1 => $fields_to_add) {
                                $table = $this->app->database_manager->real_table_name($key1);
                                $this->app->database_manager->build_table($table, $fields_to_add);
                            }
                        }
                    }
                }
            }
        }
        app()->module_repository->clearCache();

    }

    public function get_saved_modules_as_template($params)
    {
        $params = parse_params($params);

        if ($this->app->user_manager->is_admin() == false) {
            return false;
        }

        $table = 'module_templates';

        $params['table'] = $table;

        $data = $this->app->database_manager->get($params);

        return $data;
    }

    public function delete_module_as_template($data)
    {
        if ($this->app->user_manager->is_admin() == false) {
            return false;
        }

        $table = 'module_templates';
        $save = false;

        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $this->app->database_manager->delete_by_id($table, $c_id);
        }

        if (isset($data['ids']) and is_array($data['ids'])) {
            foreach ($data['ids'] as $value) {
                $c_id = intval($value);
                $this->app->database_manager->delete_by_id($table, $c_id);
            }
        }
        app()->module_repository->clearCache();

    }

    public function save_module_as_template($data_to_save)
    {
        if ($this->app->user_manager->is_admin() == false) {
            return false;
        }

        $table = 'module_templates';
        $save = false;

        if (!empty($data_to_save)) {
            $s = $data_to_save;

            $save = $this->app->database_manager->save($table, $s);
        }
        app()->module_repository->clearCache();

        return $save;
    }

    public function scan_for_elements($options = array())
    {
        if (is_string($options)) {
            $params = parse_str($options, $params2);
            $options = $params2;
        }

        $options['is_elements'] = 1;
        $options['dir_name'] = normalize_path(elements_path());

        if (isset($options['cleanup_db'])) {
            $this->app->layouts_manager->delete_all();
        }

        return $this->scan_for_modules($options);
    }

    public function get_modules_from_current_site_template()
    {
        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            $this->app->content_manager->define_constants();
        }

        $dir_name = ACTIVE_TEMPLATE_DIR . 'modules' . DS;


        if (is_dir($dir_name)) {
            $configs = array();

            $glob_patern = '*config.php';

            $dir = rglob($glob_patern, 0, $dir_name);
            $replace_root = normalize_path($dir_name);
            $def_icon = modules_path() . 'default.svg';
            if (!empty($dir)) {
                foreach ($dir as $module) {
                    $module_dir = dirname($module);
                    $module_dir = normalize_path($module_dir);
                    $config = array();
                    include $module;
                    $module_name = str_replace($replace_root, '', $module_dir);

                    $module_name = rtrim($module_name, '\\');
                    $module_name = rtrim($module_name, '/');
                    $config['module'] = $module_name;

                    $config['module'] = rtrim($config['module'], '\\');
                    $config['module'] = rtrim($config['module'], '/');

                    $try_icon = $module_dir . $module_name . '.png';
                    $try_icon_svg = $module_dir . $module_name . '.svg';
                    if (is_file($try_icon_svg)) {
                        $config['icon'] = $this->app->url_manager->link_to_file($try_icon_svg);
                    } elseif (is_file($try_icon)) {
                        $config['icon'] = $this->app->url_manager->link_to_file($try_icon);
                    } elseif (is_file($module_dir . $module_name . '.jpg')) {
                        $config['icon'] = $this->app->url_manager->link_to_file($module_dir . $module_name . '.jpg');
                    } else {
                        $config['icon'] = $this->app->url_manager->link_to_file($def_icon);
                    }

                    if (isset($config['ui'])) {
                        $config['ui'] = intval($config['ui']);
                    } else {
                        $config['ui'] = 0;
                    }

                    if ($config['ui'] == 0) {
                        continue;
                    }


                    $configs[] = $config;
                }
            }

            return $configs;
        }
    }

    public $logger = null;


    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }


    public $_module_locations_root_cache = array();

    public function locate_root_module($module_name)
    {
        if (isset($this->_module_locations_root_cache[$module_name])) {
            return $this->_module_locations_root_cache[$module_name];
        }


        $module_name_parts = explode('/', $module_name);


        if ($module_name_parts and is_array($module_name_parts)) {
            $folders_to_check = array();
            $module_name_parts_count = count($module_name_parts) - 1;

            if ($module_name_parts_count) {
                for ($id = $module_name_parts_count; $id > 0; $id--) {
                    unset($module_name_parts[$id]);
                    if ($module_name_parts) {
                        $folders_to_check[] = implode('/', $module_name_parts);
                    }
                }
            }

            if ($folders_to_check) {

                foreach ($folders_to_check as $module_name_check) {
                    $modules_dir_default = modules_path() . $module_name_check;
                    $modules_dir_default = normalize_path($modules_dir_default, true);
                    if (is_dir($modules_dir_default) and is_file($modules_dir_default . 'config.php')) {
                        $this->_module_locations_root_cache[$module_name] = $module_name_check;
                        return $module_name_check;
//                        $is_installed = $this->app->module_manager->is_installed($module_name_check);
//                        if (!$is_installed) {
//                            return '';
//                        }
                    }

                }

            }

        }

    }


    public function boot_module($module)
    {
        if(!mw_is_installed()){
            return;
        }
        if (isset($module['settings']) and $module['settings'] and isset($module['settings']['autoload_namespace']) and is_array($module['settings']['autoload_namespace']) and !empty($module['settings']['autoload_namespace'])) {
            foreach ($module['settings']['autoload_namespace'] as $namespace_item) {

                if (isset($namespace_item['path']) and isset($namespace_item['namespace'])) {
                    $path = normalize_path($namespace_item['path'], 1);
                    $namespace = $namespace_item['namespace'];
                    if ($path and is_dir($path)) {
                        autoload_add_namespace($path, $namespace);
                    }
                }
            }

        }

        if (isset($module['settings']) and $module['settings'] and isset($module['settings']['service_provider']) and is_array($module['settings']['service_provider']) and !empty($module['settings']['service_provider'])) {
            foreach ($module['settings']['service_provider'] as $service_provider) {
                if (class_exists($service_provider)) {
                    app()->register($service_provider);
                }
            }
        }

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
