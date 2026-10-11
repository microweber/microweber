<?php

namespace MicroweberPackages\LaravelTemplates\Commands\Publish;

use Illuminate\Console\Command;
use MicroweberPackages\LaravelTemplates\Support\TemplateGenerateConfigReader;
use Nwidart\Modules\Publishing\AssetPublisher;

class TemplateAssetPublisher extends AssetPublisher
{
    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath(): string
    {
        return $this->getModule()->getExtraPath(
            TemplateGenerateConfigReader::read('assets')->getPath()
        );
    }


    public function publish()
    {
        if (!$this->console instanceof Command) {
            $message = "The 'console' property must instance of \\Illuminate\\Console\\Command.";

            throw new \RuntimeException($message);
        }

        if (!$this->getFilesystem()->isDirectory($sourcePath = $this->getSourcePath())) {
            return;
        }
        $destinationPath = $this->getDestinationPath();

        if (is_link($destinationPath)) {
            if ($this->showMessage === true) {
                $this->console->components->task($this->module->getStudlyName(), fn() => true);
            }
            return;
        }


        if (!$this->getFilesystem()->isDirectory($destinationPath = $this->getDestinationPath())) {
            $this->getFilesystem()->makeDirectory($destinationPath, 0775, true);
        }

        if ($this->getFilesystem()->copyDirectory($sourcePath, $destinationPath)) {
            if ($this->showMessage === true) {
                $this->console->components->task($this->module->getStudlyName(), fn() => true);
            }
        } else {
            $this->console->components->task($this->module->getStudlyName(), fn() => false);
            $this->console->components->error($this->error);
        }
    }

}
