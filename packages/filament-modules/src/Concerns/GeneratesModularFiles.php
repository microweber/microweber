<?php

namespace Coolsam\Modules\Concerns;

use Coolsam\Modules\Facades\FilamentModules;
use Illuminate\Support\Str;
use Nwidart\Modules\Module;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

trait GeneratesModularFiles
{
    protected function getArguments(): array
    {
        return array_merge(parent::getArguments(), [
            ['module', InputArgument::REQUIRED, 'The name of the module in which this should be installed'],
        ]);
    }

    protected function resolveStubPath($stub): string
    {
        return FilamentModules::packagePath('Commands' . DIRECTORY_SEPARATOR . trim($stub, DIRECTORY_SEPARATOR));
    }

    public function getModule(): Module
    {
        return FilamentModules::getModule($this->argument('module'));
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return trim($rootNamespace, '\\') . '\\' . trim(Str::replace(DIRECTORY_SEPARATOR, '\\', $this->getRelativeNamespace()), '\\');
    }

    abstract protected function getRelativeNamespace(): string;

    protected function rootNamespace(): string
    {
        return $this->getModule()->namespace('');
    }

    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), 'app', $name);

        return $this->getModule()->getExtraPath(str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php');
    }

    protected function possibleModels()
    {
        $modelPath = $this->getModule()->appPath('Models');

        return collect(Finder::create()->files()->depth(0)->in($modelPath))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->sort()
            ->values()
            ->all();
    }

    protected function viewPath($path = ''): string
    {
        $views = $this->getModule()->resourcesPath('views');

        return $views . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->applyStubReplacements($stub)->replaceClass($stub, $name);
    }

    protected function applyStubReplacements(&$stub): static
    {
        foreach ($this->stubReplacements() as $key => $replacement) {
            $stub = str_replace(["{{ $key }}", "{{{$key}}}"], $replacement, $stub);
        }

        return $this;
    }

    protected function stubReplacements(): array
    {
        return [];
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => [
                'What should the ' . strtolower($this->type ?: 'class') . ' be named?',
                match ($this->type) {
                    'Cast' => 'E.g. Json',
                    'Channel' => 'E.g. OrderChannel',
                    'Console command' => 'E.g. SendEmails',
                    'Component' => 'E.g. Alert',
                    'Controller' => 'E.g. UserController',
                    'Event' => 'E.g. PodcastProcessed',
                    'Exception' => 'E.g. InvalidOrderException',
                    'Factory' => 'E.g. PostFactory',
                    'Job' => 'E.g. ProcessPodcast',
                    'Listener' => 'E.g. SendPodcastNotification',
                    'Mailable' => 'E.g. OrderShipped',
                    'Middleware' => 'E.g. EnsureTokenIsValid',
                    'Model' => 'E.g. Flight',
                    'Notification' => 'E.g. InvoicePaid',
                    'Observer' => 'E.g. UserObserver',
                    'Policy' => 'E.g. PostPolicy',
                    'Provider' => 'E.g. ElasticServiceProvider',
                    'Request' => 'E.g. StorePodcastRequest',
                    'Resource' => 'E.g. UserResource',
                    'Rule' => 'E.g. Uppercase',
                    'Scope' => 'E.g. TrendingScope',
                    'Seeder' => 'E.g. UserSeeder',
                    'Test' => 'E.g. UserTest',
                    'Filament Cluster' => 'E.g Settings',
                    'Filament Plugin' => 'e.g AccessControlPlugin',
                    default => '',
                },
            ],
        ];
    }
}
