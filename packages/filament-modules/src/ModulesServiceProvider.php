<?php

namespace Coolsam\Modules;

use Coolsam\Modules\Facades\FilamentModules;
use Coolsam\Modules\Testing\TestsModules;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Nwidart\Modules\Module;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ModulesServiceProvider extends PackageServiceProvider
{
    public static string $name = 'modules';

    public static string $viewNamespace = 'modules';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->endWith(function (InstallCommand $command) {
                        $command->askToStarRepoOnGitHub('savannabits/filament-modules');
                    });
            });

        $configFileName = 'filament-modules';

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile($configFileName);
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        $this->registerModuleMacros();
    }

    public function attemptToRegisterModuleProviders(): void
    {
        // It is necessary to register them here to avoid late registration (after Panels have already been booted)
        $providers = glob(config('modules.paths.modules') . '/*' . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . 'Providers' . DIRECTORY_SEPARATOR . '*ServiceProvider.php');
        foreach ($providers as $provider) {
            $namespace = FilamentModules::convertPathToNamespace($provider);
            $module = str($namespace)->before('\Providers\\')->afterLast('\\')->toString();
            $className = str($namespace)->afterLast('\\')->toString();
            if (str($className)->startsWith($module)) {
                // register the module service provider
                $this->app->register($namespace);
            }
        }
    }

    public function packageBooted(): void
    {
        $this->attemptToRegisterModuleProviders();
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/modules/{$file->getFilename()}"),
                ], 'modules-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsModules);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'coolsam/modules';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            Commands\ModuleFilamentInstallCommand::class,
            Commands\ModuleMakeFilamentClusterCommand::class,
            Commands\ModuleMakeFilamentPluginCommand::class,
            Commands\ModuleMakeFilamentResourceCommand::class,
            Commands\ModuleMakeFilamentPageCommand::class,
            Commands\ModuleMakeFilamentWidgetCommand::class,
            Commands\ModuleMakeFilamentThemeCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            //            'create_modules_table',
        ];
    }

    protected function registerModuleMacros(): void
    {
        Module::macro('namespace', function (string $relativeNamespace = '') {
            $base = trim($this->app['config']->get('modules.namespace', 'Modules'), '\\');
            $relativeNamespace = trim($relativeNamespace, '\\');
            $studlyName = $this->getStudlyName();

            return trim("{$base}\\{$studlyName}\\{$relativeNamespace}", '\\');
        });

        Module::macro('getTitle', function () {
            return str($this->getStudlyName())->kebab()->title()->replace('-', ' ')->toString();
        });

        Module::macro('appNamespace', function (string $relativeNamespace = '') {
            $relativeNamespace = trim($relativeNamespace, '\\');
            $relativeNamespace = str_replace('App\\', '', $relativeNamespace);
            $relativeNamespace = str_replace('App', '', $relativeNamespace);
            $relativeNamespace = trim($relativeNamespace, '\\');
            $relativeNamespace = '\\' . $relativeNamespace;

            return $this->namespace($relativeNamespace);
        });
        Module::macro('appPath', function (string $relativePath = '') {
            $appPath = $this->getExtraPath('app');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('databasePath', function (string $relativePath = '') {
            $appPath = $this->getExtraPath('database');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('resourcesPath', function (string $relativePath = '') {
            $appPath = $this->getExtraPath('resources');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('migrationsPath', function (string $relativePath = '') {
            $appPath = $this->databasePath('migrations');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('seedersPath', function (string $relativePath = '') {
            $appPath = $this->databasePath('seeders');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('factoriesPath', function (string $relativePath = '') {
            $appPath = $this->databasePath('factories');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });
    }
}
