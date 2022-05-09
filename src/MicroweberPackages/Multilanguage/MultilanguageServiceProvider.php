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

namespace MicroweberPackages\Multilanguage;

use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Application;
use MicroweberPackages\Form\FormElementBuilder;
use MicroweberPackages\Module\Module;
use MicroweberPackages\Multilanguage\Listeners\LocaleUpdatedListener;
use MicroweberPackages\Multilanguage\Repositories\MultilanguageRepository;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


class MultilanguageServiceProvider extends ServiceProvider
{
    /**
     * The application instance.
     *
     * @var  Application
     */
    protected $app;



    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
        $this->app->singleton('translate_manager', function ($app) {
            return new TranslateManager();
        });

        include_once(__DIR__ . '/helpers/multilanguage_functions.php');

    }


    public function boot()
    {
        View::addNamespace('multilanguage', __DIR__ . '/resources/views');

        $this->loadRoutesFrom(__DIR__ . '/routes/api_public.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        if (!mw_is_installed()) {
            return;
        }

        $isMultilanguageActive = false;

        if (is_module('multilanguage') && is_module_installed('multilanguage') && get_option('is_active', 'multilanguage_settings') === 'y') {
            $isMultilanguageActive = true;
        }

        if (defined('MW_DISABLE_MULTILANGUAGE')) {
            $isMultilanguageActive = false;
        }
        /**
         * @property MultilanguageRepository $multilanguage_repository
         */
        $this->app->bind('multilanguage_repository', function () {
            return new MultilanguageRepository();
        });

       $getSupportedLocales = $this->app->multilanguage_repository->getSupportedLocales(true);

        if (empty($getSupportedLocales)) {
            $isMultilanguageActive = false;
        }

        MultilanguageHelpers::setMultilanguageEnabled($isMultilanguageActive);


        if ($isMultilanguageActive) {

            $this->app[Dispatcher::class]->listen(LocaleUpdated::class, LocaleUpdatedListener::class);


            $this->app->bind('permalink_manager', function () {
                return new MultilanguagePermalinkManager();
            });

            // $this->app->register(MultilanguageEventServiceProvider::class);
            $this->app->bind(FormElementBuilder::class, function ($app) {
                return new MultilanguageFormElementBuilder();
            });

            $this->bootTranslateManager();

        }

    }


    public function bootTranslateManager()
    {
        event_bind('mw.after.boot', function () {

//            $autodetected_lang = \Cookie::get('autodetected_lang');
//            $lang_is_set = \Cookie::get('lang');
//
//            // if (!isset($_COOKIE['autodetected_lang']) and !isset($_COOKIE['lang'])) {
//            if (!$autodetected_lang and !$lang_is_set) {
//                $homepageLanguage = get_option('homepage_language', 'website');
//                if ($homepageLanguage) {
//                    if (is_lang_supported($homepageLanguage)) {
//                        change_language_by_locale($homepageLanguage);
//                        \Cookie::queue('autodetected_lang', 1, 60);
//
//                        //setcookie('autodetected_lang', 1, false, '/');
//                        // $_COOKIE['autodetected_lang'] = 1;
//                    }
//                }
//            }

            $currentUrl = mw()->url_manager->current();
            if ($currentUrl !== api_url('multilanguage/change_language')) {
                if (!defined('MW_DISABLE_MULTILANGUAGE')) {
                    if (MultilanguageHelpers::multilanguageIsEnabled()) {
                        run_translate_manager();
                    }
                }
//                if (!defined('MW_DISABLE_MULTILANGUAGE')) {
//                    run_translate_manager();
//                }
            }
        });
    }


}
