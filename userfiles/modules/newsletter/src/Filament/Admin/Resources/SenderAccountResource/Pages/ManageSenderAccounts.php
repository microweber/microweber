<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountResource;

class ManageSenderAccounts extends ManageRecords
{
    protected static string $resource = SenderAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
