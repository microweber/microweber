<?php
namespace MicroweberPackages\Content;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use MicroweberPackages\Database\Observers\CreatedByObserver;
use MicroweberPackages\Content\Content;

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
        Content::observe(BaseModelObserver::class);
      //  Content::observe(CreatedByObserver::class);

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
    }
}
