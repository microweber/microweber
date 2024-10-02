<?php

namespace MicroweberPackages\LaravelTemplates\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

abstract class BaseTemplateServiceProvider extends ServiceProvider
{
    /**
     * Register config.
     */
    public function registerConfig(): void
    {
        $this->publishes([template_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower . '.php')], 'config');
        $this->mergeConfigFrom(template_path($this->moduleName, 'config/config.php'), 'templates.' . $this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/templates/' . $this->moduleNameLower);
        $sourcePath = template_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower . '-template-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), 'templates.' . $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('templates.namespace') . '\\' . $this->moduleName . '\\' . ltrim(config('templates.paths.generator.component-class.path'), config('templates.paths.app_folder', '')));
        Blade::componentNamespace($componentNamespace, 'templates.' . $this->moduleNameLower);
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/templates/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'templates.' . $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(template_path($this->moduleName, 'lang'), 'templates.' . $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(template_path($this->moduleName, 'lang'));
        }
    }

    /**
     * @return array<string>
     */
    public function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/templates/' . $this->moduleNameLower)) {
                $paths[] = $path . '/templates/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }


}
