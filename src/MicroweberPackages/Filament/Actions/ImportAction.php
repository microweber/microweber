<?php

namespace MicroweberPackages\Filament\Actions;

use Filament\Actions\ImportAction as BaseImportAction;
use MicroweberPackages\Filament\MwFilamentImport;

class ImportAction extends BaseImportAction
{
    public $importedData = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (BaseImportAction $action, array $data) {

            $this->importedData = MwFilamentImport::startImport($action, $data);

            if ($this->afterImport) {
                $this->evaluate($this->afterImport);
            }
        });
    }

    public function getImportedData()
    {
        return $this->importedData;
    }

    public function afterImport(callable $callback): static
    {
        $this->afterImport = $callback;

        return $this;
    }
}
