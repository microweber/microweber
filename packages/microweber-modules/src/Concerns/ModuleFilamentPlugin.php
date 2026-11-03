<?php

namespace Coolsam\Modules\Concerns;

use Filament\Panel;
use Nwidart\Modules\Facades\Module;

trait ModuleFilamentPlugin
{
    abstract public function getModuleName(): string;

    public function getModule(): \Nwidart\Modules\Module
    {
        return Module::findOrFail($this->getModuleName());
    }

    public function register(Panel $panel): void
    {
        $module = $this->getModule();

        if (! $module->isEnabled()) {
            return;
        }

        $useClusters = config('filament-modules.clusters.enabled', false);
        $panel->discoverPages(
            in: $module->appPath('Filament' . DIRECTORY_SEPARATOR . 'Pages'),
            for: $module->appNamespace('\\Filament\\Pages')
        );
        $panel->discoverResources(
            in: $module->appPath('Filament' . DIRECTORY_SEPARATOR . 'Resources'),
            for: $module->appNamespace('\\Filament\\Resources')
        );
        $panel->discoverWidgets(
            in: $module->appPath('Filament' . DIRECTORY_SEPARATOR . 'Widgets'),
            for: $module->appNamespace('\\Filament\\Widgets')
        );

        $panel->discoverLivewireComponents(
            in: $module->appPath('Livewire'),
            for: $module->appNamespace('\\Livewire')
        );

        if ($useClusters) {
            $path = $module->appPath('Filament' . DIRECTORY_SEPARATOR . 'Clusters');
            $namespace = $module->appNamespace('\\Filament\\Clusters');
            $panel->discoverClusters(
                in: $path,
                for: $namespace,
            );
        }
        $this->afterRegister($panel);
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function afterRegister(Panel $panel)
    {
        // override this to implement additional logic
    }
}
