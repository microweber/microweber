<?php
namespace MicroweberPackages\Translation\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;
use MicroweberPackages\Translation\Models\Translation;
use MicroweberPackages\Translation\TranslationManager;
use MicroweberPackages\Translation\Translator;

class TranslationServiceProvider extends IlluminateTranslationServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        $this->loadMigrationsFrom(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'migrations/');

        /*
         * This is an example how to add namespace to your package
         * andd how to call it with trans function
         *
         * Example:
         *  trans('translation::all.name')
         */
        Lang::addNamespace('translation', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources/lang');

        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/web.php');

        if (mw_is_installed()) {
//            if (!Schema::hasTable('translations')) {
//                app()->mw_migrator->run([
//                    dirname(__DIR__) . DIRECTORY_SEPARATOR . 'migrations'
//                ]);
//            }

            $this->app->terminating(function () {
                $getNewTexts = app()->translator->getNewTexts();
                if (!empty($getNewTexts)) {

                    \Log::debug($getNewTexts);

                    \Config::set('microweber.disable_model_cache', 1);

                    DB::beginTransaction();
                    try {
                        $toSave = [];
                        foreach ($getNewTexts as $text) {

                            $text['translation_key'] = trim($text['translation_key']);
                            $text['translation_group'] = trim($text['translation_group']);
                            $text['translation_namespace'] = trim($text['translation_namespace']);
                            $text['translation_locale'] = trim($text['translation_locale']);

                            $findTranslation = Translation::where('translation_namespace', $text['translation_namespace'])
                                ->where('translation_group', $text['translation_group'])
                                ->where(\DB::raw('md5(translation_key)'), md5($text['translation_key']))
                                ->where('translation_locale', $text['translation_locale'])->first();
                            if ($findTranslation == null) {
                                 $toSave[] = $text;
                               // Translation::insert($text);
                            }
                        }
                        if ($toSave) {

                            var_dump('xodi gladen');

                            Translation::insert($toSave);
                        }

                        DB::commit();
                        // all good
                     } catch (\Exception $e) {
                         DB::rollback();
                        // something went wrong
                     }
                }
            });

        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLoader();

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }

    protected function registerLoader()
    {
        if (mw_is_installed()) {
            $this->app->singleton('translation.loader', function ($app) {
                return new TranslationManager($app['files'], $app['path.lang']);
            });
        }
    }
}
