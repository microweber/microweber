<?php

namespace MicroweberPackages\User\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\User\Filament\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

}
