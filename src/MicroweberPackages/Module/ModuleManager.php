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

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Database\Utils as DbUtils;
use MicroweberPackages\LaravelModules\Helpers\StaticModuleCreator;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Symfony\Component\Console\Output\BufferedOutput;


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


        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        if (mw_is_installed()) {
            $this->activeLicenses = app()->module_repository->getSystemLicenses();
//            $getSystemLicense = SystemLicenses::get();
//            if ($getSystemLicense != null) {
//                $this->activeLicenses = $getSystemLicense->toArray();
//            }
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

    public function reload_laravel_templates()
    {
        if (!app()->bound('templates')) {
            return;
        }

        config()->set('templates.cache.enabled', false);
        $laravelModules = app('templates');
        $modules = $laravelModules->scan();


        if ($modules) {
            if (!defined('STDIN')) {
                define('STDIN', fopen("php://stdin", "r"));
            }

            foreach ($modules as $module) {
                /** @var \Nwidart\Modules\Laravel\Module $module */

                if (!$module) {
                    continue;
                }
                $composerPath = $module->getPath() . DS . 'composer.json';
                if (!is_file($composerPath)) {
                    continue;
                }
                $moduleJsonPath = $module->getPath() . DS . 'module.json';
                if (!is_file($moduleJsonPath)) {
                    continue;
                }


                $modulePath = $module->getPath();

//                $moduleDisabled = $module->isStatus(0);
//
//                if($moduleDisabled){
//
//                    continue;
//                }


                $json = @file_get_contents($moduleJsonPath);
                $json = @json_decode($json, true);
                if (!$json) {
                    continue;
                }

                $name = $module->getName();
                //  StaticModuleCreator::registerNamespacesFromComposer($composerPath);
                //$module->enable();
                //$module->registerProviders();

                if (!$module->isEnabled()) {
                    $module->enable();
                    $module->register();
                    $module->boot();
                }

                $output = new BufferedOutput();
                $output->setDecorated(false);

                $autoload = $module->getComposerAttr('autoload', $json);

                if (is_dir($modulePath . DS . 'database/migrations')) {
                    $this->log('Migrating template: ' . $name);

                    //  app()->mw_migrator->run([ $modulePath . DS . 'database/migrations']);
                    Artisan::call('template:migrate', ['module' => $name, '--force'], $output);
                    $this->log($output->fetch());
                }


                $output = new BufferedOutput();
                $output->setDecorated(false);

                $this->log('Publishing template: ' . $name);
                Artisan::call('template:publish', ['module' => $name], $output);
                $this->log($output->fetch());

            }
        }
    }

    public function publish_vendor_assets()
    {
        if (!defined('STDIN')) {
            define('STDIN', fopen("php://stdin", "r"));
        }

        $this->log('Publishing vendor assets');


        Artisan::call('vendor:publish', [
            '--provider' => 'Livewire\LivewireServiceProvider',
            '--tag' => 'livewire:assets',
        ]);

        Artisan::call('filament:assets');
        Artisan::call('vendor:publish', ['--force' => true, '--tag' => 'public']);
        Artisan::call('vendor:publish', ['--force' => true, '--tag' => 'laravel-assets']);
        Artisan::call('vendor:publish', ['--force' => true, '--tag' => 'assets']);
        $this->log('Publishing vendor assets ready');


    }

    public function reload_laravel_modules()
    {
        //  return;
        /** @var LaravelModulesFileRepository $laravelModules */
        if (!app()->bound('modules')) {
            return;
        }

        config()->set('modules.cache.enabled', false);
        config()->set('modules.scan.enabled', true);
        $laravelModules = app('modules');
        $modules = $laravelModules->scan();


        if ($modules) {
            if (!defined('STDIN')) {
                define('STDIN', fopen("php://stdin", "r"));
            }

            foreach ($modules as $module) {
                /** @var \Nwidart\Modules\Laravel\Module $module */

                if (!$module) {
                    continue;
                }
                $composerPath = $module->getPath() . DS . 'composer.json';
                if (!is_file($composerPath)) {
                    continue;
                }
                $moduleJsonPath = $module->getPath() . DS . 'module.json';
                if (!is_file($moduleJsonPath)) {
                    continue;
                }


                $modulePath = $module->getPath();

//                $moduleDisabled = $module->isStatus(0);
//                if($moduleDisabled){
//                    continue;
//                }


                $json = @file_get_contents($moduleJsonPath);
                $json = @json_decode($json, true);
                if (!$json) {
                    continue;
                }

                //  StaticModuleCreator::registerNamespacesFromComposer($composerPath);

                if (!$module->isEnabled()) {
                    $module->enable();
                    $module->register();
                    $module->boot();
                }

                //$module->enable();


                // $module->register();

                //$module->registerProviders();
                $name = $module->getName();
                $output = new BufferedOutput();
                $output->setDecorated(false);

                $autoload = $module->getComposerAttr('autoload', $json);

                if (is_dir($modulePath . DS . 'database/migrations')) {
                    $this->log('Migrating module: ' . $name);

                    // app()->mw_migrator->run([ $modulePath . DS . 'database/migrations']);
                    Artisan::call('module:migrate', ['module' => $name, '--force'], $output);
                    $this->log($output->fetch());
                }


                $output = new BufferedOutput();
                $output->setDecorated(false);

                $this->log('Publishing module: ' . $name);
                Artisan::call('module:publish', ['module' => $name], $output);
                $this->log($output->fetch());


                $moduleToSave = [];
                $moduleToSave['module'] = $module->getLowerName();
                $moduleToSave['name'] = $module->get('name');
                $moduleToSave['description'] = $module->get('description');
                $moduleToSave['author'] = $module->get('author');
                $moduleToSave['website'] = $module->get('website');
                $moduleToSave['keywords'] = $module->get('keywords');
                $moduleToSave['position'] = $module->getPriority();
                $moduleToSave['type'] = 'laravel-module';
                $moduleToSave['installed'] = 1;
                $moduleToSave['settings'] = [];
                $moduleToSave['settings']['composer_autoload'] = $autoload;
                $moduleToSave['settings']['module_json'] = $json;
                $moduleToSave['settings']['module_path'] = $modulePath;
                $moduleToSave['settings']['module_path_relative'] = str_replace(base_path(), '', $modulePath);

                //  app()->module_repository->installLaravelModule($moduleToSave);

            }
        }
    }

    /* @deprecated */

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
            // if (is_cli()) {
            $this->_install_mode = true;
            //  }
            if (!$list_as_element) {

                AbstractRepository::disableCache();

                $this->reload_laravel_modules();
                $this->reload_laravel_templates();
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


    }

    public function save($data_to_save)
    {
        if (mw()->user_manager->is_admin() == false and $this->_install_mode == false) {
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


        if (app()->bound('microweber')) {
            if (app()->microweber->hasModule($module_name)) {
                return true;
            }
        }


        if (app()->bound('modules')) {

            if (app()->modules->find($module_name)) {
                return true;
            }
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

    /* @deprecated */
    public function locate($module_name, $custom_view = false, $no_fallback_to_view = false)
    {

        return $this->dir($module_name);



        $template_dir = templates_dir() . 'default/';

        if (defined('ACTIVE_TEMPLATE_DIR')) {
            $template_dir = ACTIVE_TEMPLATE_DIR;
            //  $this->app->content_manager->define_constants();
        }

        //  dd(debug_backtrace(1));

        $module_name = trim($module_name);
        // prevent hack of the directory
        $module_name = str_replace('\\', '/', $module_name);
        $module_name = sanitize_path($module_name);

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



    public function ui($name, $arr = false)
    {
        return $this->app->ui->module($name, $arr);
    }

    public function load($module_name, $attrs = array())
    {
        return $this->app->parser->load($module_name, $attrs);

    }

    public function format_attr($attr_value)
    {
        $attr_value = str_replace('"', '&quot;', $attr_value);
        $attr_value = str_replace("'", '&#39;', $attr_value);
        $attr_value = str_replace('<', '&lt;', $attr_value);
        $attr_value = str_replace('>', '&gt;', $attr_value);
        $attr_value = str_replace('&', '&amp;', $attr_value);
        $attr_value = str_replace(']', '&#93;', $attr_value);
        $attr_value = str_replace('[', '&#91;', $attr_value);
        $attr_value = str_replace('{', '&#123;', $attr_value);
        $attr_value = str_replace('}', '&#125;', $attr_value);
        $attr_value = str_replace('`', '&#96;', $attr_value);
        $attr_value = str_replace(';', '&#59;', $attr_value);
        return $attr_value;
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

            return asset('modules/' . $lowerName) ;
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

    /* @deprecated */
    public function path($module_name)
    {
        return $this->dir($module_name);
    }

    /* @deprecated */
    public function dir($module_name)
    {
        $mod = app()->modules->find($module_name);
        if ($mod) {
            return normalize_path($mod->getPath());
        }
    }

    public function is_installed($module_name)
    {

        if (!mw_is_installed()) {
            return false;
        }
        $module_name = trim($module_name);
        $module_namei = $module_name;
        if (strstr($module_name, 'admin')) {
            $module_namei = str_ireplace('\\admin', '', $module_namei);
            $module_namei = str_ireplace('/admin', '', $module_namei);
        }

        if (app()->bound('microweber')) {
            if (app()->microweber->hasModule($module_name)) {
                return true;
            }
        }

        if (app()->bound('modules')) {
            $module = app()->modules->find($module_name);
            if ($module && $module->isEnabled()) {
                return true;
            }
        }


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
                $this->app->database_manager->update_position_field($table, $indx);
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

            $save = $this->app->database_manager->save($table, $s);
        }
        app()->module_repository->clearCache();

        return $save;
    }

    /* @deprecated */
    public function scan_for_elements($options = array())
    {
        /* @deprecated */
    }


    public $logger = null;


    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }


}
