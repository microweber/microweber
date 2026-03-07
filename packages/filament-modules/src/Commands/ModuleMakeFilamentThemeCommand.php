<?php

namespace Coolsam\Modules\Commands;

use Coolsam\Modules\Facades\FilamentModules;
use Filament\Commands\MakeThemeCommand;
use Filament\Panel;
use Illuminate\Filesystem\Filesystem;
use Nwidart\Modules\Module;

use function Laravel\Prompts\text;

class ModuleMakeFilamentThemeCommand extends MakeThemeCommand
{
    protected $signature = 'module:make:filament-theme {module?} {--pm=} {--F|force}';

    protected $description = 'Create a new Filament theme in a module';

    public function handle(Filesystem $filesystem): int
    {
        $module = $this->getModule();

        $this->call('vendor:publish', [
            '--provider' => 'Nwidart\Modules\LaravelModulesServiceProvider',
            '--tag' => 'vite',
        ]);

        $pm = $this->option('pm') ?? 'npm';

        exec("{$pm} -v", $pmVersion, $pmVersionExistCode);

        if ($pmVersionExistCode !== 0) {
            $this->error('Node.js is not installed. Please install before continuing.');

            return static::FAILURE;
        }

        $this->info("Using {$pm} v{$pmVersion[0]}");

        $installCommand = match ($pm) {
            'yarn' => 'yarn add',
            default => "{$pm} install",
        };
        $cdCommand = 'cd ' . $module->getPath();

        exec("$cdCommand && {$installCommand} tailwindcss @tailwindcss/forms @tailwindcss/typography postcss postcss-nesting autoprefixer --save-dev");

        // $panel = $this->argument('panel');

        $cssFilePath = $module->resourcesPath('css/filament/theme.css');
        $tailwindConfigFilePath = $module->resourcesPath('css/filament/tailwind.config.js');

        if (! $this->option('force') && $this->checkForCollision([
            $cssFilePath,
            $tailwindConfigFilePath,
        ])) {
            return static::INVALID;
        }

        $classPathPrefix = '';

        $viewPathPrefix = '';

        $this->copyStubToApp('filament-theme-css', $cssFilePath);
        $this->copyStubToApp('filament-theme-tailwind-config', $tailwindConfigFilePath, [
            'classPathPrefix' => $classPathPrefix,
            'viewPathPrefix' => $viewPathPrefix,
        ]);

        $this->components->info('Filament theme [resources/css/filament/theme.css] and [resources/css/filament/tailwind.config.js] created successfully in ' . $module->getStudlyName() . ' module.');

        $buildDirectory = 'build-' . $module->getLowerName();
        $moduleStudlyName = $module->getStudlyName();

        if (empty(glob($module->getExtraPath('vite.config.*s')))) {
            $this->components->warn('Action is required to complete the theme setup:');
            $this->components->bulletList([
                "It looks like you don't have Vite installed in your module. Please use your asset bundling system of choice to compile `resources/css/filament/theme.css` into `public/$buildDirectory/css/filament/theme.css`.",
                "If you're not currently using a bundler, we recommend using Vite. Alternatively, you can use the Tailwind CLI with the following command inside the $moduleStudlyName module:",
                'npx tailwindcss --input ./resources/css/filament/theme.css --output ./public/' . $buildDirectory . '/css/filament/theme.css --config ./resources/css/filament/tailwind.config.js --minify',
                "Make sure to register the theme in the {$moduleStudlyName} module plugin under the afterRegister() function using `->theme(asset('css/filament/theme.css'))`",
            ]);

            return static::SUCCESS;
        }

        $postcssConfigPath = $module->getExtraPath('postcss.config.js');

        if (! file_exists($postcssConfigPath)) {
            $this->copyStubToApp('filament-theme-postcss', $postcssConfigPath);

            $this->components->info('Filament theme [postcss.config.js] created successfully.');
        }

        $this->components->warn('Action is required to complete the theme setup:');
        $this->components->bulletList([
            "First, add a new item to the `input` array of `vite.config.js`: `resources/css/filament/theme.css` in the $moduleStudlyName module.",
            "Next, register the theme in the {$module->getStudlyName()} module plugin under the `afterRegister()` method using `->viteTheme('resources/css/filament/theme.css', '$buildDirectory')`",
            "Finally, run `{$pm} run build` from the root of this module to compile the theme.",
        ]);

        return static::SUCCESS;
    }

    protected function getDefaultStubPath(): string
    {
        return __DIR__ . '/stubs';
    }

    private function getModule(): Module
    {
        $moduleName = $this->argument('module') ?? text('In which Module should we create this?', 'e.g Blog', required: true);
        $moduleStudlyName = str($moduleName)->studly()->toString();

        return FilamentModules::getModule($moduleStudlyName);
    }
}
