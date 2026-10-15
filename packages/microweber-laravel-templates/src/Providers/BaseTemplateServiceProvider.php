<?php

namespace MicroweberPackages\LaravelTemplates\Providers;

use Illuminate\Support\Facades\Blade;
use MicroweberPackages\ConfigMerge\MergesConfigFromPackage;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use MicroweberPackages\Package\ModulePackage;
use Spatie\LaravelPackageTools\Package;

/**
 * Base service provider for CMS templates.
 *
 * Uses the standalone Microweber package loader so templates share the same
 * Spatie-based lifecycle as packages/* and modules.
 *
 * Concrete providers set `$moduleName` / `$moduleNameLower`
 * (see Templates/* and the scaffold stub).
 */
abstract class BaseTemplateServiceProvider extends MicroweberPackageServiceProvider
{
    use MergesConfigFromPackage;

    protected string $moduleName;

    protected string $moduleNameLower;

    protected bool $requiresModuleType = false;

    public function configurePackage(Package $package): void
    {
        $name = (isset($this->moduleNameLower) && is_string($this->moduleNameLower) && $this->moduleNameLower !== '')
            ? $this->moduleNameLower
            : strtolower(static::class);

        $package->name('template-' . $name);
    }

    public function configureModule(ModulePackage $module): void
    {
        if (isset($this->moduleNameLower) && is_string($this->moduleNameLower) && $this->moduleNameLower !== '') {
            $module->type('template-' . $this->moduleNameLower);
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

        // Own views first, then the parent chain as fallbacks: a thin child
        // template (parent_template in config/config.php) can ship only the views
        // it overrides (e.g. index + clean) and inherit everything else — master
        // layout, post/product/shop/blog, the whole skin library — from its
        // parent (ultimately Base). Laravel's view finder tries paths in order,
        // so the child's own views always win.
        $paths = array_merge($this->getPublishableViewPaths(), [$sourcePath]);
        foreach ($this->parentTemplateViewPaths() as $parentViewPath) {
            $paths[] = $parentViewPath;
        }
        $this->loadViewsFrom($paths, 'templates.' . $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('templates.namespace') . '\\' . $this->moduleName . '\\' . ltrim(config('templates.paths.generator.component-class.path'), config('templates.paths.app_folder', '')));
        Blade::componentNamespace($componentNamespace, 'templates.' . $this->moduleNameLower);
    }

    /**
     * Resolve the parent_template chain's view directories (walking up
     * parent_template config, cycle-guarded), so a child inherits its parent's
     * views. Returns absolute, existing paths in nearest-ancestor-first order.
     *
     * @return array<string>
     */
    protected function parentTemplateViewPaths(): array
    {
        $paths = [];
        $seen = [strtolower($this->moduleName) => true];
        $current = $this->moduleName;

        for ($i = 0; $i < 10; $i++) {
            $parent = $this->readParentTemplateName($current);
            if (!$parent || isset($seen[strtolower($parent)])) {
                break;
            }
            $seen[strtolower($parent)] = true;

            $parentViews = template_path($parent, 'resources/views');
            if (is_dir($parentViews)) {
                $paths[] = $parentViews;
            }
            $current = $parent;
        }

        return $paths;
    }

    /**
     * Read a template's parent_template from its config/config.php (no container
     * / merged-config dependency, so it is safe during registration).
     */
    protected function readParentTemplateName(string $templateName): ?string
    {
        $file = template_path($templateName, 'config/config.php');
        if (!is_file($file)) {
            return null;
        }
        $data = include $file;

        return (is_array($data) && !empty($data['parent_template'])) ? (string) $data['parent_template'] : null;
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
