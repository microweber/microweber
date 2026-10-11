<?php

namespace MicroweberPackages\Role\Filament\Resources\RoleResource\Pages;

use MicroweberPackages\Role\Filament\Resources\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Role')
                ->icon('heroicon-o-plus'),
        ];
    }
}
