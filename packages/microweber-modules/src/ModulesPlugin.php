<?php

namespace Coolsam\Modules;

use Coolsam\Modules\Facades\FilamentModules;
use Filament\Contracts\Plugin;
use Filament\Panel;

class ModulesPlugin implements Plugin
{
    public function getId(): string
    {
        return 'modules';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->topNavigation(config('filament-modules.clusters.enabled', false) && config('filament-modules.clusters.use-top-navigation', false));
        $plugins = $this->getModulePlugins();
        foreach ($plugins as $modulePlugin) {
            $panel->plugin($modulePlugin::make());
        }
    }

    public function boot(Panel $panel): void {}

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

    protected function getModulePlugins(): array
    {
        if (! config('filament-modules.auto-register-plugins', false)) {
            return [];
        }
        // get a glob of all Filament plugins
        $basePath = str(config('modules.paths.modules', 'Modules'));
        $pattern = $basePath . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Filament' . DIRECTORY_SEPARATOR . '*Plugin.php';
        $pluginPaths = glob($pattern);

        return collect($pluginPaths)->map(fn ($path) => FilamentModules::convertPathToNamespace($path))->toArray();

    }
}
