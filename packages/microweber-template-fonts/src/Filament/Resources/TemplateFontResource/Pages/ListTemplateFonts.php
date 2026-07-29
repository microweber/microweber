<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource;

class ListTemplateFonts extends ListRecords
{
    protected static string $resource = TemplateFontResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
