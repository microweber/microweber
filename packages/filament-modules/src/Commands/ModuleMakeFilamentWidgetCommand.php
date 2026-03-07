<?php

namespace Coolsam\Modules\Commands;

use Coolsam\Modules\Facades\FilamentModules;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Widgets\Commands\MakeWidgetCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class ModuleMakeFilamentWidgetCommand extends MakeWidgetCommand
{
    protected $signature = 'module:make:filament-widget {name?} {module?} {--R|resource=} {--C|chart} {--T|table} {--S|stats-overview} {--panel=} {--F|force}';

    public function handle(): int
    {
        $moduleName = $this->argument('module') ?? text('In which Module should we create this?', 'e.g Blog', required: true);
        $moduleStudlyName = str($moduleName)->studly()->toString();
        $module = FilamentModules::getModule($moduleStudlyName);
        $widget = (string) str($this->argument('name') ?? text(
            label: 'What is the widget name?',
            placeholder: 'BlogPostsChart',
            required: true,
        ))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $widgetClass = (string) str($widget)->afterLast('\\');
        $widgetNamespace = str($widget)->contains('\\') ?
            (string) str($widget)->beforeLast('\\') :
            '';

        $resource = null;
        $resourceClass = null;

        $type = match (true) {
            $this->option('chart') => 'Chart',
            $this->option('stats-overview') => 'Stats overview',
            $this->option('table') => 'Table',
            default => select(
                label: 'What type of widget do you want to create?',
                options: ['Chart', 'Stats overview', 'Table', 'Custom'],
            ),
        };

        if (class_exists(Resource::class)) {
            $resourceInput = $this->option('resource') ?? text(
                label: 'What is the resource you would like to create this in?',
                placeholder: '[Optional] BlogPostResource',
            );

            if (filled($resourceInput)) {
                $resource = (string) str($resourceInput)
                    ->studly()
                    ->trim('/')
                    ->trim('\\')
                    ->trim(' ')
                    ->replace('/', '\\');

                if (! str($resource)->endsWith('Resource')) {
                    $resource .= 'Resource';
                }

                $resourceClass = (string) str($resource)
                    ->afterLast('\\');
            }
        }

        $panel = null;

        if (class_exists(Panel::class)) {
            $panel = $this->option('panel');

            if ($panel) {
                $panel = Filament::getPanel($panel);
            }

            if (! $panel) {
                $panels = Filament::getPanels();
                // $namespace = config('livewire.class_namespace');
                $namespace = $module->appNamespace('Livewire'); // Livewire namespace

                /** @var ?Panel $panel */
                $panel = $panels[select(
                    label: 'Where would you like to create this?',
                    options: array_unique([
                        ...array_map(
                            fn (Panel $panel): string => "The [{$panel->getId()}] panel",
                            $panels,
                        ),
                        $namespace => "[{$namespace}] alongside other Livewire components",
                    ])
                )] ?? null;
            }
        }

        $path = null;
        $namespace = null;
        $resourcePath = null;
        $resourceNamespace = null;

        if (! $panel) {
            $namespace = $module->appNamespace('Livewire');
            $path = $module->appPath((string) str($namespace)->after('App\\')->replace('\\', '/'));
        } elseif ($resource === null) {
            $widgetDirectories = collect($panel->getWidgetDirectories())->filter(fn ($dir) => str($dir)->contains($module->appPath()))->values()->all();
            $widgetNamespaces = collect($panel->getWidgetNamespaces())->filter(fn ($dir) => str($dir)->contains($module->appNamespace()))->values()->all();

            $namespace = (count($widgetNamespaces) > 1) ?
                select(
                    label: 'Which namespace would you like to create this in?',
                    options: $widgetNamespaces,
                ) :
                (Arr::first($widgetNamespaces) ?? $module->appNamespace('Filament\\Widgets'));
            $path = (count($widgetDirectories) > 1) ?
                $widgetDirectories[array_search($namespace, $widgetNamespaces)] :
                (Arr::first($widgetDirectories) ?? $module->appPath('Filament/Widgets/'));
        } else {
            $resourceDirectories = collect($panel->getResourceDirectories())->filter(fn ($dir) => str($dir)->contains($module->appPath()))->values()->all();
            $resourceNamespaces = collect($panel->getResourceNamespaces())->filter(fn ($dir) => str($dir)->contains($module->appNamespace()))->values()->all();

            $resourceNamespace = (count($resourceNamespaces) > 1) ?
                select(
                    label: 'Which namespace would you like to create this in?',
                    options: $resourceNamespaces,
                ) :
                (Arr::first($resourceNamespaces) ?? $module->appNamespace('Filament\\Resources'));
            $resourcePath = (count($resourceDirectories) > 1) ?
                $resourceDirectories[array_search($resourceNamespace, $resourceNamespaces)] :
                (Arr::first($resourceDirectories) ?? $module->appPath('Filament/Resources/'));
        }

        $view = str(str($widget)->prepend(
            (string) str($resource === null ? ($panel ? "{$namespace}\\" : 'livewire\\') : "{$resourceNamespace}\\{$resource}\\widgets\\")
                ->replaceFirst($module->appNamespace() . '\\', '')
        )
            ->replace('\\', '/')
            ->explode('/')
            ->map(fn ($segment) => Str::lower(Str::kebab($segment)))
            ->implode('.'))->prepend($module->getLowerName() . '::');

        $path = (string) str($widget)
            ->prepend('/')
            ->prepend($resource === null ? $path : "{$resourcePath}\\{$resource}\\Widgets\\")
            ->replace('\\', '/')
            ->replace('//', '/')
            ->append('.php');

        $viewPath = $module->resourcesPath(
            (string) str($view)
                ->replace($module->getLowerName() . '::', '')
                ->replace('.', '/')
                ->prepend('views/')
                ->append('.blade.php'),
        );

        if (! $this->option('force') && $this->checkForCollision([
            $path,
            ...($this->option('stats-overview') || $this->option('chart')) ? [] : [$viewPath],
        ])) {
            return static::INVALID;
        }

        if ($type === 'Chart') {
            $chartType = select(
                label: 'Which type of chart would you like to create?',
                options: [
                    'Bar chart',
                    'Bubble chart',
                    'Doughnut chart',
                    'Line chart',
                    'Pie chart',
                    'Polar area chart',
                    'Radar chart',
                    'Scatter chart',
                ],
            );

            $this->copyStubToApp('ChartWidget', $path, [
                'class' => $widgetClass,
                'namespace' => filled($resource) ? "{$resourceNamespace}\\{$resource}\\Widgets" . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : $namespace . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
                'type' => match ($chartType) {
                    'Bar chart' => 'bar',
                    'Bubble chart' => 'bubble',
                    'Doughnut chart' => 'doughnut',
                    'Pie chart' => 'pie',
                    'Polar area chart' => 'polarArea',
                    'Radar chart' => 'radar',
                    'Scatter chart' => 'scatter',
                    default => 'line',
                },
            ]);
        } elseif ($type === 'Stats overview') {
            $this->copyStubToApp('StatsOverviewWidget', $path, [
                'class' => $widgetClass,
                'namespace' => filled($resource) ? "{$resourceNamespace}\\{$resource}\\Widgets" . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : $namespace . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
            ]);
        } elseif ($type === 'Table') {
            $this->copyStubToApp('TableWidget', $path, [
                'class' => $widgetClass,
                'namespace' => filled($resource) ? "{$resourceNamespace}\\{$resource}\\Widgets" . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : $namespace . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
            ]);
        } else {
            $this->copyStubToApp('Widget', $path, [
                'class' => $widgetClass,
                'namespace' => filled($resource) ? "{$resourceNamespace}\\{$resource}\\Widgets" . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : '') : $namespace . ($widgetNamespace !== '' ? "\\{$widgetNamespace}" : ''),
                'view' => $view,
            ]);

            $this->copyStubToApp('WidgetView', $viewPath);
        }

        $this->components->info("Filament widget [{$path}] created successfully.");

        if ($resource !== null) {
            $this->components->info("Make sure to register the widget in `{$resourceClass}::getWidgets()`, and then again in `getHeaderWidgets()` or `getFooterWidgets()` of any `{$resourceClass}` page.");
        }

        return static::SUCCESS;
    }

    protected function getDefaultStubPath(): string
    {
        return base_path('vendor/filament/widgets/stubs');
    }
}
