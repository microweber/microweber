<?php

namespace MicroweberPackages\Filament\Actions;

use Filament\Tables\Actions\ImportAction as ImportTableAction;
use MicroweberPackages\Filament\MwFilamentImport;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\CreateCampaign;

class ImportAction extends \Filament\Actions\ImportAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (\Filament\Actions\ImportAction|ImportTableAction $action, array $data) {

            $import =  MwFilamentImport::startImport($action, $data);

            $this->dispatchTo(CreateCampaign::class, 'subscribersImported');

            return $import;
        });

    }
}
