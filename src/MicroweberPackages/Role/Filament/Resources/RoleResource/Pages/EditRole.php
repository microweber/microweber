<?php

namespace MicroweberPackages\Role\Filament\Resources\RoleResource\Pages;

use MicroweberPackages\Role\Filament\Resources\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected static ?string $title = 'Edit Role';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalDescription('Are you sure you want to delete this role? Users with this role will lose access.')
                ->disabled(fn ($record) => $record->is_system),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
