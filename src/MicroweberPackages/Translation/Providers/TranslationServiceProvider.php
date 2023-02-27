<?php

namespace MicroweberPackages\Translation\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;
use MicroweberPackages\Translation\Models\Translation;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Repositories\TranslationKeyRepository;
use MicroweberPackages\Translation\TranslationLoader;
use MicroweberPackages\Translation\Translator;
use \WhiteCube\Lingua\Service as Lingua;





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
         * and how to call it with trans function
         *
         * Example:
         *  trans('translation::all.name')
         */
        //Lang::addNamespace('translation', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources/lang');
        Lang::addJsonPath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources/lang');

        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/web.php');

        if (mw_is_installed()) {

//            $dbDriver = \Config::get('database.default');
//            if ($dbDriver == 'sqlite') {
//                $pdo = DB::connection('sqlite')->getPdo();
//                $pdo->sqliteCreateFunction('regexp',
//                    function ($pattern, $data, $delimiter = '~', $modifiers = 'isuS') {
//                        if (isset($pattern, $data) === true) {
//                            return preg_match(sprintf('%1$s%2$s%1$s%3$s', $delimiter, $pattern, $modifiers), $data) > 0;
//                        }
//
//                        return;
//                    }
//                );
//                $pdo->sqliteCreateFunction('md5', 'md5');
//
//            }



            // If you are import old database we must run migrations
//            if (!Schema::hasTable('translations_keys')) {
//                app()->mw_migrator->run([
//                    dirname(__DIR__) . DIRECTORY_SEPARATOR . 'migrations'
//                ]);
//            }






            // @todo fix the insert logic
           //return;

            $this->app->terminating(function () {
                $getNewKeys = app()->translator->getNewKeys();

                if (!empty($getNewKeys)) {

                    \Config::set('microweber.disable_model_cache', 1);


                    $toSave = [];
                    foreach ($getNewKeys as $newKey) {
// do not trim  see https://stackoverflow.com/a/10133237/731166

//                            $newKey['translation_key'] = trim($newKey['translation_key']);
//                            $newKey['translation_group'] = trim($newKey['translation_group']);
//                            $newKey['translation_namespace'] = trim($newKey['translation_namespace']);
//                            $newKey['translation_namespace'] = trim($newKey['translation_namespace']);
                        //\Log::debug($newKey);

                        $findTranslationKey = TranslationKey::where('translation_namespace', $newKey['translation_namespace'])
                            ->where('translation_group', $newKey['translation_group'])
                           // ->where(\DB::raw('md5(translation_key)'), md5($newKey['translation_key']))
                            ->where('translation_key', $newKey['translation_key'])
                            ->limit(1)
                            ->first();
                        //   \Log::debug($findTranslationKey);
                        if ($findTranslationKey == null) {
                            $toSave[] = $newKey;
                            // TranslationKey::insert($newKey);
                        }
                    }


                    try {
                        if ($toSave) {
                          //  \Log::debug($getNewKeys);
                            DB::beginTransaction();

                            $toSave_chunked = array_chunk($toSave, 100);
                            foreach ($toSave_chunked as $k => $toSave_chunk) {
                                TranslationKey::insert($toSave_chunk);
                            }


                            DB::commit();
                          //  \Cache::tags('translation_keys')->flush();
                        }
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

        if (!class_exists(Lingua::class)) {
            exit('The class ' . Lingua::class . ' cannot be found. Please run composer install.');
        }


        $this->registerLoader();



        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(TranslationKey::class, function () {
                return new TranslationKeyRepository();
            });
        });



        $this->app->bind('translation_key_repostory', function () {

            /**
             * @mixin Application
             * @property TranslationKeyRepository   $translation_key_repostory
             * @return Application
             */
            return $this->app->repository_manager->driver(TranslationKey::class);;
        });






        app()->singleton('translator', function ($app) {
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
                return new TranslationLoader($app['files'], $app['path.lang']);
            });
        }
    }
}
