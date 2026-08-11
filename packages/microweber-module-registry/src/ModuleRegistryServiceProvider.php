<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class ModuleRegistryServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('microweber-packages/module-registry')
            ->hasViews('module-registry');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(ModuleRegistryManager::class, static function (): ModuleRegistryManager {
            return new ModuleRegistryManager();
        });

        // CMS / historical binding used by app()->microweber and the Microweber facade
        $this->app->singleton('microweber', static function ($app): ModuleRegistryManager {
            /** @var \Illuminate\Contracts\Foundation\Application $app */
            return $app->make(ModuleRegistryManager::class);
        });

        // BC view namespace previously published as 'microweber::livewire.no-settings'
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'microweber');
    }

    /**
     * @return list<string>
     */
    public function provides(): array
    {
        return [
            ModuleRegistryManager::class,
            'microweber',
        ];
    }
}
