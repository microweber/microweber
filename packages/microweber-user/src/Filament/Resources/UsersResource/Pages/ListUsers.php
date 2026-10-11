<?php

namespace MicroweberPackages\User\Filament\Resources\UsersResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\User\Filament\Resources\UsersResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
