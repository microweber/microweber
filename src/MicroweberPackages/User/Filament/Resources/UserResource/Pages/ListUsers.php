<?php

namespace MicroweberPackages\User\Filament\Resources\UserResource\Pages;

use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;
use MicroweberPackages\User\Filament\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected int $defaultTableRecordsPerPageSelectOption = 50;

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 25, 50, 100, 500, -1];
    }


    public function getActions(): array
    {
        return [
            Action::make('Export all')
                ->icon('heroicon-o-download')
                ->button()
                ->color('secondary')
                ->action(function ($record) {
                    return redirect(admin_url('user?exportResults=1'));
                }),

            Action::make('Create New User')
                ->icon('heroicon-o-plus')
                ->button()
                ->action(function() {
                    return redirect(admin_url('module/view?type=users/edit-user:0'));
                }),

        ];
    }


}
