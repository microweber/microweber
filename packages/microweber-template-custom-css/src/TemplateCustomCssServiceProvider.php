<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface;
use MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Services\CustomCssManager;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Services\RegisteredCssFileHandler;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;
use MicroweberPackages\TemplateCustomCss\Support\ArrayOptionStore;
use MicroweberPackages\TemplateCustomCss\Support\CmsOptionStore;

class TemplateCustomCssServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/template-custom-css.php', 'template-custom-css');

        $this->app->singleton(OptionStoreInterface::class, function () {
            if (function_exists('get_option') && function_exists('save_option')) {
                return new CmsOptionStore();
            }

            return new ArrayOptionStore();
        });

        $this->app->singleton(CssValidator::class, function ($app) {
            $config = (array) config('template-custom-css', []);

            return new CssValidator((bool) ($config['allow_empty_css'] ?? true));
        });

        $this->app->singleton(CssUrlRewriter::class, function ($app) {
            $config = $this->resolveRuntimeConfig();
            $userfilesUrlRaw = $config['userfiles_url'] ?? '';
            $userfilesUrl = is_string($userfilesUrlRaw) ? $userfilesUrlRaw : '';
            $siteUrl = '';
            if (function_exists('site_url')) {
                $siteUrl = (string) site_url();
            } elseif (function_exists('config')) {
                $cfgUrl = config('app.url', '');
                $siteUrl = is_string($cfgUrl) ? $cfgUrl : '';
            }

            return new CssUrlRewriter($userfilesUrl, $siteUrl);
        });

        $this->app->singleton(LiveEditCssManager::class, function ($app) {
            return new LiveEditCssManager(
                $this->resolveRuntimeConfig(),
                $app->make(OptionStoreInterface::class),
                $app->make(CssValidator::class),
                $app->make(CssUrlRewriter::class),
            );
        });

        $this->app->singleton(CustomCssManager::class, function ($app) {
            return new CustomCssManager(
                $this->resolveRuntimeConfig(),
                $app->make(OptionStoreInterface::class),
                $app->make(CssValidator::class),
            );
        });

        $this->app->singleton(TemplateCustomCssManager::class, function ($app) {
            $config = $this->resolveRuntimeConfig();
            $manager = new TemplateCustomCssManager(
                $config,
                $app->make(OptionStoreInterface::class),
                $app->make(CssValidator::class),
                $app->make(CssUrlRewriter::class),
                $app->make(LiveEditCssManager::class),
                $app->make(CustomCssManager::class),
            );

            // Register any extra file types from config (beyond live_edit / custom)
            $fileTypes = $config['file_types'] ?? [];
            if (is_array($fileTypes)) {
                foreach ($fileTypes as $key => $typeConfig) {
                    if (!is_string($key) || in_array($key, ['live_edit', 'custom'], true)) {
                        continue;
                    }
                    if (!is_array($typeConfig)) {
                        continue;
                    }
                    /** @var array{filename?: string|null, storage?: string, option_key?: string|null, option_group?: string|null, option_group_prefix?: string|null, multisite?: bool, rewrite_urls?: bool, validate?: bool, cache?: bool} $typedConfig */
                    $typedConfig = $typeConfig;
                    $manager->registerFileType(new RegisteredCssFileHandler(
                        $key,
                        $typedConfig,
                        $config,
                        $app->make(OptionStoreInterface::class),
                        $app->make(CssValidator::class),
                        $app->make(CssUrlRewriter::class),
                    ));
                }
            }

            return $manager;
        });

        $this->app->alias(TemplateCustomCssManager::class, 'template-custom-css');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'template-custom-css');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/template-custom-css.php' => config_path('template-custom-css.php'),
            ], 'template-custom-css-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/template-custom-css'),
            ], 'template-custom-css-views');
        }
    }

    /**
     * Merge config with CMS path helpers so file locations stay at
     * userfiles/css/{template}/live_edit.css for backup/restore.
     *
     * @return array<string, mixed>
     */
    protected function resolveRuntimeConfig(): array
    {
        $config = (array) config('template-custom-css', []);

        if (function_exists('userfiles_path')) {
            $config['css_base_path'] = rtrim(userfiles_path(), "/\\") . DIRECTORY_SEPARATOR . 'css';
            $config['css_cache_path'] = rtrim(userfiles_path(), "/\\") . DIRECTORY_SEPARATOR . 'cache';
        }
        if (function_exists('userfiles_url')) {
            $config['css_base_url'] = rtrim(userfiles_url(), '/') . '/css';
            $config['css_cache_url'] = rtrim(userfiles_url(), '/') . '/cache';
            $config['userfiles_url'] = rtrim(userfiles_url(), '/') . '/';
        }
        if (function_exists('mw_is_multisite')) {
            $config['multisite'] = (bool) mw_is_multisite();
        }
        if (function_exists('app')) {
            try {
                $config['environment'] = (string) app()->environment();
            } catch (\Throwable) {
                // ignore
            }
        }
        if (function_exists('config')) {
            try {
                $compile = config('microweber.compile_assets');
                if ($compile !== null) {
                    $config['compile_assets'] = (bool) $compile;
                }
            } catch (\Throwable) {
                // ignore
            }
        }

        return $config;
    }
}
