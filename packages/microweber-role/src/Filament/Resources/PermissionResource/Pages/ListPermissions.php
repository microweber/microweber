<?php

namespace MicroweberPackages\Role\Filament\Resources\PermissionResource\Pages;

use MicroweberPackages\Role\Filament\Resources\PermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Permission')
                ->icon('heroicon-o-plus'),
        ];
    }
}
