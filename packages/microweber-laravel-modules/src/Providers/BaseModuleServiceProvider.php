<?php

namespace MicroweberPackages\LaravelModules\Providers;

use BladeUI\Icons\Factory;
use Illuminate\Support\Facades\Blade;
use MicroweberPackages\ConfigMerge\MergesConfigFromPackage;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use MicroweberPackages\Package\ModulePackage;
use Spatie\LaravelPackageTools\Package;

/**
 * Base service provider for CMS modules.
 *
 * Extends the standalone Microweber package loader so modules share the same
 * Spatie-based lifecycle as packages/* while keeping nwidart module helpers
 * (config/views/translations).
 *
 * Concrete providers declare `$moduleName` / `$moduleNameLower` themselves
 * (legacy modules may use untyped properties).
 *
 * @property string $moduleName
 * @property string $moduleNameLower
 */
abstract class BaseModuleServiceProvider extends MicroweberPackageServiceProvider
{
    use MergesConfigFromPackage;

    /**
     * Modules may optionally declare a type via configureModule(); not required
     * for every module (many still register Filament pages manually).
     */
    protected bool $requiresModuleType = false;

    public function configurePackage(Package $package): void
    {
        $name = (isset($this->moduleNameLower) && is_string($this->moduleNameLower) && $this->moduleNameLower !== '')
            ? $this->moduleNameLower
            : strtolower(static::class);

        $package->name('module-' . $name);
    }

    /**
     * Optional CMS module registry configuration.
     * Override in module providers to use the fluent ModulePackage API.
     */
    public function configureModule(ModulePackage $module): void
    {
        if (isset($this->moduleNameLower) && is_string($this->moduleNameLower) && $this->moduleNameLower !== '') {
            $module->type($this->moduleNameLower);
        }
    }

    /**
     * Ensure the package loader runs even when child providers override register()
     * without calling parent::register().
     *
     * @return $this
     */
    public function register()
    {
        if (! isset($this->package)) {
            parent::register();
        }

        return $this;
    }

    /**
     * Register config.
     */
    public function registerConfig(): void
    {
        $configPath = module_path($this->moduleName, 'config/config.php');

        // Only register config if the file exists
        if (file_exists($configPath)) {
            $this->publishes([$configPath => config_path($this->moduleNameLower . '.php')], 'config');
            $this->mergeConfigFrom($configPath, 'modules.' . $this->moduleNameLower);
        }
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

        // register blade icons from folder resources/svg if exists
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
