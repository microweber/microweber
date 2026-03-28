<?php

namespace MicroweberPackages\LaravelModules\Repositories;


use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;

use MicroweberPackages\LaravelModules\Contracts\LaravelModulesCacheRepositoryContract;
use MicroweberPackages\LaravelModules\Traits\ModulesRepositoryTrait;
use Nwidart\Modules\FileRepository;
use Nwidart\Modules\Laravel\LaravelFileRepository;


class LaravelModulesFileRepository extends LaravelFileRepository
{
    public $configPrefix = 'modules';

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
    protected ?string $path;

    /**
     * The scanned paths.
     *
     * @var array
     */
    protected array $paths = [];

    /**
     * @var string
     */
    protected ?string $stubPath;

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

    public function __construct(Container $app, $path = null)
    {
        $this->app = $app;
        $this->path = $path;
        $this->url = $app['url'];
        $this->config = app()->config;
        $this->files = $app['files'];
        $this->cache = $app['cache'];



   //     $this->cacheRepository = new LaravelModulesCacheRepository();
        $this->cacheRepository = app()->make(LaravelModulesCacheRepositoryContract::class);


    }




}
