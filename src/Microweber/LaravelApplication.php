<?php

namespace Microweber;


use Illuminate\Foundation\Application;

class LaravelApplication extends Application
{
    private $base_path_local;

    public function __construct($basePath = null)
    {
        $this->base_path_local = $basePath;
        $this->_check_system();
        parent::__construct($basePath);
    }

    private function _check_system()
    {
        $this->__ensure_bootstrap_cache_dir();
        $this->__ensure_dot_env_file_exists();
    }

    private function __ensure_bootstrap_cache_dir()
    {
        /*
        * fix of error: The bootstrap/cache directory must be present and writable.
        */
        $bootstrap_dir = $this->base_path_local . DIRECTORY_SEPARATOR . 'bootstrap';
        $bootstrap_cache_dir = $bootstrap_dir . DIRECTORY_SEPARATOR . 'cache';
        if (is_dir($bootstrap_dir)
            and
            (
                !is_dir($bootstrap_cache_dir)
                and
                !is_link($bootstrap_cache_dir)
            )
        ) {
            mkdir($bootstrap_cache_dir);
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
}
