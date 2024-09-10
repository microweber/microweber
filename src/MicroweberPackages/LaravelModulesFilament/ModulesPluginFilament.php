<?php
//
namespace Coolsam\Modules;

namespace MicroweberPackages\LaravelModulesFilament;

use Coolsam\Modules\Facades\FilamentModules;
use Coolsam\Modules\ModulesPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;

class ModulesPluginFilament extends ServiceProvider
{

    public function register(): void
    {
        $plugins = $this->getModulePlugins();
        foreach ($plugins as $modulePlugin) {

            FilamentRegistry::registerPlugin($modulePlugin);
        }

        $pages = $this->getModulePages();
        if ($pages) {
            foreach ($pages as $modulePage) {

                FilamentRegistry::registerPage($modulePage, $scope = \MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider::class);
            }
        }

    }

    protected function getModulePlugins(): array
    {
        if (!config('filament-modules.auto-register-plugins', false)) {
            return [];
        }
        // get a glob of all Filament plugins
        $basePath = str(config('modules.paths.modules', 'Modules'));
        $pattern = $basePath . '/*/app/Filament/*Plugin.php';
        $pluginPaths = glob($pattern);
        $filesAll = collect($pluginPaths);
        $files = $filesAll->map(function ($path) {
            $mainComposerPathPath = dirname($path, 3);
            $mainComposerPathPathFile = normalize_path($mainComposerPathPath . '/composer.json', false);
            $namespace = FilamentModules::convertPathToNamespace($path);
            $namespace = str_replace('/', '\\', $namespace);
            if (file_exists($mainComposerPathPathFile)) {
                LaravelModulesFileRepository::registerNamespacesFromComposer($mainComposerPathPathFile);
            }

            return $namespace;
        })->toArray();
        return $files;
    }


    protected function getModulePages(): array
    {
        if (!config('filament-modules.auto-register-pages', false)) {
            return [];
        }

        // get a glob of all Filament plugins
        $basePath = str(config('modules.paths.modules', 'Modules'));
        $pattern = $basePath . '/*/app/Filament/Pages/*.php';
        $pluginPaths = glob($pattern);
        $filesAll = collect($pluginPaths);
        $files = $filesAll->map(function ($path) {
            $mainComposerPathPath = dirname($path, 3);
            $mainComposerPathPathFile = normalize_path($mainComposerPathPath . '/composer.json', false);
            $namespace = FilamentModules::convertPathToNamespace($path);
            $namespace = str_replace('/', '\\', $namespace);
            if (file_exists($mainComposerPathPathFile)) {
                LaravelModulesFileRepository::registerNamespacesFromComposer($mainComposerPathPathFile);
            }

            return $namespace;
        })->toArray();
        return $files;
    }
}
