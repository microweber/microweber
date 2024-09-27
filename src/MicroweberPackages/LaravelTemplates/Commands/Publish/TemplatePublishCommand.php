<?php

namespace MicroweberPackages\LaravelTemplates\Commands\Publish;
use MicroweberPackages\LaravelTemplates\Support\TemplateAssetPublisher;
use Nwidart\Modules\Commands\BaseCommand;
use Nwidart\Modules\Publishing\AssetPublisher;



class TemplatePublishCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'template:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a template\'s assets to the application';

    public function executeAction($name): void
    {
        $module = app('templates')->findOrFail($name);

        $this->components->task("Publishing Assets <fg=cyan;options=bold>{$module->getName()}</> Template", function () use ($module) {
            with(new TemplateAssetPublisher($module))
                ->setRepository($this->laravel['templates'])
                ->setConsole($this)
                ->publish();
        });

    }

    public function getInfo(): ?string
    {
        return 'Publishing module asset files ...';
    }
}
