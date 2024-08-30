<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;

class ManageSenderAccounts extends ManageRecords
{
    protected static string $resource = SenderAccountsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
