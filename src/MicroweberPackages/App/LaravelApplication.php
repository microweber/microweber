<?php

namespace MicroweberPackages\App;


use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Mix;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\View\ViewServiceProvider;
use MicroweberPackages\App\Repositories\ProviderRepository;
use MicroweberPackages\Cache\TaggableFileCacheServiceProvider;
use MicroweberPackages\Install\UpdateMissingConfigFiles;

class LaravelApplication extends Application
{

    //remember to change also in version.txt
    const APP_VERSION = '3.0-dev13';


    private $base_path_local;

    public function __construct($basePath = null)
    {

        $this->base_path_local = $basePath;
        $this->_check_system();
        parent::__construct($basePath);

    }


    public function boot()
    {

        $this->_check_new_config_files();
        if (!config('app.key') or config('app.key') == 'YourSecretKey!!!') {

            $this->_ensure_app_key_is_set_in_dot_env_file();
        }
        parent::boot();
    }

    /**
     * Get the path to the cached services.php file.
     *
     * @return string
     */
    public function getCachedServicesPath()
    {

        return $this->normalizeCachePath('APP_SERVICES_CACHE', 'cache/cache_' . $this->getVersionPrefix() . '_services.php');
    }

    /**
     * Get the path to the cached packages.php file.
     *
     * @return string
     */
    public function getCachedPackagesPath()
    {
        return $this->normalizeCachePath('APP_PACKAGES_CACHE', 'cache/cache_' . $this->getVersionPrefix() . '_packages.php');
    }

    public function getCachedConfigPath()
    {
        return $this->normalizeCachePath('APP_PACKAGES_CACHE', 'cache/cache_' . $this->getVersionPrefix() . '_config.php');
    }

    public function getCachedMicroweberServiceProvidersPath()
    {

        return $this->normalizeCachePath('APP_MW_SERVICE_PROVIDERS_CACHE', 'cache/cache_' . $this->getVersionPrefix() . '_mw_loaded_providers.php');
    }

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {

        parent::registerBaseServiceProviders();

        $this->register(new ViewServiceProvider($this));
        $this->register(new SessionServiceProvider($this));
        $this->register(new FilesystemServiceProvider($this));
        $this->register(new TaggableFileCacheServiceProvider($this));

    }
//    protected function registerBaseBindings()
//    {
//        static::setInstance($this);
//
//        $this->instance('app', $this);
//
//        $this->instance(Container::class, $this);
//        $this->singleton(Mix::class);
//
//        $this->singleton(PackageManifest::class, fn () => new PackageManifest(
//            new Filesystem, $this->basePath(), $this->getCachedPackagesPath()
//        ));
//    }
    private function _check_new_config_files()
    {
        // we check if there is cached file for the current version and copy the missing config files if there is no cached file
        $mwVersionFile = $this->normalizeCachePath('APP_SERVICES_CACHE', 'cache/cache_' . $this->getVersionPrefix() . '_app_version.txt');
        $checkDir = dirname($mwVersionFile);
        if (!is_dir($checkDir)) {
            mkdir($checkDir);
        }

        $mwVersionFile = normalize_path($mwVersionFile, false);
        if (!is_file($mwVersionFile)) {
            $copyConfigs = new UpdateMissingConfigFiles();
            $copyConfigs->copyMissingConfigStubs();
            file_put_contents($mwVersionFile, $this->getVersionPrefix());
        }
    }

    private function _check_system()
    {
        $this->__ensure_bootstrap_cache_dir();
        $this->__ensure_storage_dir();
        $this->__ensure_dot_env_file_exists();
    }

    private function __ensure_bootstrap_cache_dir()
    {
        /*
        * fix of error: The bootstrap/cache directory must be present and writable.
        */
        $bootstrap_dir = $this->base_path_local . DIRECTORY_SEPARATOR . 'bootstrap';
        $bootstrap_cache_dir = $bootstrap_dir . DIRECTORY_SEPARATOR . 'cache';

        if (is_dir($bootstrap_dir) and (!is_dir($bootstrap_cache_dir) and !is_link($bootstrap_cache_dir))) {
            mkdir($bootstrap_cache_dir);
        }
    }

    private function __ensure_storage_dir()
    {
        /*
        * fix of error: The bootstrap/cache directory must be present and writable.
        */
        $storage_dir = $this->base_path_local . DIRECTORY_SEPARATOR . 'storage';

        $storage_sessions_dir = $storage_dir . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'sessions';
        if (!is_dir($storage_sessions_dir) and !is_link($storage_sessions_dir)) {
            $this->_mkdir_recursive($storage_sessions_dir);
        }

        $storage_view_dir = $storage_dir . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'views';
        if (!is_dir($storage_view_dir) and !is_link($storage_view_dir)) {
            $this->_mkdir_recursive($storage_view_dir);
        }


    }


    private function __ensure_dot_env_file_exists()
    {

        /*
        *
        * fix of error:  ErrorException: file_get_contents(my_site/.env): failed to open stream: No such file or directory
        * Illuminate\Foundation\Console\KeyGenerateCommand.php:95
        *
        */

        $file = $this->base_path_local . DIRECTORY_SEPARATOR . '.env';
        if (!is_file($file) and !is_link($file)) {
            @touch($file);
        }

    }

    private function _ensure_app_key_is_set_in_dot_env_file()
    {

        /*
        *
        * fix of error:  ErrorException: file_get_contents(my_site/.env): failed to open stream: No such file or directory
        * Illuminate\Foundation\Console\KeyGenerateCommand.php:95
        *
        */

        $existingKey = env('APP_KEY');

        if (!$existingKey) {
            $key = 'base64:' . base64_encode(random_bytes(32));
            @file_put_contents($this->base_path_local . DIRECTORY_SEPARATOR . '.env', PHP_EOL . 'APP_KEY=' . $key, FILE_APPEND);
            Config::set('app.key', $key);
        }


    }


    private function getVersionPrefix()
    {
        return str_slug(self::VERSION . '_' . self::APP_VERSION, '_');
    }

    private function _mkdir_recursive($pathname)
    {
        if ($pathname == '') {
            return false;
        }
        is_dir(dirname($pathname)) || $this->_mkdir_recursive(dirname($pathname));

        return is_dir($pathname) || @mkdir($pathname);
    }


//    /**
//     * Write the service manifest file to disk.
//     *
//     * @param array $manifest
//     * @return array
//     *
//     * @throws \Exception
//     */
//    public function writeManifest($manifest)
//    {
//        try {
//            parent::writeManifest($manifest);
//        } catch (\ErrorException $e) {
//
//        } catch (\Exception $e) {
//
//        }
//
//    }

    public function rebootApplication()
    {

        $this->booted = false;
        $this->boot();
    }


}
