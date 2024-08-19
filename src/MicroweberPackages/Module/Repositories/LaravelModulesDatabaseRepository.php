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
        if (!Schema::hasTable('modules')) {
            return [];
        }
        // todo
        return [];

        $scannedModules = parent::scan();
        $allModules = app()->module_repository->getAllModules();


        // todo - check if module is enabled
       // dd($scannedModules, 23123123213);
//        if ($this->config ('activator') === 'database')
//        {
//            if (Schema::hasTable ($this->config ('activators.database.table', 'gc_modules')))
//            {
//                $repo_modules = \Goodcatch\Modules\Laravel\Model\Module::get ();
//
//                $repo_modules = $repo_modules
//                    ->reduce(function ($arr, $module) {
//                        if(!file_exists($module->path)){
//                            $module->delete();
//                        }
//                        $arr->push($module);
//                        return $arr;
//                    },  \collect([]));
//
//                foreach (Arr::except($modules, $repo_modules->pluck('name')->values()->all()) as $name => $module) {
//                    $module->enable();
//                }
//
//                foreach (Arr::except($repo_modules
//                    ->reduce(
//                        function($arr, $item) {
//                            $arr [$item->name] = $item;
//                            return $arr;
//                        }, []), \collect($modules)->keys()->all()) as $name => $module) {
//                    if (! empty($module->path) && file_exists ($module->path)){
//                        $modules [$module->name] = $this->createModule($this->app, $module->name, $module->path);
//                    }
//
//                }
//            }
//        }
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
        $modulesPathFromConfig = config('modules.paths.modules');

         foreach ($paths as $key => $path) {
            $manifests = $this->getFiles()->glob("{$path}/module.json");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                try {

                    $composerJsonFile = dirname($manifest) . '/composer.json';
                    $module = Json::make($manifest)->getAttributes();
                    $module ['abspath'] = normalize_path(dirname($manifest));
                    $module ['path'] = str_replace(normalize_path($modulesPathFromConfig), '', $module ['abspath']);
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
}
