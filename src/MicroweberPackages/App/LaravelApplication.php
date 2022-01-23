<?php

namespace MicroweberPackages\App;


use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Session\SessionServiceProvider;
use MicroweberPackages\Cache\TaggableFileCacheServiceProvider;

class LaravelApplication extends Application
{
    private $base_path_local;

    public function __construct($basePath = null)
    {
        $this->base_path_local = $basePath;
        $this->_check_system();
        parent::__construct($basePath);
    }


    /**
     * Get the path to the cached services.php file.
     *
     * @return string
     */
    public function getCachedServicesPath()
    {

         return $this->normalizeCachePath('APP_SERVICES_CACHE', 'cache/services.'.self::VERSION.'.php');
    }

    /**
     * Get the path to the cached packages.php file.
     *
     * @return string
     */
    public function getCachedPackagesPath()
    {
        return $this->normalizeCachePath('APP_PACKAGES_CACHE', 'cache/packages.'.self::VERSION.'.php');
    }



    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {

        parent::registerBaseServiceProviders();

        $this->register(new SessionServiceProvider($this));
        $this->register(new FilesystemServiceProvider($this));
        $this->register(new TaggableFileCacheServiceProvider($this));

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

        $storage_sessions_dir = $storage_dir . DIRECTORY_SEPARATOR . 'framework'.DIRECTORY_SEPARATOR.'sessions';
        if (!is_dir($storage_sessions_dir) and !is_link($storage_sessions_dir)) {
            $this->_mkdir_recursive($storage_sessions_dir);
        }

        $storage_view_dir = $storage_dir . DIRECTORY_SEPARATOR . 'framework'.DIRECTORY_SEPARATOR.'views';
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

    private function _mkdir_recursive($pathname)
    {
        if ($pathname == '') {
            return false;
        }
        is_dir(dirname($pathname)) || $this->_mkdir_recursive(dirname($pathname));

        return is_dir($pathname) || @mkdir($pathname);
    }
}
