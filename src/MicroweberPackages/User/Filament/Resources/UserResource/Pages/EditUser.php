<?php

namespace MicroweberPackages\User\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\User\Filament\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
