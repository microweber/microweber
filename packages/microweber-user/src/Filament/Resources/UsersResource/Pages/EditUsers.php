<?php

namespace MicroweberPackages\User\Filament\Resources\UsersResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\User\Filament\Resources\UsersResource;

class EditUsers extends EditRecord
{
    protected static string $resource = UsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
