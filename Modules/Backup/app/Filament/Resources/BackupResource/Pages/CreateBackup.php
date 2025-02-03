<?php

namespace Modules\Backup\Filament\Resources\BackupResource\Pages;

use Modules\Backup\Filament\Resources\BackupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBackup extends CreateRecord
{
    protected static string $resource = BackupResource::class;
}
