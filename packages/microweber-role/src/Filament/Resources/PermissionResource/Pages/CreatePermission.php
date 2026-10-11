<?php

namespace MicroweberPackages\Role\Filament\Resources\PermissionResource\Pages;

use MicroweberPackages\Role\Filament\Resources\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected static ?string $title = 'Create Permission';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
