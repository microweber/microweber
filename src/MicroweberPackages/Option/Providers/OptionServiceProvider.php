<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Option\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use MicroweberPackages\Menu\TranslateTables\TranslateMenu;
use MicroweberPackages\Option\TranslateTables\TranslateOption;
use MicroweberPackages\Option\Facades\Option as OptionFacade;
use MicroweberPackages\Option\GlobalOptions;
use MicroweberPackages\Option\Models\Option as OptionModel;
use MicroweberPackages\Option\Models\Option;
use MicroweberPackages\Option\OptionManager;
use MicroweberPackages\Option\Repositories\OptionRepository;


class OptionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('option_manager', function ($app) {
            return new OptionManager();
        });

        $this->app->bind('option',function(){
            return new OptionModel();
        });

        $this->app->singleton('global_options', function ($app) {
            return new GlobalOptions(OptionModel::all());
        });


        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(Option::class, function () {
                return new OptionRepository();
            });
        });

        /**
         * @property OptionRepository   $option_repository
         */
        $this->app->bind('option_repository', function () {
            return app()->make(OptionRepository::class);
        });

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(dirname(__DIR__) . '/migrations/');

        $this->app->translate_manager->addTranslateProvider(TranslateOption::class);

        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias('Option', OptionFacade::class);

        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/api.php');

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['option_manager', 'option'];
    }
}
