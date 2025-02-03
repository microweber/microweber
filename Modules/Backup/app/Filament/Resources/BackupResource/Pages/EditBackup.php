<?php

namespace Modules\Backup\Filament\Resources\BackupResource\Pages;

use Modules\Backup\Filament\Resources\BackupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBackup extends EditRecord
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
