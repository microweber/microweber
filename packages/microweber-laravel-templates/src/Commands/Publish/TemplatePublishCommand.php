<?php

namespace MicroweberPackages\LaravelTemplates\Commands\Publish;
use Nwidart\Modules\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\multiselect;


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
    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        $modules = $this->hasOption('direction')
            ? array_keys($this->laravel['templates']->getOrdered($input->hasOption('direction')))
            : array_keys($this->laravel['templates']->all());

        if ($input->getOption(strtolower(self::ALL))) {
            $input->setArgument('module', $modules);

            return;
        }

        if (! empty($input->getArgument('module'))) {
            return;
        }

        $selected_item = multiselect(
            label   : 'Select Modules',
            options : [
                self::ALL,
                ...$modules,
            ],
            required: 'You must select at least one module',
        );

        $input->setArgument(
            'module',
            value: in_array(self::ALL, $selected_item)
                ? $modules
                : $selected_item
        );
    }

    protected function getModuleModel($name)
    {
        return $name instanceof \Nwidart\Modules\Module
            ? $name
            : $this->laravel['templates']->findOrFail($name);
    }

    public function getInfo(): ?string
    {
        return 'Publishing module asset files ...';
    }
}
