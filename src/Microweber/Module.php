<?php
namespace Microweber;


$_mw_modules_info_register = array();
event_bind('mw_db_init', mw('Microweber\Module')->db_init());


$mw_mod_counter = 0;
$mw_mod_counter_array = array();
$mw_loaded_mod_memory = array();
$mw_defined_module_classes = array();
class Module
{
    public $app;
    function __construct($app=null)
    {
        if (!defined("MW_DB_TABLE_MODULES")) {
            define('MW_DB_TABLE_MODULES', MW_TABLE_PREFIX . 'modules');
        }

        if (!defined("MW_DB_TABLE_ELEMENTS")) {
            define('MW_DB_TABLE_ELEMENTS', MW_TABLE_PREFIX . 'elements');
        }

        if (!defined("MW_DB_TABLE_MODULE_TEMPLATES")) {
            define('MW_DB_TABLE_MODULE_TEMPLATES', MW_TABLE_PREFIX . 'module_templates');
        }


        if (!defined('EMPTY_MOD_STR')) {
            define("EMPTY_MOD_STR", "<div class='mw-empty-module '>{module_title} {type}</div>");
        }

        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw('application');
            }

        }
    }

    public function get($params = false)
    {

        $table = MW_TABLE_PREFIX . 'modules';
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
            $params['module'] = str_replace('/admin', '', $params['module']);
        }
        if (!isset($params['ui'])) {
            $params['ui'] = 1;
            //
        }

        if (isset($params['ui']) and $params['ui'] == 'any') {
            // d($params);
            unset($params['ui']);
        }

        return $this->app->db->get($params);
    }

    public function load($module_name, $attrs = array())
    {

        $is_element = false;
        $custom_view = false;
        if (isset($attrs['view'])) {

            $custom_view = $attrs['view'];
            $custom_view = trim($custom_view);
            $custom_view = str_replace('\\', '/', $custom_view);
            $custom_view = str_replace('..', '', $custom_view);
        }

        if ($custom_view != false and strtolower($custom_view) == 'admin') {
            if (is_admin() == false) {
                mw_error('Not logged in as admin');
            }
        }

        $module_name = trim($module_name);
        $module_name = str_replace('\\', '/', $module_name);
        $module_name = str_replace('..', '', $module_name);
        // prevent hack of the directory
        $module_name = reduce_double_slashes($module_name);

        $module_namei = $module_name;

        if (strstr($module_name, 'admin')) {

            $module_namei = str_ireplace('\\admin', '', $module_namei);
            $module_namei = str_ireplace('/admin', '', $module_namei);
        }

        //$module_namei = str_ireplace($search, $replace, $subject)e


        $uninstall_lock = $this->get('one=1&ui=any&module=' . $module_namei);

        if (isset($uninstall_lock["installed"]) and $uninstall_lock["installed"] != '' and intval($uninstall_lock["installed"]) != 1) {
            return '';
        }

        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            mw('content')->define_constants();
        }

        $module_in_template_dir = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '';
        $module_in_template_dir = normalize_path($module_in_template_dir, 1);
        $module_in_template_file = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '.php';
        $module_in_template_file = normalize_path($module_in_template_file, false);

        $try_file1 = false;

        $mod_d = $module_in_template_dir;
        $mod_d1 = normalize_path($mod_d, 1);
        $try_file1zz = $mod_d1 . 'index.php';

        if (is_dir($module_in_template_dir) and is_file($try_file1zz)) {
            $try_file1 = $try_file1zz;

        } elseif (is_file($module_in_template_file)) {
            $try_file1 = $module_in_template_file;
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

            $config['path_to_module'] = $config['mp'] = $config['path'] = normalize_path((dirname($try_file1)) . '/', true);
            $config['the_module'] = $module_name;
            $config['module'] = $module_name;

            $config['module_name'] = dirname($module_name);

            $config['module_name_url_safe'] = module_name_encode($module_name);
            $find_base_url = $this->app->url->current(1);
            if ($pos = strpos($find_base_url, ':' . $module_name) or $pos = strpos($find_base_url, ':' . $config['module_name_url_safe'])) {
                //	d($pos);
                $find_base_url = substr($find_base_url, 0, $pos) . ':' . $config['module_name_url_safe'];
            }
            $config['url'] = $find_base_url;

            $config['url_main'] = $config['url_base'] = strtok($find_base_url, '?');

            $config['module_api'] = $this->app->url->site('api/' . $module_name);
            $config['module_view'] = $this->app->url->site('module/' . $module_name);
            $config['ns'] = str_replace('/', '\\', $module_name);
            $config['module_class'] = module_css_class($module_name);
            $config['url_to_module'] = $this->app->url->link_to_file($config['path_to_module']);
            $get_module_template_settings_from_options = mw_var('get_module_template_settings_from_options');


            if (isset($attrs['id'])) {
                $attrs['id'] = str_replace('__MODULE_CLASS_NAME__', $config['module_class'], $attrs['id']);

                $template = false;

            }

            //$config['url_to_module'] = rtrim($config['url_to_module'], '///');
            $lic = load_module_lic($module_name);
            //  $lic = 'valid';
            if ($lic != false) {
                $config['license'] = $lic;
            }

            if (isset($attrs['module-id']) and $attrs['module-id'] != false) {
                $attrs['id'] = $attrs['module-id'];
            }

            if (!isset($attrs['id'])) {
                global $mw_mod_counter;
                $mw_mod_counter++;

                $attrs1 = crc32(serialize($attrs) . $this->app->url->segment(0) . $mw_mod_counter);


                $attrs['id'] = ($config['module_class'] . '-' . $attrs1);

            }
            if (isset($attrs['id']) and strstr($attrs['id'], '__MODULE_CLASS_NAME__')) {
                $attrs['id'] = str_replace('__MODULE_CLASS_NAME__', $config['module_class'], $attrs['id']);

                //$attrs['id'] = ('__MODULE_CLASS__' . '-' . $attrs1);
            }

            $l1 = new \Microweber\View($try_file1);
            $l1->config = $config;
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
                //d($mw_restore_get);
                $l1->_GET = $mw_restore_get;
                $_GET = $mw_restore_get;
            }

            $l1->params = $attrs;
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

                //$lic_l1 = null;
                unset($lic_l1);
                $module_file = $lic_l1e_file . $module_file;
            }
            // d($module_file);
            // $mw_loaded_mod_memory[$function_cache_id] = $module_file;
            return $module_file;
        } else {
            //define($cache_content, FALSE);
            // $mw_loaded_mod_memory[$function_cache_id] = false;
            return false;
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

    public function templates($module_name, $template_name = false)
    {
        $module_name = str_replace('admin', '', $module_name);
        $module_name_l = $this->locate($module_name);

        $module_name_l = dirname($module_name_l) . DS . 'templates' . DS;

        $module_name_l_theme = ACTIVE_TEMPLATE_DIR . 'modules' . DS . $module_name . DS . 'templates' . DS;
        //	d($module_name_l_theme);
        if (!is_dir($module_name_l)) {
            return false;
        } else {
            if ($template_name == false) {
                $options = array();
                $options['no_cache'] = 1;
                $options['for_modules'] = 1;
                $options['path'] = $module_name_l;
                $module_name_l = layouts_list($options);
                if (is_dir($module_name_l_theme)) {
                    $options['path'] = $module_name_l_theme;
                    //d($options);
                    $module_skins_from_theme = layouts_list($options);
                    //	d($module_skins_from_theme);
                    if (is_array($module_skins_from_theme)) {
                        if (!is_array($module_name_l)) {
                            $module_name_l = array();
                        }
                        $fnfound = array();
                        $comb = array_merge($module_skins_from_theme, $module_name_l);
                        array_unique($comb);
                        if (!empty($comb)) {
                            foreach ($comb as $k1 => $itm) {
                                if (!in_array($itm['layout_file'], $fnfound)) {
                                    $fnfound[] = $itm['layout_file'];
                                } else {
                                    unset($comb[$k1]);
                                }
                            }
                        }
                        $module_name_l = ($comb);
                    }
                    // d($module_skins_from_theme);
                }

                return $module_name_l;
            } else {

                $template_name = str_replace('..', '', $template_name);


                $is_dot_php = get_file_extension($template_name);
                if ($is_dot_php != false and $is_dot_php != 'php') {
                    $template_name = $template_name . '.php';
                }

                $tf = $module_name_l . $template_name;
                $tf_theme = $module_name_l_theme . $template_name;
                $tf_from_other_theme = MW_TEMPLATES_DIR . $template_name;
                $tf_from_other_theme = normalize_path($tf_from_other_theme, false);


                if (strstr($tf_from_other_theme, 'modules') and is_file($tf_from_other_theme)) {
                    return $tf_from_other_theme;
                } else if (is_file($tf_theme)) {
                    return $tf_theme;
                } else if (is_file($tf)) {
                    return $tf;
                } else {
                    return false;
                }


            }

            // d($module_name_l);
        }
    }


    public function url($module_name)
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

        static $checked = array();

        if (!isset($checked[$module_name])) {
            $ch = $this->locate($module_name, $custom_view = false);

            if ($ch != false) {
                $ch = dirname($ch);
                $ch = $this->app->url->link_to_file($ch);
                $ch = $ch . '/';
                //	$ch = trim($ch,'\//');

                $checked[$module_name] = $ch;
            } else {
                $checked[$module_name] = false;
            }
        }

        $this->app->cache->save($checked[$module_name], $function_cache_id, $cache_group);

        return $checked[$module_name];

    }

    public function info($module_name)
    {
        //d($module_name);
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
                //$ch = $this->app->url->link_to_file($ch);
                $ch = normalize_path($ch, 1);
                //	$ch = trim($ch,'\//');

                $checked[$module_name] = $ch;
            } else {
                $checked[$module_name] = false;
            }
        }

        $this->app->cache->save($checked[$module_name], $function_cache_id, $cache_group, 'files');

        return $checked[$module_name];

    }


    public function locate($module_name, $custom_view = false, $no_fallback_to_view = false)
    {

        if (!defined("ACTIVE_TEMPLATE_DIR")) {
            mw('content')->define_constants();
        }

        $module_name = trim($module_name);
        $module_name = str_replace('\\', '/', $module_name);
        $module_name = str_replace('..', '', $module_name);
        // prevent hack of the directory
        $module_name = reduce_double_slashes($module_name);

        $module_in_template_dir = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '';

        $module_in_template_dir = normalize_path($module_in_template_dir, 1);
        $module_in_template_file = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '.php';
        $module_in_template_file = normalize_path($module_in_template_file, false);
        //d($module_in_template_dir);
        $module_in_default_file12 = MW_MODULES_DIR . $module_name . '.php';

        $try_file1 = false;

        $mod_d = $module_in_template_dir;
        $mod_d1 = normalize_path($mod_d, 1);
        $try_file1x = $mod_d1 . 'index.php';

        if (is_file($try_file1x)) {

            $try_file1 = $try_file1x;
        } elseif (is_file($module_in_template_file)) {
            $try_file1 = $module_in_template_file;
            //d($try_file1);
        } elseif (is_file($module_in_default_file12) and $custom_view == false) {
            $try_file1 = $module_in_default_file12;
            //	d($try_file1);
        } else {

            $module_in_default_dir = MW_MODULES_DIR . $module_name . '';
            $module_in_default_dir = normalize_path($module_in_default_dir, 1);
            //  d($module_in_default_dir);
            $module_in_default_file = MW_MODULES_DIR . $module_name . '.php';
            $module_in_default_file_custom_view = MW_MODULES_DIR . $module_name . '_' . $custom_view . '.php';

            $element_in_default_file = MW_ELEMENTS_DIR . $module_name . '.php';
            $element_in_default_file = normalize_path($element_in_default_file, false);

            //
            $module_in_default_file = normalize_path($module_in_default_file, false);

            if (is_file($module_in_default_file)) {

                if ($custom_view == true and is_file($module_in_default_file_custom_view)) {
                    $try_file1 = $module_in_default_file_custom_view;
                    if ($no_fallback_to_view == true) {
                        return $try_file1;
                    }

                } else {

                    //  $try_file1 = $module_in_default_file;
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

                        //temp
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

    public function is_installed($module_name)
    {

        $module_name = trim($module_name);

        $module_namei = $module_name;
        if (strstr($module_name, 'admin')) {

            $module_namei = str_ireplace('\\admin', '', $module_namei);
            $module_namei = str_ireplace('/admin', '', $module_namei);
        }

        //$module_namei = str_ireplace($search, $replace, $subject)e

        $uninstall_lock = $this->get('one=1&ui=any&module=' . $module_namei);

        if (empty($uninstall_lock) or (isset($uninstall_lock["installed"]) and $uninstall_lock["installed"] != '' and intval($uninstall_lock["installed"]) != 1)) {
            return false;
        } else {
            return true;
        }
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

    public function db_init()
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'modules' . __FUNCTION__ . crc32($function_cache_id);

        $cache_content = $this->app->cache->get($function_cache_id, 'db', 'files');

        if (($cache_content) != false) {

            return $cache_content;
        }

        $table_name = MW_DB_TABLE_MODULES;
        $table_name2 = MW_DB_TABLE_ELEMENTS;
        $table_name3 = MW_DB_TABLE_MODULE_TEMPLATES;

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('expires_on', 'datetime default NULL');

        $fields_to_add[] = array('created_by', 'int(11) default NULL');

        $fields_to_add[] = array('edited_by', 'int(11) default NULL');

        $fields_to_add[] = array('name', 'TEXT default NULL');
        $fields_to_add[] = array('parent_id', 'int(11) default NULL');
        $fields_to_add[] = array('module_id', 'TEXT default NULL');

        $fields_to_add[] = array('module', 'TEXT default NULL');
        $fields_to_add[] = array('description', 'TEXT default NULL');
        $fields_to_add[] = array('icon', 'TEXT default NULL');
        $fields_to_add[] = array('author', 'TEXT default NULL');
        $fields_to_add[] = array('website', 'TEXT default NULL');
        $fields_to_add[] = array('help', 'TEXT default NULL');

        $fields_to_add[] = array('installed', 'int(11) default NULL');
        $fields_to_add[] = array('ui', 'int(11) default 0');
        $fields_to_add[] = array('position', 'int(11) default NULL');
        $fields_to_add[] = array('as_element', 'int(11) default 0');
        $fields_to_add[] = array('ui_admin', 'int(11) default 0');
        $fields_to_add[] = array('is_system', 'int(11) default 0');

        $fields_to_add[] = array('version', 'varchar(11) default NULL');

        $fields_to_add[] = array('notifications', 'int(11) default 0');

        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        $fields_to_add[] = array('layout_type', 'varchar(110) default "static"');

        \mw('Microweber\DbUtils')->add_table_index('module', $table_name, array('module(255)'));
        \mw('Microweber\DbUtils')->add_table_index('module_id', $table_name, array('module_id(255)'));

        \mw('Microweber\DbUtils')->build_table($table_name2, $fields_to_add);

        \mw('Microweber\DbUtils')->add_table_index('module', $table_name2, array('module(255)'));
        \mw('Microweber\DbUtils')->add_table_index('module_id', $table_name2, array('module_id(255)'));

        $fields_to_add = array();
        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        $fields_to_add[] = array('edited_by', 'int(11) default NULL');
        $fields_to_add[] = array('module_id', 'TEXT default NULL');
        $fields_to_add[] = array('name', 'TEXT default NULL');
        $fields_to_add[] = array('module', 'TEXT default NULL');
        \mw('Microweber\DbUtils')->build_table($table_name3, $fields_to_add);

        $this->app->cache->save(true, $function_cache_id, $cache_group = 'db', 'files');
        // $fields = (array_change_key_case ( $fields, CASE_LOWER ));
        return true;

        //print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
    }


    public function license($module_name = false)
    {
        return true;

    }


}