<?php

namespace MicroweberPackages\Translation\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\TranslationLoader;
use MicroweberPackages\Translation\Translator;

class TranslationServiceProvider extends IlluminateTranslationServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Lang::addJsonPath(dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'resources/lang');

        $this->loadMigrationsFrom(dirname(__DIR__) . '/Database/Migrations');

        $this->publishes([
            dirname(__DIR__) . '/Database/Migrations' => database_path('migrations'),
        ], 'microweber-translation-migrations');

        $this->publishes([
            dirname(__DIR__, 2) . '/resources/lang' => resource_path('lang/vendor/microweber-translation'),
        ], 'microweber-translation-lang');

        $this->publishes([
            dirname(__DIR__, 2) . '/config/translation.php' => config_path('microweber-translation.php'),
        ], 'microweber-translation-config');

        if ($this->isDatabaseReady()) {
            $this->registerTerminatingCallback();
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/translation.php', 'microweber-translation'
        );

        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $locale = $app->config['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app->config['app.fallback_locale']);

            return $trans;
        });

        $this->app->singleton(\Illuminate\Contracts\Translation\Translator::class, function ($app) {
            return $app['translator'];
        });
    }

    /**
     * Check if the database is ready (tables exist).
     */
    protected function isDatabaseReady(): bool
    {
        // Check for CMS-specific function first
        if (function_exists('mw_is_installed')) {
            return mw_is_installed();
        }

        // For standalone usage, check if the table exists
        try {
            $connection = DB::connection();
            $connection->getPdo();
            return \Schema::hasTable('translation_keys');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Register the terminating callback to save new translation keys.
     */
    protected function registerTerminatingCallback(): void
    {
        $this->app->terminating(function () {
            $translator = app()->translator;
            if (!method_exists($translator, 'getNewKeys')) {
                return;
            }

            $getNewKeys = $translator->getNewKeys();
            if (!empty($getNewKeys)) {
                Config::set('microweber.disable_model_cache', 1);

                $toSave = [];
                foreach ($getNewKeys as $newKey) {
                    $findTranslationKey = TranslationKey::query()
                        ->where('translation_namespace', $newKey['translation_namespace'])
                        ->where('translation_group', $newKey['translation_group'])
                        ->where('translation_key', $newKey['translation_key'])
                        ->limit(1)
                        ->first();

                    if ($findTranslationKey == null) {
                        $toSave[] = $newKey;
                    }
                }

                try {
                    if ($toSave) {
                        DB::beginTransaction();

                        $toSave_chunked = array_chunk($toSave, 100);
                        foreach ($toSave_chunked as $k => $toSave_chunk) {
                            TranslationKey::insert($toSave_chunk);
                        }

                        DB::commit();
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                }
            }
        });
    }
}