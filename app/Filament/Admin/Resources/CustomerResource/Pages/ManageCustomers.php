<?php

namespace App\Filament\Admin\Resources\CustomerResource\Pages;

use App\Filament\Admin\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCustomers extends ManageRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
