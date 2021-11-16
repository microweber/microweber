<?php
namespace MicroweberPackages\Content;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Content\TranslateTables\TranslateContent;
use MicroweberPackages\Content\TranslateTables\TranslateContentFields;
use MicroweberPackages\Database\Observers\BaseModelObserver;

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
        $this->app->translate_manager->addTranslateProvider(TranslateContent::class);
        $this->app->translate_manager->addTranslateProvider(TranslateContentFields::class);

        Content::observe(BaseModelObserver::class);
      //  Content::observe(CreatedByObserver::class);

        View::addNamespace('content', __DIR__ . '/resources/views');

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }
}
