<?php

namespace MicroweberPackages\Filament\Actions;

use Filament\Tables\Actions\ImportAction as ImportTableAction;
use MicroweberPackages\Filament\MwFilamentImport;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\CreateCampaign;

class ImportAction extends \Filament\Actions\ImportAction
{
    public $importedData = [];
    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (\Filament\Actions\ImportAction|ImportTableAction $action, array $data) {

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
