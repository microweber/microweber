<?php

namespace MicroweberPackages\User\Filament\Resources\UsersResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use MicroweberPackages\User\Filament\Resources\UsersResource;

class CreateUsers extends CreateRecord
{
    protected static string $resource = UsersResource::class;
}
