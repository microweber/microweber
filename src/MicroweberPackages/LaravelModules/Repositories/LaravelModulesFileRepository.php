<?php

namespace MicroweberPackages\LaravelModules\Repositories;

use Arcanedev\Html\Elements\P;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;
use MicroweberPackages\Cache\CacheFileHandler\Facades\Cache;
use MicroweberPackages\LaravelModules\Facades\LaravelModulesCache;
use MicroweberPackages\LaravelModules\Helpers\StaticModuleCreator;
use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelModules\Traits\ModulesRepositoryTrait;
use Nwidart\Modules\Collection;
use Nwidart\Modules\FileRepository;
use Nwidart\Modules\Json;
use Nwidart\Modules\Module;
use Nwidart\Modules\Process\Installer;
use Nwidart\Modules\Process\Updater;

class LaravelModulesFileRepository extends FileRepository
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
    public $configPrefix = 'modules';

    public function __construct(Container $app, $path = null)
    {
        $this->app = $app;
        $this->path = $path;
        $this->url = $app['url'];
        $this->config = $app['config'];
        $this->files = $app['files'];
        $this->cache = $app['cache'];

        $this->cacheRepository = $app[LaravelModulesCache::class];


    }




}
