<?php
namespace Microweber;


api_expose('module/save');
api_expose('module/reorder_modules');
api_expose('module/license_save');

class Module
{
    public $app;
    public $tables = array();
    public $ui = array();
    public $table_prefix = false;
    public $current_module = false;
    public $current_module_params = false;

    function __construct($app = null)
    {

        if (!defined('EMPTY_MOD_STR')) {
            define("EMPTY_MOD_STR", "<div class='mw-empty-module '>{module_title} {type}</div>");
        }

        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = Application::getInstance();
            }

        }
        $this->set_table_names();


        if (!defined("MW_DB_TABLE_MODULES_INIT")) {
            define('MW_DB_TABLE_MODULES_INIT', 1);
            $this->db_init();
        }

    }

    public function set_table_names($tables = false)
    {

        if (!isset($tables['prefix'])) {
            $prefix = $this->table_prefix;
        } else {
            $prefix = $tables['prefix'];
        }

        if ($prefix == false) {
            $prefix = $this->app->config->get('database.connections.mysql.prefix');
        }

        if ($prefix == false and defined("MW_TABLE_PREFIX")) {
            $prefix = MW_TABLE_PREFIX;
        }

        if (!is_array($tables)) {
            $tables = array();
        }


        if (!isset($tables['modules'])) {
            $tables['modules'] = $prefix . 'modules';
        }
        if (!isset($tables['elements'])) {
            $tables['elements'] = $prefix . 'elements';
        }
        if (!isset($tables['module_templates'])) {
            $tables['module_templates'] = $prefix . 'module_templates';
        }
        if (!isset($tables['system_licenses'])) {
            $tables['system_licenses'] = $prefix . 'system_licenses';
        }
        $this->tables['modules'] = $tables['modules'];
        $this->tables['elements'] = $tables['elements'];
        $this->tables['module_templates'] = $tables['module_templates'];
        $this->tables['system_licenses'] = $tables['system_licenses'];

        /**
         * Define table names constants for global default usage
         */
        if (!defined("MW_DB_TABLE_MODULES")) {
            define('MW_DB_TABLE_MODULES', $tables['modules']);
        }

        if (!defined("MW_DB_TABLE_ELEMENTS")) {
            define('MW_DB_TABLE_ELEMENTS', $tables['elements']);
        }

        if (!defined("MW_DB_TABLE_MODULE_TEMPLATES")) {
            define('MW_DB_TABLE_MODULE_TEMPLATES', $tables['module_templates']);
        }

    }

    public function db_init()
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'modules' . __FUNCTION__ . crc32($function_cache_id) . 'tables';

        $cache_content = $this->app->cache->get($function_cache_id, 'db');

        if (($cache_content) != false) {

            return $cache_content;
        }


        $table_name = $this->tables['modules'];
        $table_name2 = $this->tables['elements'];
        $table_name3 = $this->tables['module_templates'];
        $table_name4 = $this->tables['system_licenses'];

        $fields_to_add = array();

        $fields_to_add['updated_on']= 'dateTime';
        $fields_to_add['created_on']= 'dateTime';
        $fields_to_add['expires_on']= 'dateTime';

        $fields_to_add['created_by']= 'integer';

        $fields_to_add['edited_by']= 'integer';

        $fields_to_add['name']= 'longText';
        $fields_to_add['parent_id']= 'integer';
        $fields_to_add['module_id']= 'longText';

        $fields_to_add['module']= 'longText';
        $fields_to_add['description']= 'longText';
        $fields_to_add['icon']= 'longText';
        $fields_to_add['author']= 'longText';
        $fields_to_add['website']= 'longText';
        $fields_to_add['help']= 'longText';
        $fields_to_add['type']= 'longText';

        $fields_to_add['installed']= 'integer';
        $fields_to_add['ui']= 'int(11) default 0');
        $fields_to_add['position']= 'integer';
        $fields_to_add['as_element']= 'int(11) default 0');
        $fields_to_add['ui_admin']= 'int(11) default 0');
        $fields_to_add['ui_admin_iframe']= 'int(11) default 0');
        $fields_to_add['is_system']= 'int(11) default 0');

        $fields_to_add['version']= 'string(11) default NULL';

        $fields_to_add['notifications']= 'int(11) default 0');

        $this->app->database->build_table($table_name, $fields_to_add);

        $fields_to_add['layout_type']= 'string';

        $this->app->database->add_table_index('module', $table_name, array('module(255)'));
        $this->app->database->add_table_index('module_id', $table_name, array('module_id(255)'));

        $this->app->database->build_table($table_name2, $fields_to_add);

        $this->app->database->add_table_index('module', $table_name2, array('module(255)'));
        $this->app->database->add_table_index('module_id', $table_name2, array('module_id(255)'));

        $fields_to_add = array();
        $fields_to_add['updated_on']= 'dateTime';
        $fields_to_add['created_on']= 'dateTime';
        $fields_to_add['created_by']= 'integer';
        $fields_to_add['edited_by']= 'integer';
        $fields_to_add['module_id']= 'longText';
        $fields_to_add['name']= 'longText';
        $fields_to_add['module']= 'longText';
        $this->app->database->build_table($table_name3, $fields_to_add);


        $fields_to_add = array();
        $fields_to_add['updated_on']= 'dateTime';
        $fields_to_add['created_on']= 'dateTime';
        $fields_to_add['created_by']= 'integer';
        $fields_to_add['edited_by']= 'integer';
        $fields_to_add['rel']= 'longText';
        $fields_to_add['rel_name']= 'longText';
        $fields_to_add['local_key']= 'longText';
        $fields_to_add['local_key_hash']= 'longText';
        $fields_to_add['registered_name']= 'longText';
        $fields_to_add['company_name']= 'longText';
        $fields_to_add['domains']= 'longText';
        $fields_to_add['status']= 'longText';
        $fields_to_add['product_id']= 'integer';
        $fields_to_add['service_id']= 'integer';
        $fields_to_add['billing_cycle']= 'longText';
        $fields_to_add['reg_on']= 'dateTime';
        $fields_to_add['due_on']= 'dateTime';

        $this->app->database->build_table($table_name4, $fields_to_add);

        $this->app->cache->save(true, $function_cache_id, $cache_group = 'db');
        // $fields = (array_change_key_case ( $fields, CASE_LOWER ));
        return true;

    }

    function ui($name, $arr = false)
    {
        return $this->app->ui->module($name, $arr);
    }

    public function load($module_name, $attrs = array())
    {

        $is_element = false;
        $custom_view = false;
        if (isset($attrs['view'])) {

            $custom_view = $attrs['view'];
            $custom_view = trim($custom_view);
            $custom_view = str_replace('\\']= '/', $custom_view);
            $attrs['view'] = $custom_view = str_replace('..']= '', $custom_view);
        }

        if ($custom_view != false and strtolower($custom_view) == 'admin') {
            if ($this->app->user->is_admin() == false) {
                mw_error('Not logged in as admin');
            }
        }

        $module_name = trim($module_name);
        $module_name = str_replace('\\']= '/', $module_name);
        $module_name = str_replace('..']= '', $module_name);
        // prevent hack of the directory
        $module_name = reduce_double_slashes($module_name);

        $module_namei = $module_name;

        if (strstr($module_name, 'admin')) {

            $module_namei = str_ireplace('\\admin']= '', $module_namei);
            $module_namei = str_ireplace('/admin']= '', $module_namei);
        }

        //$module_namei = str_ireplace($search, $replace, $subject)e


        $uninstall_lock = $this->get('one=1&ui=any&module=' . $module_namei);

        if (isset($uninstall_lock["installed"]) and $uninstall_lock["installed"] != '' and intval($uninstall_lock["installed"]) != 1) {
            return '';
        }

        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            $this->app->content->define_constants();
        }

        $module_in_template_dir = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '';
        $module_in_template_dir = normalize_path($module_in_template_dir, 1);
        $module_in_template_file = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '.php';
        $module_in_template_file = normalize_path($module_in_template_file, false);

        $try_file1 = false;

        $mod_d = $module_in_template_dir;
        $mod_d1 = normalize_path($mod_d, 1);
        $try_file1zz = $mod_d1 . 'index.php';
        $in_dir = false;


        if ($custom_view == true) {

            $try_file1zz = $mod_d1 . trim($custom_view) . '.php';
        } else {
            $try_file1zz = $mod_d1 . 'index.php';
        }


        if (is_dir($module_in_template_dir) and is_file($try_file1zz)) {
            $try_file1 = $try_file1zz;


            $in_dir = true;
        } elseif (is_file($module_in_template_file)) {
            $try_file1 = $module_in_template_file;
            $in_dir = false;
        } else {


            $module_in_default_dir = MW_MODULES_DIR . $module_name . '';
            $module_in_default_dir = normalize_path($module_in_default_dir, 1);
            // d($module_in_default_dir);
            $module_in_default_file = MW_MODULES_DIR . $module_name . '.php';
            $module_in_default_file_custom_view = MW_MODULES_DIR . $module_name . '_' . $custom_view . '.php';

            $element_in_default_file = MW_ELEMENTS_DIR . $module_name . '.php';
            $element_in_default_file = normalize_path($element_in_default_file, false);

            //


            $module_in_default_file = normalize_path($module_in_default_file, false);

            if (is_file($module_in_default_file)) {
                $in_dir = false;
                if ($custom_view == true and is_file($module_in_default_file_custom_view)) {
                    $try_file1 = $module_in_default_file_custom_view;
                } else {

                    $try_file1 = $module_in_default_file;
                }
            } else {

                if (is_dir($module_in_default_dir)) {
                    $in_dir = true;
                    $mod_d1 = normalize_path($module_in_default_dir, 1);

                    if ($custom_view == true) {

                        $try_file1 = $mod_d1 . trim($custom_view) . '.php';
                    } else {
                        $try_file1 = $mod_d1 . 'index.php';
                    }
                } elseif (is_file($element_in_default_file)) {
                    $in_dir = false;
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
                    if (strtolower($attrs_k2) == 'data-') {
                        $attrs_k21 = substr($attrs_k, 5);
                        $attrs2[$attrs_k21] = $attrs_v;
                    } elseif (!isset($attrs['data-' . $attrs_k])) {
                        $attrs2['data-' . $attrs_k] = $attrs_v;

                    }

                    $attrs2[$attrs_k] = $attrs_v;
                }
                $attrs = $attrs2;
            }


            $config['path_to_module'] = $config['mp'] = $config['path'] = normalize_path((dirname($try_file1)) . '/', true);
            $config['the_module'] = $module_name;
            $config['module'] = $module_name;
            $module_name_dir = dirname($module_name);
            $config['module_name'] = $module_name_dir;

            $config['module_name_url_safe'] = module_name_encode($module_name);


            $find_base_url = $this->app->url->current(1);
            if ($pos = strpos($find_base_url, ':' . $module_name) or $pos = strpos($find_base_url, ':' . $config['module_name_url_safe'])) {
                $find_base_url = substr($find_base_url, 0, $pos) . ':' . $config['module_name_url_safe'];
            }
            $config['url'] = $find_base_url;

            $config['url_main'] = $config['url_base'] = strtok($find_base_url, '?');

            if ($in_dir != false) {
                $mod_api = str_replace('/admin']= '', $module_name);
            } else {
                $mod_api = str_replace('/admin']= '', $module_name_dir);
            }

            $config['module_api'] = $this->app->url->site('api/' . $mod_api);
            $config['module_view'] = $this->app->url->site('module/' . $module_name);
            $config['ns'] = str_replace('/']= '\\', $module_name);
            $config['module_class'] = $this->css_class($module_name);
            //$config['url_to_module'] = $this->app->url->link_to_file($config['path_to_module']);
            //$config['url_to_module'] = $this->app->url->link_to_file($config['path_to_module']);

//            if (trim($module_name_dir) != '' and $module_name_dir != './') {
//                $mod_url = MW_MODULES_URL . (trim(str_replace(' ']= '%20', $module_name_dir))) . '/';
//
//                $config['url_to_module'] = $module_name_dir;
//            } else {
//                $config['url_to_module'] = $this->app->url->link_to_file($config['path_to_module']);
//            } // $config['url_to_module2'] =$config['url_base'];

            $config['url_to_module'] = $this->app->url->link_to_file($config['path_to_module']);

            $get_module_template_settings_from_options = mw_var('get_module_template_settings_from_options');

            if (isset($attrs['id'])) {
                $attrs['id'] = str_replace('__MODULE_CLASS_NAME__', $config['module_class'], $attrs['id']);

                $template = false;

            }

            //$config['url_to_module'] = rtrim($config['url_to_module'], '///');
            $lic = $this->license($module_name);
            //  $lic = 'valid';
            if ($lic != false) {
                $config['license'] = $lic;
            }

            if (!isset($attrs['id']) and isset($attrs['module-id']) and $attrs['module-id'] != false) {
                $attrs['id'] = $attrs['module-id'];
            }

            if (!isset($attrs['id'])) {
                global $mw_mod_counter;
                $mw_mod_counter++;
                $seg_clean = $this->app->url->segment(0);
                if (defined('IS_HOME')) {
                    $seg_clean = '';
                }
                $seg_clean = str_replace('%20']= '-', $seg_clean);
                $seg_clean = str_replace(' ']= '-', $seg_clean);
                $seg_clean = str_replace('.']= '', $seg_clean);
                $attrs1 = crc32(serialize($attrs) . $seg_clean . $mw_mod_counter);
                $attrs1 = str_replace('%20']= '-', $attrs1);
                $attrs1 = str_replace(' ']= '-', $attrs1);
                $attrs['id'] = ($config['module_class'] . '-' . $attrs1);
            }
            if (isset($attrs['id']) and strstr($attrs['id'], '__MODULE_CLASS_NAME__')) {
                $attrs['id'] = str_replace('__MODULE_CLASS_NAME__', $config['module_class'], $attrs['id']);
                //$attrs['id'] = ('__MODULE_CLASS__' . '-' . $attrs1);
            }
            $l1 = new \Microweber\View($try_file1);
            $l1->config = $config;
            $l1->app = $this->app;
            if (!empty($config)) {
                foreach ($config as $key1 => $value1) {
                    mw_var($key1, $value1);
                }
            }


            if (!isset($attrs['module'])) {
                $attrs['module'] = $module_name;
            }

            if (!isset($attrs['parent-module'])) {
                $attrs['parent-module'] = $module_name;
            }

            if (!isset($attrs['parent-module-id'])) {
                $attrs['parent-module-id'] = $attrs['id'];
            }
            $mw_restore_get = mw_var('mw_restore_get');
            if ($mw_restore_get != false and is_array($mw_restore_get)) {
                $l1->_GET = $mw_restore_get;
                $_GET = $mw_restore_get;
            }
            if (defined('MW_MODULE_ONDROP')) {
                if (!isset($attrs['ondrop'])) {
                    $attrs['ondrop'] = true;
                }
            }
            $l1->params = $attrs;
            if ($config) {
                $this->current_module = ($config);
            }
            if ($attrs) {
                $this->current_module_params = ($attrs);

            }
            if (isset($attrs['view']) && (trim($attrs['view']) == 'empty')) {

                $module_file = EMPTY_MOD_STR;
            } elseif (isset($attrs['view']) && (trim($attrs['view']) == 'admin')) {
                $module_file = $l1->__toString();
            } else {
                if (isset($attrs['display']) && (trim($attrs['display']) == 'custom')) {
                    $module_file = $l1->__get_vars();
                    return $module_file;
                } else if (isset($attrs['format']) && (trim($attrs['format']) == 'json')) {
                    $module_file = $l1->__get_vars();
                    header("Content-type: application/json");
                    exit(json_encode($module_file));
                } else {
                    $module_file = $l1->__toString();
                }
            }
            //	$l1 = null;
            unset($l1);
            if ($lic != false and isset($lic["error"]) and ($lic["error"] == 'no_license_found')) {
                $lic_l1_try_file1 = MW_ADMIN_VIEWS_DIR . 'activate_license.php';
                $lic_l1 = new \Microweber\View($lic_l1_try_file1);

                $lic_l1->config = $config;
                $lic_l1->params = $attrs;

                $lic_l1e_file = $lic_l1->__toString();
                unset($lic_l1);
                $module_file = $lic_l1e_file . $module_file;
            }
            // $mw_loaded_mod_memory[$function_cache_id] = $module_file;
            return $module_file;
        } else {
            //define($cache_content, FALSE);
            // $mw_loaded_mod_memory[$function_cache_id] = false;
            return false;
        }
    }

    public function css_class($module_name)
    {
        global $mw_defined_module_classes;

        if (isset($mw_defined_module_classes[$module_name]) != false) {
            return $mw_defined_module_classes[$module_name];
        } else {

            $module_class = str_replace('/']= '-', $module_name);
            $module_class = str_replace('\\']= '-', $module_class);
            $module_class = str_replace(' ']= '-', $module_class);
            $module_class = str_replace('%20']= '-', $module_class);
            $module_class = str_replace('_']= '-', $module_class);
            $module_class = 'module-' . $module_class;

            $mw_defined_module_classes[$module_name] = $module_class;
            return $module_class;
        }
    }

    /**
     * module_templates
     *
     * Gets all templates for a module
     *
     * @package        modules
     * @subpackage    functions
     * @category    modules api
     */

    public function templates($module_name, $template_name = false, $get_settings_file = false)
    {


        $module_name = str_replace('admin']= '', $module_name);
        $module_name_l = $this->locate($module_name);

        if ($module_name_l == false) {
            $module_name_l = MW_MODULES_DIR . DS . $module_name . DS;
            $module_name_l = normalize_path($module_name_l, 1);

        } else {
            $module_name_l = dirname($module_name_l) . DS . 'templates' . DS;
            $module_name_l = normalize_path($module_name_l, 1);

        }


        $module_name_l_theme = ACTIVE_TEMPLATE_DIR . 'modules' . DS . $module_name . DS . 'templates' . DS;
        $module_name_l_theme = normalize_path($module_name_l_theme, 1);


        if (!is_dir($module_name_l) and !is_dir($module_name_l_theme)) {
            return false;
        } else {

            if ($template_name == false) {
                $options = array();
                $options['for_modules'] = 1;
                $options['no_cache'] = 1;
                $options['path'] = $module_name_l;
                $module_name_l = $this->app->layouts->scan($options);
                if (is_dir($module_name_l_theme)) {
                    $options['path'] = $module_name_l_theme;
                    $module_skins_from_theme = $this->app->layouts->scan($options);
                    if (is_array($module_skins_from_theme)) {
                        if (!is_array($module_name_l)) {
                            $module_name_l = array();
                        }
                        $file_names_found = array();
                        if (is_array($module_skins_from_theme)) {
                            $comb = array_merge($module_skins_from_theme, $module_name_l);
                            if (is_array($comb) and !empty($comb)) {
                                foreach ($comb as $k1 => $itm) {
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


                $template_name = str_replace('..']= '', $template_name);
                $template_name_orig = $template_name;

                if ($get_settings_file == true) {
                    $is_dot_php = get_file_extension($template_name);
                    if ($is_dot_php != false and $is_dot_php == 'php') {
                        $template_name = str_ireplace('.php']= '', $template_name);
                    }
                    $template_name = $template_name . '_settings';
                }

                $is_dot_php = get_file_extension($template_name);
                if ($is_dot_php != false and $is_dot_php != 'php') {
                    $template_name = $template_name . '.php';
                }


                $tf = $module_name_l . $template_name;
                $tf_theme = $module_name_l_theme . $template_name;
                $tf_from_other_theme = MW_TEMPLATES_DIR . $template_name;
                $tf_from_other_theme = normalize_path($tf_from_other_theme, false);

                $tf_other_module = MW_MODULES_DIR . $template_name;
                $tf_other_module = normalize_path($tf_other_module, false);

                if (strstr($tf_from_other_theme, 'modules') and is_file($tf_from_other_theme)) {
                    return $tf_from_other_theme;
                } else if (is_file($tf_theme)) {
                    return $tf_theme;
                } else if (is_file($tf)) {
                    return $tf;
                } else if (strtolower($template_name_orig) != 'default' and is_file($tf_other_module)) {
                    return $tf_other_module;
                } else {
                    return false;
                }
            }
        }
    }

    public function locate($module_name, $custom_view = false, $no_fallback_to_view = false)
    {

        if (!defined("ACTIVE_TEMPLATE_DIR")) {
            $this->app->content->define_constants();
        }

        $module_name = trim($module_name);
        // prevent hack of the directory
        $module_name = str_replace('\\']= '/', $module_name);
        $module_name = str_replace('..']= '', $module_name);

        $module_name = reduce_double_slashes($module_name);
        $module_in_template_dir = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '';
        $module_in_template_dir = normalize_path($module_in_template_dir, 1);
        $module_in_template_file = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '.php';
        $module_in_template_file = normalize_path($module_in_template_file, false);
        $module_in_default_file12 = MW_MODULES_DIR . $module_name . '.php';

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
            $module_in_default_dir = MW_MODULES_DIR . $module_name . '';
            $module_in_default_dir = normalize_path($module_in_default_dir, 1);
            $module_in_default_file = MW_MODULES_DIR . $module_name . '.php';
            $module_in_default_file_custom_view = MW_MODULES_DIR . $module_name . '_' . $custom_view . '.php';
            $element_in_default_file = MW_ELEMENTS_DIR . $module_name . '.php';
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

    public function url($module_name = false)
    {

        if ($module_name == false) {
            $mod_data = $this->current_module;
            if (isset($mod_data["url_to_module"])) {
                return $mod_data["url_to_module"];
            }
        }

        if (!is_string($module_name)) {
            return false;
        }

        $secure_connection = false;
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER["HTTPS"] == "on") {
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

        $cache_content = $this->app->cache->get($cache_id, $cache_group);

        if (($cache_content) != false) {

            return $cache_content;
        }

        static $checked = array();

        if (!isset($checked[$module_name])) {
            $ch = $this->locate($module_name, $custom_view = false);

            if ($ch != false) {
                $ch = dirname($ch);
                $ch = $this->app->url->link_to_file($ch);
                $ch = $ch . '/';
                $checked[$module_name] = $ch;
            } else {
                $checked[$module_name] = false;
            }
        }
        $this->app->cache->save($checked[$module_name], $function_cache_id, $cache_group);
        if ($secure_connection == true) {
            $checked[$module_name] = str_ireplace('http://']= 'https://', $checked[$module_name]);
        }
        return $checked[$module_name];

    }

    public function info($module_name)
    {
        global $_mw_modules_info_register;
        if (isset($_mw_modules_info_register[$module_name])) {
            return $_mw_modules_info_register[$module_name];
        }
        $params = array();
        $params['module'] = $module_name;
        $params['ui'] = 'any';
        $params['limit'] = 1;
        $data = $this->get($params);
        if (isset($data[0])) {
            $_mw_modules_info_register[$module_name] = $data[0];
            return $data[0];
        }
    }

    public function get($params = false)
    {

        $table = $this->tables['modules'];
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $options = $params2;
        }
        $params['table'] = $table;
        $params['group_by'] = 'module';
        $params['order_by'] = 'position asc';
        $params['cache_group'] = 'modules/global';
        if (isset($params['id'])) {
            $params['limit'] = 1;
        } else {
            $params['limit'] = 1000;
        }
        if (isset($params['module'])) {
            $params['module'] = str_replace('/admin']= '', $params['module']);
        }
        if (isset($params['keyword'])) {
            $params['search_in_fields'] = array('name']= 'module']= 'description']= 'author']= 'website']= 'version']= 'help');
        }

        if (!isset($params['ui'])) {
            //  $params['ui'] = 1;
            //
        }

        if (isset($params['ui']) and $params['ui'] == 'any') {
            unset($params['ui']);
        }

        return $this->app->database->get($params);
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
        $cache_content = $this->app->cache->get($cache_id, $cache_group);
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
        $this->app->cache->save($checked[$module_name], $function_cache_id, $cache_group);
        return $checked[$module_name];

    }

    public function is_installed($module_name)
    {
        $module_name = trim($module_name);
        $module_namei = $module_name;
        if (strstr($module_name, 'admin')) {
            $module_namei = str_ireplace('\\admin']= '', $module_namei);
            $module_namei = str_ireplace('/admin']= '', $module_namei);
        }
        $uninstall_lock = $this->get('one=1&ui=any&module=' . $module_namei);
        if (empty($uninstall_lock) or (isset($uninstall_lock["installed"]) and $uninstall_lock["installed"] != '' and intval($uninstall_lock["installed"]) != 1)) {
            return false;
        } else {
            return true;
        }
    }

    public function license($module_name = false)
    {
        $module_name = str_replace('\\']= '/', $module_name);
        $lic = $this->app->update->get_licenses('status=active&one=1&rel=' . $module_name);
        if (!empty($lic)) {
            return true;
        }

    }

    public function reorder_modules($data)
    {

        $adm = $this->app->user->is_admin();
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
                    $i++;
                }
                $this->app->database->update_position_field($table, $indx);
                return $indx;
            }
        }
        // $this->db_init();
    }

    public function delete_all()
    {
        if ($this->app->user->is_admin() == false) {
            return false;
        } else {

            $table = $this->tables['modules'];
            $db_categories = $this->table_prefix . 'categories';
            $db_categories_items = $this->table_prefix . 'categories_items';

            $q = "DELETE FROM $table ";
            $this->app->database->q($q);

            $q = "DELETE FROM $db_categories WHERE rel='modules' AND data_type='category' ";
            $this->app->database->q($q);

            $q = "DELETE FROM $db_categories_items WHERE rel='modules' AND data_type='category_item' ";
            $this->app->database->q($q);
            $this->app->cache->delete('categories' . DIRECTORY_SEPARATOR . '');
            $this->app->cache->delete('categories_items' . DIRECTORY_SEPARATOR . '');

            $this->app->cache->delete('modules' . DIRECTORY_SEPARATOR . '');
        }
    }

    public function icon_with_title($module_name, $link = true)
    {
        $params = array();
        $to_print = '';
        $params['module'] = $module_name;
        $params['ui'] = 'any';
        $params['limit'] = 1;

        $data = $this->get($params);
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

    public function uninstall($params)
    {

        if ($this->app->user->is_admin() == false) {
            return false;
        }
        if (isset($params['id'])) {
            $id = intval($params['id']);
            $this_module = $this->get('ui=any&one=1&id=' . $id);
            if ($this_module != false and is_array($this_module) and isset($this_module['id'])) {
                $module_name = $this_module['module'];
                if ($this->app->user->is_admin() == false) {
                    return false;
                }

                if (trim($module_name) == '') {
                    return false;
                }
                //            $uninstall_lock = MW_STORAGE_DIR . 'disabled_modules' . DS;
                //            if (!is_dir($uninstall_lock)) {
                //                mkdir_recursive($uninstall_lock);
                //            }
                //            $unistall_file = $this->app->url->slug($module_name);
                //            $unistall_file = $uninstall_lock . $unistall_file . '.php';
                //            touch($unistall_file);
                //  d($unistall_file);
                $loc_of_config = $this->locate($module_name, 'config');
                $res = array();
                $loc_of_functions = $this->locate($module_name, 'functions');
                $cfg = false;
                if (is_file($loc_of_config)) {
                    include($loc_of_config);
                    if (isset($config)) {
                        $cfg = $config;
                    }

                    if (is_array($cfg) and !empty($cfg)) {

                        if (isset($cfg['on_uninstall'])) {

                            $func = $cfg['on_uninstall'];

                            if (!function_exists($func)) {
                                if (is_file($loc_of_functions)) {
                                    include_once($loc_of_functions);
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
                $this->save($to_save);
                // $this->delete_module($id);
            }
        }
        $this->app->cache->delete('modules' . DIRECTORY_SEPARATOR . '');

        // d($params);
    }

    public function update_db()
    {

        if (isset($options['glob'])) {
            $glob_patern = $options['glob'];
        } else {
            $glob_patern = 'config.php';
        }

        //$this->app->cache->clear();
        //clearstatcache();
        $dir_name_mods = MW_MODULES_DIR;
        $modules_remove_old = false;
        $dir = rglob($glob_patern, 0, $dir_name_mods);

        if (!empty($dir)) {
            $configs = array();
            foreach ($dir as $value) {
                $loc_of_config = $value;
                if ($loc_of_config != false and is_file($loc_of_config)) {
                    include($loc_of_config);
                    if (isset($config)) {
                        $cfg = $config;
                        if (isset($config['tables']) and is_array($config['tables'])) {
                            $tabl = $config['tables'];
                            foreach ($tabl as $key1 => $fields_to_add) {
                                $table = $this->app->database->real_table_name($key1);
                                $this->app->database->build_table($table, $fields_to_add);
                            }
                        }
                    }

                }
                //d($value);
            }
        }


    }

    public function get_saved_modules_as_template($params)
    {
        $params = parse_params($params);

        if ($this->app->user->is_admin() == false) {
            return false;
        }

        $table = MW_DB_TABLE_MODULE_TEMPLATES;

        $params['table'] = $table;

        $data = $this->app->database->get($params);
        return $data;
    }

    public function delete_module_as_template($data)
    {

        if ($this->app->user->is_admin() == false) {
            return false;
        }

        $table = 'module_templates';
        $save = false;
        // d($table);

        $adm = $this->app->user->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $this->app->database->delete_by_id($table, $c_id);
        }

        if (isset($data['ids']) and is_array($data['ids'])) {
            foreach ($data['ids'] as $value) {
                $c_id = intval($value);
                $this->app->database->delete_by_id($table, $c_id);
            }

        }


    }

    public function save_module_as_template($data_to_save)
    {

        if ($this->app->user->is_admin() == false) {
            return false;
        }

        $table = MW_DB_TABLE_MODULE_TEMPLATES;
        $save = false;

        if (!empty($data_to_save)) {
            $s = $data_to_save;

            $save = $this->app->database->save($table, $s);
        }

        return $save;
    }

    public function get_layouts($options = array())
    {

        if (is_string($options)) {
            $params = parse_str($options, $params2);
            $options = $params2;
        }

        $options['is_elements'] = 1;
        $options['dir_name'] = normalize_path(MW_ELEMENTS_DIR);


        if (isset($options['cleanup_db'])) {
            $this->app->layouts->delete_all();
        }

        return $this->scan_for_modules($options);


    }


    public function get_modules_from_current_site_template()
    {

        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            $this->app->content->define_constants();
        }


        $dir_name = ACTIVE_TEMPLATE_DIR . 'modules' . DS;

        if (is_dir($dir_name)) {
            $configs = array();


            $glob_patern = '*config.php';

            $dir = rglob($glob_patern, 0, $dir_name);
            $replace_root = normalize_path($dir_name);
            $def_icon = MW_MODULES_DIR . 'default.png';
            if (!empty($dir)) {
                foreach ($dir as $module) {
                    $module_dir = dirname($module);
                    $module_dir = normalize_path($module_dir);
                    $config = array();
                    include($module);
                    $module_name = str_replace($replace_root, '', $module_dir);

                    $module_name = rtrim($module_name, '\\');
                    $module_name = rtrim($module_name, '/');
                    $config['module'] = $module_name;


                    $config['module'] = rtrim($config['module'], '\\');
                    $config['module'] = rtrim($config['module'], '/');

                    $try_icon = $module_dir . $module_name . '.png';
                    if (is_file($try_icon)) {
                        $config['icon'] = $this->app->url->link_to_file($try_icon);
                    } else {
                        $config['icon'] = $this->app->url->link_to_file($def_icon);

                    }
                    $configs[] = $config;


                }
            }


            return $configs;
        }


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
            $dir_name = normalize_path(MW_MODULES_DIR);
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

        }

        if (isset($options['cleanup_db']) == true) {

            if ($this->app->user->is_admin() == true) {
                $this->app->cache->delete('categories');
                $this->app->cache->delete('categories_items');
                $this->app->cache->delete('db');
                $this->app->cache->delete('modules');
            }
        }

        if (isset($options['skip_cache']) == false and isset($options['no_cache']) == false) {

            $cache_content = $this->app->cache->get($cache_id, $cache_group);

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
            set_time_limit(600);
        }


        $modules_remove_old = false;
        $dir = rglob($glob_patern, 0, $dir_name);
        $dir_name_mods = MW_MODULES_DIR;
        $dir_name_mods2 = MW_ELEMENTS_DIR;

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

                    $value_fn = $mod_name = str_replace('_config.php']= '', $value);
                    $value_fn = $mod_name = str_replace('config.php']= '', $value_fn);
                    $value_fn = $mod_name = str_replace('index.php']= '', $value_fn);


                    $value_fn = $mod_name_dir = str_replace($dir_name_mods, '', $value_fn);
                    $value_fn = $mod_name_dir = str_replace($dir_name_mods2, '', $value_fn);


                    $def_icon = MW_MODULES_DIR . 'default.png';

                    ob_start();


                    $is_mw_ignore = dirname($value) . DS . '.mwignore';
                    if (!is_file($is_mw_ignore)) {
                        include($value);
                    }

                    $content = ob_get_contents();
                    ob_end_clean();
                    $value_fn = str_replace(MW_MODULES_DIR, '', $value_fn);


                    $replace_root = MW_ROOTPATH . DS . 'userfiles' . DS . 'modules' . DS;

                    $value_fn = str_replace($replace_root, '', $value_fn);

                    $replace_root = dirname(dirname(MW_APP_PATH)) . DS . 'userfiles' . DS . 'modules' . DS;
                    $value_fn = str_replace($replace_root, '', $value_fn);


                    $value_fn = rtrim($value_fn, '\\');
                    $value_fn = rtrim($value_fn, '/');
                    $value_fn = str_replace('\\']= '/', $value_fn);
                    $value_fn = str_replace(MW_MODULES_DIR, '', $value_fn);


                    $config['module'] = $value_fn;
                    $config['module'] = rtrim($config['module'], '\\');
                    $config['module'] = rtrim($config['module'], '/');

                    $config['module_base'] = str_replace('admin/']= '', $value_fn);
                    if (is_dir($mod_name)) {
                        $bname = basename($mod_name);
                        $t1 = MW_MODULES_DIR . $config['module'] . DS . $bname;

                        $try_icon = $t1 . '.png';

                    } else {
                        $try_icon = $mod_name . '.png';
                    }

                    $try_icon = normalize_path($try_icon, false);

                    if (is_file($try_icon)) {

                        $config['icon'] = $this->app->url->link_to_file($try_icon);
                    } else {
                        $config['icon'] = $this->app->url->link_to_file($def_icon);
                    }

                    $configs[] = $config;

                    if (isset($config['name']) and $skip_save !== true and $skip_module == false) {
                        if (trim($config['module']) != '') {

                            if ($list_as_element == true) {

                                $this->app->layouts->save($config);
                            } else {

                                $this->save($config);
                                $modules_remove_old = true;
                                $config['installed'] = 'auto';


                                if (!defined('MW_FORCE_MOD_INSTALLED')) {
                                    define('MW_FORCE_MOD_INSTALLED', 1);
                                }
                                $this->install($config);


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
                $uninstall_lock = $this->get('ui=any');
                if (is_array($uninstall_lock) and !empty($uninstall_lock)) {
                    foreach ($uninstall_lock as $value) {
                        $ism = $this->exists($value['module']);
                        if ($ism == false) {
                            $this->delete_module($value['id']);
                            $mn = $value['module'];
                            $q = "DELETE FROM $table WHERE option_group='{$mn}'  ";

                            $this->app->database->q($q);
                        }

                    }
                }
            }


            $c2 = array_merge($cfg_ordered, $cfg);


            $this->app->cache->save($c2, $cache_id, $cache_group);

            return $c2;
        }
    }

    public function save($data_to_save)
    {

        if ($this->app->user->is_admin() == false) {
            return false;
        }
        if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {
            exit(d($data_to_save));
        }

        $table = $this->tables['modules'];
        $save = false;
        // d($table);

        // d($data_to_save);

        if (!empty($data_to_save)) {
            $s = $data_to_save;
            // $s["module_name"] = $data_to_save["name"];

            if (!isset($s["parent_id"])) {
                $s["parent_id"] = 0;
            }
            if (!isset($s["id"]) and isset($s["module"])) {
                $s["module"] = $data_to_save["module"];
                if (!isset($s["module_id"])) {
                    $save = $this->get('ui=any&limit=1&module=' . $s["module"]);

                    if ($save != false and isset($save[0]) and is_array($save[0])) {
                        $s["id"] = intval($save[0]["id"]);
                        $s["position"] = intval($save[0]["position"]);
                        $save = $this->app->database->save($table, $s);
                        $mname_clen = str_replace('\\']= '/', $s["module"]);
                        $mname_clen = $this->app->database->escape_string($mname_clen);
                        if ($s["id"] > 0) {
                            $delid = $s["id"];
                            $del = "DELETE FROM {$table} WHERE module='{$mname_clen}' AND id!={$delid} ";
                            $this->app->database->q($del);
                        }
                    } else {

                        $save = $this->app->database->save($table, $s);
                    }
                } else {

                }

            } else {

                $save = $this->app->database->save($table, $s);
            }
        }
        $this->app->cache->delete('modules' . DIRECTORY_SEPARATOR . 'functions');
        if (!isset($data_to_save['keep_cache'])) {
            if ($save != false) {
                //   $this->app->cache->delete('modules' . DIRECTORY_SEPARATOR . intval($save));
                // $this->app->cache->delete('modules' . DIRECTORY_SEPARATOR . 'global');
                //$this->app->cache->delete('modules' . DIRECTORY_SEPARATOR . '');
            }
        }
        return $save;
    }

    public function install($params)
    {


        if (defined('MW_FORCE_MOD_INSTALLED')) {

        } else {
            if ($this->app->user->is_admin() == false) {
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

        $loc_of_config = $this->locate($module_name, 'config', 1);
        //d($loc_of_config);
        $res = array();
        $loc_of_functions = $this->locate($module_name, 'functions', 1);
        $cfg = false;
        if ($loc_of_config != false and is_file($loc_of_config)) {
            include($loc_of_config);
            if (isset($config)) {
                $cfg = $config;

            }

        }

        //    $uninstall_lock = MW_STORAGE_DIR . 'disabled_modules' . DS;
        //    if (!is_dir($uninstall_lock)) {
        //        mkdir_recursive($uninstall_lock);
        //    }
        //    $unistall_file = $this->app->url->slug($module_name);
        //    $unistall_file = $uninstall_lock . $unistall_file . '.php';
        //    // d($unistall_file);
        //    if (is_file($unistall_file)) {
        //        unlink($unistall_file);
        //    }

        $this_module = $this->get('no_cache=1&ui=any&one=1&module=' . $module_name);

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
            if (isset($cfg['categories'])) {
                $to_save['categories'] = $cfg['categories'];
            }
            if ($to_save['installed'] == '1') {
                if (isset($config)) {
                    if (isset($config['tables']) and is_array($config['tables'])) {
                        $tabl = $config['tables'];
                        foreach ($tabl as $key1 => $fields_to_add) {
                            $table = $this->app->database->real_table_name($key1);
                            $this->app->database->build_table($table, $fields_to_add);
                        }
                    }
                    if (is_array($config) and !empty($config)) {

                        if (isset($config['on_install'])) {

                            $func = $config['on_install'];

                            if (!function_exists($func)) {
                                if (is_file($loc_of_functions)) {
                                    include_once($loc_of_functions);
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
                    if (isset($config['options']) and is_array($config['options'])) {
                        $changes = false;
                        $tabl = $config['options'];
                        foreach ($tabl as $key => $value) {
                            //$table = db_get_real_table_name($key);
                            //d($value);
                            $value['module'] = $module_name;
                            $ch = $this->app->option->set_default($value);
                            //	d($ch);
                            if ($ch == true) {
                                $changes = true;
                            }
                        }

                        if ($changes == true) {

                            $this->app->cache->delete('options/global');
                        }
                    }


                    if (isset($config['options']) and is_array($config['options'])) {
                        $changes = false;
                        $tabl = $config['options'];
                        foreach ($tabl as $key => $value) {
                            //$table = db_get_real_table_name($key);
                            //d($value);
                            $value['module'] = $module_name;
                            $ch = $this->app->option->set_default($value);
                            //	d($ch);
                            if ($ch == true) {
                                $changes = true;
                            }
                        }

                        if ($changes == true) {

                            $this->app->cache->delete('options/global');
                        }
                    }

                    //
                }
            }
            $to_save['keep_cache'] = '1';
            //   $to_save['module'] = $module_name;

            $this->save($to_save);
        }

    }

    public function exists($module_name)
    {
        if (!is_string($module_name)) {
            return false;
        }
        if (trim($module_name) == '') {
            return false;
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

    public function delete_module($id)
    {
        if ($this->app->user->is_admin() == false) {
            return false;
        }
        $id = intval($id);

        $table = $this->tables['modules'];
        $db_categories = $this->table_prefix . 'categories';
        $db_categories_items = $this->table_prefix . 'categories_items';

        $q = "DELETE FROM $table WHERE id={$id}";
        $this->app->database->q($q);

        $q = "DELETE FROM $db_categories_items WHERE rel='modules' AND data_type='category_item' AND rel_id={$id}";
        $this->app->database->q($q);
        $this->app->cache->delete('categories' . DIRECTORY_SEPARATOR . '');
        // $this->app->cache->delete('categories_items' . DIRECTORY_SEPARATOR . '');

        $this->app->cache->delete('modules' . DIRECTORY_SEPARATOR . '');
    }


}


$_mw_modules_info_register = array();
$mw_mod_counter = 0;
$mw_mod_counter_array = array();
$mw_loaded_mod_memory = array();
$mw_defined_module_classes = array();