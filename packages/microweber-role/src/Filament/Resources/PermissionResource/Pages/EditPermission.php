<?php

namespace MicroweberPackages\Role\Filament\Resources\PermissionResource\Pages;

use MicroweberPackages\Role\Filament\Resources\PermissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected static ?string $title = 'Edit Permission';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalDescription('Are you sure you want to delete this permission? Roles using this permission will lose access.'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
