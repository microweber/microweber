<?php

namespace Coolsam\Modules\Commands;

use Coolsam\Modules\Concerns\GeneratesModularFiles;
use Illuminate\Console\GeneratorCommand;

class ModuleMakeFilamentClusterCommand extends GeneratorCommand
{
    use GeneratesModularFiles;

    protected $name = 'module:make:filament-cluster';

    protected $description = 'Create a new Filament cluster class in the module';

    protected $type = 'Filament Cluster';

    protected function getRelativeNamespace(): string
    {
        return 'Filament\\Clusters';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/filament-cluster.stub');
    }

    protected function stubReplacements(): array
    {
        return [
            'moduleStudlyName' => $this->getModule()->getStudlyName(),
            'navigationLabel' => str($this->argument('name'))->kebab()->title()->replace('-', ' ')->toString(),
            'navigationIcon' => 'heroicon-o-squares-2x2',
        ];
    }
}
