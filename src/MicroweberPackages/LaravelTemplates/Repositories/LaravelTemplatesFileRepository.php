<?php

namespace MicroweberPackages\LaravelTemplates\Repositories;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesCacheRepository;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use MicroweberPackages\LaravelModules\Traits\ModulesRepositoryTrait;
use MicroweberPackages\LaravelTemplates\Contracts\LaravelTemplatesCacheRepositoryContract;
use MicroweberPackages\LaravelTemplates\Helpers\StaticTemplateCreator;
use Nwidart\Modules\Collection;


class LaravelTemplatesFileRepository extends LaravelModulesFileRepository
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application|\Laravel\Lumen\Application
     */
    protected $app;

    /**
     * The module path.
     *
     * @var string|null
     */
    protected $path;

    /**
     * The scanned paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * @var string
     */
    protected $stubPath;

    /**
     * @var UrlGenerator
     */
    private $url;

    /**
     * @var ConfigRepository
     */
    private $config;

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * @var CacheManager
     */
    private $cache;




    use ModulesRepositoryTrait;
    public $configPrefix = 'templates';
    public function __construct(Container $app, $path = null)
    {
        $this->setConfigPrefix('templates');

        $this->app = $app;
        $this->path = $path;
        $this->url = $app['url'];
        $this->config = app()->config;
        $this->files = $app['files'];
        $this->cache = $app['cache'];

       // $this->cacheRepository = new LaravelModulesCacheRepository();
       $this->cacheRepository = app()->make(LaravelTemplatesCacheRepositoryContract::class);


    }

    public function boot(): void
    {
        Debugbar::startMeasure('module_boot', 'Booting modules');
        $modules = $this->getOrdered();

        foreach ($modules as $module) {
            /** @var LaravelModule $module */

            $module->boot();
        }
        Debugbar::stopMeasure('module_boot');
    }


    public function getUsedStoragePath(): string
    {

        $directory = storage_path('app/modules');
        if ($this->getFiles()->exists($directory) === false) {
            $this->getFiles()->makeDirectory($directory, 0777, true);
        }

        $path = storage_path('app/modules/templates.used');
        if (!$this->getFiles()->exists($path)) {
            $this->getFiles()->put($path, '');
        }

        return $path;
    }
//


    public function config(string $key, $default = null)
    {
        return $this->config->get('templates.'.$key, $default);
    }


    protected function createModule(...$args)
    {

        return StaticTemplateCreator::createModule(...$args);

    }

    public function all(): array
    {


        $enabledCache = $this->config[$this->configPrefix]['cache']['enabled'] ?? false;

        if ($this->memoryCacheDisabled) {
            $enabledCache = false;
        }

        if (!$enabledCache) {
            return $this->scan();
        }
        $allCached = $this->cacheRepository->all();
        if ($allCached) {
            return $this->cacheRepository->all();
        }
        start_measure('all_templates', 'all_templates');
        $hasCached = $this->getCached();

        stop_measure('all_templates');

        return $this->formatCached($hasCached);
    }

    public function toCollection(): Collection
    {

        return new Collection($this->scan());
    }
    public function getPath(): string
    {

        return $this->path ?: $this->config('paths.modules', base_path('Templates'));
    }

    public function getCached()
    {


        return $this->cache->store($this->config->get('templates.cache.driver'))
            ->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
                $arr = [];
                $modules = $this->toCollection();

                foreach ($modules as $key => $module) {
                    $moduleArr = $module->json('module.json')->getAttributes();

                    $composerJson = $module->json('composer.json')->getAttributes();

                    $moduleArr['composer'] = $composerJson;
                    $moduleArr['path'] = $module->getPath();
                    $arr[$key] = $moduleArr;
                }

                return $arr;
            });

    }

    public function register(): void
    {
        Debugbar::startMeasure('templates_register', 'Registering templates');
        $modules = $this->getOrdered();

        foreach ($modules as $module) {
            /** @var LaravelModule $module */
            $module->register();
        }
        Debugbar::stopMeasure('templates_register');
    }

    public function getFiles(): Filesystem
    {

        return $this->files;
    }

    public function flushCache()
    {

        self::$cachedModules = [];
        $this->scanMemory = [];
        $this->cache->store($this->config->get($this->configPrefix . '.cache.driver'))->forget($this->config('cache.key'));
        $this->cacheRepository->flush();
    }

//    public function findOrFail(string $name)
//    {
//        $module = $this->find($name);
//
//        if ($module !== null) {
//            return $module;
//        }
//
//        throw new ModuleNotFoundException("Template [{$name}] does not exist!");
//    }
}
