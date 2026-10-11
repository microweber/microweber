<?php

namespace MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource;

class ListModuleDependencies extends ListRecords
{
    protected static string $resource = ModuleDependencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
