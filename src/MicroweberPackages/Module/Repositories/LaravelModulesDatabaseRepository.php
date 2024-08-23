<?php

namespace MicroweberPackages\Module\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\FileRepository;
use Nwidart\Modules\Json;
use Nwidart\Modules\Process\Updater;
use Symfony\Component\Process\Process;

class LaravelModulesDatabaseRepository extends FileRepository
{

    /**
     * @var ConfigRepository
     */
    private $config;

    /**
     * @var $modules array cache
     */
    protected $modules_cache = [];

    /**
     * The constructor.
     * @param Container $app
     * @param string|null $path
     */
    public function __construct(Container $app, $path = null)
    {
        parent::__construct($app, $path);

        $this->config = $app ['config'];
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if (!empty($this->config->get('app.key'))) {
            parent::boot();
        }
    }

    /**
     * @inheritDoc
     *
     */
    public function register(): void
    {
        if (!empty($this->config->get('app.key'))) {
            parent::register();
        }
    }


    /**
     * {@inheritdoc}
     */
    protected function createModule(...$args)
    {


        $key = md5(implode('-', \collect($args)->filter(function ($arg) {
            return 'string' === gettype($arg);
        })->values()->all()));
        if (!Arr::has($this->modules_cache, $key)) {
            Arr::set($this->modules_cache, $key, new \Nwidart\Modules\Laravel\Module (...$args));
        }
        return Arr::get($this->modules_cache, $key);
    }

    /**
     * Update dependencies for the specified module.
     *
     * @param string $module
     */
    public function update($module)
    {
        dd('update', $module);
    }

    /**
     * Install the specified module.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool $subtree
     *
     * @return Process
     */
    public function install($name, $version = 'dev-master', $type = 'composer', $subtree = false)
    {

        //todo
        dd('install', $name, $version, $type, $subtree);
    }

    /**
     * Get & scan all modules.
     *
     * @return array
     */
    public function scan()
    {

        static $alreadyScanned = false;
        if (!Schema::hasTable('modules')) {
            return [];
        }
        if (!$alreadyScanned and $this->config('scan.enabled')) {
            $scannedModules = $this->scanJson();
            $alreadyScanned = true;
            if ($scannedModules) {
                foreach ($scannedModules as $scannedModule) {
                    //if (!app()->module_repository->getModule($scannedModule['alias'])) {
                    app()->module_repository->installLaravelModule($scannedModule);
                    // }
                }
            }
        }

        $modules = $this->createAllModules();


        return $modules;


    }

    public function forceScan()
    {
        static $alreadyScanned = false;

        if($alreadyScanned){
            return;
        }
        $scannedModules = $this->scanJson();
        $alreadyScanned = true;
        if ($scannedModules) {
            foreach ($scannedModules as $scannedModule) {
                //if (!app()->module_repository->getModule($scannedModule['alias'])) {
                app()->module_repository->installLaravelModule($scannedModule);
                // }
            }
        }
    }

    public function createAllModules()
    {
        if(!mw_is_installed()){
            return [];
        }

        $allModules = app()->module_repository->getAllModules();

        $modules = [];

        if(!$allModules){
            return $modules;
        }

        foreach ($allModules as $allModulesItem) {

            if (isset($allModulesItem['is_installed']) and $allModulesItem['is_installed'] == 0) {
                continue;
            }
            if (!isset($allModulesItem['settings']) or empty($allModulesItem['settings'])) {
                continue;
            }
            if (!isset($allModulesItem['name']) or empty($allModulesItem['name'])) {
                continue;
            }


            $settings = $allModulesItem['settings'];

            if (!isset($settings['path']) or empty($settings['path'])) {
                continue;
            }
            if (!isset($settings['composer']) or empty($settings['composer'])) {
                continue;
            }



            $path = $settings['path'] ?? '';
            $pathRelativeToBasepath = $settings['pathRelativeToBasepath'] ?? '';

            if ($pathRelativeToBasepath) {
                $pathRelativeToBasepathExist = normalize_path(base_path($pathRelativeToBasepath));

                if (is_dir($pathRelativeToBasepathExist)) {
                    $path = $pathRelativeToBasepathExist;
                }
            }



            if (!$path) {
                continue;
            }
            if (!is_dir($path)) {
                continue;
            }
            $manifest = $path . DS . 'module.json';
            $autoloadNamespaces = $settings['composer']['autoload']['psr-4'] ?? [];
            $autoloadFiles = $settings['composer']['autoload']['files'] ?? [];
            if($autoloadNamespaces){
                foreach ($autoloadNamespaces as  $autoloadNamespace=>$autoloadNamespacePath) {
                    $autoloadNamespacePathFull = normalize_path($path . DS . $autoloadNamespacePath);
                     autoload_add_namespace($autoloadNamespacePathFull,$autoloadNamespace);
                }
            }
            if($autoloadFiles){
                foreach ($autoloadFiles as $autoloadFile) {
                    if(is_file($path . DS . $autoloadFile)) {
                        include_once $path . DS . $autoloadFile;
                    }
                }
            }


            $moduleManifest = Json::make($manifest)->getAttributes();




            $module = new \Nwidart\Modules\Laravel\Module ($this->app, $moduleManifest['name'], $path);
         //   ..$module = new \Nwidart\Modules\Laravel\Module ($this->app, $allModulesItem['name'], $path);
          //  $module->registerProviders();
          //  $module->registerAliases();

            $modules [$moduleManifest['name']] = $module;
            //$modules [$allModulesItem['name']] = $module;
            //$modules [$allModulesItem['name']] = $this->createModule($this->app, $allModulesItem['name'], $path);
        }

        return $modules;
    }



    /**
     * Get & scan all modules.
     *
     * @return array
     */
    public function scanJson()
    {
        $paths = $this->getScanPaths();

        $modules = [];
        //  $modulesPathFromConfig = config('modules.paths.modules');
        $basePath = base_path();
        $basePath = normalize_path($basePath);
        foreach ($paths as $key => $path) {
            $manifests = $this->getFiles()->glob("{$path}/module.json");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                try {

                    $composerJsonFile = dirname($manifest) . '/composer.json';
                    $module = Json::make($manifest)->getAttributes();
                    $module ['abspath'] = normalize_path(dirname($manifest));
                    $module ['pathRelativeToBasepath'] = str_replace(normalize_path($basePath), '', $module ['abspath']);

                    $module ['path'] = str_replace(normalize_path($path), '', $module ['abspath']);
                    $module ['path'] = trim($module ['path'], DS);
                    $module ['path'] = trim($module ['path'], '/');
                    $module ['path'] = str_replace(DS, '/', $module ['path']);

                    if (is_file($composerJsonFile)) {
                        $composerJson = Json::make($composerJsonFile)->getAttributes();
                        $module['composer'] = $composerJson;
                    }


                    $modules [] = $module;
                } catch (\Exception $e) {

                }
            }
        }

        return $modules;
    }

    public function all(): array
    {

        if (!$this->config('cache.enabled')) {
            return $this->scan();
        }

        return $this->formatCached($this->getCached());
    }

    public function getByStatus($status): array
    {
        $modules = [];

        $all = $this->all();
//        /** @var Module $module */

        if ($all) {

            foreach ($all as $name => $module) {
                //  if ($module->isStatus($status)) {
                $modules[$name] = $module;
                //  }
            }
        }
        return $modules;
    }

    public function find(string $name)
    {
        $all = $this->all();

        return $all[$name] ?? null;

    }
}
