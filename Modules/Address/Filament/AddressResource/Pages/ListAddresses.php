<?php

namespace Modules\Address\Filament\AddressResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Address\Filament\AddressResource;

class ListAddresses extends ListRecords
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
