<?php

namespace MicroweberPackages\Module\Concerns;

use Illuminate\Support\Facades\Artisan;
use MicroweberPackages\LaravelModules\Helpers\StaticModuleCreator;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ScansAndReloadsModules
{
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

        app()->cache_manager->delete('db');
        app()->cache_manager->clear();
        app()->module_repository->clearCache();

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

    /* @deprecated */
    public function scan_for_elements($options = array())
    {
        /* @deprecated */
    }
}
