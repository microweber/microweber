<?php

namespace MicroweberPackages\App;


use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\View\ViewServiceProvider;
use MicroweberPackages\AppBootstrapCache\HasVersionedBootstrapCache;
use MicroweberPackages\TaggableFileCache\TaggableFileCacheServiceProvider;
use MicroweberPackages\Install\UpdateMissingConfigFiles;

class LaravelApplication extends Application
{
    use HasVersionedBootstrapCache;

    //remember to change also in version.txt
    const APP_VERSION = '4.0-dev17';


    private $base_path_local;

    public function __construct($basePath = null)
    {

        $this->base_path_local = $basePath;
        $this->_check_system();
        parent::__construct($basePath);

        // Register Laravel's default facade aliases (Route, DB, Schema, …) so
        // that bare-aliased facades resolve for both normal and config-cached
        // boots. Laravel 11 ships no default aliases and MW relies on them in
        // ~600 files; seeding here (instead of a config/app.php 'aliases' key)
        // keeps config clean and survives config:cache. See HasVersionedBootstrapCache.
        $this->registerDefaultFacadeAliases();
    }


    public function boot()
    {

        $this->_check_new_config_files();
        if (!config('app.key') or config('app.key') == 'YourSecretKey!!!') {

            $this->_ensure_app_key_is_set_in_dot_env_file();
        }
        parent::boot();
    }

    public function getCachedMicroweberServiceProvidersPath()
    {
        return $this->normalizeCachePath(
            'APP_MW_SERVICE_PROVIDERS_CACHE',
            $this->buildVersionedCachePath('mw_loaded_providers')
        );
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

    private function _check_new_config_files()
    {
        // we check if there is cached file for the current version and copy the missing config files if there is no cached file
        $prefix = $this->getBootstrapCacheVersionPrefix();
        $mwVersionFile = $this->normalizeCachePath('APP_SERVICES_CACHE', 'cache/cache_' . $prefix . '_app_version.txt');
        $checkDir = dirname($mwVersionFile);
        if (!is_dir($checkDir)) {
            mkdir($checkDir);
        }

        $mwVersionFile = normalize_path($mwVersionFile, false);
        if (!is_file($mwVersionFile)) {
            $copyConfigs = new UpdateMissingConfigFiles();
            $copyConfigs->copyMissingConfigStubs();
            file_put_contents($mwVersionFile, $prefix);
        }
    }

    private function _check_system()
    {
        $this->ensureBootstrapCacheDirectoryExists();
        $this->__ensure_storage_dir();
        $this->_ensure_storage_public_symlink();
        $this->__ensure_dot_env_file_exists();
    }

    private function _ensure_storage_public_symlink()
    {
        /*
         * Ensure public/storage -> storage/app/public so uploaded/public files
         * are reachable at /storage even outside the installer (a git clone or a
         * deploy may not carry the symlink; the installer's storage:link only
         * runs on fresh installs). Paths are built from base_path_local rather
         * than public_path()/storage_path() because this runs BEFORE
         * parent::__construct(), where those helpers are not yet available.
         */
        $publicStorage = $this->base_path_local . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'storage';

        if (is_link($publicStorage)) {
            return;
        }

        // Don't clobber a real directory that a deploy may have shipped in place.
        if (!is_dir($publicStorage)) {
            @symlink(
                $this->base_path_local . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public',
                $publicStorage
            );
        }
    }

    private function __ensure_storage_dir()
    {
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
        $file = $this->base_path_local . DIRECTORY_SEPARATOR . '.env';
        if (!is_file($file) and !is_link($file)) {
            @touch($file);
        }
    }

    private function _ensure_app_key_is_set_in_dot_env_file()
    {
        $existingKey = env('APP_KEY');

        if (!$existingKey) {
            $key = 'base64:' . base64_encode(random_bytes(32)) . "\n";
            @file_put_contents($this->base_path_local . DIRECTORY_SEPARATOR . '.env', PHP_EOL . 'APP_KEY=' . $key, FILE_APPEND);
            Config::set('app.key', $key);
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

    public function rebootApplication()
    {
        $this->booted = false;
        $this->boot();
    }
}
