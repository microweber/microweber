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
use MicroweberPackages\Database\Facades\DatabaseManager;
use MicroweberPackages\Url\Facades\UrlManager;
use MicroweberPackages\Module\Concerns\FormatsModuleAttributes;
use MicroweberPackages\Module\Concerns\ResolvesModulePaths;
use MicroweberPackages\Module\Concerns\ScansAndReloadsModules;

class ModuleManager
{
    use FormatsModuleAttributes;
    use ResolvesModulePaths;
    use ScansAndReloadsModules;

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

        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        if (mw_is_installed()) {
            $this->activeLicenses = app()->module_repository->getSystemLicenses();
        }

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

    public function save($data_to_save)
    {
        if (app()->user_manager->is_admin() == false and $this->_install_mode == false) {
            return false;
        }
        if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {
        }

        $table = 'modules';
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

                        $save = DatabaseManager::save($table, $s);
                        // print_r($save);
                        $mname_clen = str_replace('\\', '/', $s['module']);
                        if ($s['id'] > 0) {

                            //$delid = $s["id"];
                            DB::table($table)->where('id', '!=', $s['id'])->where('module', $s['module'])->delete();
                            // $del = "DELETE FROM {$table} WHERE module='{$mname_clen}' AND id!={$delid} ";
                            //DatabaseManager::q($del);
                        }
                    } else {

                        $save = DatabaseManager::save($table, $s);
                    }
                }
            } else {
                $save = DatabaseManager::save($table, $s);
            }
        }
        return $save;
    }

    /* @deprecated */
    public function get_modules($params)
    {
        return $this->get($params);
    }

    /* @deprecated */
    public function get($params = false)
    {
//        if (!mw_is_installed()) {
//            return false;
//        }

        $table = 'modules';
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $options = $params2;
        }
        if (!is_array($params)) {
            $params = array();
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
            $params['nolimit'] = true;
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

        $data = DatabaseManager::get($params);

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

    public function ui($name, $arr = false)
    {
        return $this->app->ui->module($name, $arr);
    }

    public function load($module_name, $attrs = array())
    {
        return $this->app->parser->load($module_name, $attrs);

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
    public function templates($module_name, $template_name = false, $get_settings_file = false, $template_dir = false)
    {

        if (app()->bound('microweber')) {
            $microweberModule = app()->microweber->hasModule($module_name);
            $templates = [];

            if ($microweberModule) {
                $templates = app()->microweber->getTemplates($module_name, $template_name);
                return $templates;
            }

        }

    }

    /* @deprecated */
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
        $laravelModule = app()->modules->find($module_name);

        if ($laravelModule) {
            $lowerName = $laravelModule->getLowerName();

            return asset('modules/' . $lowerName);
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

        $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id . site_url());

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
                $ch = UrlManager::link_to_file($ch);
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

    /* @deprecated */
    public function reorder_modules($data)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = 'modules';
        foreach ($data as $value) {
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    ++$i;
                }
                DatabaseManager::update_position_field($table, $indx);
                app()->module_repository->clearCache();

                return $indx;
            }
        }
        // $this->db_init();
    }

    /* @deprecated */
    public function uninstall($params)
    {
        if (is_array($params) and isset($params['for_module'])) {
            $module_name = $params['for_module'];
        } else {
            $module_name = $params;
        }

        if (app()->bound('modules')) {
            $module = app()->modules->find($module_name);
            if ($module) {
                if ($module->isEnabled()) {
                    $module->disable();
                    app()->module_repository->clearCache();
                }
            }
        }

        $this->app->cache_manager->clear();
        app()->module_repository->clearCache();
    }

    public function set_installed($params)
    {

        if (is_array($params) and isset($params['for_module'])) {
            $module_name = $params['for_module'];
        } else {
            $module_name = $params;
        }

        if (app()->bound('modules')) {
            $module = app()->modules->find($module_name);
            if ($module) {
                if (!$module->isEnabled()) {
                    $module->enable();
                    app()->module_repository->clearCache();
                }
            }
        }

    }

    public function get_saved_modules_as_template($params)
    {
        $params = parse_params($params);

        if ($this->app->user_manager->is_admin() == false) {
            return false;
        }

        $table = 'module_templates';

        $params['table'] = $table;
        $params['order_by'] = 'position asc';
        $data = DatabaseManager::get($params);

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
            DatabaseManager::delete_by_id($table, $c_id);
        }

        if (isset($data['ids']) and is_array($data['ids'])) {
            foreach ($data['ids'] as $value) {
                $c_id = intval($value);
                DatabaseManager::delete_by_id($table, $c_id);
            }
        }
        app()->module_repository->clearCache();

    }

    /* @deprecated */
    public function save_module_as_template($data_to_save)
    {
        if ($this->app->user_manager->is_admin() == false) {
            return false;
        }

        $table = 'module_templates';
        $save = false;

        if (!empty($data_to_save)) {
            $s = $data_to_save;

            $save = DatabaseManager::save($table, $s);
        }
        app()->module_repository->clearCache();

        return $save;
    }

    public $logger = null;

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }

}
