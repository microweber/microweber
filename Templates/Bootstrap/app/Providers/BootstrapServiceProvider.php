<?php

namespace Templates\Bootstrap\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Package\ModulePackage;

class BootstrapServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Bootstrap';

    protected string $moduleNameLower = 'bootstrap';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
 
    }


    /**
     * Register the service provider.
     */
    public function register(): void
    {

        $this->registerConfig();
        $this->registerViews();

    }

   
    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([template_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower.'.php')], 'config');
        $this->mergeConfigFrom(template_path($this->moduleName, 'config/config.php'), 'templates.'.$this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/templates/'.$this->moduleNameLower);
        $sourcePath = template_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]),  'templates.'.$this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('templates.namespace').'\\'.$this->moduleName.'\\'.ltrim(config('templates.paths.generator.component-class.path', ''), config('templates.paths.app_folder', '')));

        Blade::componentNamespace($componentNamespace,  'templates.'.$this->moduleNameLower);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/templates/'.$this->moduleNameLower)) {
                $paths[] = $path.'/templates/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
