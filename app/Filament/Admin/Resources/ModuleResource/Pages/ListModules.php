<?php

namespace App\Filament\Admin\Resources\ModuleResource\Pages;

use App\Filament\Admin\Resources\ModuleResource;
use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reload_modules')
                ->action(function () {
                    mw_post_update();

                    Notification::make()
                        ->title('Modules have been reloaded')
                        ->success()
                        ->send();

                })
        ];
    }
}
