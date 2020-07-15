<?php
namespace MicroweberPackages\Content;

use Illuminate\Support\ServiceProvider;

/**
 * Class ConfigSaveServiceProvider
 * @package MicroweberPackages\Config
 */

class ContentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}
