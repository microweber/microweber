<?php

namespace MicroweberPackages\Module\Filament\Resources\ModuleResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\Module\Filament\Resources\ModuleResource;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
