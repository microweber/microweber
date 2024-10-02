<?php

namespace MicroweberPackages\LaravelModules;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Translation\Translator;
use MicroweberPackages\LaravelModules\Helpers\StaticModuleCreator;
use Nwidart\Modules\Contracts\ActivatorInterface;
use Nwidart\Modules\Json;
use Nwidart\Modules\Laravel\Module;


class LaravelModule extends Module
{
    use Macroable;

    /**
     * The laravel|lumen application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application|\Laravel\Lumen\Application
     */
    protected $app;

    /**
     * The module name.
     */
    protected $name;

    /**
     * The module path.
     *
     * @var string
     */
    protected $path;

    /**
     * @var array of cached Json objects, keyed by filename
     */
    protected $moduleJson = [];

    /**
     * @var CacheManager
     */
    private $cache;

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var ActivatorInterface
     */
    private $activator;

    public function __construct(Container $app, string $name, $path)
    {
        $this->name = $name;
        $this->path = $path;
        $this->cache = $app['cache'];

        $this->files = $app['files'];
        $this->translator = $app['translator'];
        $this->activator = $app[ActivatorInterface::class];
        $this->app = $app;

    }

    public function getCachedServicesPath(): string
    {
        // This checks if we are running on a Laravel Vapor managed instance
        // and sets the path to a writable one (services path is not on a writable storage in Vapor).
        if (!is_null(env('VAPOR_MAINTENANCE_MODE', null))) {
            return Str::replaceLast('config.php', $this->getSnakeName() . '_module.php', $this->app->getCachedConfigPath());
         //   return Str::replaceLast('config.php', $this->getSnakeName() . '_module.php', $this->app->getCachedConfigPath());
        }

        return Str::replaceLast('services.php', $this->getSnakeName() . '_module.php', $this->app->getCachedServicesPath());
       // return Str::replaceLast('services.php', $this->getSnakeName() . '_module.php', $this->app->getCachedServicesPath());
    }

    public function isStatus(bool $status): bool
    {
        return $this->activator->hasStatus($this, $status);
    }

    public function enable(): void
    {
        $this->fireEvent('enabling');

        $this->activator->enable($this);
        $this->flushCache();

        $this->fireEvent('enabled');
    }

    /**
     * Get json contents from the cache, setting as needed.
     *
     * @param string $file
     */
    public static $jsonMemoryCache = [];
    public $jsonMemoryCacheData = [];

    public function setJsonCacheData($data = null): void
    {

        $this->jsonMemoryCacheData = $data;

    }

    public function get(string $key, $default = null)
    {
        if (!empty($this->jsonMemoryCacheData)) {

            if (isset($this->jsonMemoryCacheData[$key])) {

                return $this->jsonMemoryCacheData[$key];
            } else {
                return $default;
            }
        }

        return $this->json()->get($key, $default);
    }

    public function json($file = null): Json
    {

        if ($file === null) {
            $file = 'module.json';
        }



//        if ($file == 'module.json') {
//            if ($this->jsonMemoryCacheData) {
//                return $this->jsonMemoryCacheData;
//            }
//        }


        $path = $this->getPath() . '/' . $file;
        if (isset(self::$jsonMemoryCache[$path])) {

            return self::$jsonMemoryCache[$path];
        }


        //new ModuleJsonFromArray($this->getPath() . '/' . $file, $this->files,$data);

        $json = Arr::get($this->moduleJson, $file, function () use ($file, $path) {
            return $this->moduleJson[$file] = new Json($path, $this->files);
        });


        self::$jsonMemoryCache[$path] = $json;
        return $json;
    }

    private function flushCache(): void
    {

        self::$jsonMemoryCache = [];
        StaticModuleCreator::$modulesCache = [];
        $this->app->modules->flushCache();
        $this->jsonMemoryCacheData = [];
        $this->cache->store(config('modules.cache.driver'))->flush();

    }

    public function registerProviders(): void
    {



        $providers = $this->get('providers', []);

        if ($providers) {
            foreach ($providers as $provider) {

                $this->app->register($provider);
            }
        }

//        (new ProviderRepository($this->app, new Filesystem(), $this->getCachedServicesPath()))
//            ->load($this->get('providers', []));

//        $manifestPath = $this->app->getCachedServicesPath();
//        $manifestPath = Str::replaceLast('services.php',  'modules.php', $manifestPath);
//
//        (new ProviderRepository($this->app, new Filesystem(), $manifestPath))
//            ->load($this->get('providers', []));
    }
}
