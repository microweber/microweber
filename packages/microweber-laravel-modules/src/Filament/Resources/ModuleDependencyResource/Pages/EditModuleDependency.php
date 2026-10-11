<?php

namespace MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource;

class EditModuleDependency extends EditRecord
{
    protected static string $resource = ModuleDependencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
