<?php

namespace MicroweberPackages\Module\Filament\Resources\ModuleResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\Module\Filament\Resources\ModuleResource;

class EditModule extends EditRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
