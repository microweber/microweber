<?php
namespace MicroweberPackages\Config;

use Illuminate\Support\ServiceProvider;

/**
 * Class ConfigSaveServiceProvider
 * @package MicroweberPackages\Config
 */

class ConfigSaveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		$this->app->bind('Config', function($app){
			return new ConfigSave($app);
		});

        $this->app->alias('Config', ConfigSave::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
    }
}
