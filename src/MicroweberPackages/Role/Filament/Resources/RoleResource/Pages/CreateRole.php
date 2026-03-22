<?php

namespace MicroweberPackages\Role\Filament\Resources\RoleResource\Pages;

use MicroweberPackages\Role\Filament\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected static ?string $title = 'Create Role';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
