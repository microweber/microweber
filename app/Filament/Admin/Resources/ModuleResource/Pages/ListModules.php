<?php

namespace App\Filament\Admin\Resources\ModuleResource\Pages;

use App\Filament\Admin\Resources\ModuleResource;
use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reload_modules')
                ->icon('mw-reload')
                ->link()
                ->color('secondary')
                ->action(function () {
                    mw_post_update();

                    Notification::make()
                        ->title('Modules have been reloaded')
                        ->success()
                        ->send();

                })
        ];
    }


    public function getTabs(): array
    {
        return [
            //   null => Tab::make('All'),
            'Installed' => Tab::make()->query(fn ($query) => $query->where('installed', 1)),
            'Not Installed' => Tab::make()->query(fn ($query) => $query->where('installed', 0))
        ];
    }

}