<?php

namespace Modules\TweetEmbed\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\TweetEmbed\Filament\TweetEmbedModuleSettings;
use Modules\TweetEmbed\Microweber\TweetEmbedModule;

class TweetEmbedServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'TweetEmbed';

    protected string $moduleNameLower = 'tweet_embed';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(TweetEmbedModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\TweetEmbed\Microweber\TweetEmbedModule::class);

    }

}
