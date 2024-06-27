<?php

namespace MicroweberPackages\Filament\Tables\Actions;

use Filament\Actions\Imports\Models\Import;
use Filament\Tables\Actions\ImportAction as ImportTableAction;
use Illuminate\Support\Arr;
use League\Csv\Reader as CsvReader;
use League\Csv\Statement;
use MicroweberPackages\Filament\MwFilamentImport;

class ImportAction extends \Filament\Tables\Actions\ImportAction
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (\Filament\Actions\ImportAction|ImportTableAction $action, array $data) {

            $import =  MwFilamentImport::startImport($action, $data);

            $this->dispatch('subscribersImported');

            return $import;
        });

    }
}
