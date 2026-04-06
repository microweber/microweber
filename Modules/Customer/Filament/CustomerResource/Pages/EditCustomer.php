<?php

namespace Modules\Customer\Filament\CustomerResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Customer\Filament\CustomerResource;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
