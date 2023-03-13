<?php

namespace MicroweberPackages\User\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use MicroweberPackages\User\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

}
