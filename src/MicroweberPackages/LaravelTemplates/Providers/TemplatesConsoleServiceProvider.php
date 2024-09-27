<?php

namespace MicroweberPackages\LaravelTemplates\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelTemplates\Contracts\TemplatesRepositoryInterface;
use MicroweberPackages\LaravelTemplates\Repositories\LaravelTemplatesFileRepository;
use Nwidart\Modules\Contracts\RepositoryInterface;
use Nwidart\Modules\Providers\BootstrapServiceProvider;
use Nwidart\Modules\Commands;

class TemplatesConsoleServiceProvider extends ServiceProvider
{


    public function register(): void
    {
        $this->commands(config('templates.commands', self::defaultCommands()->toArray()));
    }

    public function provides(): array
    {
        return self::defaultCommands()->toArray();
    }

    /**
     * Get the package default commands.
     */
    public static function defaultCommands(): Collection
    {
        return collect([
            \MicroweberPackages\LaravelTemplates\Commands\Make\TemplateMakeCommand::class,
            \MicroweberPackages\LaravelTemplates\Commands\Make\TemplateProviderMakeCommand::class,
            \MicroweberPackages\LaravelTemplates\Commands\Publish\TemplatePublishCommand::class,
        ]);
    }
}

