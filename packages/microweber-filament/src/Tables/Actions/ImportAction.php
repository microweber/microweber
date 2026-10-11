<?php

namespace MicroweberPackages\Filament\Tables\Actions;

use Filament\Actions\ImportAction as BaseImportAction;
use MicroweberPackages\Filament\MwFilamentImport;

class ImportAction extends BaseImportAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (BaseImportAction $action, array $data) {

            $import = MwFilamentImport::startImport($action, $data);

            $this->dispatchTo('subscribersImported', 'imported', $import);

            return $import;
        });
    }
}
