<?php

namespace MicroweberPackages\LaravelTemplates\Repositories;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use MicroweberPackages\LaravelTemplates\Helpers\StaticTemplateCreator;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;
use Nwidart\Modules\Exceptions\ModuleNotFoundException;
use Nwidart\Modules\FileRepository;
use Nwidart\Modules\Json;

class LaravelTemplatesFileRepository extends LaravelModulesFileRepository
{
    use Macroable;

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

    public function __construct(Container $app, $path = null)
    {
        $this->app = $app;
        $this->path = $path;
        $this->url = $app['url'];
        $this->config = $app['config'];
        $this->files = $app['files'];
        $this->cache = $app['cache'];

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

    public function getAssetsPath(): string
    {
        return $this->config('paths.assets');
    }

    public function config(string $key, $default = null)
    {

        return config()->get('templates.' . $key, $default);
    }

    public function getPath(): string
    {
        return $this->path ?: $this->config('paths.modules', base_path('Templates'));
    }


    protected function createModule(...$args)
    {

        return StaticTemplateCreator::createModule(...$args);

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


    public function getFiles(): Filesystem
    {

        return $this->files;
    }

    public function findOrFail(string $name)
    {
        $module = $this->find($name);

        if ($module !== null) {
            return $module;
        }

        throw new ModuleNotFoundException("Template [{$name}] does not exist!");
    }
}
