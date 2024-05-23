<?php

namespace MicroweberPackages\User\Filament\Resources\UsersResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\User\Filament\Resources\UsersResource;
use MicroweberPackages\User\Models\User;

class EditUsers extends EditRecord
{
    protected static string $resource = UsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $getUser = User::where('email', $data['email'])->first();
        if ($getUser) {
            if (empty($data['password'])) {
                $data['password'] = $getUser->password;
            }
        }
        return $data;
    }
}
