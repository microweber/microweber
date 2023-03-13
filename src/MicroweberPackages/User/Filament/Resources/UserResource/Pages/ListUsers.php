<?php

namespace MicroweberPackages\User\Filament\Resources\UserResource\Pages;

use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\User\Filament\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getActions(): array
    {
        return [
            Action::make('Create New User')
                ->icon('heroicon-o-plus')
                ->action(function() {
                    return redirect(admin_url('view:modules/load_module:users/edit-user:0'));
                })
        ];
    }

}
