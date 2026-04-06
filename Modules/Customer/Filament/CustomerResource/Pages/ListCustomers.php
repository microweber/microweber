<?php

namespace Modules\Customer\Filament\CustomerResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Customer\Filament\CustomerResource;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
