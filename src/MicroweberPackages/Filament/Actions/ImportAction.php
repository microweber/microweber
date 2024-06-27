<?php

namespace MicroweberPackages\Filament\Actions;

use Filament\Tables\Actions\ImportAction as ImportTableAction;
use MicroweberPackages\Filament\MwFilamentImport;

class ImportAction extends \Filament\Actions\ImportAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (\Filament\Actions\ImportAction|ImportTableAction $action, array $data) {

            return MwFilamentImport::startImport($action, $data);

        });

    }
}
