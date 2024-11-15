<?php

namespace MicroweberPackages\LaravelModules\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use BladeUI\Icons\Factory;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    /**
     * Register config.
     */
    public function registerConfig(): void
    {
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower . '.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), 'modules.' . $this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), 'modules.' . $this->moduleNameLower);


        $componentNamespace = str_replace('/', '\\', config('modules.namespace') . '\\' . $this->moduleName . '\\' . ltrim(config('modules.paths.generator.component-class.path'), config('modules.paths.app_folder', '')));
        Blade::componentNamespace($componentNamespace, 'modules.' . $this->moduleNameLower);


        //register blade icons from folder resources/svg if exists
        $svgPath = module_path($this->moduleName, 'resources/svg');
        $iconsPrefix = 'modules.' . $this->moduleNameLower;
        if (is_dir($svgPath)) {
            $this->callAfterResolving(Factory::class, function (Factory $factory) use ($svgPath, $iconsPrefix) {
                $factory->add($iconsPrefix, [
                    'path' => $svgPath,
                    'prefix' => $iconsPrefix,
                ]);
            });
        }

    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'modules.' . $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), 'modules.' . $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * @return array<string>
     */
    public function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }



}
